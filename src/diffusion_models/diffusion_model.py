import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
from typing import Optional


class SinusoidalPositionEmbedding(nn.Module):
    """
    Sinusoidal position embedding untuk timestep encoding
    """
    def __init__(self, dim):
        super().__init__()
        self.dim = dim
    
    def forward(self, x):
        device = x.device
        half_dim = self.dim // 2
        emb = np.log(10000) / (half_dim - 1)
        emb = torch.exp(torch.arange(half_dim, device=device) * -emb)
        emb = x[:, None] * emb[None, :]
        emb = torch.cat([torch.sin(emb), torch.cos(emb)], dim=-1)
        return emb


class ResidualBlock(nn.Module):
    """
    Residual block dengan timestep embedding
    """
    def __init__(self, in_channels, out_channels, time_emb_dim):
        super().__init__()
        self.time_mlp = nn.Sequential(
            nn.SiLU(),
            nn.Linear(time_emb_dim, out_channels)
        )
        
        self.block1 = nn.Sequential(
            nn.GroupNorm(8, in_channels),
            nn.SiLU(),
            nn.Conv2d(in_channels, out_channels, 3, padding=1)
        )
        
        self.block2 = nn.Sequential(
            nn.GroupNorm(8, out_channels),
            nn.SiLU(),
            nn.Conv2d(out_channels, out_channels, 3, padding=1)
        )
        
        if in_channels != out_channels:
            self.residual_conv = nn.Conv2d(in_channels, out_channels, 1)
        else:
            self.residual_conv = nn.Identity()
    
    def forward(self, x, time_emb):
        h = self.block1(x)
        time_emb = self.time_mlp(time_emb)
        h = h + time_emb[:, :, None, None]
        h = self.block2(h)
        return h + self.residual_conv(x)


class AttentionBlock(nn.Module):
    """
    Self-attention block untuk better feature extraction
    """
    def __init__(self, channels):
        super().__init__()
        self.norm = nn.GroupNorm(8, channels)
        self.qkv = nn.Conv2d(channels, channels * 3, 1)
        self.proj = nn.Conv2d(channels, channels, 1)
    
    def forward(self, x):
        b, c, h, w = x.shape
        qkv = self.qkv(self.norm(x))
        q, k, v = qkv.chunk(3, dim=1)
        
        # Reshape for attention
        q = q.view(b, c, -1)
        k = k.view(b, c, -1)
        v = v.view(b, c, -1)
        
        # Attention
        attn = torch.matmul(q.transpose(1, 2), k) * (c ** -0.5)
        attn = F.softmax(attn, dim=-1)
        out = torch.matmul(v, attn.transpose(1, 2))
        
        # Reshape back
        out = out.view(b, c, h, w)
        return self.proj(out)


class UNet(nn.Module):
    """
    U-Net architecture untuk diffusion model
    """
    def __init__(self, in_channels=3, out_channels=3, time_emb_dim=128, channels=[64, 128, 256, 512]):
        super().__init__()
        self.time_emb = SinusoidalPositionEmbedding(time_emb_dim)
        self.time_mlp = nn.Sequential(
            nn.Linear(time_emb_dim, time_emb_dim * 4),
            nn.SiLU(),
            nn.Linear(time_emb_dim * 4, time_emb_dim)
        )
        
        # Encoder
        self.encoders = nn.ModuleList()
        self.encoders.append(nn.Conv2d(in_channels, channels[0], 3, padding=1))
        
        for i in range(len(channels)):
            if i > 0:
                self.encoders.append(nn.Conv2d(channels[i-1], channels[i], 3, padding=1, stride=2))
            self.encoders.append(ResidualBlock(channels[i], channels[i], time_emb_dim))
            self.encoders.append(AttentionBlock(channels[i]))
        
        # Middle
        self.middle = nn.Sequential(
            ResidualBlock(channels[-1], channels[-1], time_emb_dim),
            AttentionBlock(channels[-1]),
            ResidualBlock(channels[-1], channels[-1], time_emb_dim)
        )
        
        # Decoder
        self.decoders = nn.ModuleList()
        for i in reversed(range(len(channels))):
            if i > 0:
                self.decoders.append(nn.ConvTranspose2d(channels[i] + channels[i-1], channels[i-1], 4, stride=2, padding=1))
            else:
                self.decoders.append(nn.ConvTranspose2d(channels[i], channels[i], 4, stride=2, padding=1))
            self.decoders.append(ResidualBlock(channels[i], channels[i], time_emb_dim))
            self.decoders.append(AttentionBlock(channels[i]))
        
        # Output
        self.output = nn.Conv2d(channels[0], out_channels, 3, padding=1)
    
    def forward(self, x, timestep):
        # Time embedding
        time_emb = self.time_emb(timestep)
        time_emb = self.time_mlp(time_emb)
        
        # Encoder
        skips = []
        h = x
        for i, layer in enumerate(self.encoders):
            if isinstance(layer, (ResidualBlock, AttentionBlock)):
                h = layer(h, time_emb)
            else:
                h = layer(h)
            skips.append(h)
        
        # Middle
        h = self.middle[0](h, time_emb)
        h = self.middle[1](h)
        h = self.middle[2](h, time_emb)
        
        # Decoder
        for i, layer in enumerate(self.decoders):
            if isinstance(layer, (ResidualBlock, AttentionBlock)):
                h = layer(h, time_emb)
            else:
                if skips:
                    h = torch.cat([h, skips.pop()], dim=1)
                h = layer(h)
        
        # Output
        return self.output(h)


class DiffusionProcess:
    """
    Diffusion process untuk training dan sampling
    """
    def __init__(self, num_timesteps=1000, beta_start=0.0001, beta_end=0.02):
        self.num_timesteps = num_timesteps
        
        # Beta schedule
        self.betas = torch.linspace(beta_start, beta_end, num_timesteps)
        
        # Alpha schedule
        self.alphas = 1 - self.betas
        self.alphas_cumprod = torch.cumprod(self.alphas, dim=0)
        
        # For sampling
        self.alphas_cumprod_prev = F.pad(self.alphas_cumprod[:-1], (1, 0), value=1.0)
        
        # Calculations for diffusion q(x_t | x_{t-1})
        self.sqrt_alphas_cumprod = torch.sqrt(self.alphas_cumprod)
        self.sqrt_one_minus_alphas_cumprod = torch.sqrt(1 - self.alphas_cumprod)
        
        # Calculations for posterior q(x_{t-1} | x_t, x_0)
        self.posterior_variance = (
            self.betas * (1 - self.alphas_cumprod_prev) / (1 - self.alphas_cumprod)
        )
        self.posterior_log_variance_clipped = torch.log(self.posterior_variance.clamp(min=1e-20))
        self.posterior_mean_coef1 = (
            self.betas * torch.sqrt(self.alphas_cumprod_prev) / (1 - self.alphas_cumprod)
        )
        self.posterior_mean_coef2 = (
            (1 - self.alphas_cumprod_prev) * torch.sqrt(self.alphas) / (1 - self.alphas_cumprod)
        )
    
    def q_sample(self, x_start, t, noise=None):
        """
        Sample from q(x_t | x_0)
        """
        if noise is None:
            noise = torch.randn_like(x_start)
        
        sqrt_alphas_cumprod_t = self._extract(self.sqrt_alphas_cumprod, t, x_start.shape)
        sqrt_one_minus_alphas_cumprod_t = self._extract(
            self.sqrt_one_minus_alphas_cumprod, t, x_start.shape
        )
        
        return sqrt_alphas_cumprod_t * x_start + sqrt_one_minus_alphas_cumprod_t * noise
    
    def p_losses(self, model, x_start, t, noise=None):
        """
        Calculate loss untuk training
        """
        if noise is None:
            noise = torch.randn_like(x_start)
        
        # Sample noisy image
        x_noisy = self.q_sample(x_start, t, noise)
        
        # Predict noise
        predicted_noise = model(x_noisy, t)
        
        # Calculate loss
        loss = F.mse_loss(predicted_noise, noise)
        
        return loss
    
    def p_sample(self, model, x, t):
        """
        Sample dari p(x_{t-1} | x_t)
        """
        # Predict noise
        predicted_noise = model(x, t)
        
        # Calculate mean
        sqrt_recip_alphas_t = self._extract(1 / torch.sqrt(self.alphas), t, x.shape)
        betas_t = self._extract(self.betas, t, x.shape)
        sqrt_one_minus_alphas_cumprod_t = self._extract(
            self.sqrt_one_minus_alphas_cumprod, t, x.shape
        )
        
        mean = sqrt_recip_alphas_t * (
            x - betas_t * predicted_noise / sqrt_one_minus_alphas_cumprod_t
        )
        
        # Add noise
        if t[0] > 0:
            posterior_variance_t = self._extract(self.posterior_variance, t, x.shape)
            noise = torch.randn_like(x)
            return mean + torch.sqrt(posterior_variance_t) * noise
        else:
            return mean
    
    def _extract(self, a, t, x_shape):
        """
        Extract values from tensor a at indices t
        """
        batch_size = t.shape[0]
        out = a.gather(-1, t)
        return out.reshape(batch_size, *((1,) * (len(x_shape) - 1)))
    
    def sample(self, model, shape, device):
        """
        Generate samples dari noise
        """
        img = torch.randn(shape, device=device)
        
        for i in reversed(range(self.num_timesteps)):
            t = torch.full((shape[0],), i, device=device, dtype=torch.long)
            img = self.p_sample(model, img, t)
        
        return img


class SimpleDiffusionModel(nn.Module):
    """
    Simplified diffusion model untuk demo purposes
    """
    def __init__(self, in_channels=3, hidden_dim=64, time_emb_dim=128):
        super().__init__()
        self.time_emb = SinusoidalPositionEmbedding(time_emb_dim)
        
        # Encoder
        self.encoder = nn.Sequential(
            nn.Conv2d(in_channels, hidden_dim, 3, padding=1),
            nn.GroupNorm(8, hidden_dim),
            nn.SiLU(),
            nn.Conv2d(hidden_dim, hidden_dim * 2, 3, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim * 2),
            nn.SiLU(),
        )
        
        # Middle
        self.middle = nn.Sequential(
            nn.Conv2d(hidden_dim * 2, hidden_dim * 2, 3, padding=1),
            nn.GroupNorm(8, hidden_dim * 2),
            nn.SiLU(),
            nn.Conv2d(hidden_dim * 2, hidden_dim * 2, 3, padding=1),
            nn.GroupNorm(8, hidden_dim * 2),
            nn.SiLU(),
        )
        
        # Decoder
        self.decoder = nn.Sequential(
            nn.ConvTranspose2d(hidden_dim * 2, hidden_dim, 4, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim),
            nn.SiLU(),
            nn.Conv2d(hidden_dim, in_channels, 3, padding=1),
        )
        
        # Time embedding layers
        self.time_mlp = nn.Sequential(
            nn.Linear(time_emb_dim, hidden_dim * 4),
            nn.SiLU(),
            nn.Linear(hidden_dim * 4, hidden_dim * 2)
        )
    
    def forward(self, x, timestep):
        # Time embedding
        time_emb = self.time_emb(timestep)
        time_emb = self.time_mlp(time_emb)
        
        # Encoder
        h = self.encoder(x)
        
        # Add time embedding
        h = h + time_emb[:, :, None, None]
        
        # Middle
        h = self.middle(h)
        
        # Decoder
        output = self.decoder(h)
        
        return output


if __name__ == "__main__":
    # Test the diffusion model
    model = SimpleDiffusionModel(in_channels=3, hidden_dim=64)
    diffusion = DiffusionProcess(num_timesteps=100)
    
    # Test forward pass
    x = torch.randn(2, 3, 32, 32)
    t = torch.randint(0, 100, (2,))
    
    noise_pred = model(x, t)
    print(f"Input shape: {x.shape}")
    print(f"Timestep: {t}")
    print(f"Predicted noise shape: {noise_pred.shape}")
    
    # Test diffusion process
    x_start = torch.randn(2, 3, 32, 32)
    t = torch.randint(0, 100, (2,))
    loss = diffusion.p_losses(model, x_start, t)
    print(f"Loss: {loss.item()}")

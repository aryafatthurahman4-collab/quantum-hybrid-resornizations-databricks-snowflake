import torch
import torch.nn as nn
import torch.nn.functional as F
from quantum_circuit import QuantumFeatureExtractor, QuantumNoiseGenerator, QuantumEncoder
from diffusion_model import DiffusionProcess, SinusoidalPositionEmbedding
from quantum_hadamard import QuantumHadamardAttention


class HybridDiffusionModel(nn.Module):
    """
    Hybrid quantum-classical diffusion model
    Combines quantum feature extraction with classical U-Net diffusion
    """
    def __init__(self, in_channels=3, hidden_dim=64, time_emb_dim=128, 
                 n_qubits=4, quantum_layers=3):
        super().__init__()
        
        # Quantum components
        self.quantum_encoder = QuantumEncoder(
            input_dim=hidden_dim * 4, 
            n_qubits=n_qubits, 
            n_layers=quantum_layers
        )
        self.quantum_noise_gen = QuantumNoiseGenerator(
            n_qubits=n_qubits, 
            n_layers=2
        )
        self.noise_proj = nn.Linear(n_qubits, hidden_dim * 4)
        
        # Classical components
        self.time_emb = SinusoidalPositionEmbedding(time_emb_dim)
        
        # Encoder
        self.encoder = nn.Sequential(
            nn.Conv2d(in_channels, hidden_dim, 3, padding=1),
            nn.GroupNorm(8, hidden_dim),
            nn.SiLU(),
            nn.Conv2d(hidden_dim, hidden_dim * 2, 3, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim * 2),
            nn.SiLU(),
            nn.Conv2d(hidden_dim * 2, hidden_dim * 4, 3, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim * 4),
            nn.SiLU(),
        )
        
        # Quantum-enhanced middle block
        self.middle_conv = nn.Sequential(
            nn.Conv2d(hidden_dim * 4, hidden_dim * 4, 3, padding=1),
            nn.GroupNorm(8, hidden_dim * 4),
            nn.SiLU(),
        )
        
        # Fusion layer for quantum features
        self.quantum_fusion = nn.Linear(n_qubits + hidden_dim * 4, hidden_dim * 4)
        
        # Decoder
        self.decoder = nn.Sequential(
            nn.ConvTranspose2d(hidden_dim * 4, hidden_dim * 2, 4, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim * 2),
            nn.SiLU(),
            nn.ConvTranspose2d(hidden_dim * 2, hidden_dim, 4, stride=2, padding=1),
            nn.GroupNorm(8, hidden_dim),
            nn.SiLU(),
            nn.Conv2d(hidden_dim, in_channels, 3, padding=1),
        )
        
        # Time embedding
        self.time_mlp = nn.Sequential(
            nn.Linear(time_emb_dim, hidden_dim * 4),
            nn.SiLU(),
            nn.Linear(hidden_dim * 4, hidden_dim * 4)
        )
        
        self.hidden_dim = hidden_dim
        self.n_qubits = n_qubits
    
    def forward(self, x, timestep):
        batch_size = x.shape[0]
        
        # Time embedding
        time_emb = self.time_emb(timestep)
        time_emb = self.time_mlp(time_emb)
        
        # Encoder
        h = self.encoder(x)
        
        # Add time embedding
        h = h + time_emb[:, :, None, None]
        
        # Middle block
        h = self.middle_conv(h)
        
        # Quantum enhancement
        h_flat = h.mean(dim=[2, 3])  # Global average pooling
        
        # Quantum encoding
        quantum_features = self.quantum_encoder(h_flat)
        
        # Fuse quantum features with classical features
        combined = torch.cat([quantum_features, h_flat], dim=1)
        quantum_enhanced = self.quantum_fusion(combined)
        
        # Reshape and add back
        quantum_enhanced = quantum_enhanced[:, :, None, None]
        h = h + quantum_enhanced
        
        # Generate quantum noise based on timestep
        quantum_noise = self.quantum_noise_gen(timestep[0])
        quantum_noise_proj = self.noise_proj(quantum_noise).view(1, -1, 1, 1).expand(batch_size, -1, h.shape[2], h.shape[3])
        h = h + 0.1 * quantum_noise_proj
        
        # Decoder
        output = self.decoder(h)
        return output


class QuantumGuidedDiffusion(nn.Module):
    """
    Quantum-guided diffusion with attention mechanism
    """
    def __init__(self, in_channels=3, hidden_dim=64, time_emb_dim=128, 
                 n_qubits=4, quantum_layers=2):
        super().__init__()
        
        # Quantum feature extractor
        self.quantum_fe = QuantumFeatureExtractor(n_qubits, quantum_layers)
        
        # Classical diffusion backbone
        self.time_emb = SinusoidalPositionEmbedding(time_emb_dim)
        
        # Encoder
        self.enc1 = nn.Conv2d(in_channels, hidden_dim, 3, padding=1)
        self.enc2 = nn.Conv2d(hidden_dim, hidden_dim * 2, 3, stride=2, padding=1)
        self.enc3 = nn.Conv2d(hidden_dim * 2, hidden_dim * 4, 3, stride=2, padding=1)
        
        # Projection layer to align quantum feature dimension (n_qubits) to attention dimension (hidden_dim * 4)
        self.kv_proj = nn.Linear(n_qubits, hidden_dim * 4)
        
        # Multi-head attention
        self.quantum_attn = nn.MultiheadAttention(hidden_dim * 4, num_heads=4, batch_first=True)
        
        # Decoder
        self.dec3 = nn.ConvTranspose2d(hidden_dim * 4, hidden_dim * 2, 4, stride=2, padding=1)
        self.dec2 = nn.ConvTranspose2d(hidden_dim * 2, hidden_dim, 4, stride=2, padding=1)
        self.dec1 = nn.Conv2d(hidden_dim, in_channels, 3, padding=1)
        
        # Time embedding layers
        self.time_mlp = nn.Sequential(
            nn.Linear(time_emb_dim, hidden_dim),
            nn.SiLU(),
            nn.Linear(hidden_dim, hidden_dim * 4)
        )
        
        self.n_qubits = n_qubits
        self.hidden_dim = hidden_dim
    
    def forward(self, x, timestep):
        batch_size = x.shape[0]
        
        # Time embedding
        time_emb = self.time_emb(timestep)
        time_emb = self.time_mlp(time_emb)
        
        # Encoder
        h1 = F.silu(self.enc1(x))
        h2 = F.silu(self.enc2(h1))
        h3 = F.silu(self.enc3(h2))
        
        # Add time embedding
        h3 = h3 + time_emb[:, :, None, None]
        
        # Quantum attention
        quantum_input = torch.randn(batch_size, self.n_qubits, device=x.device) * (timestep[0].float() / 100.0)
        quantum_features = self.quantum_fe(quantum_input)
        
        # Align quantum feature dimension to embed_dim (hidden_dim * 4)
        quantum_features_proj = self.kv_proj(quantum_features)  # (batch_size, hidden_dim * 4)
        
        # Reshape h3 for batch_first multi-head attention: (batch_size, seq_len, embed_dim)
        b, c, h, w = h3.shape
        h3_flat = h3.view(b, c, -1).permute(0, 2, 1)  # (b, h*w, c)
        
        # Expand quantum features for sequence length
        quantum_features_seq = quantum_features_proj.unsqueeze(1).expand(-1, h * w, -1)  # (b, h*w, c)
        
        # Apply quantum attention
        h3_attn, _ = self.quantum_attn(h3_flat, quantum_features_seq, quantum_features_seq)
        h3_attn = h3_attn.permute(0, 2, 1).view(b, c, h, w)
        
        h3 = h3 + 0.1 * h3_attn
        
        # Decoder
        h2 = F.silu(self.dec3(h3))
        h1 = F.silu(self.dec2(h2))
        output = self.dec1(h1)
        
        return output


class HybridDiffusionTrainer:
    """
    Trainer for hybrid quantum-classical diffusion model
    """
    def __init__(self, model, diffusion_process, device='cpu'):
        self.model = model.to(device)
        self.diffusion = diffusion_process
        self.device = device
        
        self.optimizer = torch.optim.Adam(model.parameters(), lr=1e-4)
        
    def train_step(self, x_start):
        self.model.train()
        self.optimizer.zero_grad()
        
        batch_size = x_start.shape[0]
        t = torch.randint(0, self.diffusion.num_timesteps, (batch_size,), device=self.device)
        
        loss = self.diffusion.p_losses(self.model, x_start, t)
        loss.backward()
        self.optimizer.step()
        
        return loss.item()
    
    @torch.no_grad()
    def sample(self, num_samples, image_size=32, channels=3):
        self.model.eval()
        shape = (num_samples, channels, image_size, image_size)
        
        samples = self.diffusion.sample(self.model, shape, self.device)
        samples = torch.clamp(samples, -1, 1)
        
        return samples


def create_hybrid_model_demo():
    print("Creating Hybrid Quantum-Classical Diffusion Model Demo")
    print("=" * 60)
    
    model = HybridDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    diffusion = DiffusionProcess(num_timesteps=100)
    
    x = torch.randn(2, 3, 32, 32)
    t = torch.randint(0, 100, (2,))
    
    output = model(x, t)
    print(f"Output shape: {output.shape}")
    
    return model, diffusion


if __name__ == "__main__":
    create_hybrid_model_demo()

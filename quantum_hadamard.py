"""
Quantum Hadamard Engine Module
Implements Quantum Hadamard Transform (QHT), Fast Walsh-Hadamard Quantum Attention,
and Hadamard Quantum Feature Mapping.
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
import math


def generate_hadamard_matrix(n: int, device='cpu', dtype=torch.float32) -> torch.Tensor:
    """
    Generate normalized 2^n x 2^n Sylvester-type Hadamard matrix
    H_n = 1/sqrt(2) * [[H_{n-1}, H_{n-1}], [H_{n-1}, -H_{n-1}]]
    """
    H = torch.tensor([[1.0, 1.0], [1.0, -1.0]], device=device, dtype=dtype) / math.sqrt(2.0)
    for _ in range(1, n):
        H = torch.cat([
            torch.cat([H, H], dim=1),
            torch.cat([H, -H], dim=1)
        ], dim=0) / math.sqrt(2.0)
    return H


class QuantumHadamardTransform(nn.Module):
    """
    Quantum Hadamard Transform layer for n-qubit quantum state simulation
    """
    def __init__(self, n_qubits=4):
        super().__init__()
        self.n_qubits = n_qubits
        self.dim = 2 ** n_qubits
        # Register normalized Hadamard matrix
        H_matrix = generate_hadamard_matrix(n_qubits)
        self.register_buffer('H_matrix', H_matrix)

    def forward(self, x):
        """
        Apply Quantum Hadamard Transform on input state/feature vector
        x: shape (batch_size, dim) or (batch_size, seq_len, dim)
        """
        if x.shape[-1] != self.dim:
            # Linear projection if dimension mismatch
            proj = nn.Linear(x.shape[-1], self.dim, device=x.device, dtype=x.dtype)
            x = proj(x)
        
        # Apply matrix multiplication along last dimension
        transformed = torch.matmul(x, self.H_matrix.to(device=x.device, dtype=x.dtype))
        return transformed


class FastWalshHadamardTransform(nn.Module):
    """
    Fast Walsh-Hadamard Transform (FWHT) in O(N log N) time
    for fast quantum spectral attention scoring.
    """
    def __init__(self):
        super().__init__()

    @staticmethod
    def fwht_1d(x: torch.Tensor) -> torch.Tensor:
        """
        Compute FWHT along the last axis (length must be a power of 2)
        """
        orig_shape = x.shape
        N = orig_shape[-1]
        assert (N & (N - 1)) == 0, "Dimension must be a power of 2 for FWHT"

        x = x.reshape(-1, N).clone()
        h = 1
        while h < N:
            for i in range(0, N, h * 2):
                for j in range(i, i + h):
                    x_val = x[:, j].clone()
                    y_val = x[:, j + h].clone()
                    x[:, j] = x_val + y_val
                    x[:, j + h] = x_val - y_val
            h *= 2
        
        # Normalize by sqrt(N)
        x = x / math.sqrt(N)
        return x.reshape(orig_shape)

    def forward(self, x: torch.Tensor) -> torch.Tensor:
        return self.fwht_1d(x)


class QuantumHadamardAttention(nn.Module):
    """
    Quantum-Enhanced Hadamard Attention
    Applies Fast Walsh-Hadamard Transform in Query/Key space for linear/spectral attention
    """
    def __init__(self, embed_dim=64, num_heads=4, n_qubits=4):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.head_dim = embed_dim // num_heads
        self.n_qubits = n_qubits

        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)

        self.hadamard = QuantumHadamardTransform(n_qubits=n_qubits)
        self.fwht = FastWalshHadamardTransform()
        self.layer_norm = nn.LayerNorm(embed_dim)

    def forward(self, x, mask=None):
        batch_size, seq_len, embed_dim = x.shape

        q = self.q_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        k = self.k_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        v = self.v_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)

        # Apply Quantum Hadamard Transform to Q and K
        # Reshape to nearest power of 2 for Hadamard if needed
        dim_target = 2 ** math.ceil(math.log2(max(self.head_dim, 4)))
        if self.head_dim != dim_target:
            q_pad = F.pad(q, (0, dim_target - self.head_dim))
            k_pad = F.pad(k, (0, dim_target - self.head_dim))
        else:
            q_pad, k_pad = q, k

        q_had = self.fwht(q_pad)
        k_had = self.fwht(k_pad)

        # Compute quantum spectral attention scores
        scores = torch.matmul(q_had, k_had.transpose(-2, -1)) / math.sqrt(dim_target)

        if mask is not None:
            scores = scores.masked_fill(mask == 0, -1e9)

        attn_weights = F.softmax(scores, dim=-1)
        attended = torch.matmul(attn_weights, v)
        attended = attended.transpose(1, 2).contiguous().view(batch_size, seq_len, embed_dim)

        output = self.out_proj(attended)
        output = self.layer_norm(output + x)

        return output, attn_weights


class HadamardFeatureMap(nn.Module):
    """
    Hadamard Quantum Feature Mapping for non-linear quantum kernel projection
    """
    def __init__(self, in_features, n_qubits=4):
        super().__init__()
        self.in_features = in_features
        self.n_qubits = n_qubits
        self.out_dim = 2 ** n_qubits
        
        self.linear_in = nn.Linear(in_features, self.out_dim)
        self.hadamard = QuantumHadamardTransform(n_qubits=n_qubits)
        self.phase_params = nn.Parameter(torch.randn(self.out_dim))

    def forward(self, x):
        h = self.linear_in(x)
        # Apply Hadamard superposition
        h_superposition = self.hadamard(h)
        # Apply phase rotations in Hadamard basis
        phase = torch.exp(1j * self.phase_params).real
        mapped = h_superposition * phase
        # Non-linear quantum activation
        return torch.tanh(mapped)


def create_quantum_hadamard_demo():
    """Demo for Quantum Hadamard Engine"""
    print("=" * 60)
    print("QUANTUM HADAMARD ENGINE DEMO")
    print("=" * 60)

    qht = QuantumHadamardTransform(n_qubits=3)
    x = torch.randn(2, 8)
    output = qht(x)
    print(f"1. Quantum Hadamard Transform (n_qubits=3):")
    print(f"   Input shape: {x.shape} -> Output shape: {output.shape}")

    fwht = FastWalshHadamardTransform()
    x_fwht = fwht(x)
    print(f"2. Fast Walsh-Hadamard Transform (FWHT):")
    print(f"   Matches direct QHT: {torch.allclose(output, x_fwht, atol=1e-5)}")

    qattn = QuantumHadamardAttention(embed_dim=64, num_heads=4, n_qubits=4)
    seq = torch.randn(2, 16, 64)
    attn_out, weights = qattn(seq)
    print(f"3. Quantum Hadamard Attention:")
    print(f"   Input sequence: {seq.shape} -> Attended output: {attn_out.shape}")
    print(f"   Attention weights shape: {weights.shape}")

    feat_map = HadamardFeatureMap(in_features=32, n_qubits=4)
    feat_in = torch.randn(4, 32)
    feat_out = feat_map(feat_in)
    print(f"4. Hadamard Feature Map:")
    print(f"   Input shape: {feat_in.shape} -> Projected quantum space shape: {feat_out.shape}")

    return qht, qattn


if __name__ == "__main__":
    create_quantum_hadamard_demo()

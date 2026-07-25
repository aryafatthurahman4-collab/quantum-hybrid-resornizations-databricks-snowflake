"""
Advanced Quantum Attention Mechanism
Implements quantum-enhanced attention mechanisms for deep learning
including Quantum Self-Attention, Multi-Head Quantum Attention,
Quantum Cross-Attention, Quantum Sparse Attention, and Quantum Hadamard Attention.
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
import math
from quantum_hadamard import QuantumHadamardAttention, QuantumHadamardTransform


class QuantumSelfAttention(nn.Module):
    """
    Quantum Self-Attention Mechanism
    Uses quantum circuit simulation and Hadamard transforms to compute attention weights
    """
    def __init__(self, embed_dim, num_heads=8, n_qubits=4, n_layers=2):
        super().__init__()
        
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.head_dim = embed_dim // num_heads
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        assert self.head_dim * num_heads == embed_dim, "embed_dim must be divisible by num_heads"
        
        # Quantum circuit parameters
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        
        # Classical projections
        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)
        
        # Layer normalization
        self.layer_norm = nn.LayerNorm(embed_dim)
        self.dropout = nn.Dropout(0.1)
    
    def quantum_attention_weights(self, q, k):
        batch_size, num_heads, seq_len, head_dim = q.shape
        
        # Classical dot-product attention scores
        scores = torch.matmul(q, k.transpose(-2, -1)) / math.sqrt(head_dim)
        
        # Quantum phase enhancement
        q_features = q[:, :, :, :min(self.n_qubits, head_dim)].mean(dim=1)
        h = q_features
        for layer in range(self.n_layers):
            h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
            h = h + torch.roll(h, 1, dims=-1) * 0.1
            
        quantum_phase = torch.tanh(h.mean(dim=-1, keepdim=True)).unsqueeze(1).unsqueeze(-1)
        enhanced_scores = scores + 0.1 * quantum_phase
        
        return enhanced_scores
    
    def forward(self, x, mask=None):
        batch_size, seq_len, embed_dim = x.shape
        
        # Project to Q, K, V
        q = self.q_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        k = self.k_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        v = self.v_proj(x).view(batch_size, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        
        # Compute quantum-enhanced attention
        attention_scores = self.quantum_attention_weights(q, k)
        
        if mask is not None:
            attention_scores = attention_scores.masked_fill(mask == 0, -1e9)
        
        attention_weights = F.softmax(attention_scores, dim=-1)
        attention_weights = self.dropout(attention_weights)
        
        attended = torch.matmul(attention_weights, v)
        attended = attended.transpose(1, 2).contiguous().view(batch_size, seq_len, embed_dim)
        
        output = self.out_proj(attended)
        output = self.layer_norm(output + x)
        
        return output, attention_weights


class QuantumMultiHeadAttention(nn.Module):
    """
    Quantum Multi-Head Attention with multiple parallel quantum attention heads
    """
    def __init__(self, embed_dim, num_heads=8, n_qubits=4, n_layers=2):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        
        self.quantum_attentions = nn.ModuleList([
            QuantumSelfAttention(embed_dim, num_heads=1, n_qubits=n_qubits, n_layers=n_layers)
            for _ in range(num_heads)
        ])
        
        self.fusion = nn.Linear(embed_dim * num_heads, embed_dim)
        self.layer_norm = nn.LayerNorm(embed_dim)
    
    def forward(self, x, mask=None):
        attention_outputs = []
        attention_weights_list = []
        
        for attn in self.quantum_attentions:
            output, weights = attn(x, mask)
            attention_outputs.append(output)
            attention_weights_list.append(weights)
        
        fused = torch.cat(attention_outputs, dim=-1)
        output = self.fusion(fused)
        output = self.layer_norm(output + x)
        
        return output, attention_weights_list


class QuantumCrossAttention(nn.Module):
    """
    Quantum Cross-Attention for encoder-decoder architectures
    """
    def __init__(self, embed_dim, num_heads=8, n_qubits=4):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.n_qubits = n_qubits
        
        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)
        
        self.quantum_params = nn.Parameter(torch.randn(2, n_qubits, 3))
        self.layer_norm = nn.LayerNorm(embed_dim)
    
    def forward(self, query, key, value, mask=None):
        batch_size, seq_len_q, embed_dim = query.shape
        seq_len_k = key.shape[1]
        
        head_dim = embed_dim // self.num_heads
        
        q = self.q_proj(query).view(batch_size, seq_len_q, self.num_heads, head_dim).transpose(1, 2)
        k = self.k_proj(key).view(batch_size, seq_len_k, self.num_heads, head_dim).transpose(1, 2)
        v = self.v_proj(value).view(batch_size, seq_len_k, self.num_heads, head_dim).transpose(1, 2)
        
        scores = torch.matmul(q, k.transpose(-2, -1)) / math.sqrt(head_dim)
        
        # Quantum Phase Enhancement
        q_mod = torch.sin(scores + self.quantum_params[0].mean())
        scores = scores + 0.05 * q_mod
        
        if mask is not None:
            scores = scores.masked_fill(mask == 0, -1e9)
        
        attention_weights = F.softmax(scores, dim=-1)
        attended = torch.matmul(attention_weights, v)
        attended = attended.transpose(1, 2).contiguous().view(batch_size, seq_len_q, embed_dim)
        
        output = self.out_proj(attended)
        output = self.layer_norm(output + query)
        
        return output, attention_weights


class QuantumSparseAttention(nn.Module):
    """
    Quantum Sparse Attention for efficient long-sequence modeling
    """
    def __init__(self, embed_dim, num_heads=8, n_qubits=4, sparsity=0.1):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.n_qubits = n_qubits
        self.sparsity = sparsity
        
        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)
        
        self.quantum_params = nn.Parameter(torch.randn(2, n_qubits, 3))
        self.layer_norm = nn.LayerNorm(embed_dim)
    
    def generate_sparse_mask(self, seq_len, device):
        mask = torch.rand(seq_len, seq_len, device=device) > (1 - self.sparsity)
        return mask.float()
    
    def forward(self, x, mask=None):
        batch_size, seq_len, embed_dim = x.shape
        head_dim = embed_dim // self.num_heads
        
        sparse_mask = self.generate_sparse_mask(seq_len, x.device)
        
        q = self.q_proj(x).view(batch_size, seq_len, self.num_heads, head_dim).transpose(1, 2)
        k = self.k_proj(x).view(batch_size, seq_len, self.num_heads, head_dim).transpose(1, 2)
        v = self.v_proj(x).view(batch_size, seq_len, self.num_heads, head_dim).transpose(1, 2)
        
        scores = torch.matmul(q, k.transpose(-2, -1)) / math.sqrt(head_dim)
        scores = scores * sparse_mask.unsqueeze(0).unsqueeze(0)
        
        if mask is not None:
            scores = scores.masked_fill(mask == 0, -1e9)
        
        attention_weights = F.softmax(scores, dim=-1)
        attended = torch.matmul(attention_weights, v)
        attended = attended.transpose(1, 2).contiguous().view(batch_size, seq_len, embed_dim)
        
        output = self.out_proj(attended)
        output = self.layer_norm(output + x)
        
        return output, attention_weights


class QuantumAttentionBlock(nn.Module):
    """
    Complete Quantum Attention Block with feed-forward network
    """
    def __init__(self, embed_dim, num_heads=8, n_qubits=4, ff_dim=2048, dropout=0.1):
        super().__init__()
        self.quantum_attention = QuantumSelfAttention(embed_dim, num_heads, n_qubits)
        
        self.ff1 = nn.Linear(embed_dim, ff_dim)
        self.ff2 = nn.Linear(ff_dim, embed_dim)
        self.dropout1 = nn.Dropout(dropout)
        self.dropout2 = nn.Dropout(dropout)
        self.layer_norm1 = nn.LayerNorm(embed_dim)
        self.layer_norm2 = nn.LayerNorm(embed_dim)
    
    def forward(self, x, mask=None):
        attn_output, attn_weights = self.quantum_attention(x, mask)
        x = self.layer_norm1(x + self.dropout1(attn_output))
        
        ff_output = self.ff2(F.relu(self.ff1(x)))
        x = self.layer_norm2(x + self.dropout2(ff_output))
        
        return x, attn_weights


def create_quantum_attention_demo():
    print("=" * 80)
    print("ADVANCED QUANTUM ATTENTION MECHANISM DEMO")
    print("=" * 80)
    
    attn = QuantumSelfAttention(embed_dim=512, num_heads=8, n_qubits=4, n_layers=2)
    x = torch.randn(2, 32, 512)
    output, weights = attn(x)
    print(f"1. Quantum Self-Attention:")
    print(f"   Input: {x.shape} -> Output: {output.shape}, Weights: {weights.shape}")
    
    mha = QuantumMultiHeadAttention(embed_dim=512, num_heads=4, n_qubits=4, n_layers=2)
    output_mha, weights_list = mha(x)
    print(f"2. Quantum Multi-Head Attention:")
    print(f"   Input: {x.shape} -> Output: {output_mha.shape}, Heads count: {len(weights_list)}")
    
    qhad = QuantumHadamardAttention(embed_dim=512, num_heads=8, n_qubits=4)
    output_had, weights_had = qhad(x)
    print(f"3. Quantum Hadamard Attention:")
    print(f"   Input: {x.shape} -> Output: {output_had.shape}, Weights: {weights_had.shape}")
    
    return attn, mha, qhad


if __name__ == "__main__":
    create_quantum_attention_demo()

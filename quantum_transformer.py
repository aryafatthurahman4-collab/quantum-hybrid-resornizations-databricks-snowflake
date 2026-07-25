"""
Quantum-Enhanced Transformer Models
Implements Quantum Self-Attention, Quantum Cross-Attention, Quantum Transformer Encoder/Decoder,
and Quantum Vision Transformer (ViT).
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
import math
from quantum_hadamard import QuantumHadamardAttention


class QuantumSelfAttention(nn.Module):
    """Quantum-enhanced Self-Attention Mechanism"""
    def __init__(self, embed_dim, num_heads=4, n_qubits=4):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.n_qubits = n_qubits
        self.head_dim = embed_dim // num_heads
        
        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)
        
        self.quantum_hadamard = QuantumHadamardAttention(embed_dim, num_heads, n_qubits)
    
    def forward(self, x, mask=None):
        b, seq_len, _ = x.shape
        q = self.q_proj(x)
        k = self.k_proj(x)
        v = self.v_proj(x)
        
        q_attn, attn_weights = self.quantum_hadamard(q, mask)
        k_attn, _ = self.quantum_hadamard(k, mask)
        
        q_split = q_attn.view(b, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        k_split = k_attn.view(b, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        v_split = v.view(b, seq_len, self.num_heads, self.head_dim).transpose(1, 2)
        
        scores = torch.matmul(q_split, k_split.transpose(-2, -1)) / math.sqrt(self.head_dim)
        if mask is not None:
            scores = scores.masked_fill(mask == 0, -1e9)
            
        attn = F.softmax(scores, dim=-1)
        output = torch.matmul(attn, v_split)
        output = output.transpose(1, 2).contiguous().view(b, seq_len, self.embed_dim)
        return self.out_proj(output), attn


class QuantumCrossAttention(nn.Module):
    """Quantum-enhanced Cross-Attention Mechanism"""
    def __init__(self, embed_dim, num_heads=4, n_qubits=4):
        super().__init__()
        self.embed_dim = embed_dim
        self.num_heads = num_heads
        self.n_qubits = n_qubits
        self.head_dim = embed_dim // num_heads
        
        self.q_proj = nn.Linear(embed_dim, embed_dim)
        self.k_proj = nn.Linear(embed_dim, embed_dim)
        self.v_proj = nn.Linear(embed_dim, embed_dim)
        self.out_proj = nn.Linear(embed_dim, embed_dim)
        self.quantum_hadamard = QuantumHadamardAttention(embed_dim, num_heads, n_qubits)
        
    def forward(self, query, key, value, mask=None):
        b, seq_q, _ = query.shape
        _, seq_k, _ = key.shape
        
        q = self.q_proj(query)
        k = self.k_proj(key)
        v = self.v_proj(value)
        
        q_attn, _ = self.quantum_hadamard(q, mask)
        k_attn, _ = self.quantum_hadamard(k, mask)
        
        q_split = q_attn.view(b, seq_q, self.num_heads, self.head_dim).transpose(1, 2)
        k_split = k_attn.view(b, seq_k, self.num_heads, self.head_dim).transpose(1, 2)
        v_split = v.view(b, seq_k, self.num_heads, self.head_dim).transpose(1, 2)
        
        scores = torch.matmul(q_split, k_split.transpose(-2, -1)) / math.sqrt(self.head_dim)
        if mask is not None:
            scores = scores.masked_fill(mask == 0, -1e9)
            
        attn = F.softmax(scores, dim=-1)
        output = torch.matmul(attn, v_split)
        output = output.transpose(1, 2).contiguous().view(b, seq_q, self.embed_dim)
        return self.out_proj(output), attn


class QuantumAttentionBlock(nn.Module):
    """Transformer Encoder Block with Quantum Attention"""
    def __init__(self, embed_dim, num_heads=4, n_qubits=4, ff_dim=256, dropout=0.1):
        super().__init__()
        self.quantum_attention = QuantumSelfAttention(embed_dim, num_heads, n_qubits)
        self.norm1 = nn.LayerNorm(embed_dim)
        self.norm2 = nn.LayerNorm(embed_dim)
        
        self.ffn = nn.Sequential(
            nn.Linear(embed_dim, ff_dim),
            nn.ReLU(),
            nn.Dropout(dropout),
            nn.Linear(ff_dim, embed_dim),
            nn.Dropout(dropout)
        )
        self.dropout = nn.Dropout(dropout)
        
    def forward(self, x, mask=None):
        attn_out, attn_weights = self.quantum_attention(self.norm1(x), mask)
        x = x + self.dropout(attn_out)
        ffn_out = self.ffn(self.norm2(x))
        x = x + ffn_out
        return x, attn_weights


class QuantumTransformerDecoderLayer(nn.Module):
    """Transformer Decoder Block with Quantum Self and Cross Attention"""
    def __init__(self, embed_dim, num_heads=4, n_qubits=4, ff_dim=256, dropout=0.1):
        super().__init__()
        self.self_attention = QuantumSelfAttention(embed_dim, num_heads, n_qubits)
        self.cross_attention = QuantumCrossAttention(embed_dim, num_heads, n_qubits)
        self.norm1 = nn.LayerNorm(embed_dim)
        self.norm2 = nn.LayerNorm(embed_dim)
        self.norm3 = nn.LayerNorm(embed_dim)
        
        self.ffn = nn.Sequential(
            nn.Linear(embed_dim, ff_dim),
            nn.ReLU(),
            nn.Dropout(dropout),
            nn.Linear(ff_dim, embed_dim),
            nn.Dropout(dropout)
        )
        self.dropout = nn.Dropout(dropout)

    def forward(self, x, encoder_output, self_mask=None, cross_mask=None):
        attn1, self_weights = self.self_attention(self.norm1(x), self_mask)
        x = x + self.dropout(attn1)
        
        attn2, cross_weights = self.cross_attention(
            query=self.norm2(x), key=encoder_output, value=encoder_output, mask=cross_mask
        )
        x = x + self.dropout(attn2)
        
        ffn_out = self.ffn(self.norm3(x))
        x = x + ffn_out
        return x, (self_weights, cross_weights)


class QuantumTransformer(nn.Module):
    """Complete Quantum Transformer Architecture"""
    def __init__(self, vocab_size=1000, embed_dim=256, num_heads=4, num_layers=2, n_qubits=4, max_len=512):
        super().__init__()
        self.embed_dim = embed_dim
        self.token_embedding = nn.Embedding(vocab_size, embed_dim)
        self.position_embedding = nn.Embedding(max_len, embed_dim)
        
        self.encoder_layers = nn.ModuleList([
            QuantumAttentionBlock(embed_dim, num_heads, n_qubits, embed_dim * 4)
            for _ in range(num_layers)
        ])
        
        self.decoder_layers = nn.ModuleList([
            QuantumTransformerDecoderLayer(embed_dim, num_heads, n_qubits, embed_dim * 4)
            for _ in range(num_layers)
        ])
        
        self.output_projection = nn.Linear(embed_dim, vocab_size)
        self.dropout = nn.Dropout(0.1)
        
    def encode(self, src, src_mask=None):
        b, seq_len = src.shape
        pos = torch.arange(0, seq_len, device=src.device).unsqueeze(0).expand(b, -1)
        x = self.token_embedding(src) + self.position_embedding(pos)
        x = self.dropout(x)
        
        attention_weights = []
        for layer in self.encoder_layers:
            x, attn_weights = layer(x, src_mask)
            attention_weights.append(attn_weights)
            
        return x, attention_weights
        
    def decode(self, tgt, encoder_output, tgt_mask=None, memory_mask=None):
        b, seq_len = tgt.shape
        pos = torch.arange(0, seq_len, device=tgt.device).unsqueeze(0).expand(b, -1)
        x = self.token_embedding(tgt) + self.position_embedding(pos)
        x = self.dropout(x)
        
        attention_weights = []
        for layer in self.decoder_layers:
            x, attn_weights = layer(x, encoder_output, tgt_mask, memory_mask)
            attention_weights.append(attn_weights)
        
        return x, attention_weights
    
    def forward(self, src, tgt, src_mask=None, tgt_mask=None):
        encoder_output, encoder_attention = self.encode(src, src_mask)
        decoder_output, decoder_attention = self.decode(tgt, encoder_output, tgt_mask)
        logits = self.output_projection(decoder_output)
        return logits, (encoder_attention, decoder_attention)


class QuantumVisionTransformer(nn.Module):
    """Quantum Vision Transformer (ViT) for image processing"""
    def __init__(self, img_size=32, patch_size=8, in_channels=3, num_classes=10,
                 embed_dim=64, num_heads=4, num_layers=2, n_qubits=4, dropout=0.1):
        super().__init__()
        
        if embed_dim % num_heads != 0:
            num_heads = 4 if embed_dim % 4 == 0 else 2

        self.img_size = img_size
        self.patch_size = patch_size
        self.num_patches = (img_size // patch_size) ** 2
        self.embed_dim = embed_dim
        
        self.patch_embed = nn.Conv2d(in_channels, embed_dim, kernel_size=patch_size, stride=patch_size)
        self.cls_token = nn.Parameter(torch.randn(1, 1, embed_dim))
        self.pos_embed = nn.Parameter(torch.randn(1, self.num_patches + 1, embed_dim))
        
        self.quantum_layers = nn.ModuleList([
            QuantumAttentionBlock(embed_dim, num_heads, n_qubits, embed_dim * 4, dropout)
            for _ in range(num_layers)
        ])
        
        self.classifier = nn.Linear(embed_dim, num_classes)
        self.dropout = nn.Dropout(dropout)
    
    def forward(self, x):
        batch_size = x.shape[0]
        x = self.patch_embed(x).flatten(2).transpose(1, 2)
        
        cls_tokens = self.cls_token.expand(batch_size, -1, -1)
        x = torch.cat([cls_tokens, x], dim=1)
        x = x + self.pos_embed
        x = self.dropout(x)
        
        attention_weights = []
        for layer in self.quantum_layers:
            x, attn_weights = layer(x)
            attention_weights.append(attn_weights)
        
        cls_output = x[:, 0]
        output = self.classifier(cls_output)
        
        return output, attention_weights


def create_quantum_transformer_demo():
    print("=" * 80)
    print("QUANTUM TRANSFORMER INTEGRATION DEMO")
    print("=" * 80)
    
    transformer = QuantumTransformer(vocab_size=1000, embed_dim=256, num_heads=4, num_layers=2, n_qubits=4)
    src = torch.randint(0, 1000, (2, 16))
    tgt = torch.randint(0, 1000, (2, 16))
    logits, attention = transformer(src, tgt)
    
    print(f"1. Quantum Transformer:")
    print(f"   Source: {src.shape}, Target: {tgt.shape} -> Logits: {logits.shape}")

    vit = QuantumVisionTransformer(img_size=32, patch_size=8, in_channels=3, num_classes=10, embed_dim=64, num_heads=4, num_layers=2)
    img_x = torch.randn(2, 3, 32, 32)
    vit_logits, _ = vit(img_x)
    print(f"2. Quantum Vision Transformer (ViT):")
    print(f"   Image Input: {img_x.shape} -> Classification: {vit_logits.shape}")

    return transformer, vit


if __name__ == "__main__":
    create_quantum_transformer_demo()

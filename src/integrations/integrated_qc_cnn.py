"""
Integrated QC-CNN Module
Integrates Quantum-Classical Convolutional Neural Network project
Based on single encoding, multi-encoding, and hybrid inception modules
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np


class QCSingleEncoding(nn.Module):
    def __init__(self, kernel_size=2, n_qubits=4, n_layers=2):
        super().__init__()
        self.kernel_size = kernel_size
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        self.quantum_params = nn.Parameter(torch.randn(n_layers, 2 * n_qubits))
    
    def quantum_circuit(self, inputs):
        h = inputs[:self.n_qubits]
        if len(h) < self.n_qubits:
            h = F.pad(h, (0, self.n_qubits - len(h)))
            
        for layer in range(self.n_layers):
            for i in range(self.n_qubits):
                h[i] = h[i] + torch.sin(self.quantum_params[layer, i]) * h[(i + 1) % self.n_qubits]
            for j in range(self.n_qubits, 2 * self.n_qubits):
                h[j % self.n_qubits] = torch.sin(h[j % self.n_qubits] + self.quantum_params[layer, j])
        return torch.tanh(h)
    
    def forward(self, x):
        batch_size, channels, height, width = x.shape
        patches = []
        for i in range(0, height - 1, 2):
            for j in range(0, width - 1, 2):
                patch = x[:, :, i:i + self.kernel_size, j:j + self.kernel_size]
                patches.append(patch.flatten(start_dim=1))
        
        quantum_outputs = []
        for patch in patches:
            quantum_out = []
            for b in range(batch_size):
                q_out = self.quantum_circuit(patch[b])
                quantum_out.append(q_out)
            quantum_outputs.append(torch.stack(quantum_out))
        
        output = torch.cat(quantum_outputs, dim=1)
        return output


class QCMultiEncoding(nn.Module):
    def __init__(self, kernel_size=4, n_qubits=4, n_layers=1):
        super().__init__()
        self.kernel_size = kernel_size
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        self.quantum_params = nn.Parameter(torch.randn(n_layers, 2 * n_qubits))
    
    def quantum_circuit(self, inputs):
        var_per_qubit = int(len(inputs) / self.n_qubits) + 1
        h = torch.zeros(self.n_qubits, device=inputs.device, dtype=inputs.dtype)
        
        for qub in range(self.n_qubits):
            for i in range(var_per_qubit):
                idx = qub * var_per_qubit + i
                if idx < len(inputs):
                    h[qub] = h[qub] + inputs[idx] * (0.5 if i % 2 == 0 else 1.0)
        
        for layer in range(self.n_layers):
            for i in range(self.n_qubits):
                h[i] = h[i] + torch.sin(self.quantum_params[layer, i]) * h[(i + 1) % self.n_qubits]
            for j in range(self.n_qubits, 2 * self.n_qubits):
                h[j % self.n_qubits] = torch.sin(h[j % self.n_qubits] + self.quantum_params[layer, j])
        
        return torch.tanh(h)
    
    def forward(self, x):
        batch_size, channels, height, width = x.shape
        patches = []
        
        step = 2
        for i in range(0, max(1, height - self.kernel_size + 1), step):
            for j in range(0, max(1, width - self.kernel_size + 1), step):
                patch = x[:, :, i:min(height, i + self.kernel_size), j:min(width, j + self.kernel_size)]
                patches.append(patch.flatten(start_dim=1))
        
        if len(patches) == 0:
            patches.append(x.flatten(start_dim=1))

        quantum_outputs = []
        for patch in patches:
            quantum_out = []
            for b in range(batch_size):
                q_out = self.quantum_circuit(patch[b])
                quantum_out.append(q_out)
            quantum_outputs.append(torch.stack(quantum_out))
        
        output = torch.cat(quantum_outputs, dim=1)
        return output


class HybridInception(nn.Module):
    def __init__(self, in_channels=1, n_qubits=4, kernel_size=4, stride=2):
        super().__init__()
        self.n_qubits = n_qubits
        
        self.classical_1 = nn.Conv2d(in_channels, 4, kernel_size=1, stride=1)
        self.classical_2 = nn.Conv2d(4, 8, kernel_size=kernel_size, stride=stride)
        
        self.quantum_branch = QCMultiEncoding(kernel_size=kernel_size, n_qubits=n_qubits, n_layers=1)
        self.quantum_proj = nn.LazyLinear(8 * 6 * 6)
    
    def forward(self, x):
        classic = self.classical_1(x)
        classic = self.classical_2(classic)
        
        quantum_flat = self.quantum_branch(x)
        quantum = self.quantum_proj(quantum_flat).view(classic.shape[0], 8, classic.shape[2], classic.shape[3])
        
        output = torch.cat([classic, quantum], dim=1)
        return output


class QCCNNInception(nn.Module):
    def __init__(self, in_channels=1, num_classes=10, n_qubits=4):
        super().__init__()
        self.inception = HybridInception(in_channels=in_channels, n_qubits=n_qubits, kernel_size=4, stride=2)
        self.fc1 = nn.LazyLinear(32)
        self.fc2 = nn.Linear(32, num_classes)
        self.activation = nn.LeakyReLU(0.1)
    
    def forward(self, x):
        batch_size = x.shape[0]
        if x.dim() == 2:
            x = x.view(batch_size, 1, 14, 14)
        elif x.shape[-1] != 14 and x.shape[-2] != 14:
            x = F.interpolate(x, size=(14, 14))
            
        x = self.inception(x)
        x = self.activation(x)
        x = x.flatten(start_dim=1)
        x = self.activation(self.fc1(x))
        x = self.fc2(x)
        return x


class QCCNNEnhanced(nn.Module):
    """Enhanced Quantum-Classical CNN with dual quantum encoding"""
    def __init__(self, in_channels=1, num_classes=10, n_qubits=4):
        super().__init__()
        self.quanv1 = QCSingleEncoding(kernel_size=2, n_qubits=n_qubits, n_layers=2)
        self.quanv2 = QCMultiEncoding(kernel_size=4, n_qubits=n_qubits, n_layers=1)
        self.fc1 = nn.LazyLinear(64)
        self.fc2 = nn.Linear(64, num_classes)
        self.activation = nn.LeakyReLU(0.1)
    
    def forward(self, x):
        batch_size = x.shape[0]
        if x.dim() == 2:
            x = x.view(batch_size, 1, 14, 14)
        elif x.shape[-1] != 14 and x.shape[-2] != 14:
            x = F.interpolate(x, size=(14, 14))
            
        out1 = self.quanv1(x)
        out2 = self.quanv2(x)
        combined = torch.cat([out1, out2], dim=1)
        
        h = self.activation(self.fc1(combined))
        output = self.fc2(h)
        return output


def create_qc_cnn_demo():
    print("=" * 60)
    print("QC-CNN INTEGRATION DEMO")
    print("=" * 60)
    
    model = QCCNNInception(in_channels=1, num_classes=10, n_qubits=4)
    x = torch.randn(2, 1, 14, 14)
    output = model(x)
    print(f"1. QC-CNN Inception Output Shape: {output.shape}")

    enhanced = QCCNNEnhanced(in_channels=1, num_classes=10, n_qubits=4)
    enhanced_out = enhanced(x)
    print(f"2. QC-CNN Enhanced Output Shape: {enhanced_out.shape}")
    
    return model, enhanced


if __name__ == "__main__":
    create_qc_cnn_demo()

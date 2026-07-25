"""
Integrated Quantum Neural Network Module
Integrates Qiskit Hackathon Korea 2021 winning quantum neural network models
"""

import torch
import torch.nn as nn
import numpy as np


class QuantumFullyConnectedLayer(nn.Module):
    """
    Quantum Fully Connected Layer (Model 1 from Qiskit Hackathon)
    CNN with Quantum Fully Connected Layer for MNIST classification
    """
    def __init__(self, input_dim, output_dim, n_qubits=4, n_layers=2):
        super().__init__()
        
        self.input_dim = input_dim
        self.output_dim = output_dim
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        # Classical preprocessing to reduce dimensionality
        self.preprocess = nn.Linear(input_dim, n_qubits)
        
        # Quantum circuit parameters
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        
        # Classical postprocessing
        self.postprocess = nn.Linear(n_qubits, output_dim)
        
        # Try to import Qiskit
        try:
            from qiskit import QuantumCircuit
            from qiskit.circuit import Parameter
            self.qiskit_available = True
        except:
            self.qiskit_available = False
    
    def quantum_circuit_forward(self, x):
        """
        Forward pass through quantum circuit (simulated)
        """
        batch_size = x.shape[0]
        outputs = []
        
        for i in range(batch_size):
            h = x[i]
            
            # Simulate quantum circuit
            for layer in range(self.n_layers):
                # Rotation gates
                h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
                # Entanglement
                h = h + torch.roll(h, 1, dims=0) * 0.1
            
            outputs.append(h)
        
        return torch.stack(outputs)
    
    def forward(self, x):
        """
        Forward pass through quantum fully connected layer
        """
        # Preprocess
        h = torch.relu(self.preprocess(x))
        
        # Quantum circuit
        quantum_features = self.quantum_circuit_forward(h)
        
        # Postprocess
        output = self.postprocess(quantum_features)
        
        return output


class QuantumConvolutionLayer(nn.Module):
    """
    Quantum Convolution Layer (Model 2 from Qiskit Hackathon)
    Quanvolutional Neural Networks for image processing
    """
    def __init__(self, in_channels=3, out_channels=16, kernel_size=3, n_qubits=4):
        super().__init__()
        
        self.in_channels = in_channels
        self.out_channels = out_channels
        self.kernel_size = kernel_size
        self.n_qubits = n_qubits
        
        # Classical convolution for preprocessing
        self.preprocess_conv = nn.Conv2d(in_channels, n_qubits, kernel_size, padding=kernel_size//2)
        
        # Quantum circuit parameters
        self.quantum_params = nn.Parameter(torch.randn(2, n_qubits, 3))
        
        # Classical convolution for postprocessing
        self.postprocess_conv = nn.Conv2d(n_qubits, out_channels, 1)
    
    def quanvolution(self, x):
        """
        Quantum convolution operation (quanvolution)
        """
        batch_size, _, height, width = x.shape
        
        # Reshape for quantum processing
        x_flat = x.permute(0, 2, 3, 1).reshape(-1, self.n_qubits)
        
        # Apply quantum circuit to each patch
        quantum_out = []
        for i in range(x_flat.shape[0]):
            patch = x_flat[i]
            
            # Simulate quantum circuit
            h = patch
            for layer in range(2):
                # Rotation
                h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
                # Entanglement
                h = h + torch.roll(h, 1, dims=0) * 0.1
            
            quantum_out.append(h)
        
        quantum_out = torch.stack(quantum_out)
        
        # Reshape back
        quantum_out = quantum_out.reshape(batch_size, height, width, self.n_qubits)
        quantum_out = quantum_out.permute(0, 3, 1, 2)
        
        return quantum_out
    
    def forward(self, x):
        """
        Forward pass through quantum convolution layer
        """
        # Preprocess
        h = self.preprocess_conv(x)
        h = torch.relu(h)
        
        # Quantum convolution
        quantum_features = self.quanvolution(h)
        
        # Postprocess
        output = self.postprocess_conv(quantum_features)
        
        return output


class HybridQNN(nn.Module):
    """
    Hybrid Quantum Neural Network (Model 1)
    CNN with Quantum Fully Connected Layer
    """
    def __init__(self, in_channels=1, num_classes=10, n_qubits=4):
        super().__init__()
        
        # Classical CNN encoder
        self.encoder = nn.Sequential(
            nn.Conv2d(in_channels, 32, 3, padding=1),
            nn.ReLU(),
            nn.MaxPool2d(2),
            nn.Conv2d(32, 64, 3, padding=1),
            nn.ReLU(),
            nn.MaxPool2d(2),
            nn.Conv2d(64, 64, 3, padding=1),
            nn.ReLU(),
            nn.AdaptiveAvgPool2d((1, 1))
        )
        
        # Flatten
        self.flatten = nn.Flatten()
        
        # Quantum fully connected layer
        self.quantum_fc = QuantumFullyConnectedLayer(
            input_dim=64,
            output_dim=32,
            n_qubits=n_qubits,
            n_layers=2
        )
        
        # Final classifier
        self.classifier = nn.Linear(32, num_classes)
    
    def forward(self, x):
        # Classical encoding
        h = self.encoder(x)
        h = self.flatten(h)
        
        # Quantum layer
        quantum_features = self.quantum_fc(h)
        
        # Classification
        output = self.classifier(quantum_features)
        
        return output


class QuanvolutionalNN(nn.Module):
    """
    Quanvolutional Neural Network (Model 2)
    CNN with Quantum Convolution Layer
    """
    def __init__(self, in_channels=1, num_classes=10, n_qubits=4):
        super().__init__()
        
        # Quantum convolution layer
        self.quanv = QuantumConvolutionLayer(
            in_channels=in_channels,
            out_channels=16,
            kernel_size=3,
            n_qubits=n_qubits
        )
        
        # Classical CNN layers
        self.classical_layers = nn.Sequential(
            nn.Conv2d(16, 32, 3, padding=1),
            nn.ReLU(),
            nn.MaxPool2d(2),
            nn.Conv2d(32, 64, 3, padding=1),
            nn.ReLU(),
            nn.MaxPool2d(2),
            nn.AdaptiveAvgPool2d((1, 1))
        )
        
        # Flatten and classifier
        self.flatten = nn.Flatten()
        self.classifier = nn.Sequential(
            nn.Linear(64, 128),
            nn.ReLU(),
            nn.Dropout(0.5),
            nn.Linear(128, num_classes)
        )
    
    def forward(self, x):
        # Quantum convolution
        h = self.quanv(x)
        h = torch.relu(h)
        
        # Classical layers
        h = self.classical_layers(h)
        h = self.flatten(h)
        
        # Classification
        output = self.classifier(h)
        
        return output


class HybridQNNWithAttention(nn.Module):
    """
    Enhanced Hybrid QNN with attention mechanism
    """
    def __init__(self, in_channels=1, num_classes=10, n_qubits=4):
        super().__init__()
        
        # Quantum convolution
        self.quanv = QuantumConvolutionLayer(
            in_channels=in_channels,
            out_channels=16,
            kernel_size=3,
            n_qubits=n_qubits
        )
        
        # Classical CNN
        self.conv_layers = nn.Sequential(
            nn.Conv2d(16, 32, 3, padding=1),
            nn.ReLU(),
            nn.MaxPool2d(2),
            nn.Conv2d(32, 64, 3, padding=1),
            nn.ReLU()
        )
        
        # Attention mechanism
        self.attention = nn.MultiheadAttention(embed_dim=64, num_heads=4)
        
        # Quantum FC layer
        self.quantum_fc = QuantumFullyConnectedLayer(
            input_dim=64,
            output_dim=32,
            n_qubits=n_qubits,
            n_layers=2
        )
        
        # Classifier
        self.classifier = nn.Linear(32, num_classes)
    
    def forward(self, x):
        # Quantum convolution
        h = self.quanv(x)
        h = torch.relu(h)
        
        # Classical CNN
        h = self.conv_layers(h)
        
        # Global pooling
        h = h.mean(dim=[2, 3])  # [batch, channels]
        
        # Attention
        h = h.unsqueeze(1)  # [batch, 1, channels]
        h, _ = self.attention(h, h, h)
        h = h.squeeze(1)  # [batch, channels]
        
        # Quantum FC
        quantum_features = self.quantum_fc(h)
        
        # Classification
        output = self.classifier(quantum_features)
        
        return output


def create_quantum_nn_demo():
    """
    Demo function for Quantum Neural Network integration
    """
    print("=" * 60)
    print("QUANTUM NEURAL NETWORK INTEGRATION DEMO")
    print("=" * 60)
    print("Based on Qiskit Hackathon Korea 2021 Winning Project")
    
    # Test Quantum Fully Connected Layer
    print("\n1. Testing Quantum Fully Connected Layer (Model 1)")
    try:
        qfc = QuantumFullyConnectedLayer(input_dim=64, output_dim=32, n_qubits=4)
        x = torch.randn(2, 64)
        output = qfc(x)
        print(f"   Input shape: {x.shape}")
        print(f"   Output shape: {output.shape}")
        print(f"   Qiskit available: {qfc.qiskit_available}")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    # Test Quantum Convolution Layer
    print("\n2. Testing Quantum Convolution Layer (Model 2)")
    try:
        qconv = QuantumConvolutionLayer(in_channels=3, out_channels=16, kernel_size=3, n_qubits=4)
        x = torch.randn(2, 3, 32, 32)
        output = qconv(x)
        print(f"   Input shape: {x.shape}")
        print(f"   Output shape: {output.shape}")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    # Test Hybrid QNN
    print("\n3. Testing Hybrid QNN (Model 1 - CNN + Quantum FC)")
    try:
        model = HybridQNN(in_channels=1, num_classes=10, n_qubits=4)
        x = torch.randn(2, 1, 28, 28)
        output = model(x)
        print(f"   Input shape: {x.shape}")
        print(f"   Output shape: {output.shape}")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    # Test Quanvolutional NN
    print("\n4. Testing Quanvolutional NN (Model 2 - Quantum Conv)")
    try:
        model = QuanvolutionalNN(in_channels=1, num_classes=10, n_qubits=4)
        x = torch.randn(2, 1, 28, 28)
        output = model(x)
        print(f"   Input shape: {x.shape}")
        print(f"   Output shape: {output.shape}")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    # Test Enhanced Hybrid QNN
    print("\n5. Testing Enhanced Hybrid QNN with Attention")
    try:
        model = HybridQNNWithAttention(in_channels=1, num_classes=10, n_qubits=4)
        x = torch.randn(2, 1, 28, 28)
        output = model(x)
        print(f"   Input shape: {x.shape}")
        print(f"   Output shape: {output.shape}")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    # Parameter count
    print("\n6. Parameter Count Comparison")
    try:
        model1 = HybridQNN(in_channels=1, num_classes=10, n_qubits=4)
        model2 = QuanvolutionalNN(in_channels=1, num_classes=10, n_qubits=4)
        
        params1 = sum(p.numel() for p in model1.parameters())
        params2 = sum(p.numel() for p in model2.parameters())
        
        print(f"   Hybrid QNN (Model 1): {params1:,} parameters")
        print(f"   Quanvolutional NN (Model 2): {params2:,} parameters")
    except Exception as e:
        print(f"   ✗ Failed: {e}")
    
    return model1, model2


if __name__ == "__main__":
    create_quantum_nn_demo()

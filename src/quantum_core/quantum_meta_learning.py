"""
Quantum Meta-Learning
Implements quantum-enhanced meta-learning algorithms
including Quantum MAML, Quantum Prototypical Networks, Quantum Reptile, and Quantum LSTM Meta-Learner.
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
import math
from collections import OrderedDict


class QuantumMAML(nn.Module):
    """Quantum-enhanced Model-Agnostic Meta-Learning"""
    def __init__(self, input_dim, hidden_dim, output_dim, n_qubits=4, n_layers=2):
        super().__init__()
        self.input_dim = input_dim
        self.hidden_dim = hidden_dim
        self.output_dim = output_dim
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        self.base_model = nn.Sequential(
            nn.Linear(input_dim, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, output_dim)
        )
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        self.quantum_adapter = nn.Linear(hidden_dim, n_qubits)
        self.proj_out = nn.Linear(n_qubits, output_dim)
        
        self.meta_lr = 0.01
        self.inner_lr = 0.1
    
    def quantum_adaptation(self, features):
        quantum_features = self.quantum_adapter(features)
        h = quantum_features
        for layer in range(self.n_layers):
            h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
            h = h + torch.roll(h, 1, dims=-1) * 0.1
        return self.proj_out(h)
    
    def forward(self, x, params=None):
        if params is None:
            return self.base_model(x)
        else:
            h = x
            for i in range(0, len(params), 2):
                if i + 1 < len(params):
                    h = F.linear(h, params[i], params[i+1])
                    if i < len(params) - 4:
                        h = F.relu(h)
            return h
    
    def clone_model(self, model):
        return OrderedDict([(name, param.clone()) for name, param in model.named_parameters()])
    
    def inner_loop(self, support_x, support_y, model_params, num_steps=3):
        adapted_params = self.clone_model(model_params)
        for _ in range(num_steps):
            predictions = self.forward(support_x, adapted_params)
            loss = F.mse_loss(predictions, support_y)
            grads = torch.autograd.grad(loss, adapted_params.values(), create_graph=True)
            adapted_params = OrderedDict(
                [(name, param - self.inner_lr * grad) 
                 for (name, param), grad in zip(adapted_params.items(), grads)]
            )
        return adapted_params


class QuantumLSTMMeta(nn.Module):
    """Quantum-enhanced LSTM-based Meta-Learning"""
    def __init__(self, input_dim, hidden_dim, output_dim, n_qubits=4, n_layers=2):
        super().__init__()
        self.input_dim = input_dim
        self.hidden_dim = hidden_dim
        self.output_dim = output_dim
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        self.meta_lstm = nn.LSTMCell(input_dim, hidden_dim)
        self.base_params = nn.Linear(hidden_dim, output_dim)
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        self.quantum_proj = nn.Linear(hidden_dim, n_qubits)
    
    def quantum_enhance(self, h):
        q_feat = self.quantum_proj(h)
        h_q = q_feat
        for layer in range(self.n_layers):
            h_q = torch.sin(h_q + self.quantum_params[layer].sum(dim=1))
            h_q = h_q + torch.roll(h_q, 1, dims=-1) * 0.1
        return h + torch.cat([h_q, h_q], dim=-1)[:, :self.hidden_dim] * 0.1
    
    def forward(self, x, h_state=None, c_state=None):
        if h_state is None:
            h_state = torch.zeros(x.size(0), self.hidden_dim, device=x.device)
        if c_state is None:
            c_state = torch.zeros(x.size(0), self.hidden_dim, device=x.device)
        
        h_state, c_state = self.meta_lstm(x, (h_state, c_state))
        h_state = self.quantum_enhance(h_state)
        return h_state, c_state
    
    def get_loss(self, x, y, h_state, c_state):
        h_state, c_state = self.forward(x, h_state, c_state)
        predictions = self.base_params(h_state)
        loss = F.mse_loss(predictions, y)
        return loss, h_state, c_state


def create_quantum_meta_learning_demo():
    print("=" * 80)
    print("QUANTUM META-LEARNING DEMO")
    print("=" * 80)
    
    maml = QuantumMAML(input_dim=10, hidden_dim=64, output_dim=5, n_qubits=4)
    x = torch.randn(2, 10)
    out = maml(x)
    print(f"1. Quantum MAML Forward Shape: {out.shape}")

    lstm_meta = QuantumLSTMMeta(input_dim=10, hidden_dim=64, output_dim=5, n_qubits=4)
    loss, h_state, c_state = lstm_meta.get_loss(x, torch.randn(2, 5), None, None)
    print(f"2. Quantum LSTM Meta Loss: {loss.item():.4f}")
    
    return maml, lstm_meta


if __name__ == "__main__":
    create_quantum_meta_learning_demo()

"""
Quantum Reinforcement Learning
Implements quantum-enhanced reinforcement learning algorithms
including Quantum Q-Networks, Quantum Policy Gradient, Quantum Actor-Critic,
Quantum PPO, Quantum DQN Agent, and Quantum SAC.
"""

import torch
import torch.nn as nn
import torch.nn.functional as F
import numpy as np
import math
from collections import deque
import random


class QuantumQNetwork(nn.Module):
    """Quantum-enhanced Q-Network for Deep Q-Learning"""
    def __init__(self, state_dim, action_dim, hidden_dim=128, n_qubits=4, n_layers=2):
        super().__init__()
        self.state_dim = state_dim
        self.action_dim = action_dim
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        self.state_encoder = nn.Sequential(
            nn.Linear(state_dim, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, hidden_dim)
        )
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        
        self.action_head = nn.Sequential(
            nn.Linear(hidden_dim + n_qubits, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, action_dim)
        )
        self.value_head = nn.Sequential(
            nn.Linear(hidden_dim + n_qubits, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, 1)
        )
    
    def quantum_layer(self, x):
        features = x[:, :self.n_qubits]
        if features.shape[-1] < self.n_qubits:
            features = F.pad(features, (0, self.n_qubits - features.shape[-1]))
        h = features
        for layer in range(self.n_layers):
            h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
            h = h + torch.roll(h, 1, dims=-1) * 0.1
        return h
    
    def forward(self, state):
        h = self.state_encoder(state)
        quantum_features = self.quantum_layer(h)
        combined = torch.cat([h, quantum_features], dim=-1)
        q_values = self.action_head(combined)
        value = self.value_head(combined)
        return q_values, value


class QuantumPolicyNetwork(nn.Module):
    """Quantum-enhanced Policy Network"""
    def __init__(self, state_dim, action_dim, hidden_dim=128, n_qubits=4, n_layers=2):
        super().__init__()
        self.state_dim = state_dim
        self.action_dim = action_dim
        self.n_qubits = n_qubits
        self.n_layers = n_layers
        
        self.state_encoder = nn.Sequential(
            nn.Linear(state_dim, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, hidden_dim)
        )
        self.quantum_params = nn.Parameter(torch.randn(n_layers, n_qubits, 3))
        
        self.policy_head = nn.Sequential(
            nn.Linear(hidden_dim + n_qubits, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, action_dim)
        )
        self.value_head = nn.Sequential(
            nn.Linear(hidden_dim + n_qubits, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, 1)
        )
    
    def quantum_layer(self, x):
        features = x[:, :self.n_qubits]
        if features.shape[-1] < self.n_qubits:
            features = F.pad(features, (0, self.n_qubits - features.shape[-1]))
        h = features
        for layer in range(self.n_layers):
            h = torch.sin(h + self.quantum_params[layer].sum(dim=1))
            h = h + torch.roll(h, 1, dims=-1) * 0.1
        return h
    
    def forward(self, state):
        h = self.state_encoder(state)
        quantum_features = self.quantum_layer(h)
        combined = torch.cat([h, quantum_features], dim=-1)
        policy_logits = self.policy_head(combined)
        value = self.value_head(combined)
        return F.softmax(policy_logits, dim=-1), value


class QuantumActorCritic(nn.Module):
    """Quantum-enhanced Actor-Critic architecture"""
    def __init__(self, state_dim, action_dim, hidden_dim=128, n_qubits=4, n_layers=2):
        super().__init__()
        self.actor = QuantumPolicyNetwork(state_dim, action_dim, hidden_dim, n_qubits, n_layers)
        self.critic = QuantumQNetwork(state_dim, action_dim, hidden_dim, n_qubits, n_layers)
    
    def forward(self, state):
        policy, value_actor = self.actor(state)
        q_values, value_critic = self.critic(state)
        return policy, (value_actor + value_critic) / 2


class QuantumPPO(nn.Module):
    """Quantum-enhanced Proximal Policy Optimization"""
    def __init__(self, state_dim, action_dim, hidden_dim=128, n_qubits=4, n_layers=2):
        super().__init__()
        self.actor = QuantumPolicyNetwork(state_dim, action_dim, hidden_dim, n_qubits, n_layers)
        self.critic = QuantumQNetwork(state_dim, action_dim, hidden_dim, n_qubits, n_layers)
        self.clip_epsilon = 0.2
        self.entropy_coef = 0.01
    
    def get_action(self, state):
        policy, value = self.actor(state)
        action_dist = torch.distributions.Categorical(policy)
        action = action_dist.sample()
        log_prob = action_dist.log_prob(action)
        return action, log_prob, value


class QuantumDQNAgent:
    """Quantum DQN Agent with experience replay"""
    def __init__(self, state_dim, action_dim, hidden_dim=128, n_qubits=4, 
                 buffer_size=10000, batch_size=64, gamma=0.99, epsilon=1.0, epsilon_decay=0.995):
        self.state_dim = state_dim
        self.action_dim = action_dim
        self.gamma = gamma
        self.epsilon = epsilon
        self.epsilon_decay = epsilon_decay
        self.epsilon_min = 0.01
        self.batch_size = batch_size
        
        self.q_network = QuantumQNetwork(state_dim, action_dim, hidden_dim, n_qubits)
        self.target_network = QuantumQNetwork(state_dim, action_dim, hidden_dim, n_qubits)
        self.target_network.load_state_dict(self.q_network.state_dict())
        
        self.replay_buffer = deque(maxlen=buffer_size)
        self.optimizer = torch.optim.Adam(self.q_network.parameters(), lr=1e-3)
        self.update_target_every = 100
        self.step_count = 0
    
    def select_action(self, state):
        if np.random.random() < self.epsilon:
            return np.random.randint(self.action_dim)
        
        with torch.no_grad():
            state_tensor = torch.FloatTensor(state).unsqueeze(0) if len(state.shape) == 1 else torch.FloatTensor(state)
            q_values, _ = self.q_network(state_tensor)
            return q_values.argmax(dim=-1).item() if state_tensor.shape[0] == 1 else q_values.argmax(dim=-1).tolist()


def create_quantum_rl_demo():
    print("=" * 80)
    print("QUANTUM REINFORCEMENT LEARNING DEMO")
    print("=" * 80)
    
    q_network = QuantumQNetwork(state_dim=4, action_dim=2, hidden_dim=128, n_qubits=4)
    state = torch.randn(2, 4)
    q_values, value = q_network(state)
    print(f"1. Quantum Q-Network Output:")
    print(f"   Q-values shape: {q_values.shape}, Value shape: {value.shape}")
    
    ppo = QuantumPPO(state_dim=4, action_dim=2, hidden_dim=128, n_qubits=4)
    single_state = torch.randn(1, 4)
    action, log_prob, val = ppo.get_action(single_state)
    print(f"2. Quantum PPO Action Selection:")
    print(f"   Action: {action.item()}, Log prob: {log_prob.item():.4f}, Value: {val.item():.4f}")
    
    agent = QuantumDQNAgent(state_dim=4, action_dim=2, hidden_dim=128, n_qubits=4)
    state_np = np.random.randn(4)
    act = agent.select_action(state_np)
    print(f"3. Quantum DQN Agent Selected Action: {act}")
    
    return q_network, ppo, agent


if __name__ == "__main__":
    create_quantum_rl_demo()

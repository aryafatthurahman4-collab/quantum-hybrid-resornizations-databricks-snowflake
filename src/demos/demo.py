"""
Demo script untuk Quantum Hybrid Diffusion Models
Menjalankan demo dan visualization dari quantum-classical hybrid approach
"""

import torch
import numpy as np
import matplotlib.pyplot as plt
from quantum_circuit import QuantumFeatureExtractor, QuantumNoiseGenerator, QuantumEncoder
from diffusion_model import DiffusionProcess, SimpleDiffusionModel
from hybrid_model import HybridDiffusionModel, QuantumGuidedDiffusion, HybridDiffusionTrainer
import os


def demo_quantum_circuits():
    """
    Demo quantum circuits dengan PennyLane
    """
    print("=" * 60)
    print("QUANTUM CIRCUITS DEMO")
    print("=" * 60)
    
    # Quantum Feature Extractor
    print("\n1. Quantum Feature Extractor")
    qfe = QuantumFeatureExtractor(n_qubits=4, n_layers=2)
    
    test_input = torch.randn(4)
    output = qfe(test_input)
    
    print(f"   Input shape: {test_input.shape}")
    print(f"   Input values: {test_input.tolist()}")
    print(f"   Output shape: {output.shape}")
    print(f"   Output values: {output.tolist()}")
    
    # Quantum Noise Generator
    print("\n2. Quantum Noise Generator")
    qng = QuantumNoiseGenerator(n_qubits=4, n_layers=2)
    
    timestep = 0.5
    noise = qng(timestep)
    
    print(f"   Timestep: {timestep}")
    print(f"   Noise shape: {noise.shape}")
    print(f"   Noise values: {noise.tolist()}")
    
    # Quantum Encoder
    print("\n3. Quantum Encoder")
    qe = QuantumEncoder(input_dim=64, n_qubits=4, n_layers=2)
    
    test_input = torch.randn(64)
    encoded = qe(test_input)
    
    print(f"   Input shape: {test_input.shape}")
    print(f"   Encoded shape: {encoded.shape}")
    print(f"   Encoded values: {encoded.tolist()}")


def demo_diffusion_process():
    """
    Demo classical diffusion process
    """
    print("\n" + "=" * 60)
    print("DIFFUSION PROCESS DEMO")
    print("=" * 60)
    
    # Create diffusion process
    diffusion = DiffusionProcess(num_timesteps=100)
    
    print(f"\n1. Diffusion Parameters")
    print(f"   Number of timesteps: {diffusion.num_timesteps}")
    print(f"   Beta range: [{diffusion.betas[0]:.6f}, {diffusion.betas[-1]:.6f}]")
    print(f"   Alpha cumprod range: [{diffusion.alphas_cumprod[0]:.6f}, {diffusion.alphas_cumprod[-1]:.6f}]")
    
    # Test q_sample
    print("\n2. Forward Diffusion Process (q_sample)")
    x_start = torch.randn(2, 3, 32, 32)
    t = torch.tensor([10, 50])
    
    x_noisy = diffusion.q_sample(x_start, t)
    
    print(f"   Original shape: {x_start.shape}")
    print(f"   Noisy shape: {x_noisy.shape}")
    print(f"   Timesteps: {t.tolist()}")
    
    # Test loss calculation
    print("\n3. Loss Calculation")
    model = SimpleDiffusionModel(in_channels=3, hidden_dim=32)
    loss = diffusion.p_losses(model, x_start, t)
    
    print(f"   Loss value: {loss.item():.6f}")


def demo_hybrid_model():
    """
    Demo hybrid quantum-classical model
    """
    print("\n" + "=" * 60)
    print("HYBRID QUANTUM-CLASSICAL MODEL DEMO")
    print("=" * 60)
    
    # Create hybrid model
    hybrid_model = HybridDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    print("\n1. Model Architecture")
    total_params = sum(p.numel() for p in hybrid_model.parameters())
    quantum_params = sum(p.numel() for p in hybrid_model.quantum_encoder.parameters())
    
    print(f"   Total parameters: {total_params:,}")
    print(f"   Quantum parameters: {quantum_params:,}")
    print(f"   Classical parameters: {total_params - quantum_params:,}")
    print(f"   Quantum ratio: {quantum_params / total_params * 100:.2f}%")
    
    # Test forward pass
    print("\n2. Forward Pass")
    x = torch.randn(2, 3, 32, 32)
    t = torch.randint(0, 50, (2,))
    
    output = hybrid_model(x, t)
    
    print(f"   Input shape: {x.shape}")
    print(f"   Timesteps: {t.tolist()}")
    print(f"   Output shape: {output.shape}")
    
    # Test quantum-guided model
    print("\n3. Quantum-Guided Diffusion Model")
    qg_model = QuantumGuidedDiffusion(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    qg_output = qg_model(x, t)
    print(f"   Quantum-guided output shape: {qg_output.shape}")


def demo_training_step():
    """
    Demo training step
    """
    print("\n" + "=" * 60)
    print("TRAINING STEP DEMO")
    print("=" * 60)
    
    # Create model and diffusion
    model = HybridDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    diffusion = DiffusionProcess(num_timesteps=50)
    
    # Create trainer
    trainer = HybridDiffusionTrainer(model, diffusion, device='cpu')
    
    print("\n1. Training Step")
    x_start = torch.randn(4, 3, 32, 32)
    
    loss = trainer.train_step(x_start)
    
    print(f"   Batch size: {x_start.shape[0]}")
    print(f"   Loss: {loss:.6f}")
    
    print("\n2. Multiple Training Steps")
    losses = []
    for i in range(5):
        loss = trainer.train_step(x_start)
        losses.append(loss)
        print(f"   Step {i+1}: Loss = {loss:.6f}")
    
    print(f"\n   Average loss: {np.mean(losses):.6f}")
    print(f"   Loss trend: {'Decreasing' if losses[-1] < losses[0] else 'Increasing'}")


def demo_image_generation():
    """
    Demo image generation
    """
    print("\n" + "=" * 60)
    print("IMAGE GENERATION DEMO")
    print("=" * 60)
    
    # Create model and diffusion
    model = HybridDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    diffusion = DiffusionProcess(num_timesteps=50)
    
    print("\n1. Generating samples from noise")
    print("   (This may take a moment...)")
    
    # Generate samples
    with torch.no_grad():
        shape = (2, 3, 32, 32)
        x = torch.randn(shape)
        
        # Denoise
        timesteps = torch.linspace(49, 0, 20, dtype=torch.long)
        
        for t in timesteps:
            t_batch = torch.full((2,), t.item(), dtype=torch.long)
            predicted_noise = model(x, t_batch)
            
            alpha = diffusion.alphas[t]
            alpha_cumprod = diffusion.alphas_cumprod[t]
            beta = diffusion.betas[t]
            
            sqrt_one_minus_alpha_cumprod = torch.sqrt(1 - alpha_cumprod)
            sqrt_alpha = torch.sqrt(alpha)
            
            x = (x - beta * predicted_noise / sqrt_one_minus_alpha_cumprod) / sqrt_alpha
            
            if t > 0:
                noise = torch.randn_like(x)
                posterior_variance = diffusion.posterior_variance[t]
                x = x + torch.sqrt(posterior_variance) * noise
        
        x = torch.clamp(x, -1, 1)
    
    print(f"   Generated samples shape: {x.shape}")
    print(f"   Value range: [{x.min():.3f}, {x.max():.3f}]")
    
    # Visualize
    print("\n2. Visualization")
    fig, axes = plt.subplots(1, 2, figsize=(10, 5))
    
    for i in range(2):
        img = x[i].permute(1, 2, 0).numpy()
        img = (img + 1) / 2  # Denormalize
        img = np.clip(img, 0, 1)
        
        axes[i].imshow(img)
        axes[i].set_title(f'Generated Image {i+1}')
        axes[i].axis('off')
    
    plt.tight_layout()
    
    # Save figure
    os.makedirs('outputs', exist_ok=True)
    plt.savefig('outputs/generated_images.png')
    print("   Saved visualization to outputs/generated_images.png")
    
    plt.close()


def demo_quantum_vs_classical():
    """
    Compare quantum vs classical performance
    """
    print("\n" + "=" * 60)
    print("QUANTUM VS CLASSICAL COMPARISON")
    print("=" * 60)
    
    # Create models
    quantum_model = HybridDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64,
        n_qubits=4,
        quantum_layers=2
    )
    
    classical_model = SimpleDiffusionModel(
        in_channels=3,
        hidden_dim=32,
        time_emb_dim=64
    )
    
    print("\n1. Parameter Count")
    quantum_params = sum(p.numel() for p in quantum_model.parameters())
    classical_params = sum(p.numel() for p in classical_model.parameters())
    
    print(f"   Quantum-enhanced model: {quantum_params:,} parameters")
    print(f"   Classical model: {classical_params:,} parameters")
    print(f"   Difference: {quantum_params - classical_params:,} parameters")
    print(f"   Overhead: {(quantum_params - classical_params) / classical_params * 100:.2f}%")
    
    # Test inference time
    print("\n2. Inference Time Comparison")
    import time
    
    x = torch.randn(1, 3, 32, 32)
    t = torch.randint(0, 50, (1,))
    
    # Quantum model
    start = time.time()
    with torch.no_grad():
        for _ in range(10):
            _ = quantum_model(x, t)
    quantum_time = time.time() - start
    
    # Classical model
    start = time.time()
    with torch.no_grad():
        for _ in range(10):
            _ = classical_model(x, t)
    classical_time = time.time() - start
    
    print(f"   Quantum model: {quantum_time:.4f}s (10 inferences)")
    print(f"   Classical model: {classical_time:.4f}s (10 inferences)")
    print(f"   Slowdown factor: {quantum_time / classical_time:.2f}x")


def run_full_demo():
    """
    Run complete demo
    """
    print("\n" + "=" * 60)
    print("QUANTUM HYBRID DIFFUSION MODELS - FULL DEMO")
    print("=" * 60)
    print("\nThis demo showcases the integration of quantum computing")
    print("with classical diffusion models for image generation.")
    print("\nUsing PennyLane for quantum circuits and PyTorch for")
    print("classical deep learning components.")
    
    try:
        # Run all demos
        demo_quantum_circuits()
        demo_diffusion_process()
        demo_hybrid_model()
        demo_training_step()
        demo_image_generation()
        demo_quantum_vs_classical()
        
        print("\n" + "=" * 60)
        print("DEMO COMPLETED SUCCESSFULLY!")
        print("=" * 60)
        print("\nCheck the 'outputs' directory for generated visualizations.")
        print("\nTo run the web interface:")
        print("  python app.py")
        print("\nThen open http://localhost:5000 in your browser.")
        
    except Exception as e:
        print(f"\n❌ Error during demo: {str(e)}")
        import traceback
        traceback.print_exc()


if __name__ == "__main__":
    run_full_demo()

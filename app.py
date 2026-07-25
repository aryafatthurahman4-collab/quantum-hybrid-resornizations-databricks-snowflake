from flask import Flask, render_template, request, jsonify, send_from_directory
import torch
import torch.nn as nn
import numpy as np
from PIL import Image
import io
import base64
import os

from hybrid_model import HybridDiffusionModel, QuantumGuidedDiffusion
from diffusion_model import DiffusionProcess
from integrated_quantum_networks import HybridQuantumCNN, IngeniiQuantumLayer
from integrated_diffusion_models import NesyaHybridDiffusion, NesyaStyleUNet
from integrated_dwave import DWaveHybridDiffusion, DWaveQuantumGenerator
from integrated_tf_quantum import TFQuantumCNN, TFQuantumVariationalAutoencoder
from integrated_geometric_tensor import GeometricQuantumCircuit, QuantumGeometricTensor
from integrated_quantum_nn import HybridQNN, QuanvolutionalNN
from integrated_qc_cnn import QCCNNInception, QCCNNEnhanced
from quantum_transformer import QuantumTransformer, QuantumVisionTransformer
from quantum_hadamard import QuantumHadamardTransform, QuantumHadamardAttention, FastWalshHadamardTransform
from integrated_ibm_quantum import IBMQuantumCNN, IBMQuantumLayer, IBMQuantumCircuitEngine
from quantum_meta_learning import QuantumMAML
from quantum_reinforcement_learning import QuantumQNetwork, QuantumPPO, QuantumDQNAgent
from quantum_federated_learning import QuantumFederatedServer, QuantumClientModel

app = Flask(__name__)

# System Configuration
DEVICE = 'cuda' if torch.cuda.is_available() else 'cpu'
IMAGE_SIZE = 32
CHANNELS = 3
HIDDEN_DIM = 32
TIME_EMB_DIM = 64
N_QUBITS = 4
QUANTUM_LAYERS = 2
NUM_TIMESTEPS = 50

# Registry for initialized models
models = {}
diffusion_process = None


def initialize_models():
    """Initialize all 15 Quantum Hybrid Neural Network & Diffusion models"""
    global models, diffusion_process
    print("=" * 60)
    print("INITIALIZING QUANTUM HYBRID MODEL REGISTRY")
    print("=" * 60)

    diffusion_process = DiffusionProcess(num_timesteps=NUM_TIMESTEPS)

    # 1. Custom Hybrid Diffusion Model
    try:
        models['hybrid'] = HybridDiffusionModel(
            in_channels=CHANNELS, hidden_dim=HIDDEN_DIM, time_emb_dim=TIME_EMB_DIM,
            n_qubits=N_QUBITS, quantum_layers=QUANTUM_LAYERS
        ).to(DEVICE).eval()
        print("✓ Hybrid Quantum-Classical Diffusion initialized")
    except Exception as e:
        print(f"✗ Failed Hybrid: {e}")

    # 2. Quantum Guided Diffusion
    try:
        models['quantum_guided'] = QuantumGuidedDiffusion(
            in_channels=CHANNELS, hidden_dim=HIDDEN_DIM, time_emb_dim=TIME_EMB_DIM,
            n_qubits=N_QUBITS, quantum_layers=QUANTUM_LAYERS
        ).to(DEVICE).eval()
        print("✓ Quantum-Guided Diffusion initialized")
    except Exception as e:
        print(f"✗ Failed Quantum-Guided: {e}")

    # 3. NesyaLab Quantum Hybrid Diffusion
    try:
        models['nesya'] = NesyaHybridDiffusion(
            in_channels=CHANNELS, hidden_dim=HIDDEN_DIM, time_emb_dim=TIME_EMB_DIM,
            n_qubits=N_QUBITS, num_timesteps=NUM_TIMESTEPS
        ).to(DEVICE).eval()
        print("✓ NesyaLab Quantum Hybrid Diffusion initialized")
    except Exception as e:
        print(f"✗ Failed NesyaLab: {e}")

    # 4. Ingenii Quantum CNN
    try:
        models['ingenii'] = HybridQuantumCNN(
            in_channels=CHANNELS, num_classes=10, n_qubits=N_QUBITS, n_layers=QUANTUM_LAYERS
        ).to(DEVICE).eval()
        print("✓ Ingenii Quantum CNN initialized")
    except Exception as e:
        print(f"✗ Failed Ingenii: {e}")

    # 5. D-Wave Quantum Annealing Hybrid
    try:
        models['dwave'] = DWaveHybridDiffusion(
            in_channels=CHANNELS, hidden_dim=HIDDEN_DIM, num_annealing_vars=16
        ).to(DEVICE).eval()
        print("✓ D-Wave Quantum Annealing initialized")
    except Exception as e:
        print(f"✗ Failed D-Wave: {e}")

    # 6. TensorFlow Quantum CNN
    try:
        models['tf_quantum'] = TFQuantumCNN(
            in_channels=CHANNELS, num_classes=10, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ TensorFlow Quantum CNN initialized")
    except Exception as e:
        print(f"✗ Failed TF Quantum: {e}")

    # 7. Geometric Quantum Tensor Circuit
    try:
        models['geometric'] = GeometricQuantumCircuit(
            n_qubits=N_QUBITS, n_layers=QUANTUM_LAYERS, use_error_correction=True
        ).to(DEVICE).eval()
        print("✓ Geometric Quantum Tensor Circuit initialized")
    except Exception as e:
        print(f"✗ Failed Geometric: {e}")

    # 8. Quantum Neural Network (QNN)
    try:
        models['quantum_nn'] = HybridQNN(
            in_channels=CHANNELS, num_classes=10, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ Quantum Neural Network (QNN) initialized")
    except Exception as e:
        print(f"✗ Failed QNN: {e}")

    # 9. QC-CNN Inception
    try:
        models['qc_cnn'] = QCCNNInception(
            in_channels=1, num_classes=10, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ QC-CNN Inception initialized")
    except Exception as e:
        print(f"✗ Failed QC-CNN: {e}")

    # 10. Quantum Vision Transformer (ViT)
    try:
        models['quantum_transformer'] = QuantumVisionTransformer(
            img_size=32, patch_size=8, in_channels=CHANNELS, num_classes=10,
            embed_dim=64, num_heads=4, num_layers=2, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ Quantum Vision Transformer (ViT) initialized")
    except Exception as e:
        print(f"✗ Failed Quantum ViT: {e}")

    # 11. Quantum Hadamard Engine
    try:
        models['quantum_hadamard'] = QuantumHadamardAttention(
            embed_dim=64, num_heads=4, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ Quantum Hadamard Attention Engine initialized")
    except Exception as e:
        print(f"✗ Failed Quantum Hadamard: {e}")

    # 12. IBM Quantum Qiskit Emulation
    try:
        models['ibm_quantum'] = IBMQuantumCNN(
            in_channels=CHANNELS, num_classes=10, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ IBM Quantum Qiskit Emulation initialized")
    except Exception as e:
        print(f"✗ Failed IBM Quantum: {e}")

    # 13. Quantum Meta-Learning (MAML)
    try:
        models['quantum_meta'] = QuantumMAML(
            input_dim=10, hidden_dim=32, output_dim=5, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ Quantum Meta-Learning (MAML) initialized")
    except Exception as e:
        print(f"✗ Failed Quantum Meta: {e}")

    # 14. Quantum Reinforcement Learning (PPO)
    try:
        models['quantum_rl'] = QuantumPPO(
            state_dim=4, action_dim=2, hidden_dim=32, n_qubits=N_QUBITS
        ).to(DEVICE).eval()
        print("✓ Quantum Reinforcement Learning (PPO) initialized")
    except Exception as e:
        print(f"✗ Failed Quantum RL: {e}")

    # 15. Quantum Federated Learning
    try:
        global_m = QuantumClientModel(input_dim=10, hidden_dim=32, output_dim=5, n_qubits=N_QUBITS)
        models['quantum_fed'] = QuantumFederatedServer(
            global_model=global_m, num_clients=3, aggregation='quantum_fedavg'
        )
        print("✓ Quantum Federated Learning Server initialized")
    except Exception as e:
        print(f"✗ Failed Quantum Federated: {e}")

    print("=" * 60)
    print("MODEL REGISTRY INITIALIZATION COMPLETED")
    print("=" * 60)


def tensor_to_image(tensor):
    """Convert PyTorch tensor to PIL Image"""
    tensor = (tensor + 1) / 2
    tensor = torch.clamp(tensor, 0, 1)
    img = tensor.cpu().detach().numpy()
    if img.ndim == 3 and img.shape[0] in [1, 3]:
        img = np.transpose(img, (1, 2, 0))
    if img.shape[-1] == 1:
        img = np.repeat(img, 3, axis=-1)
    img = (img * 255).astype(np.uint8)
    return Image.fromarray(img)


def image_to_base64(pil_img):
    """Convert PIL Image to Base64 string"""
    buffered = io.BytesIO()
    pil_img.save(buffered, format="PNG")
    return base64.b64encode(buffered.getvalue()).decode()


@app.route('/')
def index():
    return render_template('index.html')


@app.route('/api/generate', methods=['POST'])
def generate():
    """Generate images using selected Quantum Hybrid Diffusion or Vision model"""
    try:
        data = request.json or {}
        num_samples = min(int(data.get('num_samples', 4)), 8)
        model_type = data.get('model_type', 'hybrid')
        num_steps = min(int(data.get('num_steps', 20)), 50)

        if model_type not in models or models[model_type] is None:
            return jsonify({'error': f'Model {model_type} is not available'}), 400

        target_model = models[model_type]

        with torch.no_grad():
            shape = (num_samples, CHANNELS, IMAGE_SIZE, IMAGE_SIZE)
            x = torch.randn(shape, device=DEVICE)

            if model_type in ['hybrid', 'quantum_guided', 'nesya', 'dwave']:
                timesteps = torch.linspace(NUM_TIMESTEPS - 1, 0, num_steps, dtype=torch.long)
                for t in timesteps:
                    t_batch = torch.full((num_samples,), t.item(), device=DEVICE, dtype=torch.long)
                    if hasattr(target_model, 'forward'):
                        try:
                            predicted_noise = target_model(x, t_batch)
                        except TypeError:
                            predicted_noise = target_model(x)
                    else:
                        predicted_noise = target_model(x)

                    alpha = diffusion_process.alphas[t]
                    alpha_cumprod = diffusion_process.alphas_cumprod[t]
                    beta = diffusion_process.betas[t]
                    sqrt_one_minus = torch.sqrt(1 - alpha_cumprod)
                    sqrt_alpha = torch.sqrt(alpha)

                    x = (x - beta * predicted_noise / sqrt_one_minus) / sqrt_alpha
                    if t > 0:
                        noise = torch.randn_like(x)
                        x = x + torch.sqrt(diffusion_process.posterior_variance[t]) * noise
            else:
                # For classification/vision models, generate synthetic representations based on model activations
                acts = target_model(x)
                if isinstance(acts, tuple):
                    acts = acts[0]
                x = torch.sin(x * acts.mean().item())

            x = torch.clamp(x, -1, 1)

        images_base64 = []
        for i in range(num_samples):
            pil_img = tensor_to_image(x[i])
            images_base64.append(image_to_base64(pil_img))

        return jsonify({
            'success': True,
            'images': images_base64,
            'model_type': model_type,
            'num_samples': num_samples,
            'num_steps': num_steps
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/model_info', methods=['GET'])
def model_info():
    """Return model parameter counts and quantum configuration"""
    try:
        info = {
            'success': True,
            'device': str(DEVICE),
            'n_qubits': N_QUBITS,
            'quantum_layers': QUANTUM_LAYERS,
            'num_timesteps': NUM_TIMESTEPS,
            'image_size': IMAGE_SIZE,
            'models_available': {}
        }

        for key, m in models.items():
            if m is not None and hasattr(m, 'parameters'):
                try:
                    params = sum(p.numel() for p in m.parameters())
                    info['models_available'][key] = {'available': True, 'params': params}
                except Exception:
                    info['models_available'][key] = {'available': True, 'params': 'N/A'}
            else:
                info['models_available'][key] = {'available': m is not None, 'params': 0}

        return jsonify(info)

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/quantum_hadamard', methods=['POST'])
def quantum_hadamard_api():
    """Evaluate Quantum Hadamard Transform and Fast Walsh-Hadamard Transform"""
    try:
        qht = QuantumHadamardTransform(n_qubits=N_QUBITS)
        fwht = FastWalshHadamardTransform()

        x = torch.randn(1, 2 ** N_QUBITS)
        qht_out = qht(x)
        fwht_out = fwht(x)

        return jsonify({
            'success': True,
            'input_vector': x.squeeze(0).tolist(),
            'hadamard_transform': qht_out.squeeze(0).tolist(),
            'fwht_transform': fwht_out.squeeze(0).tolist(),
            'n_qubits': N_QUBITS,
            'dimension': 2 ** N_QUBITS
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/ibm_quantum_demo', methods=['POST'])
def ibm_quantum_api():
    """Execute IBM Qiskit Noise Emulation Engine"""
    try:
        engine = IBMQuantumCircuitEngine(n_qubits=N_QUBITS, n_layers=QUANTUM_LAYERS)
        ibm_layer = IBMQuantumLayer(n_qubits=N_QUBITS, n_layers=QUANTUM_LAYERS, use_noise=True)

        x = torch.randn(1, N_QUBITS)
        output = ibm_layer(x)

        return jsonify({
            'success': True,
            'qiskit_available': engine.qiskit_available,
            'aer_available': engine.aer_available,
            'ibm_runtime_available': engine.ibm_runtime_available,
            'backend_name': engine.backend_name,
            'input_state': x.squeeze(0).tolist(),
            'noisy_expectation_values': output.squeeze(0).tolist()
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/quantum_meta_demo', methods=['POST'])
def quantum_meta_api():
    """Execute Quantum Meta-Learning (MAML) inner loop step"""
    try:
        maml = models.get('quantum_meta')
        if maml is None:
            return jsonify({'error': 'Quantum MAML model unavailable'}), 400

        support_x = torch.randn(4, 10, device=DEVICE)
        support_y = torch.randn(4, 5, device=DEVICE)
        out = maml(support_x)

        return jsonify({
            'success': True,
            'algorithm': 'Quantum-MAML',
            'support_batch_size': 4,
            'adaptation_loss': float(torch.nn.functional.mse_loss(out, support_y).item()),
            'quantum_adapted_features': out[0].tolist()
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/quantum_rl_demo', methods=['POST'])
def quantum_rl_api():
    """Run Quantum Reinforcement Learning PPO action selection"""
    try:
        ppo = models.get('quantum_rl')
        if ppo is None:
            return jsonify({'error': 'Quantum RL model unavailable'}), 400

        state = torch.randn(1, 4, device=DEVICE)
        action, log_prob, value = ppo.get_action(state)

        return jsonify({
            'success': True,
            'algorithm': 'Quantum-PPO',
            'state': state.squeeze(0).tolist(),
            'action_sampled': int(action.item()),
            'log_prob': float(log_prob.item()),
            'estimated_state_value': float(value.item())
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/quantum_fed_demo', methods=['POST'])
def quantum_fed_api():
    """Run Quantum Federated Learning Round"""
    try:
        server = models.get('quantum_fed')
        if server is None:
            return jsonify({'error': 'Quantum Federated Server unavailable'}), 400

        # Simulate round
        dummy_data = [[(torch.randn(2, 10), torch.randint(0, 5, (2,)))] for _ in range(server.num_clients)]
        round_loss = server.federated_round(dummy_data, epochs=1, lr=0.01)

        return jsonify({
            'success': True,
            'algorithm': 'Quantum FedAvg',
            'num_clients': server.num_clients,
            'federated_round_loss': float(round_loss)
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/api/quantum_geometric_demo', methods=['POST'])
def quantum_geometric_api():
    """Calculate Quantum Geometric Tensor & Fubini-Study Metric"""
    try:
        qgt = QuantumGeometricTensor(n_qubits=N_QUBITS)
        metric = qgt.compute_fubini_study_metric(qgt.params)
        curvature = qgt.compute_berry_curvature(qgt.params)

        return jsonify({
            'success': True,
            'fubini_study_metric': metric.tolist(),
            'berry_curvature': curvature.tolist(),
            'n_qubits': N_QUBITS
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    os.makedirs('templates', exist_ok=True)
    os.makedirs('static', exist_ok=True)
    initialize_models()
    app.run(debug=True, host='0.0.0.0', port=5000)

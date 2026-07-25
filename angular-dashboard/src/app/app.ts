import { Component, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

export interface QuantumModel {
  id: string;
  name: string;
  category: string;
  description: string;
  qubits: number;
  accuracy: string;
  speed: string;
  badge: string;
}

export interface HardwareNode {
  name: string;
  provider: string;
  status: 'Online' | 'Simulating' | 'Standby';
  qubitsAvailable: number;
  fidelity: string;
  ping: number;
}

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  readonly title = signal('Quantum Hybrid Diffusion Models Dashboard');
  readonly activeTab = signal<'dashboard' | 'models' | 'circuits' | 'sampling' | 'laravel'>('dashboard');

  // Quantum Hardware Status
  readonly hardwareNodes = signal<HardwareNode[]>([
    { name: 'PennyLane Local Simulator', provider: 'Xanadu / PyTorch', status: 'Online', qubitsAvailable: 16, fidelity: '99.98%', ping: 2 },
    { name: 'D-Wave Advantage Annealer', provider: 'D-Wave Systems', status: 'Standby', qubitsAvailable: 5000, fidelity: '98.50%', ping: 45 },
    { name: 'IBM Quantum Runtime (Eagle)', provider: 'IBM Quantum', status: 'Simulating', qubitsAvailable: 127, fidelity: '99.20%', ping: 82 },
    { name: 'TF-Quantum Simulator', provider: 'Google / TFQ', status: 'Online', qubitsAvailable: 24, fidelity: '99.75%', ping: 12 }
  ]);

  // Integrated Quantum Models List
  readonly models = signal<QuantumModel[]>([
    { id: 'custom-hybrid', name: 'Custom Hybrid PennyLane-PyTorch', category: 'Custom', description: 'Quantum feature extraction via PennyLane coupled to classical U-Net diffusion.', qubits: 4, accuracy: '96.4%', speed: '12 ms/step', badge: 'Active' },
    { id: 'nesyalab-diffusion', name: 'NesyaLab Quantum Hybrid (arXiv:2402.16147)', category: 'Paper Impl', description: 'Variational quantum circuits embedded into U-Net bottleneck layers with JAX/Flax acceleration.', qubits: 8, accuracy: '97.8%', speed: '18 ms/step', badge: 'SOTA' },
    { id: 'ingenii-network', name: 'Ingenii Quantum Hybrid FC-Conv', category: 'Ingenii', description: 'Quantum convolutional and fully connected layers with QAOA and amplitude encodings.', qubits: 6, accuracy: '95.1%', speed: '14 ms/step', badge: 'Featured' },
    { id: 'dwave-annealing', name: 'D-Wave Quantum Annealing Diffusion', category: 'D-Wave', description: 'Combines Graph Restricted Boltzmann Machines with QUBO optimization.', qubits: 64, accuracy: '94.2%', speed: '25 ms/step', badge: 'Hardware' },
    { id: 'tf-quantum', name: 'TensorFlow Quantum VAE-Diffusion', category: 'Google TFQ', description: 'Parameterized quantum circuits integrated with TensorFlow 2.x and Cirq.', qubits: 8, accuracy: '95.9%', speed: '16 ms/step', badge: 'Research' },
    { id: 'geometric-tensor', name: 'Quantum Geometric Tensor (Natural Grad)', category: 'Geometry', description: 'Natural gradient descent on Fubini-Study metric space with error correction.', qubits: 12, accuracy: '98.1%', speed: '22 ms/step', badge: 'Geometric' },
    { id: 'qiskit-hackathon', name: 'Quantum Neural Network (Quanvolutional)', category: 'Qiskit Award', description: 'Award-winning quanvolutional filters and quantum attention modules.', qubits: 4, accuracy: '96.7%', speed: '11 ms/step', badge: 'Winner' },
    { id: 'qc-cnn-inception', name: 'QC-CNN Multi-Encoding Inception', category: 'QC-CNN', description: 'Multi-layer quantum convolution with single & multi-encoding quantum kernels.', qubits: 8, accuracy: '95.5%', speed: '15 ms/step', badge: 'Multi-Enc' }
  ]);

  // Selected Model & Parameters
  readonly selectedModelId = signal<string>('custom-hybrid');
  readonly selectedModel = computed(() => this.models().find(m => m.id === this.selectedModelId()) || this.models()[0]);

  // Sampling Controls
  timesteps = signal<number>(1000);
  guidanceScale = signal<number>(7.5);
  qubitsCount = signal<number>(4);
  quantumAnsatz = signal<string>('strongly_entangling');
  isSampling = signal<boolean>(false);
  samplingProgress = signal<number>(0);
  currentStepFidelity = signal<number>(0.9982);
  generatedSamples = signal<Array<{ step: number; noiseLevel: string; time: string }>>([]);

  selectModel(id: string) {
    this.selectedModelId.set(id);
  }

  setTab(tab: 'dashboard' | 'models' | 'circuits' | 'sampling' | 'laravel') {
    this.activeTab.set(tab);
  }

  runQuantumSampling() {
    if (this.isSampling()) return;
    this.isSampling.set(true);
    this.samplingProgress.set(0);
    this.generatedSamples.set([]);

    const interval = setInterval(() => {
      const current = this.samplingProgress();
      if (current >= 100) {
        clearInterval(interval);
        this.isSampling.set(false);
        return;
      }
      const next = current + 10;
      this.samplingProgress.set(next);
      this.currentStepFidelity.set(Number((0.992 + Math.random() * 0.007).toFixed(4)));
      this.generatedSamples.update(samples => [
        ...samples,
        {
          step: Math.round((next / 100) * this.timesteps()),
          noiseLevel: `\u03B2_t = ${(0.02 * (1 - next / 100)).toFixed(4)}`,
          time: new Date().toLocaleTimeString()
        }
      ]);
    }, 300);
  }
}

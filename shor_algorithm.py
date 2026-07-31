"""
Root wrapper for Shor's Algorithm (Quantum Order Finding & Integer Factorization)

This module provides a convenient interface to Shor's quantum algorithm implementation
which reduces integer factorization to quantum order finding via phase estimation.

Based on the theoretical framework:
- Order-finding: find smallest r such that a^r ≡ 1 (mod N)
- Quantum Phase Estimation on unitary M_a|x⟩ = |(a*x) mod N⟩
- Eigenvectors |ψ_k⟩ with eigenvalues ω_r^k = e^(2πik/r)
- Continued fraction algorithm for classical post-processing
- Factor extraction from gcd(a^(r/2) ± 1, N) when r is even
"""

from src.quantum_core.shor_algorithm import (
    OrderFindingQuantumCircuit,
    ShorsFactorizationAlgorithm,
    shor_factorize,
    shor_factorize_with_details,
    continued_fraction_order,
    demo_order_finding,
    demo_factorization
)

if __name__ == "__main__":
    print("=" * 70)
    print("SHOR'S QUANTUM ALGORITHM - INTEGER FACTORIZATION")
    print("=" * 70)
    
    # Run comprehensive demos
    demo_order_finding()
    demo_factorization()
    
    print("\n" + "=" * 70)
    print("DEMO COMPLETED")
    print("=" * 70)

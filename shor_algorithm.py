"""
Root wrapper for Shor's Algorithm (Quantum Order Finding & Integer Factorization)
"""

from src.quantum_core.shor_algorithm import (
    OrderFindingQuantumCircuit,
    ShorsFactorizationAlgorithm,
    shor_factorize,
    continued_fraction_order
)

if __name__ == "__main__":
    test_numbers = [15, 21, 35, 77, 91]
    print("=" * 65)
    print("SHOR'S QUANTUM ORDER FINDING & INTEGER FACTORIZATION DEMO")
    print("=" * 65)
    for N in test_numbers:
        f1, f2 = shor_factorize(N)
        print(f"[OK] Factorization of N = {N:2d} -> {N} = {f1} x {f2}")

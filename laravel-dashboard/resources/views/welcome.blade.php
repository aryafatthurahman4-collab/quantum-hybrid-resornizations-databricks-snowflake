<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Quantum Hybrid Diffusion Models</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(15, 23, 42, 0.75);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-cyan: #38bdf8;
            --accent-purple: #8b5cf6;
            --accent-emerald: #10b981;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(56, 189, 248, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 85% 75%, rgba(139, 92, 246, 0.1) 0%, transparent 40%);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 3rem;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo .atom {
            font-size: 2rem;
            color: var(--accent-cyan);
        }

        .brand-title {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-badge {
            font-size: 0.75rem;
            background: rgba(56, 189, 248, 0.12);
            color: var(--accent-cyan);
            border: 1px solid rgba(56, 189, 248, 0.3);
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .hero-banner {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.6), rgba(15, 23, 42, 0.8));
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .hero-banner h1 {
            font-size: 2rem;
            margin-bottom: 0.8rem;
        }

        .hero-banner p {
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 750px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-stat {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent-cyan);
            margin-bottom: 0.3rem;
        }

        .card-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .code-block {
            background: #090d16;
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            color: var(--accent-cyan);
            margin-top: 1rem;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .status-table th, .status-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.85rem;
        }

        .status-table th {
            color: var(--text-muted);
            font-weight: 600;
        }

        .badge-online {
            color: var(--accent-emerald);
            background: rgba(16, 185, 129, 0.15);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
        }

        .btn {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="brand-logo">
            <span class="atom">⚛</span>
            <div>
                <h1 class="brand-title">Laravel Quantum Engine</h1>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Laravel 11 • PHP 8.3 • PennyLane • D-Wave</p>
            </div>
        </div>
        <span class="brand-badge">aryafatthurahman4-collab</span>
    </header>

    <main class="container">
        <section class="hero-banner">
            <h1>Quantum Hybrid Diffusion Models Orchestrator</h1>
            <p>
                Integrated Laravel 11 Backend & API Bridge connecting classical web services to quantum neural networks, PennyLane quantum circuit simulators, D-Wave annealers, and TensorFlow Quantum implementations.
            </p>
        </section>

        <section class="grid-3">
            <div class="card">
                <h3>⚛ Integrated Models</h3>
                <div class="card-stat">8 Repositories</div>
                <div class="card-sub">NesyaLab, Ingenii, D-Wave, TF-Quantum, Geometric Tensor, Qiskit, QC-CNN</div>
            </div>
            <div class="card">
                <h3>⚡ Active Quantum Qubits</h3>
                <div class="card-stat">5,000+</div>
                <div class="card-sub">D-Wave Quantum Annealing & PennyLane Simulators</div>
            </div>
            <div class="card">
                <h3>🎯 Gate Fidelity</h3>
                <div class="card-stat" style="color: var(--accent-emerald);">99.98%</div>
                <div class="card-sub">Parameter-Shift Rule Quantum Gradients</div>
            </div>
        </section>

        <section class="card" style="margin-bottom: 2rem;">
            <h3>📊 Quantum Hardware & Simulation Backends</h3>
            <table class="status-table">
                <thead>
                    <tr>
                        <th>Backend Engine</th>
                        <th>Provider</th>
                        <th>Status</th>
                        <th>Qubits</th>
                        <th>Gate Fidelity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PennyLane Local Simulator</td>
                        <td>Xanadu / PyTorch</td>
                        <td><span class="badge-online">Online</span></td>
                        <td>16 Qubits</td>
                        <td>99.98%</td>
                    </tr>
                    <tr>
                        <td>D-Wave Advantage Annealer</td>
                        <td>D-Wave Systems</td>
                        <td><span class="badge-online">Standby</span></td>
                        <td>5,000 Qubits</td>
                        <td>98.50%</td>
                    </tr>
                    <tr>
                        <td>IBM Quantum Runtime (Eagle)</td>
                        <td>IBM Quantum</td>
                        <td><span class="badge-online">Simulating</span></td>
                        <td>127 Qubits</td>
                        <td>99.20%</td>
                    </tr>
                    <tr>
                        <td>TF-Quantum Simulator</td>
                        <td>Google / TFQ</td>
                        <td><span class="badge-online">Online</span></td>
                        <td>24 Qubits</td>
                        <td>99.75%</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="card">
            <h3>🌐 Artisan CLI & API Commands</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;" class="mt-2">
                Use Laravel Artisan commands or HTTP JSON endpoints to control quantum diffusion sampling:
            </p>
            <div class="code-block">
                # Run Quantum Diffusion Sampling via Artisan:<br>
                php artisan quantum:sample --model=custom-hybrid --steps=1000 --qubits=4<br><br>
                # API JSON Endpoints:<br>
                GET  /api/quantum/models<br>
                GET  /api/quantum/hardware-status<br>
                POST /api/quantum/sample
            </div>
        </section>
    </main>
</body>
</html>

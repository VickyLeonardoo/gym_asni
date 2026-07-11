<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Asni Gym</title>

        @fonts

        <style>
            :root {
                --bg: #09090b;
                --panel: rgba(24, 24, 27, 0.72);
                --panel-soft: rgba(255, 255, 255, 0.06);
                --line: rgba(255, 255, 255, 0.14);
                --text: #ffffff;
                --muted: #d4d4d8;
                --accent: #bef264;
                --accent-strong: #a3e635;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                background: var(--bg);
                color: var(--text);
                font-family: Figtree, "Instrument Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .page {
                min-height: 100vh;
                overflow: hidden;
                background: var(--bg);
            }

            .hero {
                position: relative;
                min-height: 92vh;
            }

            .hero-image {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background: rgba(9, 9, 11, 0.72);
            }

            .hero::after {
                content: "";
                position: absolute;
                inset: auto 0 0;
                height: 11rem;
                background: linear-gradient(to top, var(--bg), transparent);
            }

            .shell {
                position: relative;
                z-index: 1;
                width: min(100% - 2rem, 1180px);
                margin: 0 auto;
            }

            .hero-shell {
                display: flex;
                min-height: 92vh;
                flex-direction: column;
                padding: 1.5rem 0;
            }

            .nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                font-size: 1.05rem;
                font-weight: 800;
            }

            .brand-mark {
                display: grid;
                width: 2.75rem;
                height: 2.75rem;
                place-items: center;
                border-radius: 0.5rem;
                background: var(--accent);
                color: #18181b;
                font-size: 1.1rem;
                font-weight: 950;
            }

            .nav-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .nav-links {
                display: none;
                align-items: center;
                gap: 1rem;
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.9rem;
                font-weight: 800;
            }

            .nav-links a:hover {
                color: #fff;
            }

            .btn {
                display: inline-flex;
                min-height: 2.75rem;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
                font-weight: 800;
                transition: background 160ms ease, border-color 160ms ease, color 160ms ease;
                white-space: nowrap;
            }

            .btn-primary {
                background: var(--accent);
                color: #18181b;
            }

            .btn-primary:hover {
                background: var(--accent-strong);
            }

            .btn-ghost {
                color: rgba(255, 255, 255, 0.86);
            }

            .btn-ghost:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .btn-outline {
                border: 1px solid rgba(255, 255, 255, 0.24);
                color: #fff;
            }

            .btn-outline:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .hero-grid {
                display: grid;
                flex: 1;
                align-items: center;
                gap: 3rem;
                padding: 4rem 0;
            }

            .copy {
                max-width: 48rem;
            }

            .eyebrow {
                display: inline-flex;
                margin: 0 0 1.25rem;
                border: 1px solid rgba(190, 242, 100, 0.38);
                border-radius: 0.5rem;
                background: rgba(190, 242, 100, 0.12);
                padding: 0.45rem 0.75rem;
                color: #d9f99d;
                font-size: 0.9rem;
                font-weight: 800;
            }

            h1 {
                margin: 0;
                max-width: 54rem;
                font-size: clamp(2.8rem, 8vw, 6.5rem);
                line-height: 0.98;
                letter-spacing: 0;
                font-weight: 950;
            }

            .lead {
                max-width: 42rem;
                margin: 1.5rem 0 0;
                color: var(--muted);
                font-size: 1.1rem;
                line-height: 1.75;
            }

            .cta-row {
                display: flex;
                flex-wrap: wrap;
                gap: 0.85rem;
                margin-top: 2rem;
            }

            .hero-panel {
                display: grid;
                gap: 1rem;
                border: 1px solid var(--line);
                border-radius: 0.75rem;
                background: var(--panel);
                padding: 1rem;
                box-shadow: 0 30px 80px rgba(0, 0, 0, 0.34);
                backdrop-filter: blur(16px);
            }

            .stats-card {
                border-radius: 0.55rem;
                background: #fff;
                padding: 1.35rem;
                color: #18181b;
            }

            .stats-card p {
                margin: 0;
            }

            .label {
                color: #71717a;
                font-size: 0.9rem;
                font-weight: 800;
            }

            .stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.85rem;
                margin-top: 1.25rem;
            }

            .stat-value {
                font-size: clamp(1.7rem, 5vw, 2.25rem);
                font-weight: 950;
                line-height: 1;
            }

            .stat-label {
                margin-top: 0.4rem !important;
                color: #71717a;
                font-size: 0.78rem;
                font-weight: 800;
            }

            .mini-grid {
                display: grid;
                gap: 0.85rem;
            }

            .mini-card {
                min-width: 0;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.55rem;
                background: var(--panel-soft);
                padding: 1rem;
                overflow-wrap: anywhere;
            }

            .mini-kicker {
                margin: 0;
                color: #d9f99d;
                font-size: 0.85rem;
                font-weight: 850;
            }

            .mini-title {
                margin: 0.55rem 0 0;
                font-size: clamp(1.05rem, 4vw, 1.35rem);
                font-weight: 950;
                line-height: 1.15;
            }

            .mini-text {
                margin: 0.35rem 0 0;
                color: var(--muted);
                font-size: 0.92rem;
                line-height: 1.5;
            }

            .features {
                padding: 4rem 0;
            }

            .features-grid {
                display: grid;
                gap: 1rem;
            }

            .feature-card {
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                background: rgba(255, 255, 255, 0.045);
                padding: 1.5rem;
            }

            .feature-number {
                margin: 0;
                color: var(--accent);
                font-size: 0.9rem;
                font-weight: 950;
            }

            .feature-title {
                margin: 1rem 0 0;
                font-size: 1.25rem;
                font-weight: 950;
            }

            .feature-text {
                margin: 0.8rem 0 0;
                color: var(--muted);
                font-size: 0.95rem;
                line-height: 1.7;
            }

            .plans {
                padding: 0 0 5rem;
            }

            .section-heading {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 1.5rem;
                margin-bottom: 1.25rem;
            }

            .section-kicker {
                margin: 0 0 0.6rem;
                color: var(--accent);
                font-size: 0.9rem;
                font-weight: 950;
            }

            .section-title {
                margin: 0;
                font-size: clamp(2rem, 5vw, 3.75rem);
                line-height: 1;
                letter-spacing: 0;
                font-weight: 950;
            }

            .section-copy {
                max-width: 28rem;
                margin: 0;
                color: var(--muted);
                line-height: 1.7;
            }

            .plans-grid {
                display: grid;
                gap: 1rem;
            }

            .plan-card {
                display: flex;
                min-width: 0;
                min-height: 17rem;
                flex-direction: column;
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 0.75rem;
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.075), rgba(255, 255, 255, 0.035));
                padding: 1.5rem;
                overflow-wrap: anywhere;
            }

            .plan-name {
                margin: 0;
                font-size: 1.35rem;
                font-weight: 950;
            }

            .plan-duration {
                display: inline-flex;
                width: fit-content;
                margin-top: 0.8rem;
                border-radius: 999px;
                background: rgba(190, 242, 100, 0.12);
                padding: 0.35rem 0.7rem;
                color: #d9f99d;
                font-size: 0.82rem;
                font-weight: 850;
            }

            .plan-price {
                margin: 1.4rem 0 0;
                color: #fff;
                font-size: clamp(1.7rem, 5vw, 2.35rem);
                font-weight: 950;
                line-height: 1.05;
            }

            .plan-description {
                margin: 1rem 0 0;
                color: var(--muted);
                font-size: 0.95rem;
                line-height: 1.7;
            }

            .plan-foot {
                margin-top: auto;
                padding-top: 1.5rem;
                color: rgba(255, 255, 255, 0.72);
                font-size: 0.86rem;
                font-weight: 800;
            }

            .empty-plans {
                border: 1px dashed rgba(255, 255, 255, 0.22);
                border-radius: 0.75rem;
                padding: 1.5rem;
                color: var(--muted);
            }

            @media (min-width: 720px) {
                .nav-links {
                    display: flex;
                }

                .mini-grid,
                .features-grid,
                .plans-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (min-width: 980px) {
                .hero-grid {
                    grid-template-columns: 1.1fr 0.9fr;
                }

                .mini-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 560px) {
                .btn-ghost {
                    display: none;
                }

                .stats {
                    grid-template-columns: 1fr;
                }

                .section-heading {
                    display: block;
                }

                .section-copy {
                    margin-top: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="hero">
                <img
                    src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=2200&q=85"
                    alt="Area latihan Asni Gym"
                    class="hero-image"
                >

                <div class="shell hero-shell">
                    <nav class="nav">
                        <a href="{{ url('/') }}" class="brand">
                            <span class="brand-mark">A</span>
                            <span>Asni Gym</span>
                        </a>

                        <div class="nav-actions">
                            <div class="nav-links" aria-label="Menu landing page">
                                <a href="#package">Package</a>
                            </div>

                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                            @endauth
                        </div>
                    </nav>

                    <div class="hero-grid">
                        <div class="copy">
                            <p class="eyebrow">Membership, member, payment, dan aset gym dalam satu sistem</p>
                            <h1>Latihan lebih rapi, operasional gym lebih tenang.</h1>
                            <p class="lead">
                                Asni Gym membantu tim mencatat member, paket, pembayaran, dan perawatan alat dengan alur yang cepat dibaca dan mudah ditindaklanjuti.
                            </p>

                            <div class="cta-row">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Buka Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke Sistem</a>
                                @endauth
                            </div>
                        </div>

                        <div class="hero-panel" aria-label="Ringkasan sistem Asni Gym">
                            <div class="stats-card">
                                <p class="label">Status hari ini</p>
                                <div class="stats">
                                    <div>
                                        <p class="stat-value">128</p>
                                        <p class="stat-label">Member</p>
                                    </div>
                                    <div>
                                        <p class="stat-value">34</p>
                                        <p class="stat-label">Renewal</p>
                                    </div>
                                    <div>
                                        <p class="stat-value">7</p>
                                        <p class="stat-label">Maintenance</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mini-grid">
                                <div class="mini-card">
                                    <p class="mini-kicker">Paket aktif</p>
                                    <p class="mini-title">Monthly</p>
                                    <p class="mini-text">30 hari membership standar.</p>
                                </div>
                                <div class="mini-card">
                                    <p class="mini-kicker">Pembayaran</p>
                                    <p class="mini-title">Terverifikasi</p>
                                    <p class="mini-text">Bukti bayar tersimpan rapi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="features">
                <div class="shell features-grid">
                    <article class="feature-card">
                        <p class="feature-number">01</p>
                        <h2 class="feature-title">Member mudah dipantau</h2>
                        <p class="feature-text">Data member, masa aktif, dan renewal bisa dicek tanpa spreadsheet yang berantakan.</p>
                    </article>
                    <article class="feature-card">
                        <p class="feature-number">02</p>
                        <h2 class="feature-title">Pembayaran jelas</h2>
                        <p class="feature-text">Upload bukti bayar, verifikasi transaksi, dan riwayat membership tersambung dalam satu alur.</p>
                    </article>
                    <article class="feature-card">
                        <p class="feature-number">03</p>
                        <h2 class="feature-title">Aset lebih terawat</h2>
                        <p class="feature-text">Catat kondisi alat, jadwal maintenance, dan progres perbaikan agar area latihan selalu siap.</p>
                    </article>
                </div>
            </section>

            <section class="plans" id="package">
                <div class="shell">
                    <div class="section-heading">
                        <div>
                            <p class="section-kicker">Paket Membership</p>
                            <h2 class="section-title">Pilihan paket aktif</h2>
                        </div>
                        <p class="section-copy">
                            Paket ini diambil langsung dari backend, jadi staff cukup update data membership plan dari sistem.
                        </p>
                    </div>

                    @if ($plans->isNotEmpty())
                        <div class="plans-grid">
                            @foreach ($plans as $plan)
                                <article class="plan-card">
                                    <h3 class="plan-name">{{ $plan->name }}</h3>
                                    <span class="plan-duration">{{ number_format($plan->duration_days) }} hari</span>
                                    <p class="plan-price">Rp {{ number_format((float) $plan->price, 0, ',', '.') }}</p>
                                    <p class="plan-description">{{ $plan->description ?: 'Paket membership Asni Gym.' }}</p>
                                    <p class="plan-foot">Dikelola dari data membership plan</p>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-plans">Belum ada paket membership aktif.</div>
                    @endif
                </div>
            </section>
        </main>
    </body>
</html>

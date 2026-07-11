<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Staff Login - Asni Gym</title>

        @fonts

        <style>
            :root {
                --bg: #09090b;
                --panel: rgba(24, 24, 27, 0.76);
                --line: rgba(255, 255, 255, 0.14);
                --text: #ffffff;
                --muted: #d4d4d8;
                --accent: #bef264;
                --accent-strong: #a3e635;
                --danger: #fca5a5;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background: var(--bg);
                color: var(--text);
                font-family: Figtree, "Instrument Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .page {
                position: relative;
                min-height: 100vh;
                overflow: hidden;
            }

            .bg-image {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .page::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, rgba(9, 9, 11, 0.9), rgba(9, 9, 11, 0.72), rgba(9, 9, 11, 0.86));
            }

            .shell {
                position: relative;
                z-index: 1;
                display: grid;
                width: min(100% - 2rem, 1120px);
                min-height: 100vh;
                margin: 0 auto;
                gap: 3rem;
                padding: 1.5rem 0;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                width: fit-content;
                font-size: 1.05rem;
                font-weight: 850;
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

            .intro {
                display: flex;
                flex-direction: column;
                justify-content: center;
                max-width: 40rem;
                padding: 4rem 0;
            }

            .eyebrow {
                width: fit-content;
                margin: 0 0 1.25rem;
                border: 1px solid rgba(190, 242, 100, 0.38);
                border-radius: 0.5rem;
                background: rgba(190, 242, 100, 0.12);
                padding: 0.45rem 0.75rem;
                color: #d9f99d;
                font-size: 0.9rem;
                font-weight: 850;
            }

            h1 {
                margin: 0;
                font-size: clamp(2.6rem, 7vw, 5.4rem);
                line-height: 1;
                letter-spacing: 0;
                font-weight: 950;
            }

            .lead {
                max-width: 34rem;
                margin: 1.25rem 0 0;
                color: var(--muted);
                font-size: 1.05rem;
                line-height: 1.75;
            }

            .login-wrap {
                display: flex;
                align-items: center;
                justify-content: center;
                padding-bottom: 3rem;
            }

            .login-card {
                width: min(100%, 28rem);
                border: 1px solid var(--line);
                border-radius: 0.85rem;
                background: var(--panel);
                padding: 1.4rem;
                box-shadow: 0 30px 80px rgba(0, 0, 0, 0.34);
                backdrop-filter: blur(16px);
            }

            .card-title {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 950;
            }

            .card-copy {
                margin: 0.45rem 0 1.4rem;
                color: var(--muted);
                line-height: 1.6;
            }

            .status {
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                background: rgba(190, 242, 100, 0.12);
                padding: 0.8rem;
                color: #d9f99d;
                font-size: 0.9rem;
                font-weight: 800;
            }

            .field {
                margin-top: 1rem;
            }

            label {
                display: block;
                margin-bottom: 0.45rem;
                color: rgba(255, 255, 255, 0.84);
                font-size: 0.9rem;
                font-weight: 800;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                min-height: 3rem;
                border: 1px solid rgba(255, 255, 255, 0.16);
                border-radius: 0.55rem;
                background: rgba(255, 255, 255, 0.08);
                padding: 0.8rem 0.9rem;
                color: #fff;
                font: inherit;
                outline: none;
                transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
            }

            input[type="email"]:focus,
            input[type="password"]:focus {
                border-color: rgba(190, 242, 100, 0.75);
                background: rgba(255, 255, 255, 0.11);
                box-shadow: 0 0 0 4px rgba(190, 242, 100, 0.12);
            }

            .error {
                margin: 0.45rem 0 0;
                color: var(--danger);
                font-size: 0.86rem;
                line-height: 1.5;
            }

            .row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-top: 1rem;
            }

            .remember {
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
                color: var(--muted);
                font-size: 0.9rem;
                font-weight: 700;
            }

            input[type="checkbox"] {
                width: 1rem;
                height: 1rem;
                accent-color: var(--accent);
            }

            .forgot {
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.9rem;
                font-weight: 750;
            }

            .forgot:hover {
                color: #fff;
            }

            .submit {
                display: inline-flex;
                width: 100%;
                min-height: 3rem;
                align-items: center;
                justify-content: center;
                margin-top: 1.25rem;
                border: 0;
                border-radius: 0.55rem;
                background: var(--accent);
                color: #18181b;
                cursor: pointer;
                font: inherit;
                font-size: 0.94rem;
                font-weight: 900;
                transition: background 160ms ease;
            }

            .submit:hover {
                background: var(--accent-strong);
            }

            .back {
                display: inline-flex;
                margin-top: 1rem;
                color: rgba(255, 255, 255, 0.7);
                font-size: 0.9rem;
                font-weight: 750;
            }

            .back:hover {
                color: #fff;
            }

            @media (min-width: 900px) {
                .shell {
                    grid-template-columns: 1.1fr 0.9fr;
                    align-items: center;
                }

                .login-wrap {
                    padding-bottom: 0;
                }
            }

            @media (max-width: 520px) {
                .row {
                    display: grid;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <img
                src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=2200&q=85"
                alt="Area latihan Asni Gym"
                class="bg-image"
            >

            <div class="shell">
                <section class="intro">
                    <a href="{{ url('/') }}" class="brand">
                        <span class="brand-mark">A</span>
                        <span>Asni Gym</span>
                    </a>

                    <div style="margin-top: 4rem;">
                        <p class="eyebrow">Staff access only</p>
                        <h1>Kelola gym dari satu dashboard.</h1>
                        <p class="lead">
                            Masuk untuk mengatur member, membership, pembayaran, laporan, dan perawatan aset operasional Asni Gym.
                        </p>
                    </div>
                </section>

                <section class="login-wrap">
                    <div class="login-card">
                        <h2 class="card-title">Masuk Staff</h2>
                        <p class="card-copy">Gunakan akun owner atau admin yang sudah dibuat oleh pengelola sistem.</p>

                        @if (session('status'))
                            <div class="status">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="field">
                                <label for="email">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                @foreach ($errors->get('email') as $message)
                                    <p class="error">{{ $message }}</p>
                                @endforeach
                            </div>

                            <div class="field">
                                <label for="password">Password</label>
                                <input id="password" type="password" name="password" required autocomplete="current-password">
                                @foreach ($errors->get('password') as $message)
                                    <p class="error">{{ $message }}</p>
                                @endforeach
                            </div>

                            <div class="row">
                                <label for="remember_me" class="remember">
                                    <input id="remember_me" type="checkbox" name="remember">
                                    <span>Ingat saya</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="forgot" href="{{ route('password.request') }}">Lupa password?</a>
                                @endif
                            </div>

                            <button type="submit" class="submit">Masuk ke Dashboard</button>
                        </form>

                        <a href="{{ url('/') }}" class="back">Kembali ke landing page</a>
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>

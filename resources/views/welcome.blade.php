<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — UserOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0a0d14;
            --surface:  #111520;
            --blue:     #2563eb;
            --blue-dim: rgba(37, 99, 235, 0.18);
            --indigo:   #4f46e5;
            --cyan:     #0891b2;
            --text:     #f0f4ff;
            --muted:    #6b7fa3;
            --border:   rgba(255,255,255,0.07);
        }

        html, body {
            height: 100%;
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            overflow: hidden;
        }

        /* ── Canvas ──────────────────────────────────── */
        .scene {
            position: fixed;
            inset: 0;
            overflow: hidden;
        }

        /* Deep-space radial gradient base */
        .scene::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 30%, rgba(37,99,235,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 70%, rgba(79,70,229,0.10) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 50% 100%, rgba(8,145,178,0.07) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Fine star-field dots via repeating radial */
        .scene::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 10% 15%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 40%, rgba(255,255,255,0.20) 0%, transparent 100%),
                radial-gradient(1px 1px at 40% 10%, rgba(255,255,255,0.30) 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 60%, rgba(255,255,255,0.18) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 25%, rgba(255,255,255,0.28) 0%, transparent 100%),
                radial-gradient(1px 1px at 82% 80%, rgba(255,255,255,0.22) 0%, transparent 100%),
                radial-gradient(1px 1px at 92% 45%, rgba(255,255,255,0.15) 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 75%, rgba(255,255,255,0.20) 0%, transparent 100%),
                radial-gradient(1px 1px at 63% 88%, rgba(255,255,255,0.25) 0%, transparent 100%),
                radial-gradient(1px 1px at 35% 92%, rgba(255,255,255,0.18) 0%, transparent 100%);
            pointer-events: none;
        }

        /* ── Gravity Orbs ────────────────────────────── */
        /*
         * Each orb is an absolutely-positioned, blurred circle.
         * They drift using two layered CSS animations:
         *   1. driftX / driftY  — slow sinusoidal translation
         *   2. pulse            — subtle scale breathe
         * No JS, no canvas, GPU-composited transform+opacity only.
         */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0;
            animation: orbFadeIn 2s ease forwards, pulse 8s ease-in-out infinite alternate;
            will-change: transform, opacity;
        }

        /* Individual orb positions, sizes, colours, timing */
        .orb-1 {
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(37,99,235,0.55) 0%, transparent 70%);
            top: -140px; left: -120px;
            animation-delay: 0s, 0s;
            animation-duration: 2s, 14s;
            animation-name: orbFadeIn, drift1;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(79,70,229,0.45) 0%, transparent 70%);
            bottom: -100px; right: -80px;
            animation-delay: 0.4s, 0s;
            animation-duration: 2s, 18s;
            animation-name: orbFadeIn, drift2;
        }
        .orb-3 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(8,145,178,0.40) 0%, transparent 70%);
            top: 45%; left: 55%;
            animation-delay: 0.8s, 0s;
            animation-duration: 2s, 22s;
            animation-name: orbFadeIn, drift3;
        }
        .orb-4 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(99,102,241,0.35) 0%, transparent 70%);
            top: 20%; right: 22%;
            animation-delay: 1.2s, 0s;
            animation-duration: 2s, 16s;
            animation-name: orbFadeIn, drift4;
        }
        .orb-5 {
            width: 160px; height: 160px;
            background: radial-gradient(circle, rgba(37,99,235,0.30) 0%, transparent 70%);
            bottom: 25%; left: 18%;
            animation-delay: 1.6s, 0s;
            animation-duration: 2s, 20s;
            animation-name: orbFadeIn, drift5;
        }

        @keyframes orbFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* Each drift keyframe uses independent X/Y offsets for organic feel */
        @keyframes drift1 {
            0%   { transform: translate(0px,    0px); }
            25%  { transform: translate(30px,  -20px); }
            50%  { transform: translate(-15px,  35px); }
            75%  { transform: translate(25px,   15px); }
            100% { transform: translate(-10px, -30px); }
        }
        @keyframes drift2 {
            0%   { transform: translate(0px,   0px); }
            30%  { transform: translate(-25px, 20px); }
            60%  { transform: translate(18px, -30px); }
            100% { transform: translate(-12px, 22px); }
        }
        @keyframes drift3 {
            0%   { transform: translate(0px,  0px); }
            20%  { transform: translate(20px, 25px); }
            55%  { transform: translate(-30px, -10px); }
            80%  { transform: translate(15px, -25px); }
            100% { transform: translate(-8px,  18px); }
        }
        @keyframes drift4 {
            0%   { transform: translate(0px,  0px); }
            40%  { transform: translate(-18px, -22px); }
            70%  { transform: translate(22px,  14px); }
            100% { transform: translate(-10px, 28px); }
        }
        @keyframes drift5 {
            0%   { transform: translate(0px,  0px); }
            35%  { transform: translate(24px, -18px); }
            65%  { transform: translate(-16px, 22px); }
            100% { transform: translate(10px, -14px); }
        }

        @keyframes pulse {
            from { filter: blur(60px) brightness(0.9); }
            to   { filter: blur(70px) brightness(1.1); }
        }

        /* Subtle grid overlay for depth */
        .grid-overlay {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
        }

        /* ── Hero Content ────────────────────────────── */
        .hero {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 24px;
            text-align: center;
        }

        /* Brand chip */
        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: 100px;
            padding: 5px 14px 5px 8px;
            margin-bottom: 32px;
            opacity: 0;
            animation: slideDown 0.6s 0.5s ease forwards;
        }

        .brand-chip-icon {
            width: 24px; height: 24px;
            background: var(--blue);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-chip-text {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #93c5fd;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Headline */
        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(44px, 8vw, 90px);
            font-weight: 400;
            line-height: 1.05;
            letter-spacing: -0.03em;
            color: #f0f4ff;
            opacity: 0;
            animation: fadeUp 0.8s 0.7s ease forwards;
        }

        h1 em {
            font-style: italic;
            color: transparent;
            background: linear-gradient(135deg, #60a5fa 0%, #818cf8 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .subtitle {
            margin-top: 20px;
            font-size: clamp(15px, 2vw, 18px);
            font-weight: 300;
            color: var(--muted);
            max-width: 420px;
            line-height: 1.7;
            opacity: 0;
            animation: fadeUp 0.8s 0.9s ease forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* CTA */
        .cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 40px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeUp 0.8s 1.1s ease forwards;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--blue);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid transparent;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        /* Shimmer sweep on hover */
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.4s ease;
        }
        .btn-primary:hover::after { left: 160%; }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-primary svg { transition: transform 0.2s; }
        .btn-primary:hover svg { transform: translateX(3px); }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.04);
            color: #94a3b8;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.07);
            color: #e2e8f0;
            border-color: rgba(255,255,255,0.15);
        }

        /* Feature pills */
        .feature-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 48px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeUp 0.8s 1.3s ease forwards;
        }

        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 5px 12px;
            font-size: 12px;
            color: #64748b;
        }

        .feature-pill-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
        }

        /* Footer label */
        .footer-label {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            color: #334155;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0;
            animation: fadeUp 0.6s 1.8s ease forwards;
            white-space: nowrap;
            z-index: 10;
        }

        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .orb, .brand-chip, h1, .subtitle, .cta-group, .feature-row, .footer-label {
                animation: none !important;
                opacity: 1 !important;
            }
        }
    </style>
</head>
<body>

    {{-- Background scene --}}
    <div class="scene" aria-hidden="true">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="orb orb-4"></div>
        <div class="orb orb-5"></div>
    </div>
    <div class="grid-overlay" aria-hidden="true"></div>

    {{-- Hero --}}
    <main class="hero">
        <div class="brand-chip">
            <div class="brand-chip-icon">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="brand-chip-text">UserOS</span>
        </div>

        <h1>User management,<br><em>simplified.</em></h1>

        <p class="subtitle">
            A clean, focused workspace for managing your team. Add, search, and maintain users with ease.
        </p>

        <div class="cta-group">
            <a href="{{ route('user.index') }}" class="btn-primary">
                Open Dashboard
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('user.create') }}" class="btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add a User
            </a>
        </div>

        <div class="feature-row">
            <div class="feature-pill">
                <span class="feature-pill-dot" style="background: #22c55e;"></span>
                User Directory
            </div>
            <div class="feature-pill">
                <span class="feature-pill-dot" style="background: #3b82f6;"></span>
                Search & Filter
            </div>
            <div class="feature-pill">
                <span class="feature-pill-dot" style="background: #a855f7;"></span>
                Role Management
            </div>
            <div class="feature-pill">
                <span class="feature-pill-dot" style="background: #f59e0b;"></span>
                Activity Logs
            </div>
        </div>
    </main>

    <div class="footer-label">UserOS · Admin Portal</div>

</body>
</html>

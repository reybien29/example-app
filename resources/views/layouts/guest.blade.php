<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'UserOS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: #0f1117;
            display: flex;
            align-items: stretch;
            color: #0f1117;
        }

        /* Left panel – branding */
        .auth-panel-left {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            background: linear-gradient(135deg, #0f1117 0%, #1e2330 50%, #0f1117 100%);
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 900px) {
            .auth-panel-left { display: flex; width: 42%; }
        }

        .auth-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(37,99,235,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(99,102,241,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Decorative grid */
        .auth-panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .auth-brand-icon {
            width: 40px; height: 40px;
            background: #2563eb;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .auth-brand-icon svg { color: white; }

        .auth-brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 22px;
            color: #f1f5f9;
            letter-spacing: -0.02em;
        }

        .auth-panel-left-body {
            position: relative;
            z-index: 1;
        }

        .auth-panel-left-body h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 36px;
            line-height: 1.15;
            color: #f1f5f9;
            letter-spacing: -0.03em;
        }

        .auth-panel-left-body h2 em {
            color: #93c5fd;
            font-style: italic;
        }

        .auth-panel-left-body p {
            margin-top: 16px;
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            max-width: 300px;
        }

        .auth-testimonial {
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 20px;
        }

        .auth-testimonial p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
            font-style: italic;
        }

        .auth-testimonial-author {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
        }

        .auth-testimonial-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #1e3a8a;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #93c5fd;
        }

        .auth-testimonial-name { font-size: 12px; font-weight: 500; color: #cbd5e1; }
        .auth-testimonial-role { font-size: 11px; color: #64748b; }

        /* Right panel – form */
        .auth-panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 40px 32px;
        }

        .auth-form-container {
            width: 100%;
            max-width: 380px;
        }

        {{ $slot }}
    </style>
</head>
<body>
    <div class="auth-panel-left">
        <div class="auth-brand">
            <div class="auth-brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="auth-brand-name">UserOS</span>
        </div>

        <div class="auth-panel-left-body">
            <h2>Manage your team with <em>clarity</em></h2>
            <p>A clean, powerful admin workspace. Onboard users, track activity, and keep everything organized in one place.</p>
        </div>

        <div class="auth-testimonial">
            <p>"UserOS helped us cut onboarding time in half. The interface is intuitive and everything just works."</p>
            <div class="auth-testimonial-author">
                <div class="auth-testimonial-avatar">JR</div>
                <div>
                    <div class="auth-testimonial-name">Jamie Rivera</div>
                    <div class="auth-testimonial-role">Head of Operations</div>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-panel-right">
        <div class="auth-form-container">
            {{ $slot }}
        </div>
    </div>
</body>
</html>

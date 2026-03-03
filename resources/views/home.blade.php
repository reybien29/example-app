@extends('users.layout')

@section('title', 'Overview')

@section('content')
<style>
    .home-page { animation: fadeUp 0.4s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .page-hero {
        margin-bottom: 36px;
    }

    .page-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 100px;
        padding: 3px 10px;
        margin-bottom: 14px;
    }
    .page-hero-eyebrow::before {
        content: '';
        width: 6px; height: 6px;
        background: #2563eb;
        border-radius: 50%;
    }

    .page-hero h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 34px;
        font-weight: 400;
        letter-spacing: -0.03em;
        color: #0f1117;
        line-height: 1.15;
    }

    .page-hero p {
        margin-top: 10px;
        font-size: 15px;
        color: #6b7280;
        max-width: 480px;
        line-height: 1.65;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0f1117;
        color: white;
        padding: 11px 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
    }
    .btn-hero-primary:hover {
        background: #1e2330;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-hero-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: #374151;
        padding: 11px 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
    }
    .btn-hero-secondary:hover { background: #f9fafb; border-color: #d1d5db; }

    /* Stats row */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 36px;
    }

    .stat-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }

    .stat-card-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
    }

    .stat-card-value {
        font-family: 'DM Serif Display', serif;
        font-size: 30px;
        color: #0f1117;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .stat-card-label {
        font-size: 12.5px;
        color: #9ca3af;
        margin-top: 4px;
        font-weight: 500;
    }

    .stat-card-trend {
        position: absolute;
        top: 16px; right: 16px;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 100px;
    }

    .trend-up { background: #f0fdf4; color: #16a34a; }

    /* Feature cards */
    .section-title {
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        font-weight: 400;
        color: #0f1117;
        letter-spacing: -0.02em;
        margin-bottom: 16px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .feature-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 24px;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }
    .feature-card::after {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.2s;
        background: linear-gradient(135deg, rgba(37,99,235,0.03) 0%, transparent 100%);
    }
    .feature-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-3px); }
    .feature-card:hover::after { opacity: 1; }

    .feature-card-icon {
        width: 44px; height: 44px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 18px;
    }

    .feature-card h3 {
        font-size: 15px;
        font-weight: 600;
        color: #0f1117;
        letter-spacing: -0.01em;
    }

    .feature-card p {
        font-size: 13.5px;
        color: #6b7280;
        margin-top: 6px;
        line-height: 1.6;
    }

    .feature-card-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 16px;
        font-size: 13px;
        font-weight: 500;
        color: #2563eb;
        transition: gap 0.15s;
    }
    .feature-card:hover .feature-card-link { gap: 8px; }

    .feature-card-link-muted {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 16px;
        font-size: 13px;
        font-weight: 500;
        color: #d1d5db;
    }

    .coming-soon-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        color: #9ca3af;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 100px;
        padding: 2px 8px;
        margin-top: 12px;
    }
</style>

<div class="home-page">
    {{-- Hero --}}
    <div class="page-hero">
        <div class="page-hero-eyebrow">Admin Dashboard</div>
        <h1>Good morning,<br>here's your overview.</h1>
        <p>Manage your users, monitor activity, and configure your workspace all in one place.</p>
        <div class="hero-actions">
            <a href="{{ route('user.index') }}" class="btn-hero-primary">
                Manage Users
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('user.create') }}" class="btn-hero-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add User
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #eff6ff;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat-card-trend trend-up">↑ 12%</div>
            <div class="stat-card-value">{{ \App\Models\User::count() }}</div>
            <div class="stat-card-label">Total Users</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background: #f0fdf4;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <div class="stat-card-trend trend-up">↑ 4%</div>
            <div class="stat-card-value">{{ \App\Models\User::whereDate('created_at', today())->count() }}</div>
            <div class="stat-card-label">New Today</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fef3c7;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="stat-card-value">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</div>
            <div class="stat-card-label">This Month</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fdf4ff;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div class="stat-card-value">99.9%</div>
            <div class="stat-card-label">Uptime</div>
        </div>
    </div>

    {{-- Feature Cards --}}
    <div class="section-title">Quick access</div>
    <div class="features-grid">
        <a href="{{ route('user.index') }}" class="feature-card">
            <div class="feature-card-icon" style="background: #eff6ff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3>Manage Users</h3>
            <p>Add, edit, search, and remove users from your workspace. Keep your directory organized.</p>
            <div class="feature-card-link">
                Go to Users
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </div>
        </a>

        <div class="feature-card" style="cursor: default;">
            <div class="feature-card-icon" style="background: #f9fafb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <h3 style="color: #9ca3af;">View Analytics</h3>
            <p style="color: #d1d5db;">Gain insights into user activity, growth trends, and application performance metrics.</p>
            <div class="coming-soon-pill">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Coming soon
            </div>
        </div>

        <div class="feature-card" style="cursor: default;">
            <div class="feature-card-icon" style="background: #f9fafb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                </svg>
            </div>
            <h3 style="color: #9ca3af;">System Settings</h3>
            <p style="color: #d1d5db;">Configure your application preferences, integrations, and notification settings.</p>
            <div class="coming-soon-pill">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Coming soon
            </div>
        </div>
    </div>
</div>
@endsection

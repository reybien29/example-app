@extends('users.layout')

@section('title', 'Create User')

@section('content')
<style>
    .form-page { animation: fadeUp 0.35s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .form-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 700px) {
        .form-layout { grid-template-columns: 1fr; }
    }

    .form-sidebar h2 {
        font-family: 'DM Serif Display', serif;
        font-size: 22px;
        font-weight: 400;
        color: #0f1117;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .form-sidebar p {
        margin-top: 8px;
        font-size: 13.5px;
        color: #9ca3af;
        line-height: 1.65;
    }

    .form-sidebar-tips {
        margin-top: 24px;
        padding: 16px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
    }

    .form-sidebar-tips h4 {
        font-size: 12px;
        font-weight: 600;
        color: #1d4ed8;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 12.5px;
        color: #3b82f6;
        line-height: 1.5;
        margin-bottom: 7px;
    }
    .tip-item:last-child { margin-bottom: 0; }

    .tip-item svg { flex-shrink: 0; margin-top: 1px; }

    .form-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-card-body {
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    .form-label span {
        font-size: 12px;
        font-weight: 400;
        color: #9ca3af;
        margin-left: 4px;
    }

    .form-input {
        display: block;
        width: 100%;
        padding: 9px 12px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #0f1117;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        outline: none;
        transition: all 0.15s;
    }
    .form-input:focus {
        background: white;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .form-input::placeholder { color: #d1d5db; }

    .form-input-icon-wrap {
        position: relative;
    }
    .form-input-icon-wrap .form-input { padding-left: 38px; }
    .form-input-icon-wrap .icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .form-error {
        font-size: 12px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-divider {
        height: 1px;
        background: #f3f4f6;
    }

    .form-card-footer {
        padding: 18px 28px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #2563eb;
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }
    .btn-submit:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 400;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-cancel:hover { background: #f9fafb; }
</style>

<div class="form-page">
    <div class="form-layout">
        {{-- Sidebar --}}
        <div class="form-sidebar">
            <h2>Create a new user</h2>
            <p>Add a user to your workspace. They'll be able to sign in right away with the credentials you provide.</p>

            <div class="form-sidebar-tips">
                <h4>Tips</h4>
                <div class="tip-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Use a real email address the user can access.
                </div>
                <div class="tip-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Passwords should be at least 8 characters.
                </div>
                <div class="tip-item">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Share credentials securely with the new user.
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="form-card">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Full name</label>
                        <div class="form-input-icon-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                class="form-input"
                                placeholder="e.g. Jane Smith"
                                autocomplete="name"
                            >
                        </div>
                        @error('name')
                            <p class="form-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <div class="form-input-icon-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="form-input"
                                placeholder="e.g. jane@company.com"
                                autocomplete="email"
                            >
                        </div>
                        @error('email')
                            <p class="form-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="form-input-icon-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-input"
                                placeholder="Min. 8 characters"
                                autocomplete="new-password"
                            >
                        </div>
                        @error('password')
                            <p class="form-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="form-card-footer">
                    <a href="{{ route('user.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

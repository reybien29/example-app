@extends('users.layout')

@section('title', 'Edit User')

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
        .form-sidebar-user { display: none; }
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

    .form-sidebar-user {
        margin-top: 24px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-user-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .sidebar-user-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f1117;
    }

    .sidebar-user-email {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .sidebar-user-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #16a34a;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 100px;
        padding: 2px 7px;
        margin-top: 4px;
    }

    .form-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-card-header {
        padding: 20px 28px 0;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 16px;
    }

    .form-card-header h3 {
        font-size: 15px;
        font-weight: 600;
        color: #0f1117;
    }

    .form-card-header p {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 3px;
    }

    .form-card-body {
        padding: 24px 28px;
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

    .form-error-box {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 4px;
    }

    .form-error-box ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-error-box li {
        font-size: 13px;
        color: #dc2626;
        display: flex;
        align-items: flex-start;
        gap: 7px;
    }

    .form-error {
        font-size: 12px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-card-footer {
        padding: 18px 28px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
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
        margin-left: auto;
    }
    .btn-cancel:hover { background: #f9fafb; }

    .password-note {
        font-size: 12px;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 2px;
    }
</style>

<div class="form-page">
    <div class="form-layout">
        {{-- Sidebar --}}
        <div class="form-sidebar">
            <h2>Edit user profile</h2>
            <p>Update this user's name, email address, or set a new password for them.</p>

            <div class="form-sidebar-user">
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($data->name) }}&background=random&size=80"
                    alt="{{ $data->name }}"
                    class="sidebar-user-avatar"
                >
                <div>
                    <div class="sidebar-user-name">{{ $data->name }}</div>
                    <div class="sidebar-user-email">{{ $data->email }}</div>
                    <div class="sidebar-user-tag">
                        <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="6"/>
                        </svg>
                        Active
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="form-card">
            @if ($errors->any())
                <div style="padding: 20px 28px 0;">
                    <div class="form-error-box">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px">
                                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                    </svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-card-header">
                <h3>Account information</h3>
                <p>Changes are saved immediately after submission.</p>
            </div>

            <form action="{{ route('user.update', $data->id) }}" method="POST">
                @csrf
                @method('PATCH')
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
                                id="name"
                                name="name"
                                value="{{ old('name', $data->name) }}"
                                class="form-input"
                                placeholder="Enter full name"
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
                                id="email"
                                name="email"
                                value="{{ old('email', $data->email) }}"
                                class="form-input"
                                placeholder="Enter email address"
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

                    <div style="height: 1px; background: #f3f4f6;"></div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            New password
                            <span>(leave blank to keep current)</span>
                        </label>
                        <div class="form-input-icon-wrap">
                            <span class="icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input"
                                placeholder="Enter new password"
                                autocomplete="new-password"
                            >
                        </div>
                        <div class="password-note">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                            </svg>
                            Only fill this in if you want to change the password.
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
                    <button type="submit" class="btn-submit">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('user.index') }}" class="btn-cancel">Discard</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

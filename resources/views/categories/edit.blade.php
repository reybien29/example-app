@extends('users.layout')

@section('title', 'Edit Category')

@section('content')
<style>
    .page-anim { animation: fadeUp 0.35s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .form-page-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 28px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .back-btn:hover { background: #f9fafb; border-color: #d1d5db; color: #0f1117; }

    .form-page-header h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 26px;
        font-weight: 400;
        letter-spacing: -0.03em;
        color: #0f1117;
        line-height: 1.2;
    }

    .form-page-header p {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .form-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        max-width: 600px;
    }

    .form-group { margin-bottom: 22px; }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 3px;
    }

    .form-input,
    .form-select {
        display: block;
        width: 100%;
        padding: 10px 13px;
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        color: #0f1117;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        box-sizing: border-box;
    }
    .form-input:focus,
    .form-select:focus {
        background: white;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .form-input::placeholder { color: #d1d5db; }
    .form-input.is-invalid,
    .form-select.is-invalid { border-color: #ef4444; background: #fff5f5; }

    .form-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
    }

    .form-hint {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 5px;
    }

    .form-divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 28px 0;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #0f1117;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-primary:hover { background: #1e2330; }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 400;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-ghost:hover { background: #f9fafb; color: #0f1117; }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 400;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: all 0.15s;
        margin-left: auto;
    }
    .btn-danger:hover { background: #fef2f2; border-color: #fca5a5; }
</style>

<div class="page-anim">
    <div class="form-page-header">
        <a href="{{ route('category.index') }}" class="back-btn" title="Back to Categories">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </a>
        <div>
            <h1>Edit Category</h1>
            <p>Update the details for <strong>{{ $category->name }}</strong>.</p>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('category.update', $category->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="user_id" class="form-label">
                    Owner <span class="required">*</span>
                </label>
                <select
                    id="user_id"
                    name="user_id"
                    class="form-select {{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                >
                    <option value="">— Select an owner —</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $category->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name" class="form-label">
                    Category Name <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    placeholder="e.g. Client, Family, Work…"
                    autocomplete="off"
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-hint">Must be unique per owner.</div>
            </div>

            <hr class="form-divider">

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('category.index') }}" class="btn-ghost">Cancel</a>

                <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="margin-left: auto;">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="btn-danger"
                        onclick="return confirm('Delete category \'{{ addslashes($category->name) }}\'? This cannot be undone.')"
                    >
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                        Delete Category
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection

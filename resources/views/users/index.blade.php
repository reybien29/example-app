@extends('users.layout')

@section('title', 'Users')

@section('content')
<style>
    .users-page { animation: fadeUp 0.35s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .page-header-left h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 28px;
        font-weight: 400;
        letter-spacing: -0.03em;
        color: #0f1117;
        line-height: 1.15;
    }

    .page-header-left p {
        font-size: 13.5px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .user-count-badge {
        display: inline-flex;
        align-items: center;
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 100px;
        padding: 2px 9px;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* Search bar */
    .search-bar {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-input-icon {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .search-input {
        display: block;
        width: 100%;
        padding: 9px 12px 9px 36px;
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        color: #0f1117;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    }
    .search-input:focus {
        background: white;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .search-input::placeholder { color: #d1d5db; }

    .search-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #0f1117;
        color: white;
        border: none;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .search-btn:hover { background: #1e2330; }

    .reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 400;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }
    .reset-btn:hover { background: #f9fafb; color: #0f1117; }

    /* Table */
    .table-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .table-scroll { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        padding: 11px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    th:last-child { text-align: right; }

    tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.12s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafafa; }

    td { padding: 14px 20px; vertical-align: middle; }
    td:last-child { text-align: right; }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f1117;
        line-height: 1.2;
    }

    .user-id {
        font-size: 11px;
        color: #d1d5db;
        margin-top: 2px;
        font-family: 'DM Mono', 'Fira Code', monospace;
    }

    .user-email {
        color: #6b7280;
        font-size: 13.5px;
    }

    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12.5px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .action-edit {
        background: transparent;
        color: #374151;
        border-color: #e5e7eb;
    }
    .action-edit:hover { background: #f9fafb; border-color: #d1d5db; }

    .action-delete {
        background: transparent;
        color: #dc2626;
        border-color: #fecaca;
    }
    .action-delete:hover { background: #fef2f2; border-color: #fca5a5; }

    /* Empty state */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 56px; height: 56px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state h2 {
        font-family: 'DM Serif Display', serif;
        font-size: 22px;
        font-weight: 400;
        color: #0f1117;
        letter-spacing: -0.02em;
    }

    .empty-state p {
        font-size: 13.5px;
        color: #9ca3af;
        margin-top: 6px;
        line-height: 1.6;
    }

    .empty-state-actions { margin-top: 24px; }

    /* Pagination */
    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid #f3f4f6;
    }

    /* Search result info */
    .search-info {
        font-size: 12.5px;
        color: #9ca3af;
        padding: 10px 20px;
        border-bottom: 1px solid #f3f4f6;
        background: #fafafa;
    }

    .search-term { color: #0f1117; font-weight: 600; }
</style>

<div class="users-page">
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                User Directory
                <span class="user-count-badge">{{ $data->total() ?? $data->count() }}</span>
            </h1>
            <p>Manage accounts, search records, and maintain your team roster.</p>
        </div>
        <a href="{{ route('user.create') }}" class="search-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add User
        </a>
    </div>

    {{-- Search --}}
    <div class="search-bar">
        <form action="{{ route('user.search') }}" method="GET" style="display: contents;">
            <div class="search-input-wrap">
                <span class="search-input-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="search-input"
                    placeholder="Search by name or email…"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="search-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                Search
            </button>
            @if (request('search'))
                <a href="{{ route('user.index') }}" class="reset-btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                        <path d="M21 21v-5h-5"/>
                    </svg>
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="table-card">
        @if (request('search'))
            <div class="search-info">
                Showing results for <span class="search-term">"{{ request('search') }}"</span>
            </div>
        @endif

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=80"
                                        alt="{{ $user->name }}"
                                        class="user-avatar"
                                    >
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-id">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="user-email">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span style="color: #9ca3af; font-size: 13px;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('user.edit', $user->id) }}" class="action-btn action-edit">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            onclick="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.')"
                                        >
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                        </svg>
                                    </div>
                                    <h2>No users found</h2>
                                    <p>
                                        @if (request('search'))
                                            No results for "{{ request('search') }}". Try a different search term.
                                        @else
                                            Your user directory is empty. Add your first user to get started.
                                        @endif
                                    </p>
                                    <div class="empty-state-actions">
                                        @if (request('search'))
                                            <a href="{{ route('user.index') }}" class="action-btn action-edit">Clear search</a>
                                        @else
                                            <a href="{{ route('user.create') }}" class="search-btn" style="display: inline-flex;">Add first user</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
            <div class="pagination-wrap">
                {{ $data->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection

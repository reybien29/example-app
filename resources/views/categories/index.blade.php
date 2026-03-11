@extends('users.layout')

@section('title', 'Categories')

@section('content')
<style>
    .page-anim { animation: fadeUp 0.35s ease both; }
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

    .count-badge {
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

    .add-btn {
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
        text-decoration: none;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .add-btn:hover { background: #1e2330; }

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

    .category-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f1117;
    }

    .owner-cell { display: flex; align-items: center; gap: 10px; }

    .owner-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #e5e7eb;
        flex-shrink: 0;
    }

    .owner-name {
        font-size: 13.5px;
        font-weight: 500;
        color: #374151;
        line-height: 1.2;
    }

    .owner-email {
        font-size: 11.5px;
        color: #9ca3af;
    }

    .contacts-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #059669;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 100px;
        padding: 2px 9px;
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

    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid #f3f4f6;
    }
</style>

<div class="page-anim">
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                Categories
                <span class="count-badge">{{ $categories->total() }}</span>
            </h1>
            <p>Manage contact categories and organize your directory.</p>
        </div>
        <a href="{{ route('category.create') }}" class="add-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Category
        </a>
    </div>

    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Owner</th>
                        <th>Contacts</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                <span class="category-name">{{ $category->name }}</span>
                            </td>
                            <td>
                                <div class="owner-cell">
                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($category->user->name) }}&background=random&size=60"
                                        alt="{{ $category->user->name }}"
                                        class="owner-avatar"
                                    >
                                    <div>
                                        <div class="owner-name">{{ $category->user->name }}</div>
                                        <div class="owner-email">{{ $category->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="contacts-pill">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    {{ $category->contacts_count }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #9ca3af; font-size: 13px;">
                                    {{ $category->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('category.edit', $category->id) }}" class="action-btn action-edit">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            onclick="return confirm('Delete category \'{{ addslashes($category->name) }}\'? This cannot be undone.')"
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
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                        </svg>
                                    </div>
                                    <h2>No categories yet</h2>
                                    <p>Create your first category to start organizing contacts.</p>
                                    <div class="empty-state-actions">
                                        <a href="{{ route('category.create') }}" class="add-btn" style="display: inline-flex;">Add first category</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="pagination-wrap">
                {{ $categories->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('users.layout')

@section('title', 'Contacts')

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

    .contact-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .contact-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .contact-name {
        font-size: 14px;
        font-weight: 600;
        color: #0f1117;
        line-height: 1.2;
    }

    .contact-email {
        font-size: 11.5px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        font-size: 11.5px;
        font-weight: 600;
        color: #7c3aed;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 100px;
        padding: 3px 10px;
        white-space: nowrap;
    }

    .owner-name {
        font-size: 13px;
        color: #6b7280;
    }

    .mobile-text {
        font-size: 13px;
        color: #6b7280;
        font-family: 'DM Mono', 'Fira Code', monospace;
    }

    .no-value {
        color: #d1d5db;
        font-size: 13px;
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
                Contacts
                <span class="count-badge">{{ $contacts->total() }}</span>
            </h1>
            <p>Manage your contact directory and their categories.</p>
        </div>
        <a href="{{ route('contact.create') }}" class="add-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Contact
        </a>
    </div>

    <div class="table-card">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Category</th>
                        <th>Owner</th>
                        <th>Mobile</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td>
                                <div class="contact-cell">
                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($contact->name) }}&background=random&size=80"
                                        alt="{{ $contact->name }}"
                                        class="contact-avatar"
                                    >
                                    <div>
                                        <div class="contact-name">{{ $contact->name }}</div>
                                        @if ($contact->email)
                                            <div class="contact-email">{{ $contact->email }}</div>
                                        @else
                                            <div class="contact-email no-value">No email</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($contact->category)
                                    <span class="category-badge">{{ $contact->category->name }}</span>
                                @else
                                    <span class="no-value">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="owner-name">{{ $contact->user->name ?? '—' }}</span>
                            </td>
                            <td>
                                @if ($contact->mobile_number)
                                    <span class="mobile-text">{{ $contact->mobile_number }}</span>
                                @else
                                    <span class="no-value">—</span>
                                @endif
                            </td>
                            <td>
                                <span style="color: #9ca3af; font-size: 13px;">
                                    {{ $contact->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('contact.edit', $contact->id) }}" class="action-btn action-edit">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('contact.destroy', $contact->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            onclick="return confirm('Delete contact \'{{ addslashes($contact->name) }}\'? This cannot be undone.')"
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
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <h2>No contacts yet</h2>
                                    <p>Add your first contact to start building your directory.</p>
                                    <div class="empty-state-actions">
                                        <a href="{{ route('contact.create') }}" class="add-btn" style="display: inline-flex;">Add first contact</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="pagination-wrap">
                {{ $contacts->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection

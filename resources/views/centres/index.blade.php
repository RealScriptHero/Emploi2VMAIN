@extends('layouts.app')

@section('content')

{{-- Toast --}}
<div id="ct-toast"
     style="display:none; position:fixed; top:22px; left:50%; transform:translateX(-50%); z-index:9999;
            min-width:280px; max-width:420px; border-radius:999px; padding:12px 22px;
            color:white; font-size:13px; font-weight:600; align-items:center; gap:10px;
            box-shadow:0 8px 30px rgba(0,0,0,.15);">
    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span id="ct-toast-msg" style="flex:1;"></span>
    <button onclick="ctHideToast()" style="background:none;border:none;color:white;cursor:pointer;opacity:.7;font-size:16px;line-height:1;">✕</button>
</div>

<style>
* { box-sizing: border-box; }

.ct-page { font-family:'Inter',system-ui,sans-serif; padding:24px 24px 48px; background:#f8fafc; min-height:100vh; }

.ct-breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:#9ca3af; margin-bottom:20px; }
.ct-breadcrumb .sep { color:#d1d5db; }
.ct-breadcrumb .cur { color:#374151; font-weight:600; }

.ct-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
.ct-header h1 { font-size:22px; font-weight:700; color:#111827; margin:0 0 4px; letter-spacing:-.3px; }
.ct-header p  { font-size:13px; color:#6b7280; margin:0; }
.ct-controls  { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

.ct-search { position:relative; }
.ct-search input {
    padding:9px 14px 9px 36px; border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; color:#374151; outline:none; background:white; width:220px;
    transition:border-color .15s,box-shadow .15s; font-family:inherit;
}
.ct-search input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.ct-search .ico { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; width:14px; height:14px; }

.ct-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:10px; font-size:13px;
    font-weight:600; border:none; cursor:pointer;
    transition:all .15s; white-space:nowrap; font-family:inherit;
}
.ct-btn svg { width:14px; height:14px; flex-shrink:0; }
.ct-btn-add    { background:#6366f1; color:white; }
.ct-btn-add:hover    { background:#4f46e5; box-shadow:0 4px 12px rgba(99,102,241,.3); }
.ct-btn-cancel { background:white; color:#374151; border:1.5px solid #e5e7eb; }
.ct-btn-cancel:hover { background:#f9fafb; }
.ct-btn-save   { background:#6366f1; color:white; min-width:110px; justify-content:center; }
.ct-btn-save:hover   { background:#4f46e5; }
.ct-btn-save:disabled { opacity:.6; cursor:not-allowed; }

.ct-card { background:white; border-radius:16px; border:1px solid #e8eaf0; box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; }

.ct-table { width:100%; border-collapse:collapse; }
.ct-table thead th {
    padding:13px 20px; text-align:left; font-size:11px; font-weight:700;
    letter-spacing:.07em; text-transform:uppercase; color:#9ca3af;
    background:#f9fafb; border-bottom:1px solid #f0f0f5; white-space:nowrap;
}
.ct-table thead th.th-right { text-align:right; }
.ct-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background .12s; }
.ct-table tbody tr:last-child { border-bottom:none; }
.ct-table tbody tr:hover { background:#fafbff; }
.ct-table tbody td { padding:14px 20px; vertical-align:middle; }

/* Centre icon */
.ct-icon {
    width:36px; height:36px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    background:#fef3c7; color:#d97706;
}
.ct-icon svg { width:18px; height:18px; }

/* Short name badge */
.ct-short-badge {
    display:inline-flex; align-items:center; padding:3px 10px;
    border-radius:8px; font-size:11px; font-weight:700;
    background:#ede9fe; color:#6d28d9; letter-spacing:.5px;
    font-family: 'Courier New', monospace;
}

.ct-act { width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:7px; border:none; cursor:pointer; background:transparent; transition:all .12s; }
.ct-act svg { width:15px; height:15px; }
.ct-act-edit { color:#6366f1; } .ct-act-edit:hover { background:#e0e7ff; }
.ct-act-del  { color:#ef4444; } .ct-act-del:hover  { background:#fee2e2; }

.ct-empty { padding:60px 20px; text-align:center; color:#9ca3af; font-size:14px; }

/* Modal */
.ct-modal-bg { position:fixed; inset:0; background:rgba(15,23,42,.45); backdrop-filter:blur(3px); z-index:50; align-items:center; justify-content:center; padding:16px; }
.ct-modal-box { background:white; border-radius:18px; width:100%; max-width:440px; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:ctIn .22s ease; }
@keyframes ctIn { from{opacity:0;transform:scale(.96) translateY(8px)} to{opacity:1;transform:scale(1) translateY(0)} }
.ct-modal-head { padding:20px 24px 16px; border-bottom:1px solid #f0f0f5; display:flex; align-items:center; justify-content:space-between; }
.ct-modal-head h3 { font-size:16px; font-weight:700; color:#111827; margin:0; }
.ct-modal-body { padding:22px 24px; display:flex; flex-direction:column; gap:16px; }
.ct-modal-foot { padding:12px 24px 22px; display:flex; gap:10px; justify-content:flex-end; }
.ct-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:5px; }
.ct-req { color:#ef4444; }
.ct-input { width:100%; padding:10px 13px; border:1.5px solid #e5e7eb; border-radius:9px; font-size:13px; color:#111827; outline:none; background:white; transition:border-color .15s,box-shadow .15s; font-family:inherit; }
.ct-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.ct-err { display:none; align-items:center; gap:8px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:9px; font-size:13px; color:#dc2626; }
.ct-hint { font-size:11px; color:#9ca3af; margin-top:4px; }

@keyframes spin { to { transform:rotate(360deg); } }
[x-cloak] { display:none !important; }
</style>

<script>
let _ctTimer;
function ctShowToast(msg, ok = true) {
    const t = document.getElementById('ct-toast');
    document.getElementById('ct-toast-msg').textContent = msg;
    t.style.background = ok
        ? 'linear-gradient(135deg,#10b981,#059669)'
        : 'linear-gradient(135deg,#ef4444,#dc2626)';
    t.style.display = 'flex'; t.style.opacity = '1';
    clearTimeout(_ctTimer);
    _ctTimer = setTimeout(ctHideToast, 4000);
}
function ctHideToast() {
    const t = document.getElementById('ct-toast');
    t.style.opacity = '0';
    setTimeout(() => { t.style.display='none'; t.style.opacity='1'; }, 300);
}
function showAlert(m) { ctShowToast(m); }
</script>

<div x-data="centresPage()" x-init="init()" class="ct-page">

    <div class="ct-breadcrumb">
        <span>Tableau de bord</span>
        <span class="sep">›</span>
        <span class="cur">Centers</span>
    </div>

    <div class="ct-header">
        <div>
            <h1>Centers</h1>
            <p>Manage training centers</p>
        </div>
        <div class="ct-controls">
            <div class="ct-search">
                <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
                <input x-model="search" placeholder="Search centers...">
            </div>

            <button @click="doOpenAdd()" class="ct-btn ct-btn-add">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Center
            </button>
        </div>
    </div>

    <div class="ct-card">
        <div x-show="loading" style="display:flex;align-items:center;justify-content:center;padding:60px 20px;">
            <svg style="width:32px;height:32px;color:#6366f1;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:.25;"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" style="opacity:.75;"></path>
            </svg>
        </div>

        <div x-show="!loading" style="overflow-x:auto;">
            <table class="ct-table">
                <thead>
                    <tr>
                        <th style="width:320px;">Center Name</th>
                        <th>Short Name</th>
                        <th class="th-right" style="padding-right:22px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="center in filteredCenters()" :key="center.id">
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:11px;">
                                    <div class="ct-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span style="font-weight:600;font-size:14px;color:#111827;" x-text="center.name"></span>
                                </div>
                            </td>
                            <td>
                                <template x-if="center.short">
                                    <span class="ct-short-badge" x-text="center.short"></span>
                                </template>
                                <template x-if="!center.short">
                                    <span style="font-size:13px;color:#9ca3af;font-style:italic;">—</span>
                                </template>
                            </td>
                            <td style="text-align:right;padding-right:18px;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:2px;">
                                    <button @click="doOpenEdit(center)" class="ct-act ct-act-edit" title="Edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="doRemove(center)" class="ct-act ct-act-del" title="Delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!loading && filteredCenters().length === 0">
                        <td colspan="3" class="ct-empty">No centers found. Try adjusting your search.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div id="ct-modal-bg"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,.45);
            backdrop-filter:blur(3px); z-index:50;
            align-items:center; justify-content:center; padding:16px;">
    <div class="ct-modal-box" onclick="event.stopPropagation()">
        <div class="ct-modal-head">
            <h3 id="ct-modal-title">Add Center</h3>
            <button onclick="ctCloseModal()"
                    style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:7px;border:none;cursor:pointer;background:transparent;color:#6b7280;">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="ct-modal-body">
            <div>
                <label class="ct-label">Center Name <span class="ct-req">*</span></label>
                <input type="text" id="ct-name" class="ct-input" placeholder="e.g. Centre de Formation OFPPT">
            </div>
            <div>
                <label class="ct-label">Short Name</label>
                <input type="text" id="ct-short" class="ct-input" placeholder="e.g. CFMPS">
                <p class="ct-hint">Optional abbreviation or acronym for this center.</p>
            </div>
            <div id="ct-err" class="ct-err">
                <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="ct-err-text"></span>
            </div>
        </div>
        <div class="ct-modal-foot">
            <button onclick="ctCloseModal()" class="ct-btn ct-btn-cancel">Cancel</button>
            <button id="ct-save-btn" onclick="ctSave()" class="ct-btn ct-btn-save">
                <span id="ct-save-label">Save Center</span>
            </button>
        </div>
    </div>
</div>

<script>
window._ctEditingId = null;

function ctShowErr(msg) { const b=document.getElementById('ct-err'); document.getElementById('ct-err-text').textContent=msg; b.style.display='flex'; }
function ctHideErr()    { document.getElementById('ct-err').style.display='none'; }

function ctCloseModal() { document.getElementById('ct-modal-bg').style.display='none'; window._ctEditingId=null; }
document.getElementById('ct-modal-bg').addEventListener('click', function(e){ if(e.target===this) ctCloseModal(); });

function ctOpenModal(title, saveLabel) {
    ctHideErr();
    document.getElementById('ct-modal-title').textContent = title;
    document.getElementById('ct-save-label').textContent  = saveLabel;
    document.getElementById('ct-modal-bg').style.display  = 'flex';
    setTimeout(() => document.getElementById('ct-name').focus(), 60);
}

async function ctSave() {
    ctHideErr();
    const name  = document.getElementById('ct-name').value.trim();
    const short = document.getElementById('ct-short').value.trim();
    if (!name) { ctShowErr('Center name is required.'); return; }

    const headers = { 'Content-Type':'application/json', 'Accept':'application/json' };
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) headers['X-CSRF-TOKEN'] = meta.content;

    const btn = document.getElementById('ct-save-btn');
    btn.disabled = true;
    document.getElementById('ct-save-label').textContent = 'Saving…';

    // Send all possible field name variants so any API version accepts it
    const payload = {
        nomCentre: name, name,  nom: name,
        shortName: short, short, nomCourt: short, abbreviation: short,
    };

    try {
        const isEdit = !!window._ctEditingId;
        const url    = isEdit ? `/api/centres/${window._ctEditingId}` : '/api/centres';
        const method = isEdit ? 'PUT' : 'POST';
        const res    = await fetch(url, { method, headers, body: JSON.stringify(payload) });
        if (!res.ok) { const e = await res.json().catch(()=>({})); throw new Error(e.message||'Failed to save.'); }
        ctCloseModal();
        ctShowToast(isEdit ? 'Center updated successfully!' : 'Center added successfully!');
        // Reload the data after a short delay to ensure database is updated
        setTimeout(() => {
            window.location.reload();
        }, 500);
    } catch(e) {
        ctShowErr(e.message || 'An error occurred.');
    } finally {
        btn.disabled = false;
        document.getElementById('ct-save-label').textContent = window._ctEditingId ? 'Update Center' : 'Save Center';
    }
}

function centresPage() {
    return {
        loading: false,
        search: '',
        centers: [],

        async init() { await this.loadData(); },

        getCsrf() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; },
        getHeaders() {
            return { 'Content-Type':'application/json', 'X-CSRF-TOKEN': this.getCsrf(), 'Accept':'application/json' };
        },

        async loadData() {
            this.loading = true;
            try {
                const r = await fetch('/api/centres', { headers: { 'Accept':'application/json' } });
                const d = await r.json();
                const list = d.data || d || [];
                this.centers = list.map(c => ({
                    id:    c.id,
                    name:  c.nomCentre || c.nom || c.name || '',
                    short: c.shortName || c.short || c.nomCourt || c.abbreviation || ''
                }));
            } catch(e) {
                console.error(e);
                ctShowToast('Failed to load centers.', false);
                this.centers = [];
            }
            this.loading = false;
        },

        filteredCenters() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.centers;
            return this.centers.filter(c =>
                (c.name + ' ' + (c.short||'')).toLowerCase().includes(q)
            );
        },

        doOpenAdd() {
            window._ctEditingId = null;
            document.getElementById('ct-name').value  = '';
            document.getElementById('ct-short').value = '';
            ctOpenModal('Add Center', 'Save Center');
        },

        doOpenEdit(center) {
            window._ctEditingId = center.id;
            document.getElementById('ct-name').value  = center.name  || '';
            document.getElementById('ct-short').value = center.short || '';
            ctOpenModal('Edit Center', 'Update Center');
        },

        async doRemove(center) {
            if (!confirm(`Delete center "${center.name}"?`)) return;
            try {
                const res = await fetch(`/api/centres/${center.id}`, {
                    method:'DELETE', headers: this.getHeaders()
                });
                if (!res.ok) { const e = await res.json().catch(()=>({})); throw new Error(e.message||'Delete failed'); }
                await this.loadData();
                ctShowToast('Center deleted successfully!');
                window.dispatchEvent(new CustomEvent('centers-updated'));
            } catch(e) {
                ctShowToast(e.message || 'Failed to delete center.', false);
            }
        },
    };
}

(function(){ const fn=()=>{ Alpine.data('centresPage', centresPage); }; if(window.Alpine&&Alpine.data) fn(); else document.addEventListener('alpine:init',fn); })();
</script>

@endsection
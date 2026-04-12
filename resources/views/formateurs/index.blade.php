@extends('layouts.app')

@section('content')

{{-- Toast --}}
<div id="fm-toast"
     style="display:none; position:fixed; top:22px; left:50%; transform:translateX(-50%); z-index:9999;
            min-width:280px; max-width:420px; border-radius:999px; padding:12px 22px;
            color:white; font-size:13px; font-weight:600; align-items:center; gap:10px;
            box-shadow:0 8px 30px rgba(0,0,0,.15); background:linear-gradient(135deg,#10b981,#059669);">
    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span id="fm-toast-msg" style="flex:1;"></span>
    <button onclick="fmHideToast()" style="background:none;border:none;color:white;cursor:pointer;opacity:.7;font-size:16px;line-height:1;">✕</button>
</div>

<style>
* { box-sizing: border-box; }

.fm-page {
    font-family: 'Inter', system-ui, sans-serif;
    padding: 24px 24px 48px;
    background: #f8fafc;
    min-height: 100vh;
}

/* Breadcrumb */
.fm-breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:#9ca3af; margin-bottom:20px; }
.fm-breadcrumb .sep { color:#d1d5db; }
.fm-breadcrumb .cur { color:#374151; font-weight:600; }

/* Header */
.fm-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
.fm-header h1 { font-size:22px; font-weight:700; color:#111827; margin:0 0 4px; letter-spacing:-.3px; }
.fm-header p  { font-size:13px; color:#6b7280; margin:0; }
.fm-controls  { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

/* Search */
.fm-search { position:relative; }
.fm-search input {
    padding: 9px 14px 9px 36px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 13px; color: #374151; outline: none;
    background: white; width: 220px;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
}
.fm-search input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.fm-search .ico { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; width:14px; height:14px; }

/* Buttons */
.fm-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:9px 16px; border-radius:10px; font-size:13px;
    font-weight:600; border:none; cursor:pointer;
    transition:all .15s; white-space:nowrap; font-family:inherit;
}
.fm-btn svg { width:14px; height:14px; flex-shrink:0; }
.fm-btn-outline { background:white; color:#16a34a; border:1.5px solid #16a34a; }
.fm-btn-outline:hover { background:#f0fdf4; }
.fm-btn-green  { background:#10b981; color:white; }
.fm-btn-green:hover  { background:#059669; }
.fm-btn-add    { background:#6366f1; color:white; }
.fm-btn-add:hover    { background:#4f46e5; box-shadow:0 4px 12px rgba(99,102,241,.3); }

/* Card */
.fm-card {
    background:white; border-radius:16px;
    border:1px solid #e8eaf0;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
    overflow:hidden;
}

/* Table */
.fm-table { width:100%; border-collapse:collapse; }
.fm-table thead th {
    padding: 13px 20px; text-align:left; font-size:11px;
    font-weight:700; letter-spacing:.07em; text-transform:uppercase;
    color:#9ca3af; background:#f9fafb;
    border-bottom:1px solid #f0f0f5; white-space:nowrap;
}
.fm-table thead th.th-right { text-align:right; }
.fm-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background .12s; }
.fm-table tbody tr:last-child { border-bottom:none; }
.fm-table tbody tr:hover { background:#fafbff; }
.fm-table tbody td { padding:14px 20px; vertical-align:middle; }

/* Avatar */
.fm-avatar {
    width:36px; height:36px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:13px; flex-shrink:0;
    background:#e0e7ff; color:#4f46e5;
}

/* Specialty badge */
.fm-spec {
    display:inline-flex; align-items:center; padding:3px 10px;
    border-radius:20px; font-size:12px; font-weight:600; white-space:nowrap;
    background:#f3f4f6; color:#374151;
}

/* Module tags */
.fm-module {
    display:inline-flex; align-items:center; padding:2px 8px;
    border-radius:6px; font-size:11px; font-weight:600;
    background:#dbeafe; color:#1d4ed8; white-space:nowrap;
}

/* Progress */
.fm-prog-track { height:6px; border-radius:99px; background:#e5e7eb; overflow:hidden; width:80px; }
.fm-prog-fill  { height:100%; border-radius:99px; transition:width .5s ease; }
.fm-prog-blue   { background:#3b82f6; }
.fm-prog-yellow { background:#f59e0b; }
.fm-prog-green  { background:#10b981; }

/* Action buttons */
.fm-act { width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:7px; border:none; cursor:pointer; background:transparent; transition:all .12s; }
.fm-act svg { width:15px; height:15px; }
.fm-act-edit  { color:#6366f1; } .fm-act-edit:hover  { background:#e0e7ff; }
.fm-act-del   { color:#ef4444; } .fm-act-del:hover   { background:#fee2e2; }

/* Empty */
.fm-empty { padding:60px 20px; text-align:center; color:#9ca3af; font-size:14px; }

/* ── MODAL ── */
.fm-modal-bg {
    position:fixed; inset:0; background:rgba(15,23,42,.45);
    backdrop-filter:blur(3px); z-index:50;
    align-items:center; justify-content:center; padding:16px;
}
.fm-modal-box {
    background:white; border-radius:18px; width:100%; max-width:560px;
    max-height:90vh; overflow-y:auto;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:fmIn .22s ease;
}
@keyframes fmIn { from{opacity:0;transform:scale(.96) translateY(8px)} to{opacity:1;transform:scale(1) translateY(0)} }
.fm-modal-head {
    padding:20px 24px 16px; border-bottom:1px solid #f0f0f5;
    display:flex; align-items:center; justify-content:space-between;
    position:sticky; top:0; background:white;
    border-radius:18px 18px 0 0; z-index:1;
}
.fm-modal-head h3 { font-size:16px; font-weight:700; color:#111827; margin:0; }
.fm-modal-body { padding:22px 24px; display:flex; flex-direction:column; gap:16px; }
.fm-modal-foot { padding:12px 24px 22px; display:flex; gap:10px; justify-content:flex-end; }

.fm-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:5px; }
.fm-input {
    width:100%; padding:10px 13px; border:1.5px solid #e5e7eb;
    border-radius:9px; font-size:13px; color:#111827; outline:none;
    background:white; transition:border-color .15s, box-shadow .15s;
    font-family:inherit;
}
.fm-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.fm-req { color:#ef4444; }

.fm-modules-box {
    border:1.5px solid #e5e7eb; border-radius:9px;
    padding:12px; min-height:100px; max-height:180px;
    overflow-y:auto; background:#f9fafb;
    display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px;
}
.fm-module-check {
    display:flex; align-items:center; gap:7px; padding:6px 8px;
    border-radius:7px; cursor:pointer; border:1px solid transparent;
    transition:all .12s; font-size:12px; font-weight:500; color:#374151;
    background:white;
}
.fm-module-check:hover { border-color:#c7d2fe; background:#f5f5ff; }
.fm-module-check.checked { background:#ede9fe; border-color:#a78bfa; color:#4f46e5; }
.fm-module-check input { width:13px; height:13px; accent-color:#6366f1; cursor:pointer; }

.fm-btn-cancel { background:white; color:#374151; border:1.5px solid #e5e7eb; }
.fm-btn-cancel:hover { background:#f9fafb; }
.fm-btn-save { background:#6366f1; color:white; min-width:110px; justify-content:center; }
.fm-btn-save:hover { background:#4f46e5; }

[x-cloak] { display:none !important; }
</style>

<script>
let _fmTimer;
function fmShowToast(msg, ok = true) {
    const t = document.getElementById('fm-toast');
    document.getElementById('fm-toast-msg').textContent = msg;
    t.style.background = ok
        ? 'linear-gradient(135deg,#10b981,#059669)'
        : 'linear-gradient(135deg,#ef4444,#dc2626)';
    t.style.display = 'flex';
    t.style.opacity = '1';
    clearTimeout(_fmTimer);
    _fmTimer = setTimeout(fmHideToast, 4000);
}
function fmHideToast() {
    const t = document.getElementById('fm-toast');
    t.style.opacity = '0';
    setTimeout(() => { t.style.display = 'none'; t.style.opacity = '1'; }, 300);
}
function showAlert(m) { fmShowToast(m); }

function fmAvatarColor(name) {
    const c = [['#e0e7ff','#4f46e5'],['#fce7f3','#be185d'],['#dcfce7','#15803d'],
               ['#fff7ed','#c2410c'],['#f0fdf4','#16a34a'],['#ede9fe','#7c3aed'],
               ['#dbeafe','#1d4ed8'],['#fee2e2','#b91c1c']];
    let h = 0; for (let i = 0; i < (name||'').length; i++) h = (name.charCodeAt(i) + ((h<<5)-h));
    return c[Math.abs(h) % c.length];
}
</script>

<div x-data="formateursPage" x-init="init()" class="fm-page">

    {{-- Breadcrumb --}}
    <div class="fm-breadcrumb">
        <span>Tableau de bord</span>
        <span class="sep">›</span>
        <span class="cur">Formateurs</span>
    </div>

    {{-- Header --}}
    <div class="fm-header">
        <div>
            <h1>Formateurs</h1>
            <p>Manage trainers, specialties, and assigned modules</p>
        </div>
        <div class="fm-controls">

            {{-- Search --}}
            <div class="fm-search">
                <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
                <input x-model="search" placeholder="Search formateurs...">
            </div>

            {{-- Import --}}
            <button @click="openImportFile()" class="fm-btn fm-btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6H16a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Import
            </button>

            {{-- Export --}}
            <button @click="exportCSV()" class="fm-btn fm-btn-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export
            </button>

            {{-- Add --}}
            <button @click="openAdd()" class="fm-btn fm-btn-add">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Formateur
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="fm-card">
        {{-- Loading --}}
        <div x-show="loading" style="display:flex; align-items:center; justify-content:center; padding:60px 20px;">
            <svg style="width:32px;height:32px;color:#6366f1;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:.25;"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" style="opacity:.75;"></path>
            </svg>
        </div>

        <div x-show="!loading" style="overflow-x:auto;">
            <table class="fm-table">
                <thead>
                    <tr>
                        <th style="width:220px;">Trainer Name</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Specialty</th>
                        <th>Assigned Modules</th>
                        <th style="min-width:160px;">Progress</th>
                        <th class="th-right" style="padding-right:22px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="formateur in filteredFormateurs()" :key="formateur.id">
                        <tr>
                            {{-- Name + Avatar --}}
                            <td>
                                <div style="display:flex; align-items:center; gap:11px;">
                                    <div class="fm-avatar"
                                         :style="`background:${avatarColors(formateur.nom)[0]}; color:${avatarColors(formateur.nom)[1]};`"
                                         x-text="getInitials(formateur.nom, formateur.prenom)"></div>
                                    <div>
                                        <div style="font-weight:600; font-size:14px; color:#111827;" x-text="formateur.nom + ' ' + formateur.prenom"></div>
                                    </div>
                                </div>
                            </td>
                            {{-- Email --}}
                            <td>
                                <span style="font-size:13px; color:#6b7280;" x-text="formateur.email"></span>
                            </td>
                            {{-- Tel --}}
                            <td>
                                <span style="font-size:13px; color:#6b7280;" x-text="formateur.telephone || '—'"></span>
                            </td>
                            {{-- Specialty --}}
                            <td>
                                <span class="fm-spec" x-text="formateur.specialite || '—'"></span>
                            </td>
                            {{-- Modules --}}
                            <td>
                                <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                    <template x-for="module in formateur.modules" :key="module.id">
                                        <span class="fm-module" x-text="module.nomModule"></span>
                                    </template>
                                    <span x-show="!formateur.modules || formateur.modules.length === 0"
                                          style="font-size:12px; color:#9ca3af; font-style:italic;">No modules</span>
                                </div>
                            </td>
                            {{-- Progress --}}
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="fm-prog-track">
                                        <div class="fm-prog-fill"
                                             :class="progressClass(formateur.progress)"
                                             :style="`width:${formateur.progress}%`"></div>
                                    </div>
                                    <span style="font-size:13px; font-weight:700; min-width:36px;"
                                          :style="`color:${progressTextColor(formateur.progress)}`"
                                          x-text="formateur.progress + '%'"></span>
                                </div>
                            </td>
                            {{-- Actions --}}
                            <td style="text-align:right; padding-right:18px;">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:2px;">
                                    <button @click="openEdit(formateur)" class="fm-act fm-act-edit" title="Edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteFormateur(formateur.id)" class="fm-act fm-act-del" title="Delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!loading && filteredFormateurs().length === 0">
                        <td colspan="7" class="fm-empty">No trainers found. Try adjusting your search.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== MODAL — plain JS like working groups modal ===== --}}
<div id="fm-modal-bg"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); backdrop-filter:blur(3px);
            z-index:50; align-items:center; justify-content:center; padding:16px;">
    <div id="fm-modal-box" class="fm-modal-box" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="fm-modal-head">
            <h3 id="fm-modal-title">Add Formateur</h3>
            <button onclick="fmCloseModal()" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:7px;border:none;cursor:pointer;background:transparent;color:#6b7280;">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="fm-modal-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <label class="fm-label">Last Name <span class="fm-req">*</span></label>
                    <input type="text" id="fm-nom" class="fm-input" placeholder="e.g. Benali">
                </div>
                <div>
                    <label class="fm-label">First Name <span class="fm-req">*</span></label>
                    <input type="text" id="fm-prenom" class="fm-input" placeholder="e.g. Ahmed">
                </div>
            </div>

            <div>
                <label class="fm-label">Email <span class="fm-req">*</span></label>
                <input type="email" id="fm-email" class="fm-input" placeholder="email@example.com">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <label class="fm-label">Telephone</label>
                    <input type="tel" id="fm-telephone" class="fm-input" placeholder="06 00 00 00 00">
                </div>
                <div>
                    <label class="fm-label">Specialty <span class="fm-req">*</span></label>
                    <input type="text" id="fm-specialite" class="fm-input" placeholder="e.g. Développement Web">
                </div>
            </div>

            <div>
                <label class="fm-label">Assigned Modules</label>
                <div id="fm-modules-grid" class="fm-modules-box"></div>
                <p style="margin-top:6px; font-size:11px; color:#9ca3af;">
                    Selected: <strong id="fm-modules-count">0</strong> module(s)
                </p>
            </div>

            <div id="fm-error" style="display:none; align-items:center; gap:8px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:9px; font-size:13px; color:#dc2626;">
                <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="fm-error-text"></span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="fm-modal-foot">
            <button onclick="fmCloseModal()" class="fm-btn fm-btn-cancel">Cancel</button>
            <button id="fm-save-btn" onclick="fmSave()" class="fm-btn fm-btn-save">
                <span id="fm-save-label">Save Formateur</span>
            </button>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
[x-cloak] { display:none !important; }
</style>

<script>
window._fmEditingId = null;
window._fmAllModules = [];
window._fmSelectedModules = [];

// ── Modal open/close ──────────────────────────────────────────────────────────
function fmOpenModal(title, saveLabel) {
    document.getElementById('fm-modal-title').textContent = title;
    document.getElementById('fm-save-label').textContent  = saveLabel;
    document.getElementById('fm-error').style.display = 'none';
    const bg = document.getElementById('fm-modal-bg');
    bg.style.display = 'flex';
}

function fmCloseModal() {
    document.getElementById('fm-modal-bg').style.display = 'none';
    window._fmEditingId = null;
    window._fmSelectedModules = [];
}

document.getElementById('fm-modal-bg').addEventListener('click', function(e) {
    if (e.target === this) fmCloseModal();
});

function fmShowError(msg) {
    const box = document.getElementById('fm-error');
    document.getElementById('fm-error-text').textContent = msg;
    box.style.display = 'flex';
}

// ── Render module checkboxes ──────────────────────────────────────────────────
function fmRenderModules() {
    const grid = document.getElementById('fm-modules-grid');
    grid.innerHTML = '';
    window._fmAllModules.forEach(m => {
        const checked = window._fmSelectedModules.includes(m.id);
        const label = document.createElement('label');
        label.className = 'fm-module-check' + (checked ? ' checked' : '');
        label.innerHTML = `
            <input type="checkbox" value="${m.id}" ${checked ? 'checked' : ''}>
            <span>${m.nomModule || m.nom || 'Module ' + m.id}</span>
        `;
        const cb = label.querySelector('input');
        cb.onchange = function() {
            if (this.checked) {
                window._fmSelectedModules.push(m.id);
                label.classList.add('checked');
            } else {
                window._fmSelectedModules = window._fmSelectedModules.filter(x => x !== m.id);
                label.classList.remove('checked');
            }
            document.getElementById('fm-modules-count').textContent = window._fmSelectedModules.length;
        };
        grid.appendChild(label);
    });
    document.getElementById('fm-modules-count').textContent = window._fmSelectedModules.length;
}

// ── Save ──────────────────────────────────────────────────────────────────────
async function fmSave() {
    document.getElementById('fm-error').style.display = 'none';

    const nom       = document.getElementById('fm-nom').value.trim();
    const prenom    = document.getElementById('fm-prenom').value.trim();
    const email     = document.getElementById('fm-email').value.trim();
    const telephone = document.getElementById('fm-telephone').value.trim();
    const specialite= document.getElementById('fm-specialite').value.trim();

    if (!nom)       { fmShowError('Last name is required.');    return; }
    if (!prenom)    { fmShowError('First name is required.');   return; }
    if (!email)     { fmShowError('Email is required.');        return; }
    if (!specialite){ fmShowError('Specialty is required.');    return; }

    const payload = { nom, prenom, email, telephone, specialite, modules: window._fmSelectedModules };

    const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) headers['X-CSRF-TOKEN'] = meta.content;

    const btn = document.getElementById('fm-save-btn');
    btn.disabled = true;
    document.getElementById('fm-save-label').textContent = 'Saving…';

    try {
        const isEdit = !!window._fmEditingId;
        const url    = isEdit ? `/api/formateurs/${window._fmEditingId}` : '/api/formateurs';
        const method = isEdit ? 'PUT' : 'POST';
        const res    = await fetch(url, { method, headers, body: JSON.stringify(payload) });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || 'Failed to save.');
        }

        fmCloseModal();
        fmShowToast(isEdit ? 'Formateur updated successfully!' : 'Formateur added successfully!');

        // Refresh Alpine table
        const el = document.querySelector('[x-data="formateursPage"]');
        if (el && el._x_dataStack) {
            await el._x_dataStack[0].loadData();
        }
    } catch(e) {
        fmShowError(e.message || 'An error occurred.');
    } finally {
        btn.disabled = false;
        document.getElementById('fm-save-label').textContent =
            window._fmEditingId ? 'Update Formateur' : 'Save Formateur';
    }
}

// ── Alpine component ──────────────────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.data('formateursPage', () => ({
        loading: false,
        search: '',
        formateurs: [],
        allModules: [],

        async init() {
            await this.loadData();
        },

        getHeaders() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            };
        },

        async loadData() {
            this.loading = true;
            try {
                const [fRes, mRes] = await Promise.all([
                    fetch('/api/formateurs', { headers: this.getHeaders() }),
                    fetch('/api/modules',    { headers: this.getHeaders() })
                ]);
                if (!fRes.ok || !mRes.ok) throw new Error('API error');
                this.formateurs = await fRes.json();
                this.allModules = await mRes.json();
                // Share modules with modal
                window._fmAllModules = this.allModules;
            } catch(e) {
                console.error(e);
                fmShowToast('Failed to load data', false);
            }
            this.loading = false;
        },

        filteredFormateurs() {
            const q = this.search.trim().toLowerCase();
            if (!q) return this.formateurs;
            return this.formateurs.filter(f => {
                return (f.nom + ' ' + f.prenom + ' ' + (f.email||'') + ' ' + (f.specialite||'') + ' ' + (f.telephone||''))
                    .toLowerCase().includes(q);
            });
        },

        getInitials(nom, prenom) {
            return ((nom||'').charAt(0) + (prenom||'').charAt(0)).toUpperCase();
        },

        avatarColors(name) {
            return fmAvatarColor(name);
        },

        progressClass(p) {
            if (p >= 100) return 'fm-prog-green';
            if (p >= 40)  return 'fm-prog-yellow';
            return 'fm-prog-blue';
        },

        progressTextColor(p) {
            if (p >= 100) return '#10b981';
            if (p >= 40)  return '#f59e0b';
            return '#6b7280';
        },

        openAdd() {
            window._fmEditingId = null;
            window._fmSelectedModules = [];
            document.getElementById('fm-nom').value       = '';
            document.getElementById('fm-prenom').value    = '';
            document.getElementById('fm-email').value     = '';
            document.getElementById('fm-telephone').value = '';
            document.getElementById('fm-specialite').value= '';
            fmRenderModules();
            fmOpenModal('Add Formateur', 'Save Formateur');
        },

        openEdit(f) {
            window._fmEditingId = f.id;
            window._fmSelectedModules = (f.modules || []).map(m => m.id);
            document.getElementById('fm-nom').value       = f.nom       || '';
            document.getElementById('fm-prenom').value    = f.prenom    || '';
            document.getElementById('fm-email').value     = f.email     || '';
            document.getElementById('fm-telephone').value = f.telephone || '';
            document.getElementById('fm-specialite').value= f.specialite|| '';
            fmRenderModules();
            fmOpenModal('Edit Formateur', 'Update Formateur');
        },

        async deleteFormateur(id) {
            if (!confirm('Are you sure you want to delete this formateur?')) return;
            try {
                const res = await fetch(`/api/formateurs/${id}`, {
                    method: 'DELETE', headers: this.getHeaders()
                });
                if (!res.ok) throw new Error('Delete failed');
                await this.loadData();
                fmShowToast('Formateur deleted successfully!');
            } catch(e) {
                fmShowToast('Failed to delete formateur.', false);
            }
        },

        exportCSV() {
            this.loading = true;
            fetch('/api/formateurs', { headers: this.getHeaders() })
                .then(r => r.json())
                .then(data => {
                    const hdrs = ['Last Name','First Name','Email','Telephone','Specialty','Required Hours (h)','Progress (%)','Assigned Modules'];
                    const rows = [hdrs.join(',')];
                    data.forEach(f => {
                        const mods = (f.modules||[]).map(m => m.nomModule||m.name).join(' | ');
                        rows.push([f.nom,f.prenom,f.email,f.telephone||'',f.specialite,f.heures_totales_requises??0,f.progress??0,mods||'None']
                            .map(v => `"${(v+'').replace(/"/g,'""')}"`).join(','));
                    });
                    const blob = new Blob(['\uFEFF' + rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = `formateurs_${new Date().toISOString().slice(0,10)}.csv`;
                    a.click();
                    fmShowToast('Export completed successfully!');
                })
                .catch(() => fmShowToast('Export failed.', false))
                .finally(() => { this.loading = false; });
        },

        openImportFile() {
            const input = document.createElement('input');
            input.type = 'file'; input.accept = '.csv,text/csv';
            input.onchange = (e) => this.handleImportFile(e.target.files[0]);
            input.click();
        },

        async handleImportFile(file) {
            if (!file) return;
            const text = await file.text();
            const rows = this.parseCSV(text);
            if (!rows.length) { fmShowToast('No data found in CSV.', false); return; }
            let success = 0, failed = 0;
            for (const row of rows) {
                const payload = {
                    nom:       row.nom || row['Last Name'] || row.last_name || '',
                    prenom:    row.prenom || row['First Name'] || row.first_name || '',
                    email:     row.email || row['Email'] || '',
                    telephone: row.telephone || row['Telephone'] || '',
                    specialite:row.specialite || row['Specialty'] || row.specialty || '',
                    modules:   []
                };
                if (row.module_ids) {
                    payload.modules = row.module_ids.split(/\||,|;/).map(s => s.trim()).filter(Boolean).map(Number);
                }
                try {
                    const res = await fetch('/api/formateurs', {
                        method: 'POST', headers: this.getHeaders(), body: JSON.stringify(payload)
                    });
                    if (!res.ok) throw new Error();
                    success++;
                } catch { failed++; }
            }
            await this.loadData();
            fmShowToast(`Import done! ✓ ${success} added${failed ? ', ✗ ' + failed + ' failed' : ''}`);
        },

        parseCSV(text) {
            const lines = text.split(/\r?\n/).filter(l => l.trim());
            if (!lines.length) return [];
            const headers = lines.shift().split(/,(?=(?:[^"]*"[^"]*")*[^"]*$)/)
                .map(h => h.replace(/^"|"$/g,'').trim());
            return lines.map(line => {
                const cols = line.split(/,(?=(?:[^"]*"[^"]*")*[^"]*$)/)
                    .map(c => c.replace(/^"|"$/g,'').replace(/""/g,'"').trim());
                const obj = {};
                headers.forEach((h, i) => obj[h] = cols[i] || '');
                return obj;
            });
        },
    }));
});
</script>

@endsection
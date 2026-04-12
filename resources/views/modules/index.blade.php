@extends('layouts.app')

@section('content')

{{-- Toast --}}
<div id="md-toast"
     style="display:none; position:fixed; top:22px; left:50%; transform:translateX(-50%); z-index:9999;
            min-width:280px; max-width:420px; border-radius:999px; padding:12px 22px;
            color:white; font-size:13px; font-weight:600; align-items:center; gap:10px;
            box-shadow:0 8px 30px rgba(0,0,0,.15);">
    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span id="md-toast-msg" style="flex:1;"></span>
    <button onclick="mdHideToast()" style="background:none;border:none;color:white;cursor:pointer;opacity:.7;font-size:16px;line-height:1;">✕</button>
</div>

<style>
* { box-sizing: border-box; }
.md-page { font-family:'Inter',system-ui,sans-serif; padding:24px 24px 48px; background:#f8fafc; min-height:100vh; }
.md-breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:#9ca3af; margin-bottom:20px; }
.md-breadcrumb .sep { color:#d1d5db; }
.md-breadcrumb .cur { color:#374151; font-weight:600; }
.md-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:24px; }
.md-header h1 { font-size:22px; font-weight:700; color:#111827; margin:0 0 4px; letter-spacing:-.3px; }
.md-header p  { font-size:13px; color:#6b7280; margin:0; }
.md-controls  { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.md-toolbar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.md-search { position:relative; }
.md-search input { padding:9px 14px 9px 36px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; color:#374151; outline:none; background:white; width:230px; transition:border-color .15s,box-shadow .15s; font-family:inherit; }
.md-search input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.md-search .ico { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none; width:14px; height:14px; }
.md-select { padding:9px 13px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; color:#374151; background:white; outline:none; cursor:pointer; font-family:inherit; transition:border-color .15s; }
.md-select:focus { border-color:#6366f1; }
.md-btn { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:10px; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .15s; white-space:nowrap; font-family:inherit; }
.md-btn svg { width:14px; height:14px; flex-shrink:0; }
.md-btn-add    { background:#6366f1; color:white; } .md-btn-add:hover { background:#4f46e5; box-shadow:0 4px 12px rgba(99,102,241,.3); }
.md-btn-cancel { background:white; color:#374151; border:1.5px solid #e5e7eb; } .md-btn-cancel:hover { background:#f9fafb; }
.md-btn-save   { background:#6366f1; color:white; min-width:110px; justify-content:center; } .md-btn-save:hover { background:#4f46e5; }
.md-btn-save:disabled { opacity:.6; cursor:not-allowed; }
.md-card { background:white; border-radius:16px; border:1px solid #e8eaf0; box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; }
.md-table { width:100%; border-collapse:collapse; }
.md-table thead th { padding:13px 20px; text-align:left; font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:#9ca3af; background:#f9fafb; border-bottom:1px solid #f0f0f5; white-space:nowrap; }
.md-table thead th.th-right { text-align:right; }
.md-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background .12s; }
.md-table tbody tr:last-child { border-bottom:none; }
.md-table tbody tr:hover { background:#fafbff; }
.md-table tbody td { padding:14px 20px; vertical-align:middle; }
.md-code { display:inline-flex; align-items:center; padding:3px 9px; border-radius:7px; font-size:12px; font-weight:700; background:#ede9fe; color:#6d28d9; font-family:'Courier New',monospace; letter-spacing:.4px; }
.md-hours { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:7px; font-size:12px; font-weight:600; background:#f0fdf4; color:#15803d; }
.md-group-badge { display:inline-flex; align-items:center; padding:3px 8px; margin:2px; border-radius:6px; font-size:11px; font-weight:600; background:#dbeafe; color:#1e40af; }
.md-prog-track { height:6px; border-radius:99px; background:#e5e7eb; overflow:hidden; width:100px; }
.md-prog-fill  { height:100%; border-radius:99px; transition:width .5s ease; }
.md-prog-blue { background:#3b82f6; } .md-prog-yellow { background:#f59e0b; } .md-prog-green { background:#10b981; }
.md-act { width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:7px; border:none; cursor:pointer; background:transparent; transition:all .12s; }
.md-act svg { width:15px; height:15px; }
.md-act-edit { color:#6366f1; } .md-act-edit:hover { background:#e0e7ff; }
.md-act-del  { color:#ef4444; } .md-act-del:hover  { background:#fee2e2; }
.md-empty { padding:60px 20px; text-align:center; color:#9ca3af; font-size:14px; }
.md-modal-bg { position:fixed; inset:0; background:rgba(15,23,42,.45); backdrop-filter:blur(3px); z-index:50; align-items:center; justify-content:center; padding:16px; }
.md-modal-box { background:white; border-radius:18px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:mdIn .22s ease; }
@keyframes mdIn { from{opacity:0;transform:scale(.96) translateY(8px)} to{opacity:1;transform:scale(1) translateY(0)} }
.md-modal-head { padding:20px 24px 16px; border-bottom:1px solid #f0f0f5; display:flex; align-items:center; justify-content:space-between; }
.md-modal-head h3 { font-size:16px; font-weight:700; color:#111827; margin:0; }
.md-modal-body { padding:22px 24px; display:flex; flex-direction:column; gap:16px; max-height:70vh; overflow-y:auto; }
.md-modal-foot { padding:12px 24px 22px; display:flex; gap:10px; justify-content:flex-end; }
.md-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:5px; }
.md-req { color:#ef4444; }
.md-input { width:100%; padding:10px 13px; border:1.5px solid #e5e7eb; border-radius:9px; font-size:13px; color:#111827; outline:none; background:white; transition:border-color .15s,box-shadow .15s; font-family:inherit; }
.md-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.md-err { display:none; align-items:center; gap:8px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:9px; font-size:13px; color:#dc2626; }
.md-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.md-multiselect { border:1.5px solid #e5e7eb; border-radius:9px; padding:8px; max-height:200px; overflow-y:auto; background:white; }
.md-multiselect-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border-radius:6px; cursor:pointer; transition:background .1s; }
.md-multiselect-item:hover { background:#f3f4f6; }
.md-multiselect-item input[type="checkbox"] { width:16px; height:16px; cursor:pointer; }
.md-multiselect-item label { font-size:13px; color:#374151; cursor:pointer; flex:1; }
@keyframes spin { to { transform:rotate(360deg); } }
[x-cloak] { display:none !important; }
</style>

<script>
let _mdTimer;
function mdShowToast(msg, ok = true) {
    const t = document.getElementById('md-toast');
    document.getElementById('md-toast-msg').textContent = msg;
    t.style.background = ok ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)';
    t.style.display = 'flex'; t.style.opacity = '1';
    clearTimeout(_mdTimer);
    _mdTimer = setTimeout(mdHideToast, 4000);
}
function mdHideToast() {
    const t = document.getElementById('md-toast');
    t.style.opacity = '0';
    setTimeout(() => { t.style.display='none'; t.style.opacity='1'; }, 300);
}
function showAlert(m) { mdShowToast(m); }
</script>

<div x-data="modulesPage()" x-init="init()" class="md-page">

    <div class="md-breadcrumb">
        <span>Tableau de bord</span>
        <span class="sep">›</span>
        <span class="cur">Modules</span>
    </div>

    <div class="md-header">
        <div>
            <h1>Modules</h1>
            <p>Manage training modules and their progression</p>
        </div>
        <div class="md-controls">
            <button @click="mdOpenAdd()" class="md-btn md-btn-add">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Module
            </button>
        </div>
    </div>

    <div class="md-toolbar">
        <div class="md-search">
            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <input x-model="search" placeholder="Search modules...">
        </div>
        <select x-model="filterAdvancement" class="md-select">
            <option value="all">All Advancement</option>
            <option value="not_started">Not Started (0%)</option>
            <option value="in_progress">In Progress (1–99%)</option>
            <option value="completed">Completed (100%)</option>
        </select>
        <select x-model="sortBy" class="md-select">
            <option value="code">Sort by Code</option>
            <option value="title">Sort by Title</option>
            <option value="hours">Sort by Hours</option>
            <option value="advancement">Sort by Advancement</option>
        </select>
    </div>

    <div class="md-card">
        <div x-show="loading" style="display:flex;align-items:center;justify-content:center;padding:60px 20px;">
            <svg style="width:32px;height:32px;color:#6366f1;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity:.25;"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" style="opacity:.75;"></path>
            </svg>
        </div>
        <div x-show="!loading" style="overflow-x:auto;">
            <table class="md-table">
                <thead>
                    <tr>
                        <th style="width:110px;">Module Code</th>
                        <th style="width:200px;">Title</th>
                        <th style="width:120px;">Required Hours</th>
                        <th style="width:280px;">Assigned Groups</th>
                        <th style="width:150px;">Advancement</th>
                        <th class="th-right" style="padding-right:22px; width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="module in filteredModules()" :key="module.id">
                        <tr>
                            <td><span class="md-code" x-text="module.codeModule"></span></td>
                            <td><span style="font-weight:600;font-size:13px;color:#111827;" x-text="module.nomModule"></span></td>
                            <td>
                                <span class="md-hours">
                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="module.volumeHoraire + 'h'"></span>
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    <template x-if="module.assignedGroups && module.assignedGroups.length > 0">
                                        <template x-for="group in module.assignedGroups" :key="group.id">
                                            <span class="md-group-badge" x-text="group.name"></span>
                                        </template>
                                    </template>
                                    <template x-if="!module.assignedGroups || module.assignedGroups.length === 0">
                                        <span style="font-size:12px;color:#9ca3af;font-style:italic;">No groups assigned</span>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="md-prog-track">
                                        <div class="md-prog-fill" :class="progressClass(module.advancement)" :style="`width:${module.advancement}%`"></div>
                                    </div>
                                    <span style="font-size:13px;font-weight:700;min-width:36px;" :style="`color:${progressTextColor(module.advancement)}`" x-text="module.advancement + '%'"></span>
                                </div>
                            </td>
                            <td style="text-align:right;padding-right:18px;">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:2px;">
                                    <button @click="mdOpenEdit(module)" class="md-act md-act-edit" title="Edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteModule(module.id)" class="md-act md-act-del" title="Delete">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!loading && filteredModules().length === 0">
                        <td colspan="6" class="md-empty">No modules found. Try adjusting your search.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="md-modal-bg"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,.45);
            backdrop-filter:blur(3px); z-index:50;
            align-items:center; justify-content:center; padding:16px;">
    <div class="md-modal-box" onclick="event.stopPropagation()">
        <div class="md-modal-head">
            <h3 id="md-modal-title">Add Module</h3>
            <button onclick="mdCloseModal()" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:7px;border:none;cursor:pointer;background:transparent;color:#6b7280;">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="md-modal-body">
            <div class="md-grid2">
                <div>
                    <label class="md-label">Module Code <span class="md-req">*</span></label>
                    <input type="text" id="md-code" class="md-input" placeholder="M101">
                </div>
                <div>
                    <label class="md-label">Required Hours <span class="md-req">*</span></label>
                    <input type="number" id="md-hours" class="md-input" placeholder="40" min="1">
                </div>
            </div>
            <div>
                <label class="md-label">Title <span class="md-req">*</span></label>
                <input type="text" id="md-title" class="md-input" placeholder="Module Title">
            </div>
            <div>
                <label class="md-label">Assigned Groups</label>
                <div class="md-multiselect" id="md-groups-multiselect">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            <div>
            <div id="md-err" class="md-err">
                <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="md-err-text"></span>
            </div>
        </div>
        <div class="md-modal-foot">
            <button onclick="mdCloseModal()" class="md-btn md-btn-cancel">Cancel</button>
            <button id="md-save-btn" onclick="mdSave()" class="md-btn md-btn-save">
                <span id="md-save-label">Save Module</span>
            </button>
        </div>
    </div>
</div>

<script>
window._mdEditingId = null;
window._mdAllGroups = [];

function mdShowErr(msg) { const b=document.getElementById('md-err'); document.getElementById('md-err-text').textContent=msg; b.style.display='flex'; }
function mdHideErr()    { document.getElementById('md-err').style.display='none'; }
function mdCloseModal() { document.getElementById('md-modal-bg').style.display='none'; window._mdEditingId=null; }
document.getElementById('md-modal-bg').addEventListener('click', function(e){ if(e.target===this) mdCloseModal(); });

function mdOpenModal(title, label) {
    mdHideErr();
    document.getElementById('md-modal-title').textContent = title;
    document.getElementById('md-save-label').textContent  = label;
    document.getElementById('md-modal-bg').style.display  = 'flex';
    setTimeout(() => document.getElementById('md-code').focus(), 60);
}

function mdPopulateGroupsMultiselect(selectedGroupIds = []) {
    const container = document.getElementById('md-groups-multiselect');
    container.innerHTML = '';
    
    if (window._mdAllGroups.length === 0) {
        container.innerHTML = '<div style="padding:8px;color:#9ca3af;font-size:12px;">No groups available</div>';
        return;
    }

    const selectedSet = new Set((selectedGroupIds || []).map(id => String(id)));
    
    window._mdAllGroups.forEach(group => {
        const div = document.createElement('div');
        div.className = 'md-multiselect-item';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `group-${group.id}`;
        checkbox.value = group.id;
        checkbox.checked = selectedSet.has(String(group.id));
        
        const label = document.createElement('label');
        label.setAttribute('for', `group-${group.id}`);
        label.textContent = group.nomGroupe || group.name || `Group ${group.id}`;
        
        div.appendChild(checkbox);
        div.appendChild(label);
        container.appendChild(div);
    });
}

function mdGetSelectedGroupIds() {
    const checkboxes = document.querySelectorAll('#md-groups-multiselect input[type="checkbox"]:checked');
    return Array.from(checkboxes)
        .map(cb => Number(cb.value))
        .filter(n => !Number.isNaN(n));
}

async function mdSave() {
    mdHideErr();
    const codeModule    = document.getElementById('md-code').value.trim();
    const nomModule     = document.getElementById('md-title').value.trim();
    const volumeHoraire = parseInt(document.getElementById('md-hours').value) || 0;
    const groupeIds     = mdGetSelectedGroupIds();

    if (!codeModule)    { mdShowErr('Module code is required.');    return; }
    if (!nomModule)     { mdShowErr('Module title is required.');   return; }
    if (!volumeHoraire) { mdShowErr('Required hours must be > 0.'); return; }

    const headers = { 'Content-Type':'application/json', 'Accept':'application/json' };
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) headers['X-CSRF-TOKEN'] = meta.content;

    const btn = document.getElementById('md-save-btn');
    btn.disabled = true;
    document.getElementById('md-save-label').textContent = 'Saving…';

    try {
        const isEdit = !!window._mdEditingId;
        const url    = isEdit ? `/api/modules/${window._mdEditingId}` : '/api/modules';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = {
            codeModule,    
            nomModule,    
            volumeHoraire,    
            groupe_ids: groupeIds, // Array of group IDs
            code_module:   codeModule,   
            nom_module:  nomModule,
            volume_horaire: volumeHoraire, 
            code:          codeModule,   
            nom:         nomModule,
            name:          nomModule,    
            title:       nomModule,
            hours:         volumeHoraire, 
            volume:     volumeHoraire,
        };

        console.log(`[Modules] ${method} ${url}`, payload);

        const res = await fetch(url, { method, headers, body: JSON.stringify(payload) });

        if (!res.ok) {
            const e = await res.json().catch(() => ({}));
            console.error('[Modules] save error response:', e);
            throw new Error(e.message || e.error || `HTTP ${res.status} — Failed to save.`);
        }

        const saved = await res.json();
        console.log('[Modules] save success:', saved);

        mdCloseModal();
        mdShowToast(isEdit ? 'Module updated successfully!' : 'Module added successfully!');

        try {
            const el = document.querySelector('[x-data="modulesPage()"]') || document.querySelector('[x-data]');
            if (el) {
                const comp = el._x_dataStack?.[0] || Alpine.$data(el);
                if (comp && comp.loadData) await comp.loadData();
            }
        } catch(refreshErr) {
            console.warn('[Modules] Could not auto-refresh, reloading page:', refreshErr);
            setTimeout(() => window.location.reload(), 800);
        }

    } catch(e) {
        console.error('[Modules] Save failed:', e);
        mdShowErr(e.message || 'An error occurred. Check console for details.');
    } finally {
        btn.disabled = false;
        document.getElementById('md-save-label').textContent = window._mdEditingId ? 'Update Module' : 'Save Module';
    }
}

function modulesPage() {
    return {
        loading: false,
        search: '',
        filterAdvancement: 'all',
        sortBy: 'code',
        modules: [],
        groups: [],

        async init() { 
            await this.loadGroups();
            await this.loadData(); 
        },

        getHeaders() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            };
        },

        async loadGroups() {
            try {
                const r = await fetch('/api/groupes?all=1', { headers: this.getHeaders() });
                if (!r.ok) throw new Error('Failed to load groups');
                const data = await r.json();
                this.groups = data.data || data || [];
                window._mdAllGroups = this.groups;
                console.log('[Modules] Loaded groups:', this.groups);
            } catch(e) {
                console.error('[Modules] loadGroups error:', e);
            }
        },

        async loadData() {
            this.loading = true;
            try {
                const r = await fetch('/api/modules?perPage=999', { headers: this.getHeaders() });
                if (!r.ok) throw new Error('API error ' + r.status);
                const data = await r.json();
                console.log('[Modules] API raw response:', data);
                const raw = Array.isArray(data) ? data : (data.data || data.modules || []);
                
                this.modules = raw.map(m => {
                    // Get assigned group names
// Normalize group IDs from multiple possible response formats
                // Normalize group IDs from multiple possible response formats
                let groupIds = Array.isArray(m.groupe_ids) ? m.groupe_ids
                    : Array.isArray(m.groupeIds) ? m.groupeIds
                    : Array.isArray(m.groupes) && m.groupes.length && typeof m.groupes[0] === 'object'
                        ? m.groupes.map(g => g.id)
                        : Array.isArray(m.groupes) ? m.groupes : [];

                // Normalize to numeric IDs and remove duplicates
                groupIds = Array.from(new Set(groupIds.map(id => Number(id)).filter(n => !Number.isNaN(n))));

                const assignedGroups = groupIds.map(gid => {
                    const group = this.groups.find(g => g.id == gid);
                    return {
                        id: gid,
                        name: group ? (group.nomGroupe || group.name || `Group ${gid}`) : `Group ${gid}`
                    };
                });

                return {
                    ...m,
                    id:            m.id,
                    codeModule:    m.codeModule   || m.code_module || m.code        || m.codemodule || '',
                    nomModule:     m.nomModule    || m.nom_module  || m.nom         || m.name       || m.title || '',
                    volumeHoraire: m.volumeHoraire ?? m.volume_horaire ?? m.volume ?? m.hours       ?? 0,
                    advancement:   Number(m.advancement  ?? m.avancement  ?? m.progress   ?? m.progression ?? 0),
                    groupeIds:     groupIds,
                    assignedGroups: assignedGroups
                };
                });
                console.log('[Modules] normalized:', this.modules);
            } catch(e) {
                console.error('[Modules] loadData error:', e);
                mdShowToast('Failed to load modules.', false);
            }
            this.loading = false;
        },

        filteredModules() {
            const q = this.search.trim().toLowerCase();
            let list = this.modules.filter(m => {
                const match = (m.codeModule + ' ' + m.nomModule).toLowerCase().includes(q);
                const adv = m.advancement || 0;
                let mf = true;
                if (this.filterAdvancement === 'not_started') mf = adv === 0;
                else if (this.filterAdvancement === 'in_progress') mf = adv > 0 && adv < 100;
                else if (this.filterAdvancement === 'completed')   mf = adv >= 100;
                return match && mf;
            });
            if (this.sortBy === 'code')        list.sort((a,b) => (a.codeModule||'').localeCompare(b.codeModule||''));
            else if (this.sortBy === 'title')  list.sort((a,b) => (a.nomModule||'').localeCompare(b.nomModule||''));
            else if (this.sortBy === 'hours')  list.sort((a,b) => (a.volumeHoraire||0) - (b.volumeHoraire||0));
            else if (this.sortBy === 'advancement') list.sort((a,b) => (a.advancement||0) - (b.advancement||0));
            return list;
        },

        progressClass(p) {
            if (p >= 100) return 'md-prog-green';
            if (p >= 50)  return 'md-prog-yellow';
            return 'md-prog-blue';
        },

        progressTextColor(p) {
            if (p >= 100) return '#10b981';
            if (p >= 50)  return '#f59e0b';
            return '#6b7280';
        },

        mdOpenAdd() {
            window._mdEditingId = null;
            document.getElementById('md-code').value  = '';
            document.getElementById('md-title').value = '';
            document.getElementById('md-hours').value = '';
            mdPopulateGroupsMultiselect([]);
            mdOpenModal('Add Module', 'Save Module');
        },

        mdOpenEdit(m) {
            window._mdEditingId = m.id;
            document.getElementById('md-code').value  = m.codeModule    || '';
            document.getElementById('md-title').value = m.nomModule     || '';
            document.getElementById('md-hours').value = m.volumeHoraire || '';
            mdPopulateGroupsMultiselect(m.groupeIds || []);
            mdOpenModal('Edit Module', 'Update Module');
        },

        async deleteModule(id) {
            if (!confirm('Are you sure you want to delete this module?')) return;
            try {
                const res = await fetch(`/api/modules/${id}`, { method: 'DELETE', headers: this.getHeaders() });
                if (!res.ok) throw new Error('Delete failed');
                await this.loadData();
                mdShowToast('Module deleted successfully!');
            } catch(e) {
                mdShowToast('Failed to delete module.', false);
            }
        },
    };
}

(function(){
    const fn = () => { Alpine.data('modulesPage', modulesPage); };
    if (window.Alpine && Alpine.data) fn();
    else document.addEventListener('alpine:init', fn);
})();
</script>
@endsection
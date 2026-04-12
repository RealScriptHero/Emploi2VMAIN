{{-- ====================================================
     FICHIER: resources/views/parametres/index.blade.php
     ==================================================== --}}
@extends('layouts.app')

@section('title', __('settings.title'))
@section('page-title', __('settings.title'))
@section('breadcrumb', __('settings.breadcrumb'))

@section('content')

{{-- Toast Alert --}}
<div id="successAlert"
     style="display:none; position:fixed; top:24px; left:50%; transform:translateX(-50%); z-index:9999; background-color:#22c55e; color:white; padding:14px 22px; border-radius:9999px; box-shadow:0 10px 25px rgba(0,0,0,0.2); min-width:280px; max-width:420px; align-items:center; gap:12px;">
    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span style="font-size:14px; font-weight:500; flex:1;" id="successMessage"></span>
    <button onclick="hideAlert()" style="background:none; border:none; color:white; cursor:pointer; opacity:0.8; display:flex; align-items:center;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
    let alertTimeout;
    function showAlert(message) {
        const alertBox = document.getElementById('successAlert');
        const msg = document.getElementById('successMessage');
        msg.textContent = message;
        alertBox.style.display = 'flex';
        alertBox.style.opacity = '1';
        clearTimeout(alertTimeout);
        alertTimeout = setTimeout(() => hideAlert(), 2000); // 2 seconds as requested
    }
    function hideAlert() {
        const alertBox = document.getElementById('successAlert');
        alertBox.style.opacity = '0';
        setTimeout(() => { alertBox.style.display = 'none'; alertBox.style.opacity = '1'; }, 300);
    }

    // Check for success message on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showAlert("{{ session('success') }}");
        @endif
    });
</script>

<style>
    .param-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform .18s ease, border-color .18s ease;
        height: 100%;
    }
    .param-card:hover {
        transform: translateY(-1px);
    }
    .param-card-header {
        padding: 1.15rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: .85rem;
        background: #f8fafc;
    }
    .param-card-header span {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.12);
        color: #4f46e5;
        font-size: 1rem;
    }
    .param-card-header h5 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }
    .param-card-body {
        padding: 1.5rem;
    }
    .param-label {
        display: block;
        font-size: .78rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: .45rem;
        letter-spacing: .3px;
        text-transform: uppercase;
    }
    .param-input,
    .param-select {
        width: 100%;
        padding: .85rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: .95rem;
        color: #0f172a;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none;
        margin-bottom: 1rem;
    }
    .param-input:focus,
    .param-select:focus {
        border-color: var(--accent, #6366f1);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .12);
        background: #ffffff;
    }
    .btn-save {
        width: 100%;
        padding: .85rem 1rem;
        border-radius: 12px;
        border: none;
        font-size: .95rem;
        font-weight: 700;
        cursor: pointer;
        color: #fff;
        background: var(--accent, #6366f1);
        transition: transform .15s ease, filter .15s ease, box-shadow .15s ease;
        letter-spacing: .2px;
        box-shadow: 0 10px 20px rgba(15, 23, 42, .08);
    }
    .btn-save:hover {
        filter: brightness(.95);
        transform: translateY(-1px);
    }
    .btn-save:active {
        transform: translateY(0);
    }
    .btn-purple {
        background: #8b5cf6 !important;
    }
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: .95rem 1rem;
        margin-bottom: 1.2rem;
    }
    .toggle-title { font-size: .95rem; font-weight: 700; color: #0f172a; }
    .toggle-sub   { font-size: .82rem; color: #64748b; margin-top: 2px; }
    .toggle-btn {
        padding: .48rem 1.15rem;
        border-radius: 999px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        font-size: .86rem;
        font-weight: 700;
        cursor: pointer;
        color: #475569;
        transition: all .2s ease;
        min-width: 66px;
        text-align: center;
        box-shadow: inset 0 0 0 0 rgba(99, 102, 241, .15);
    }
    .toggle-btn.active {
        background: var(--accent, #6366f1);
        color: #fff;
        border-color: var(--accent, #6366f1);
    }
    .color-grid {
        display: flex;
        gap: .9rem;
        flex-wrap: wrap;
        margin-bottom: .75rem;
    }
    .color-btn {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }
    .color-btn.selected {
        box-shadow: 0 0 0 3px #ffffff, 0 0 0 5px currentColor;
    }
    .color-btn:hover { transform: scale(1.05); }
    .hint-text,
    .warn-text {
        font-size: .82rem;
        color: #64748b;
        margin-bottom: 1rem;
    }
    .warn-text { color: #b45309; }
    .alert-success,
    .alert-error,
    .alert-warning {
        border-radius: 14px;
        padding: .95rem 1.1rem;
        margin-bottom: 1.25rem;
        font-size: .9rem;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
    }
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
    }
    .alert-warning {
        background: #fef3c7;
        color: #92400e;
    }
    .invalid-msg {
        font-size: .82rem;
        color: #dc2626;
        margin-top: -.75rem;
        margin-bottom: .75rem;
    }
    .section-divider {
        border: none;
        border-top: 1px solid #e2e8f0;
        margin: 1.25rem 0;
    }
    
    /* NEW: Equal height cards in row */
    .settings-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .settings-row > .settings-col {
        flex: 1;
        min-width: 0;
    }
    
    .security-card-wrapper {
        max-width: 600px;
    }
    
    @media (max-width: 992px) {
        .settings-row {
            flex-direction: column;
        }
        .security-card-wrapper {
            max-width: 100%;
        }
        .param-card-body {
            padding: 1.25rem;
        }
    }

    body.dark-mode {
        background: #000 !important;
        color: #fff;
    }

    body.dark-mode .param-card {
        background: rgba(20, 20, 20, 0.96);
        border-color: #2d2d2d;
    }
    body.dark-mode .param-card-header {
        background: #111;
    }
    body.dark-mode .param-input,
    body.dark-mode .param-select,
    body.dark-mode .toggle-row,
    body.dark-mode .color-btn {
        background: #111;
        border-color: #303030;
        color: #f8fafc;
    }
    body.dark-mode .param-input:focus,
    body.dark-mode .param-select:focus {
        background: #111;
    }
    body.dark-mode .alert-success,
    body.dark-mode .alert-error,
    body.dark-mode .alert-warning {
        background: #111;
        border: 1px solid #2d2d2d;
        color: #f8fafc;
    }
</style>

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- FIRST ROW: General Settings + Appearance (side by side) --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="settings-row">
    
    {{-- ⚙️ General Settings --}}
    <div class="settings-col">
        <div class="param-card">
            <div class="param-card-header">
                <span>⚙️</span>
                <h5>{{ __('settings.general.title') }}</h5>
            </div>
            <div class="param-card-body">
                <form method="POST" action="{{ route('parametres.general') }}">
                    @csrf @method('PUT')

                    <label class="param-label">{{ __('settings.general.institution_name') }}</label>
                    <input type="text" name="institution_name" class="param-input"
                           value="{{ old('institution_name', $settings['institution_name'] ?? '') }}"
                           required>

                    <label class="param-label">{{ __('settings.general.academic_year') }}</label>
                    <input type="text" name="academic_year" class="param-input"
                           value="{{ old('academic_year', $settings['academic_year'] ?? '') }}"
                           required>
                    <div class="warn-text">
                        {{ __('settings.general.academic_year_warning') }}
                    </div>

                    <label class="param-label">{{ __('settings.general.director') }}</label>
                    <input type="text" name="director_name" class="param-input"
                           value="{{ old('director_name', $settings['director_name'] ?? '') }}"
                           required>

                    <label class="param-label">{{ __('settings.general.language') }}</label>
                    @php
                        $currentLanguage = in_array($settings['language'] ?? null, ['fr', 'en'])
                            ? $settings['language']
                            : config('app.locale');
                    @endphp
                    <select name="language" class="param-select" onchange="this.form.submit()">
                        <option value="fr" {{ $currentLanguage === 'fr' ? 'selected' : '' }}>
                            {{ __('settings.general.languages.french') }}
                        </option>
                        <option value="en" {{ $currentLanguage === 'en' ? 'selected' : '' }}>
                            {{ __('settings.general.languages.english') }}
                        </option>
                    </select>

                    <button type="submit" class="btn-save">
                        {{ __('settings.general.save_changes') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 🔒 Security --}}
    <div class="settings-col">
        <div class="param-card">
            <div class="param-card-header">
                <span>🔒</span>
                <h5>{{ __('settings.security.title') }}</h5>
            </div>
            <div class="param-card-body">
                <form method="POST" action="{{ route('parametres.password') }}">
                    @csrf @method('PUT')

                    <label class="param-label">{{ __('settings.security.current_password') }}</label>
                    <input type="password" name="current_password"
                           class="param-input @error('current_password') is-invalid @enderror"
                           placeholder="••••••••" required>
                    @error('current_password')
                        <div class="invalid-msg">{{ $message }}</div>
                    @enderror

                    <label class="param-label">{{ __('settings.security.new_password') }}</label>
                    <input type="password" name="new_password"
                           class="param-input @error('new_password') is-invalid @enderror"
                           placeholder="••••••••" required minlength="8">
                    @error('new_password')
                        <div class="invalid-msg">{{ $message }}</div>
                    @enderror

                    <label class="param-label">{{ __('settings.security.confirm_password') }}</label>
                    <input type="password" name="new_password_confirmation"
                           class="param-input" placeholder="••••••••" required>

                    <button type="submit" class="btn-save btn-purple">
                        {{ __('settings.security.update_password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>{{-- end first row --}}

@endsection
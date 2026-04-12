@extends('layouts.app')

@section('content')
<div x-data="absenceGroupesPage" x-init="init()">
    {{-- Breadcrumbs --}}
    <div class="max-w-7xl mx-auto mb-4 text-sm px-4">
        <span class="text-gray-500">Tableau de bord</span>
        <span class="mx-2 text-gray-400">&gt;</span>
        <span class="text-gray-700 font-medium">Group Absences</span>
    </div>

    <div class="max-w-7xl mx-auto px-4 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Group Absences</h1>
                <p class="text-sm text-gray-600 mt-1">Manage group absence records</p>
            </div>
            <button @click="openAdd()" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Absence</span>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="card-body overflow-x-auto">
            <div x-show="loading" class="flex items-center justify-center py-8">
                <div class="animate-spin"><svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
            </div>

            <table class="min-w-full divide-y divide-gray-200" x-show="!loading">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Groupe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Raison</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                    <template x-for="absence in absences" :key="absence.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm font-medium text-gray-900" x-text="absence.dateAbsence"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm font-medium text-gray-900" x-text="getGroupeName(absence.groupe_id)"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Groupe</span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-700" x-text="getGroupeName(absence.groupe_id)"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-700" x-text="getModuleCode(absence.module)"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-700" x-text="absence.motif"></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(absence)" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteAbsence(absence.id)" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="absences.length === 0 && !loading">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">No absence records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div x-show="modalOpen" x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
         @click.self="closeModal()">

        <div @click.stop
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <span x-show="!editingId">Add Group Absence</span>
                    <span x-show="editingId">Edit Group Absence</span>
                </h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form @submit.prevent="save()" class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                    <input x-model="form.dateAbsence" type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group <span class="text-red-500">*</span></label>
                    <select x-model="form.groupe_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select group</option>
                        <template x-for="groupe in groupes" :key="groupe.id">
                            <option :value="groupe.id" x-text="groupe.nomGroupe"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Module</label>
                    <select x-model="form.module_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select module (optional)</option>
                        <template x-for="module in modules" :key="module.id">
                            <option :value="module.id" x-text="module.code ? `${module.code} - ${module.nom}` : module.nom"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason <span class="text-red-500">*</span></label>
                    <textarea x-model="form.motif" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Reason for absence"></textarea>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        <span x-show="!editingId">Save</span>
                        <span x-show="editingId">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('absenceGroupesPage', () => ({
        modalOpen: false,
        editingId: null,
        loading: false,
        form: { dateAbsence: '', groupe_id: '', motif: '', module_id: '' },
        absences: [],
        groupes: [],
        modules: [],

        async loadData() {
            this.loading = true;
            try {
                const [absencesRes, groupesRes, modulesRes] = await Promise.all([
                    fetch('/api/absence-groupes'),
                    fetch('/api/groupes?all=1'),
                    fetch('/api/modules')
                ]);

                if (!absencesRes.ok || !groupesRes.ok || !modulesRes.ok) {
                    throw new Error('API error');
                }

                const normalize = async (res) => {
                    const body = await res.json();
                    return body && body.data ? body.data : body;
                };

                this.absences = await normalize(absencesRes);
                this.groupes = await normalize(groupesRes);
                this.modules = await normalize(modulesRes);
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Failed to load absence data');
            }
            this.loading = false;
        },
        async init() {
            await this.loadData();
            this._lookupInterval = setInterval(() => this.fetchLookups(), 8000);
            window.addEventListener('focus', () => this.fetchLookups());
        },

        async fetchLookups() {
            try {
                const [groupesRes, modulesRes] = await Promise.all([
                    fetch('/api/groupes?all=1'),
                    fetch('/api/modules')
                ]);
                if (!groupesRes.ok || !modulesRes.ok) return;
                const normalize = async (res) => { const body = await res.json(); return body && body.data ? body.data : body; };
                this.groupes = await normalize(groupesRes);
                this.modules = await normalize(modulesRes);
            } catch (e) {
                console.warn('lookup refresh failed', e);
            }
        },

        toDateInputValue(value) {
            if (!value) return '';
            if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value;
            const normalized = String(value).trim().replace(/\//g, '-').replace(/\./g, '-');
            const parts = normalized.split('-').map(p => p.trim());

            if (parts.length === 3) {
                if (parts[0].length === 4) {
                    return `${parts[0].padStart(4,'0')}-${parts[1].padStart(2,'0')}-${parts[2].padStart(2,'0')}`;
                }
                return `${parts[2].padStart(4,'0')}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
            }

            const d = new Date(value);
            if (!Number.isNaN(d.getTime())) {
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            }
            return '';
        },

        openAdd() {
            this.editingId = null;
            this.form = { dateAbsence: '', groupe_id: '', motif: '', module_id: '' };
            this.modalOpen = true;
        },

        openEdit(absence) {
            this.editingId = absence.id;
            this.form = {
                dateAbsence: this.toDateInputValue(absence.dateAbsence),
                groupe_id: absence.groupe_id,
                motif: absence.motif,
                module_id: absence.module_id || ''
            };
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.editingId = null;
        },

        async save() {
            if (!this.form.dateAbsence || !this.form.groupe_id || !this.form.motif.trim()) {
                alert('Please fill all required fields');
                return;
            }

            try {
                if (this.editingId) {
                    const response = await fetch(`/api/absence-groupes/${this.editingId}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    });
                    if (!response.ok) throw new Error('Update failed');
                } else {
                    const response = await fetch('/api/absence-groupes', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    });
                    if (!response.ok) throw new Error('Create failed');
                }
                await this.loadData();
                this.closeModal();
            } catch (error) {
                console.error('Error saving absence:', error);
                alert('Failed to save absence record');
            }
        },

        async deleteAbsence(id) {
            if (!confirm('Are you sure you want to delete this absence record?')) return;
            try {
                const response = await fetch(`/api/absence-groupes/${id}`, { method: 'DELETE' });
                if (!response.ok) throw new Error('Delete failed');
                await this.loadData();
            } catch (error) {
                console.error('Error deleting absence:', error);
                alert('Failed to delete absence record');
            }
        },

        getGroupeName(id) {
            const groupe = this.groupes.find(g => String(g.id) === String(id));
            return groupe ? groupe.nomGroupe : 'Unknown';
        },

        getModuleCode(module) {
            if (!module) return '-';
            return module.code || module.nom || '-';
        }
    }));
});
</script>
@endsection

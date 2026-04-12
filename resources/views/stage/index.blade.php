@extends('layouts.app')

@section('content')

{{-- Success Alert (Top Center) --}}
<div id="successAlert"
     style="display:none; position:fixed; top:24px; left:50%; transform:translateX(-50%); z-index:9999; background-color:#22c55e; color:white; padding:16px 24px; border-radius:9999px; box-shadow:0 10px 25px rgba(0,0,0,0.2); min-width:280px; max-width:420px; align-items:center; gap:12px;">
    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span style="font-size:14px; font-weight:500; white-space:nowrap; flex:1;" id="successMessage"></span>
    <button onclick="hideAlert()" style="background:none; border:none; color:white; cursor:pointer; opacity:0.8; display:flex; align-items:center;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- Error Alert (Top Center) --}}
<div id="errorAlert"
     style="display:none; position:fixed; top:24px; left:50%; transform:translateX(-50%); z-index:9999; background-color:#ef4444; color:white; padding:16px 24px; border-radius:9999px; box-shadow:0 10px 25px rgba(0,0,0,0.2); min-width:280px; max-width:420px; align-items:center; gap:12px;">
    <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span style="font-size:14px; font-weight:500; white-space:nowrap; flex:1;" id="errorMessage"></span>
    <button onclick="hideErrorAlert()" style="background:none; border:none; color:white; cursor:pointer; opacity:0.8; display:flex; align-items:center;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
    let alertTimeout, errorAlertTimeout;

    function showAlert(message) {
        const alertBox = document.getElementById('successAlert');
        const msg = document.getElementById('successMessage');
        msg.textContent = message;
        alertBox.style.display = 'flex';
        alertBox.style.opacity = '1';
        alertBox.style.transition = 'opacity 0.3s ease';
        clearTimeout(alertTimeout);
        alertTimeout = setTimeout(() => hideAlert(), 4000);
    }

    function hideAlert() {
        const alertBox = document.getElementById('successAlert');
        alertBox.style.transition = 'opacity 0.3s ease';
        alertBox.style.opacity = '0';
        setTimeout(() => {
            alertBox.style.display = 'none';
            alertBox.style.opacity = '1';
        }, 300);
    }

    function showErrorAlert(message) {
        const alertBox = document.getElementById('errorAlert');
        const msg = document.getElementById('errorMessage');
        msg.textContent = message;
        alertBox.style.display = 'flex';
        alertBox.style.opacity = '1';
        alertBox.style.transition = 'opacity 0.3s ease';
        clearTimeout(errorAlertTimeout);
        errorAlertTimeout = setTimeout(() => hideErrorAlert(), 4000);
    }

    function hideErrorAlert() {
        const alertBox = document.getElementById('errorAlert');
        alertBox.style.transition = 'opacity 0.3s ease';
        alertBox.style.opacity = '0';
        setTimeout(() => {
            alertBox.style.display = 'none';
            alertBox.style.opacity = '1';
        }, 300);
    }
</script>

<div x-data="stagesPage" x-init="init()">
    {{-- Breadcrumbs --}}
    <div class="mb-4 text-sm">
        <span class="text-gray-500">Tableau de bord</span>
        <span class="mx-2 text-gray-400">></span>
        <span class="text-gray-700 font-medium">Stage</span>
    </div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gestion des Stages</h1>
        <button @click="openAdd()" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Ajouter Stage</span>
        </button>
    </div>

    {{-- Filters and Search --}}
    <div class="card p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <select x-model="filterStatus" class="filter-select px-8 min-w-[190px]">
                    <option value=""> Tous les Stages &ensp; &ensp;</option>
                    <option value="en-cours">En cours</option>
                    <option value="termines">Terminés</option>
                    <option value="a-venir">À venir</option>
                </select>
                <select x-model="filterGroupe" class="filter-select px-8 min-w-[200px]">
                    <option value=""> Tous les Groupes &ensp; &ensp;</option>
                    <template x-for="groupe in groupes" :key="groupe.id">
                        <option :value="groupe.id" x-text="groupe.nomGroupe"></option>
                    </template>
                </select>
                <input x-model="search" type="text" placeholder="Rechercher..." class="filter-select px-4 min-w-[280px]">
            </div>
            <button class="text-gray-500 hover:text-gray-700 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div x-show="loading" class="flex items-center justify-center py-8">
            <div class="animate-spin">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200" x-show="!loading">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Groupe</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date Début</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date Fin</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="row in filteredStageRows()" :key="`${row.groupe.id}-${row.stage?.id || 'none'}`">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row.groupe.nomGroupe"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="row.stage ? formatDate(row.stage.dateDebut) : '—'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="row.stage ? formatDate(row.stage.dateFin) : '—'"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full" :class="getStatusBadge(row.stage)">
                                <span x-text="getStatusLabel(row.stage)"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a @click="row.stage ? openEdit(row.stage) : openAdd(row.groupe)" href="javascript:void(0)"
                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 inline-flex items-center gap-1 p-1.5 rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <template x-if="row.stage">
                                <a @click="deleteStage(row.stage.id)" href="javascript:void(0)"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 inline-flex items-center gap-1 p-1.5 rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/>
                                    </svg>
                                </a>
                            </template>
                        </td>
                    </tr>
                </template>
                <tr x-show="filteredStageRows().length === 0 && !loading">
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucun stage trouvé.</td>
                </tr>
            </tbody>
        </table>
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
                    <span x-show="!editingId">Ajouter Stage</span>
                    <span x-show="editingId">Modifier Stage</span>
                </h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form @submit.prevent="save()" class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Groupe <span class="text-red-500">*</span></label>
                    <select x-model="form.groupe_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sélectionner un groupe</option>
                        <template x-for="groupe in groupes" :key="groupe.id">
                            <option :value="groupe.id" x-text="groupe.nomGroupe"></option>
                        </template>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Début <span class="text-red-500">*</span></label>
                        <input x-model="form.dateDebut" type="date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Fin <span class="text-red-500">*</span></label>
                        <input x-model="form.dateFin" type="date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formateur <span class="text-red-500">*</span></label>
                    <select x-model="form.formateur_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sélectionner un formateur</option>
                        <template x-for="formateur in formateurs" :key="formateur.id">
                            <option :value="formateur.id" x-text="`${formateur.nom} ${formateur.prenom}`"></option>
                        </template>
                    </select>
                </div>

                {{-- Validation Error --}}
                <div x-show="validationError" class="text-sm text-red-600 bg-red-50 px-4 py-2 rounded-lg" x-text="validationError"></div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        <span x-show="!editingId">Enregistrer</span>
                        <span x-show="editingId">Mettre à jour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stagesPage', () => ({
        modalOpen: false,
        editingId: null,
        loading: false,
        search: '',
        filterGroupe: '',
        filterStatus: '',
        validationError: '',
        form: { dateDebut: '', dateFin: '', groupe_id: '', formateur_id: '' },
        stages: [],
        groupes: [],
        formateurs: [],

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
                const [stagesRes, groupesRes, formateursRes] = await Promise.all([
                    fetch('/api/stages', { headers: this.getHeaders() }),
                    fetch('/api/groupes?all=1', { headers: this.getHeaders() }),
                    fetch('/api/formateurs', { headers: this.getHeaders() })
                ]);

                if (!stagesRes.ok || !groupesRes.ok || !formateursRes.ok) throw new Error('API error');

                const stagesJson = await stagesRes.json();
                const groupesJson = await groupesRes.json();
                const formateursJson = await formateursRes.json();

                this.stages = Array.isArray(stagesJson) ? stagesJson : (stagesJson.data || []);
                this.groupes = Array.isArray(groupesJson) ? groupesJson : (groupesJson.data || []);
                this.formateurs = Array.isArray(formateursJson) ? formateursJson : (formateursJson.data || []);
            } catch (error) {
                console.error('Error loading data:', error);
                showErrorAlert('Échec du chargement des données');
            }
            this.loading = false;
        },

        formatDate(dateString) {
            if (!dateString) return '—';
            const d = this.parseStageDate(dateString);
            if (!d) return '—';
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },

        parseStageDate(value) {
            if (!value) return null;
            const s = String(value).slice(0, 10);
            if (/^\d{4}-\d{2}-\d{2}$/.test(s)) {
                const [y, m, d] = s.split('-').map(Number);
                return new Date(y, m - 1, d);
            }
            const d = new Date(value);
            if (Number.isNaN(d.getTime())) return null;
            return new Date(d.getFullYear(), d.getMonth(), d.getDate());
        },

        getStageStatus(stage) {            if (stage?.status) {
                return stage.status;
            }
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const start = this.parseStageDate(stage.dateDebut);
            const end = this.parseStageDate(stage.dateFin);
            if (!start || !end) return 'a-venir';
            start.setHours(0, 0, 0, 0);
            end.setHours(0, 0, 0, 0);
            if (today < start) return 'a-venir';
            if (today >= end) return 'termines';
            return 'en-cours';
        },

        getStatusLabel(stage) {
            const status = this.getStageStatus(stage);
            switch(status) {
                case 'a-venir': return 'À venir';
                case 'en-cours': return 'En cours';
                case 'termines': return 'Terminé';
                default: return 'Inconnu';
            }
        },

        getStatusBadge(stage) {
            const status = this.getStageStatus(stage);
            switch(status) {
                case 'a-venir': return 'bg-blue-100 text-blue-700';
                case 'en-cours': return 'bg-yellow-100 text-yellow-700';
                case 'termines': return 'bg-green-100 text-green-700';
                default: return 'bg-gray-100 text-gray-700';
            }
        },

        filteredStageRows() {
            return this.groupes.map(groupe => {
                const stage = this.stages.find(stage => String(stage.groupe_id) === String(groupe.id));
                return { groupe, stage };
            }).filter(row => {
                const groupeName = row.groupe.nomGroupe || '';
                const matchesSearch = this.search === '' || groupeName.toLowerCase().includes(this.search.toLowerCase());
                const matchesFilter = this.filterGroupe === '' || String(row.groupe.id) === String(this.filterGroupe);
                const matchesStatus = this.filterStatus === '' || this.getStageStatus(row.stage) === this.filterStatus;
                return matchesSearch && matchesFilter && matchesStatus;
            });
        },

        openAdd(groupe = null) {
            this.editingId = null;
            this.form = {
                dateDebut: '',
                dateFin: '',
                groupe_id: groupe ? groupe.id : '',
                formateur_id: ''
            };
            this.validationError = '';
            this.modalOpen = true;
        },

        toDateInputValue(value) {
            if (!value) return '';
            // Already in ISO format ? keep it
            if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value;

            // Accept formats d/m/y or dd/mm/yyyy and other separators
            const normalized = value.replace(/\//g, '-').replace(/\./g, '-');
            const parts = normalized.split('-').map(p => p.trim());

            if (parts.length === 3) {
                // either dd-mm-yyyy or yyyy-mm-dd
                if (parts[0].length === 4) {
                    return `${parts[0].padStart(4, '0')}-${parts[1].padStart(2,'0')}-${parts[2].padStart(2,'0')}`;
                }
                // assume day/month/year
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

        openEdit(stage) {
            this.editingId = stage.id;
            this.form = {
                dateDebut: this.toDateInputValue(stage.dateDebut),
                dateFin: this.toDateInputValue(stage.dateFin),
                groupe_id: stage.groupe_id,
                formateur_id: stage.formateur_id
            };
            this.validationError = '';
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.editingId = null;
            this.validationError = '';
        },

        async save() {
            if (!this.form.groupe_id || !this.form.dateDebut || !this.form.dateFin || !this.form.formateur_id) {
                this.validationError = 'Veuillez remplir tous les champs obligatoires';
                return;
            }

            if (new Date(this.form.dateFin) < new Date(this.form.dateDebut)) {
                this.validationError = 'La date de fin doit être après la date de début';
                return;
            }

            try {
                let response;
                if (this.editingId) {
                    response = await fetch(`/api/stages/${this.editingId}`, {
                        method: 'PUT',
                        headers: this.getHeaders(),
                        body: JSON.stringify(this.form)
                    });
                } else {
                    response = await fetch('/api/stages', {
                        method: 'POST',
                        headers: this.getHeaders(),
                        body: JSON.stringify(this.form)
                    });
                }

                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    this.validationError = errorData?.message || 'Échec de l\'enregistrement du stage';
                    return;
                }

                await this.loadData();
                this.closeModal();
                showAlert(this.editingId ? 'Stage mis à jour avec succès !' : 'Stage créé avec succès !');

            } catch (error) {
                console.error('Error saving stage:', error);
                this.validationError = 'Erreur réseau. Veuillez réessayer.';
            }
        },

        async deleteStage(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce stage ?')) return;
            try {
                const response = await fetch(`/api/stages/${id}`, {
                    method: 'DELETE',
                    headers: this.getHeaders()
                });
                if (!response.ok) throw new Error('Delete failed');
                await this.loadData();
                showAlert('Stage supprimé avec succès !');
            } catch (error) {
                console.error('Error deleting stage:', error);
                showErrorAlert('Échec de la suppression du stage');
            }
        },

        getGroupeName(id) {
            const groupe = this.groupes.find(g => String(g.id) === String(id));
            return groupe ? groupe.nomGroupe : 'Inconnu';
        },

        getFormateurName(id) {
            const formateur = this.formateurs.find(f => String(f.id) === String(id));
            return formateur ? `${formateur.nom} ${formateur.prenom}` : 'Inconnu';
        }
    }));
});
</script>
@endsection
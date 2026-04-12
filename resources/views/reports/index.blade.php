@extends('layouts.app')

@section('content')
<div x-data="analyticsPage" x-init="init()">

    {{-- Breadcrumbs --}}
    <div class="w-full mb-4 text-sm px-4">
        <span class="text-gray-500">Tableau de bord</span>
        <span class="mx-2 text-gray-400">&gt;</span>
        <span class="text-gray-700 font-medium">Rapports & Avancement</span>
    </div>

    {{-- Header --}}
    <div class="w-full px-4 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900" :data-date="new Date().toLocaleDateString('fr-FR', {year: 'numeric', month: 'long', day: 'numeric'})">Tableau de Bord Analytique</h1>
                <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Analyses et statistiques en temps réel
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="refreshData()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors inline-flex items-center gap-2 shadow-sm">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualiser
                </button>
                <button @click="exportReport()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors inline-flex items-center gap-2 shadow-sm">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exporter PDF
                </button>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div id="section-avancement" class="w-full px-4 mb-6 report-section">
        <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:16px;">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" style="padding:16px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                    <div>
                        <p class="text-gray-500 font-semibold uppercase" style="font-size:11px;letter-spacing:.06em;margin:0 0 6px">Total Formateurs</p>
                        <p class="font-bold text-gray-900" style="font-size:28px;line-height:1;margin:0 0 8px" x-text="metrics.totalFormateurs">—</p>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <svg style="width:13px;height:13px;flex-shrink:0;color:#22c55e" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <span style="font-size:11px;font-weight:700;color:#16a34a">+2.5%</span>
                            <span style="font-size:11px;color:#9ca3af">vs mois dernier</span>
                        </div>
                    </div>
                    <div style="background:linear-gradient(135deg,#4ade80,#16a34a);padding:10px;border-radius:10px;flex-shrink:0;">
                        <svg style="width:22px;height:22px;color:white;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" style="padding:16px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                    <div>
                        <p class="text-gray-500 font-semibold uppercase" style="font-size:11px;letter-spacing:.06em;margin:0 0 6px">Total Groupes</p>
                        <p class="font-bold text-gray-900" style="font-size:28px;line-height:1;margin:0 0 8px" x-text="metrics.totalGroupes">—</p>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <svg style="width:13px;height:13px;flex-shrink:0;color:#3b82f6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <span style="font-size:11px;font-weight:700;color:#2563eb">+8.3%</span>
                            <span style="font-size:11px;color:#9ca3af">vs mois dernier</span>
                        </div>
                    </div>
                    <div style="background:linear-gradient(135deg,#60a5fa,#2563eb);padding:10px;border-radius:10px;flex-shrink:0;">
                        <svg style="width:22px;height:22px;color:white;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" style="padding:16px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                    <div style="flex:1;min-width:0;">
                        <p class="text-gray-500 font-semibold uppercase" style="font-size:11px;letter-spacing:.06em;margin:0 0 6px">Modules Actifs</p>
                        <p class="font-bold text-gray-900" style="font-size:28px;line-height:1;margin:0 0 8px">
                            <span x-text="metrics.modulesActifs">—</span><span style="font-size:16px;color:#9ca3af;font-weight:500"> / </span><span style="font-size:16px;color:#9ca3af;font-weight:500" x-text="metrics.totalModules">—</span>
                        </p>
                        <div style="background:#f3f4f6;border-radius:99px;height:6px;margin-bottom:4px;">
                            <div style="background:#a855f7;height:6px;border-radius:99px;transition:width .7s ease;" :style="`width:${metrics.totalModules>0?((metrics.modulesActifs/metrics.totalModules)*100).toFixed(0):0}%`"></div>
                        </div>
                        <span style="font-size:11px;font-weight:700;color:#9333ea" x-text="`${metrics.totalModules>0?((metrics.modulesActifs/metrics.totalModules)*100).toFixed(0):0}% actifs`"></span>
                    </div>
                    <div style="background:linear-gradient(135deg,#c084fc,#9333ea);padding:10px;border-radius:10px;flex-shrink:0;">
                        <svg style="width:22px;height:22px;color:white;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" style="padding:16px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                    <div>
                        <p class="text-gray-500 font-semibold uppercase" style="font-size:11px;letter-spacing:.06em;margin:0 0 6px">Taux d'Absence</p>
                        <p class="font-bold text-gray-900" style="font-size:28px;line-height:1;margin:0 0 8px">
                            <span x-text="metrics.tauxAbsence">—</span><span style="font-size:16px;color:#9ca3af;font-weight:500">%</span>
                        </p>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <svg style="width:13px;height:13px;flex-shrink:0;color:#22c55e" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            <span style="font-size:11px;font-weight:700;color:#16a34a">-0.5%</span>
                            <span style="font-size:11px;color:#9ca3af">amélioration</span>
                        </div>
                    </div>
                    <div style="background:linear-gradient(135deg,#f87171,#dc2626);padding:10px;border-radius:10px;flex-shrink:0;">
                        <svg style="width:22px;height:22px;color:white;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Main Chart --}}
    <div id="section-absence" class="w-full px-4 mb-6 report-section">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Avancement par Groupe</h3>
                    <p class="text-sm text-gray-500 mt-1">Taux moyen : <span class="font-semibold text-blue-600" x-text="calculateAvgAdvancement() + '%'"></span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="chartView='bar';updateMainChart()" :class="chartView==='bar'?'bg-blue-600 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">Barres</button>
                    <button @click="chartView='line';updateMainChart()" :class="chartView==='line'?'bg-blue-600 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">Lignes</button>
                    <button @click="chartView='mixed';updateMainChart()" :class="chartView==='mixed'?'bg-blue-600 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">Mixte</button>
                </div>
            </div>
            <div style="position:relative;height:400px;"><canvas id="mainChart"></canvas></div>
        </div>
    </div>

    {{-- Secondary Charts --}}
    <div id="section-charge" class="w-full px-4 mb-6 report-section">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Progression des Modules</h3>
                    <select x-model="moduleFilter" @change="updateModuleChart()" class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Tous</option>
                        <option value="DEV">DEV</option>
                        <option value="NET">NET</option>
                        <option value="RESEAUX">RÉSEAUX</option>
                    </select>
                </div>
                <div style="height:300px;"><canvas id="moduleChart"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Tendance des Absences</h3>
                    <div class="flex gap-2">
                        <button @click="absencePeriod='week';updateAbsenceChart()" :class="absencePeriod==='week'?'bg-blue-600 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="text-xs px-3 py-1.5 rounded-lg transition-colors font-medium">Semaine</button>
                        <button @click="absencePeriod='month';updateAbsenceChart()" :class="absencePeriod==='month'?'bg-blue-600 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="text-xs px-3 py-1.5 rounded-lg transition-colors font-medium">Mois</button>
                    </div>
                </div>
                <div style="height:300px;"><canvas id="absenceChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Workload + Reports --}}
    <div class="w-full px-4 mb-6">
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Charge de Travail</h3>
                <div style="height:280px;"><canvas id="workloadChart"></canvas></div>
            </div>

            {{-- RAPPORTS RÉCENTS - keeping original code --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Rapports Récents</h3>
                    <button @click="openNewReportModal()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Nouveau
                    </button>
                </div>

                <div x-show="loadingReports" class="flex items-center justify-center py-12">
                    <svg class="animate-spin" style="width:28px;height:28px;color:#2563eb" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>

                <div x-show="!loadingReports && recentReports.length === 0" class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg style="width:40px;height:40px;margin-bottom:8px;opacity:.4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm">Aucun rapport disponible</p>
                    <button @click="openNewReportModal()" class="mt-3 text-xs text-blue-600 font-semibold hover:underline">Créer le premier rapport</button>
                </div>

                <div x-show="!loadingReports && recentReports.length > 0" class="divide-y divide-gray-100">
                    <template x-for="report in recentReports" :key="report.id">
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="report.name"></p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide"
                                            :style="getReportBadgeStyle(report.type)" x-text="report.type"></span>
                                        <span class="text-xs text-gray-400" x-text="formatDate(report.created_at || report.date)"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button @click="downloadReport(report)"
                                        title="Télécharger"
                                        class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg style="width:16px;height:16px;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteReport(report.id)"
                                        title="Supprimer"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg style="width:16px;height:16px;display:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Export Sections --}}
    <div x-show="exportModal.open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45);display:none;">

        <div @click.stop
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Export PDF</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Sélectionnez les sections à inclure</p>
                </div>
                <button @click="exportModal.open=false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-5 space-y-3">
                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer" @click="exportModal.selectedSections.avancement = !exportModal.selectedSections.avancement">
                    <input type="checkbox" x-model="exportModal.selectedSections.avancement" class="w-4 h-4 text-blue-600 rounded">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Avancement par Groupe</p>
                        <p class="text-xs text-gray-500">Progression des modules et statistiques</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-red-50 cursor-pointer" @click="exportModal.selectedSections.absence = !exportModal.selectedSections.absence">
                    <input type="checkbox" x-model="exportModal.selectedSections.absence" class="w-4 h-4 text-red-600 rounded">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Absences</p>
                        <p class="text-xs text-gray-500">Détail des absences par formateur et groupe</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer" @click="exportModal.selectedSections.emploi = !exportModal.selectedSections.emploi">
                    <input type="checkbox" x-model="exportModal.selectedSections.emploi" class="w-4 h-4 text-purple-600 rounded">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Emploi du Temps</p>
                        <p class="text-xs text-gray-500">Statistiques de charge formateurs et salles</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer" @click="exportModal.selectedSections.tous = !exportModal.selectedSections.tous">
                    <input type="checkbox" x-model="exportModal.selectedSections.tous" class="w-4 h-4 text-green-600 rounded">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Rapport Complet</p>
                        <p class="text-xs text-gray-500">Toutes les sections + métriques KPI</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button @click="exportModal.open=false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button @click="performExport()"
                    :disabled="!exportModal.selectedSections.avancement && !exportModal.selectedSections.absence && !exportModal.selectedSections.emploi && !exportModal.selectedSections.tous"
                    :class="(!exportModal.selectedSections.avancement && !exportModal.selectedSections.absence && !exportModal.selectedSections.emploi && !exportModal.selectedSections.tous) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Télécharger PDF</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal & Toast - keeping original code --}}
    <div x-show="modal.open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45);display:none;">

        <div @click.stop
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Nouveau Rapport</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Le rapport sera généré et sauvegardé automatiquement</p>
                </div>
                <button @click="modal.open=false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de rapport <span class="text-red-500">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <template x-for="t in ['Avancement par Groupe','Absences','Charge de Travail','Statistiques']" :key="t">
                            <button @click="modal.type = t"
                                :class="modal.type===t ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50'"
                                class="border-2 rounded-xl px-3 py-2.5 text-sm font-medium transition-all text-left"
                                x-text="t">
                            </button>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom du rapport <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input x-model="modal.name" type="text"
                        placeholder="Ex: Rapport Semaine 06-12 Fév"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">Laissez vide pour un nom automatique avec la date</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Période</label>
                    <div class="flex gap-3">
                        <input x-model="modal.dateFrom" type="date"
                            class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-400 self-center text-sm">→</span>
                        <input x-model="modal.dateTo" type="date"
                            class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div x-show="modal.error" class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-lg px-3 py-2.5">
                    <svg style="width:16px;height:16px;color:#ef4444;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm text-red-600" x-text="modal.error"></span>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button @click="modal.open=false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button @click="submitNewReport()"
                    :disabled="modal.loading || !modal.type"
                    :class="modal.loading || !modal.type ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                    <svg x-show="modal.loading" class="animate-spin" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span x-text="modal.loading ? 'Génération...' : 'Générer le rapport'"></span>
                </button>
            </div>
        </div>
    </div>

    <div x-show="toast.show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium min-w-[260px] justify-center"
         :class="toast.type==='success'?'bg-green-500 text-white':'bg-red-500 text-white'"
         style="display:none;">
        <svg x-show="toast.type==='success'" style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toast.message"></span>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
window._analyticsCharts = window._analyticsCharts || {};

function todayLocalDateString() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const y = today.getFullYear();
    const m = String(today.getMonth() + 1).padStart(2, '0');
    const d = String(today.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

// EVERY GROUP SHOWS BOTH BARS (Présentiel + Distanciel)
function buildMainChart(groupes, viewType) {
    const id='mainChart';
    if(window._analyticsCharts[id]){window._analyticsCharts[id].destroy();delete window._analyticsCharts[id];}
    const ctx=document.getElementById(id); if(!ctx)return;
    
    const groups = groupes.slice(0,20);
    const labels = groups.map(g => g.nomGroupe || g.name || 'N/A');
    
    const presentielData = groups.map(g => {
        return Number(g.avancement_presentiel ?? g.avancement_presentiel ?? g.advancement ?? g.avancement ?? 0) || 0;
    });
    const distancielData = groups.map(g => {
        return Number(g.avancement_distanciel ?? g.avancement_distanciel ?? 0) || 0;
    });
    
    // Calculate planning rate: simple sum of presentiel + distance
    const planningRate = presentielData.map((p, i) => p + distancielData[i]);
    
    let datasets = [];
    
    if (viewType === 'line') {
        // Line view
        datasets.push({
            label: 'Avancement Présentiel (%)',
            data: presentielData,
            type: 'line',
            borderColor: 'rgba(16,185,129,1)',
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 2.5,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: 'rgba(16,185,129,1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            order: 2
        });
        datasets.push({
            label: 'Avancement Distanciel (%)',
            data: distancielData,
            type: 'line',
            borderColor: 'rgba(6,182,212,1)',
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 2.5,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: 'rgba(6,182,212,1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            order: 2
        });
    } else {
        // Bar view - BOTH bars for every group
        datasets.push({
            label: 'Avancement Présentiel (%)',
            data: presentielData,
            backgroundColor: 'rgba(16,185,129,0.85)',
            borderColor: 'rgba(16,185,129,1)',
            borderWidth: 1,
            borderRadius: 4,
            barPercentage: 0.7,
            categoryPercentage: 0.8,
            order: 2
        });
        datasets.push({
            label: 'Avancement Distanciel (%)',
            data: distancielData,
            backgroundColor: 'rgba(6,182,212,0.85)',
            borderColor: 'rgba(6,182,212,1)',
            borderWidth: 1,
            borderRadius: 4,
            barPercentage: 0.7,
            categoryPercentage: 0.8,
            order: 2
        });
    }
    
    // Add planning rate trend line in Mixed view
    if (viewType === 'mixed') {
        datasets.push({
            label: 'Taux de Planification (%)',
            data: planningRate,
            type: 'line',
            borderColor: 'rgba(139,92,246,1)',
            backgroundColor: 'rgba(139,92,246,0.05)',
            borderWidth: 2.5,
            borderDash: [8, 4],
            fill: false,
            tension: 0.42,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'rgba(139,92,246,1)',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 2,
            order: 1
        });
    }
    
    window._analyticsCharts[id] = new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 16,
                        font: { size: 11, weight: '600' },
                        color: '#374151'
                    }
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    titleFont: { size: 13, weight: '700' },
                    bodyFont: { size: 12 },
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: c => ` ${c.dataset.label}: ${c.parsed.y.toFixed(1)}%`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 10, weight: '500' },
                        color: '#6b7280',
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 120,
                    ticks: {
                        callback: v => v + '%',
                        font: { size: 10 },
                        color: '#6b7280'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.04)',
                        drawBorder: false
                    }
                }
            }
        }
    });
}

function buildModuleChart(modules,filter){
    const id='moduleChart';
    if(window._analyticsCharts[id]){window._analyticsCharts[id].destroy();delete window._analyticsCharts[id];}
    const ctx=document.getElementById(id);if(!ctx)return;
    const filtered=filter==='all'?modules.slice(0,8):modules.filter(m=>(m.categorie||'')===filter);
    const data=filtered.map(m=>m.advancement||0);
    window._analyticsCharts[id]=new Chart(ctx,{type:'bar',data:{labels:filtered.map(m=>m.code||m.codeModule||'N/A'),
    datasets:[{label:'Progression (%)',data,backgroundColor:data.map(v=>v>=80?'rgba(16,185,129,0.8)':v>=60?'rgba(59,130,246,0.8)':'rgba(251,146,60,0.8)'),borderRadius:5,borderSkipped:false}]},
    options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
    scales:{x:{beginAtZero:true,max:100,ticks:{callback:v=>v+'%',font:{size:10}},grid:{color:'rgba(0,0,0,0.04)'}},y:{grid:{display:false},ticks:{font:{size:11}}}}}});
}

function buildAbsenceChart(period, absences){
    const id='absenceChart';
    if(window._analyticsCharts[id]){window._analyticsCharts[id].destroy();delete window._analyticsCharts[id];}
    const ctx=document.getElementById(id);if(!ctx)return;
    const days=period==='week'?7:30; const now=new Date(); const labels=[]; const dayKeys=[];
    for(let i=days-1;i>=0;i--){const d=new Date(now);d.setDate(d.getDate()-i);labels.push(d.toLocaleDateString('fr-FR',days<=7?{day:'numeric',month:'short'}:{day:'numeric'}));dayKeys.push(`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`);}
    const counts = {};
    const namesByDay = {};
    (absences || []).forEach(a => {
        const dateStr = (a.date || a.dateAbsence || a.created_at || '').toString().slice(0, 10);
        if (!dateStr) return;
        counts[dateStr] = (counts[dateStr] || 0) + 1;
        const name = a.formateur
            ? `${a.formateur.nom || ''}${a.formateur.prenom ? ' ' + a.formateur.prenom : ''}`.trim()
            : (a.groupe ? (a.groupe.nomGroupe || a.groupe.name || 'Groupe') : '');
        if (name) {
            namesByDay[dateStr] = namesByDay[dateStr] || new Set();
            namesByDay[dateStr].add(name);
        }
    });
    const details = dayKeys.map(key => {
        const names = namesByDay[key] ? Array.from(namesByDay[key]) : [];
        return names.length ? names.join(', ') : 'Absence';
    });
    const data = dayKeys.map(key => counts[key] || 0);
    window._analyticsCharts[id]=new Chart(ctx,{type:'line',data:{labels,datasets:[{label:'Absences',data,
    borderColor:'rgba(239,68,68,1)',backgroundColor:'rgba(239,68,68,0.08)',tension:0.4,fill:true,pointRadius:4,pointBackgroundColor:'rgba(239,68,68,1)',pointBorderColor:'#fff',pointBorderWidth:2}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{title:function(context){return context[0]?.label||'';},label:function(context){const names=details[context.dataIndex]||'Absence';return `${names} — ${context.parsed.y||0} jour(s) absent(s)`;}}}},
    scales:{y:{beginAtZero:true,ticks:{stepSize:1,font:{size:10}},grid:{color:'rgba(0,0,0,0.05)'}},x:{grid:{display:false},ticks:{font:{size:days>10?9:10},maxRotation:days>10?45:0}}}}});
}

function buildWorkloadChart(formateurs){
    const id='workloadChart';
    if(window._analyticsCharts[id]){window._analyticsCharts[id].destroy();delete window._analyticsCharts[id];}
    const ctx=document.getElementById(id);if(!ctx)return;
    const trainers=formateurs;
    window._analyticsCharts[id]=new Chart(ctx,{type:'doughnut',data:{labels:trainers.map(f=>`${f.nom} ${f.prenom||''}`),
    datasets:[{data:trainers.map(f=>Math.max(1,(f.heures||0))),backgroundColor:['rgba(59,130,246,0.85)','rgba(16,185,129,0.85)','rgba(251,146,60,0.85)','rgba(167,139,250,0.85)','rgba(236,72,153,0.85)'],borderWidth:2,borderColor:'#fff'}]},
    options:{responsive:true,maintainAspectRatio:false,cutout:'62%',plugins:{tooltip:{callbacks:{title:function(context){const f=trainers[context[0].dataIndex];return `${f.nom} ${f.prenom||''}`;},label:function(context){const f=trainers[context.dataIndex];return[`Heures: ${f.heures||0}h`,`Progrès: ${f.avancement||0}%`];}}},legend:{position:'bottom',labels:{padding:10,font:{size:10},usePointStyle:true}}}}});
}

// MODIFIED: Remove modeFilter parameter
function buildAllCharts(data,filters){
    buildMainChart(data.groupes,filters.chartView);
    buildModuleChart(data.modules,filters.moduleFilter);
    buildAbsenceChart(filters.absencePeriod, data.absences);
    buildWorkloadChart(data.formateurs);
}

document.addEventListener('alpine:init',()=>{
    Alpine.data('analyticsPage',()=>({
        loading:false, loadingReports:false,
        chartView:'mixed', moduleFilter:'all', absencePeriod:'week',
        metrics:{totalFormateurs:0,totalGroupes:0,modulesActifs:0,totalModules:0,tauxAbsence:0},
        modules:[],groupes:[],formateurs:[],absences:[],
        recentReports:[],
        toast:{show:false,message:'',type:'success'},
        modal:{
            open:false,
            type:'',
            name:'',
            dateFrom:'',
            dateTo:'',
            loading:false,
            error:''
        },
        exportModal:{
            open:false,
            selectedSections:{
                avancement:true,
                absence:true,
                emploi:true,
                tous:false
            }
        },

        async init(){
            this.recentReports = this.loadStoredReports();
            await this.loadData();
            await this.loadReports();
            this.calculateMetrics();
            buildAllCharts(
                {groupes:this.groupes,modules:this.modules,formateurs:this.formateurs,absences:this.absences},
                {chartView:this.chartView,moduleFilter:this.moduleFilter,absencePeriod:this.absencePeriod}
            );
        },

        async loadData(){
            this.loading=true;
            try{
                const res = await fetch('/api/rapports/analytics');
                if (!res.ok) throw new Error('Failed to load analytics');

                const body = await res.json();
                this.modules = body.modules || [];
                this.groupes = body.groupes || body.groupStats || [];
                this.formateurs = body.workload || body.formateurs || [];

                this.absences = body.absences?.formateurs || [];
                console.log('Data loaded:', {modules: this.modules.length, groupes: this.groupes.length, formateurs: this.formateurs.length, absences: this.absences.length});
            }catch(e){
                console.error('Analytics load error:', e);
                this.modules=[];this.groupes=[];this.formateurs=[];this.absences=[];
            }
            this.loading=false;
        },

        async loadReports(){
            this.loadingReports=true;
            try{
                this.recentReports = this.loadStoredReports();
            }catch(e){
                console.error('Failed to load stored reports', e);
                this.recentReports = [];
            }
            this.loadingReports=false;
        },

        loadStoredReports(){
            try{
                const stored = localStorage.getItem('recentReports');
                return stored ? JSON.parse(stored) : [];
            }catch(e){
                console.error('Failed to parse stored reports', e);
                return [];
            }
        },

        saveStoredReports(){
            try{
                localStorage.setItem('recentReports', JSON.stringify(this.recentReports || []));
            }catch(e){
                console.error('Failed to save reports to storage', e);
            }
        },

        calculateMetrics(){
            this.metrics.totalFormateurs = this.formateurs.length;
            this.metrics.totalGroupes = this.groupes.length;
            this.metrics.totalModules = this.modules.length;
            this.metrics.modulesActifs = this.modules.filter(m => (m.advancement || m.modules_actifs || 0) > 0).length;
            const totalAbsences = this.absences.length || 0;
            const totalSessionsExpected = Math.max(this.groupes.length * 4 * 6, this.formateurs.length * 4 * 6, 1);
            this.metrics.tauxAbsence = totalSessionsExpected > 0 ? ((totalAbsences / totalSessionsExpected) * 100).toFixed(1) : 0;
            console.log('Metrics calculated:', this.metrics);
        },

        calculateAvgAdvancement(){
            if(!this.groupes.length) return 0;
            return(this.groupes.reduce((a,g)=>a+(g.advancement||0),0)/this.groupes.length).toFixed(1);
        },

        updateMainChart(){buildMainChart(this.groupes,this.chartView);},
        updateModuleChart(){buildModuleChart(this.modules,this.moduleFilter);},
        updateAbsenceChart(){buildAbsenceChart(this.absencePeriod,this.absences);},

        async refreshData(){
            await this.loadData(); await this.loadReports(); this.calculateMetrics();
            buildAllCharts({groupes:this.groupes,modules:this.modules,formateurs:this.formateurs,absences:this.absences},
                {chartView:this.chartView,moduleFilter:this.moduleFilter,absencePeriod:this.absencePeriod});
            this.showToast('Données actualisées','success');
        },

        openNewReportModal(){
            const today=todayLocalDateString();
            this.modal={open:true,type:'',name:'',dateFrom:today,dateTo:today,loading:false,error:''};
        },

        async submitNewReport(){
            if(!this.modal.type){
                this.modal.error='Veuillez choisir un type de rapport.';
                return;
            }
            this.modal.loading=true;
            this.modal.error='';
            await new Promise(r=>setTimeout(r,900));
            const name=this.modal.name.trim() ||
                `Rapport ${this.modal.type} — ${new Date().toLocaleDateString('fr-FR')}`;
            const newReport={
                id: Date.now(),
                name,
                type: this.modal.type,
                date_from: this.modal.dateFrom,
                date_to:   this.modal.dateTo,
                created_at: new Date().toISOString(),
                file_path: null,
                data: {
                    groupes: this.groupes,
                    modules: this.modules,
                    formateurs: this.formateurs,
                    absences: this.absences,
                    metrics: this.metrics
                }
            };
            this.recentReports.unshift(newReport);
            this.saveStoredReports();
            this.modal.loading=false;
            this.modal.open=false;
            this.showToast(`✓ Rapport "${name}" créé avec succès !`,'success');
        },

        collectChartImages(){
            const ids=['mainChart','moduleChart','absenceChart','workloadChart'];
            const chartImages={};
            ids.forEach(id=>{
                const canvas=document.getElementById(id);
                if(canvas){
                    try{
                        chartImages[id]=canvas.toDataURL('image/png',1.0);
                    }catch(e){
                        console.error('Canvas export error', id, e);
                    }
                }
            });
            return chartImages;
        },

        async requestPdfDownload(payload, fallbackFileName){
            const csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';
            const response=await fetch('/api/rapports/generate-pdf',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/pdf',
                    'X-CSRF-TOKEN':csrf
                },
                body:JSON.stringify(payload)
            });

            if(!response.ok){
                let msg='Erreur lors de la génération du PDF';
                try{
                    const body=await response.json();
                    msg=body?.message||msg;
                }catch(e){}
                throw new Error(msg);
            }

            const blob=await response.blob();
            const url=window.URL.createObjectURL(blob);
            const a=document.createElement('a');
            a.href=url;
            a.download=fallbackFileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },

        async downloadReport(report){
            this.showToast(`Génération du rapport "${report.name}"...`, 'success');
            try{
                // Recharge les datasets depuis l'API pour éviter tout décalage (progression modifiée après création de rapport)
                await this.loadData();

                const chartImages=this.collectChartImages();

                const normalizedType=(report.type||'').toLowerCase();
                const sections={
                    avancement:false,
                    absence:false,
                    emploi:false
                };

                if(normalizedType.includes('avancement')){
                    sections.avancement=true;
                } else if(normalizedType.includes('absences')){
                    sections.absence=true;
                } else if(normalizedType.includes('charge') || normalizedType.includes('travail')){
                    sections.emploi=true;
                } else if(normalizedType.includes('statist')){
                    sections.avancement=true;
                    sections.absence=true;
                    sections.emploi=true;
                } else {
                    sections.avancement=true;
                    sections.absence=true;
                    sections.emploi=true;
                }

                const pdfGroupes = this.groupes.map(g => {
                    const totalProgress = Number(g.avancement_presentiel || 0) + Number(g.avancement_distanciel || 0);
                    return {
                        ...g,
                        progress: totalProgress,
                        advancement: totalProgress,
                        avancement: totalProgress,
                    };
                });

                const payload={
                    reportId: report.id ?? null,
                    reportName: report.name || 'Rapport Analytique',
                    reportType: report.type || 'Complet',
                    dateFrom: report.date_from || report.dateFrom || null,
                    dateTo: report.date_to || report.dateTo || null,
                    sections,
                    metrics: this.metrics,
                    chartImages,
                    groupes: pdfGroupes,
                    modules: this.modules,
                    formateurs: this.formateurs,
                    absences: this.absences,
                    recentReports: this.recentReports
                };
                const cleanName=(report.name || 'rapport-analytique').replace(/[^a-z0-9]+/gi,'-').replace(/^-+|-+$/g,'').toLowerCase() || 'rapport-analytique';
                await this.requestPdfDownload(payload, `${cleanName}.pdf`);
                this.showToast('PDF téléchargé avec succès !','success');
            }catch(e){
                console.error(e);
                this.showToast(e.message || 'Erreur lors de la génération du PDF','error');
            }
        },

        async deleteReport(id){
            if(!confirm('Supprimer ce rapport définitivement ?')) return;
            try{
                const csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';
                const res=await fetch(`/api/rapports/${id}`,{
                    method:'DELETE',
                    headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}
                });
                if(!res.ok) throw new Error();
            }catch{}
            this.recentReports=this.recentReports.filter(r=>r.id!==id);
            this.saveStoredReports();
            this.showToast('Rapport supprimé','success');
        },

        async exportReport(){
            this.exportModal.open = true;
        },

        async performExport(){
            this.showToast('Génération du PDF...','success');
            try{
                const sections = {
                    avancement: this.exportModal.selectedSections.avancement || this.exportModal.selectedSections.tous,
                    absence: this.exportModal.selectedSections.absence || this.exportModal.selectedSections.tous,
                    emploi: this.exportModal.selectedSections.emploi || this.exportModal.selectedSections.tous
                };

                const chartImages=this.collectChartImages();
                const pdfGroupes = this.groupes.map(g => {
                    const totalProgress = Number(g.avancement_presentiel || 0) + Number(g.avancement_distanciel || 0);
                    return {
                        ...g,
                        progress: totalProgress,
                        advancement: totalProgress,
                        avancement: totalProgress,
                    };
                });

                const payload={
                    reportName:'Rapport Analytique',
                    reportType: this.exportModal.selectedSections.tous ? 'Complet' : 'Personnalisé',
                    dateFrom:null,
                    dateTo:null,
                    sections: sections,
                    metrics:this.metrics,
                    chartImages,
                    groupes: pdfGroupes,
                    modules:this.modules,
                    formateurs:this.formateurs,
                    absences:this.absences,
                    recentReports:this.recentReports
                };
                const fallbackFileName=`rapport-analytique-${todayLocalDateString()}.pdf`;
                await this.requestPdfDownload(payload, fallbackFileName);
                this.showToast('PDF téléchargé avec succès!','success');
                this.exportModal.open = false;
            }catch(e){
                console.error(e);
                this.showToast(e.message || 'Erreur lors de la génération du PDF','error');
            }
        },

        getReportBadgeStyle(type){
            const t = (type||'').toLowerCase();
            if(t.includes('emploi'))  return 'background:#dbeafe;color:#1e40af;';
            if(t.includes('absence')) return 'background:#fee2e2;color:#991b1b;';
            if(t.includes('charge'))  return 'background:#e0f2fe;color:#0c4a6e;';
            if(t.includes('statist')) return 'background:#f3e8ff;color:#6b21a8;';
            if(t.includes('avanc'))   return 'background:#dcfce7;color:#166534;';
            return 'background:#f3f4f6;color:#374151;';
        },
        formatDate(s){return s?new Date(s).toLocaleDateString('fr-FR'):'—';},
        showToast(message,type='success'){
            this.toast={show:true,message,type};
            setTimeout(()=>{this.toast.show=false;},3000);
        }
    }));
});

document.addEventListener('DOMContentLoaded', function () {
    // Default behavior: show all report sections (equivalent to 'all' filter value)
    document.querySelectorAll('.report-section').forEach(s => s.classList.remove('d-none'));
});
</script>

<style>
.d-none { display: none !important; }
/* Screen styles - keep button hint */
@media screen {
    button:has(svg path[d*="M12 10v6"]):hover::after {
        content: "Ctrl+P ou Cmd+P";
        position: absolute;
        bottom: -28px;
        right: 0;
        background: #1f2937;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        z-index: 50;
    }
}

@media print {
    /* Force visibility for everything */
    * {
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Hide only specific elements */
    nav, 
    aside, 
    footer,
    button,
    select,
    .no-print,
    [x-data] > div:first-child {
        display: none !important;
        visibility: hidden !important;
    }

    /* Page setup */
    @page {
        size: A4 landscape;
        margin: 1cm;
    }

    html, body {
        width: 100% !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        color: #000 !important;
        font-size: 10pt !important;
        overflow: visible !important;
    }

    /* Main container - FORCE DISPLAY */
    body > div,
    #app,
    [x-data="analyticsPage"] {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        page-break-inside: auto !important;
    }

    /* All sections visible */
    .w-full {
        display: block !important;
        width: 100% !important;
        padding: 0 8px !important;
        margin-bottom: 10px !important;
    }

    /* Header */
    h1 {
        display: block !important;
        font-size: 20pt !important;
        font-weight: bold !important;
        color: #1f2937 !important;
        margin: 0 0 4px 0 !important;
        padding: 0 0 6px 0 !important;
        border-bottom: 2px solid #3b82f6 !important;
        page-break-after: avoid !important;
    }

    h1 + p {
        display: none !important;
    }

    h2, h3 {
        display: block !important;
        font-weight: 600 !important;
        color: #374151 !important;
        margin: 8px 0 4px 0 !important;
        page-break-after: avoid !important;
    }

    h3 {
        font-size: 11pt !important;
    }

    /* KPI Cards Container */
    .w-full:has(div[style*="grid-template-columns: repeat(4"]) {
        display: block !important;
        page-break-inside: avoid !important;
        margin-bottom: 12px !important;
    }

    /* KPI Grid */
    div[style*="grid-template-columns: repeat(4"] {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 8px !important;
        width: 100% !important;
    }

    /* Individual KPI cards */
    .bg-white.rounded-xl,
    .bg-white {
        display: block !important;
        background: white !important;
        border: 1px solid #d1d5db !important;
        border-radius: 4px !important;
        padding: 8px !important;
        margin: 0 !important;
        page-break-inside: avoid !important;
        box-shadow: none !important;
    }

    /* Flex containers */
    .flex,
    div[style*="display:flex"] {
        display: flex !important;
        flex-wrap: wrap !important;
    }

    /* Text elements */
    p, span, div, label {
        color: #000 !important;
        line-height: 1.3 !important;
    }

    /* Text sizes */
    .text-gray-500,
    .text-gray-600,
    .text-gray-700 {
        color: #4b5563 !important;
    }

    .text-gray-900 {
        color: #111827 !important;
    }

    .text-blue-600 { color: #2563eb !important; }
    .text-green-600 { color: #16a34a !important; }
    .text-red-600 { color: #dc2626 !important; }
    .text-purple-600 { color: #9333ea !important; }

    /* Font weights */
    .font-bold {
        font-weight: 700 !important;
    }

    .font-semibold {
        font-weight: 600 !important;
    }

    /* Charts section */
    .w-full:has(canvas) {
        display: block !important;
        page-break-inside: avoid !important;
        margin-bottom: 12px !important;
    }

    .w-full:has(canvas) .bg-white {
        padding: 12px !important;
    }

    /* Canvas elements - hide in print */
    canvas {
        display: none !important;
        visibility: hidden !important;
    }

    /* Show chart images - FORCE DISPLAY */
    .print-chart-image,
    img.print-chart-image {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        max-height: 280px !important;
        page-break-inside: avoid !important;
        margin: 0 auto !important;
        opacity: 1 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Chart containers */
    div[style*="position:relative;height:400px"],
    div[style*="height:300px"],
    div[style*="height:280px"] {
        display: block !important;
        position: relative !important;
        width: 100% !important;
        height: 250px !important;
        max-height: 280px !important;
        page-break-inside: avoid !important;
    }

    /* Secondary charts grid */
    div[style*="grid-template-columns:1fr 1fr"] {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        page-break-inside: avoid !important;
    }

    /* Workload + Reports grid */
    div[style*="grid-template-columns:1fr 2fr"] {
        display: grid !important;
        grid-template-columns: 1fr 2fr !important;
        gap: 10px !important;
        page-break-before: auto !important;
        page-break-inside: avoid !important;
    }

    /* SVG icons */
    svg {
        display: inline-block !important;
        width: auto !important;
        height: auto !important;
        vertical-align: middle !important;
    }

    /* Gradients - convert to solid colors */
    div[style*="linear-gradient"] {
        background: #e5e7eb !important;
        border: 1px solid #9ca3af !important;
    }

    /* Reports list */
    .divide-y {
        display: block !important;
    }

    .divide-y > div {
        display: block !important;
        padding: 4px 6px !important;
        border-bottom: 1px solid #e5e7eb !important;
        page-break-inside: avoid !important;
    }

    /* Hide Alpine.js dynamic content indicators */
    [x-show],
    [x-transition] {
        display: block !important;
    }

    /* Margins and padding adjustments */
    .mb-6,
    .mb-4 {
        margin-bottom: 8px !important;
    }

    .px-4 {
        padding-left: 6px !important;
        padding-right: 6px !important;
    }

    .py-4,
    .p-6 {
        padding: 6px !important;
    }

    /* Remove all animations and transitions */
    *,
    *::before,
    *::after {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    /* Ensure backgrounds are white */
    .bg-gray-50,
    .bg-gray-100 {
        background: white !important;
    }

    /* Border colors */
    .border-gray-200,
    .border-gray-300 {
        border-color: #d1d5db !important;
    }

    /* Progress bars */
    div[style*="border-radius:99px"] {
        display: block !important;
        background: #e5e7eb !important;
        height: 4px !important;
        border-radius: 2px !important;
    }

    div[style*="border-radius:99px"] > div {
        display: block !important;
        height: 4px !important;
        border-radius: 2px !important;
    }

    /* Page footer */
    @page {
        @bottom-left {
            content: "eDT Pro - Rapport Analytique";
            font-size: 8pt;
            color: #6b7280;
        }
        @bottom-right {
            content: "Page " counter(page);
            font-size: 8pt;
            color: #6b7280;
        }
        @top-right {
            content: "Généré le " string(date);
            font-size: 8pt;
            color: #6b7280;
        }
    }

    /* Inline styles that might conflict */
    [style] {
        display: block !important;
    }

    /* Badges */
    .inline-flex {
        display: inline-flex !important;
    }

    span.inline-flex {
        padding: 2px 4px !important;
        font-size: 8pt !important;
    }

    /* Ensure all chart parents render */
    #mainChart,
    #moduleChart,
    #absenceChart,
    #workloadChart {
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
        max-height: 250px !important;
    }

    /* Gap utilities */
    .gap-2 { gap: 4px !important; }
    .gap-3 { gap: 6px !important; }
    .gap-4 { gap: 8px !important; }

    /* Rounded corners for print */
    .rounded-xl,
    .rounded-lg {
        border-radius: 4px !important;
    }

    /* Shadow removal */
    .shadow-sm,
    .shadow-md,
    .shadow-lg,
    .shadow-xl,
    .shadow-2xl {
        box-shadow: none !important;
    }

    /* Make sure overflow doesn't hide content */
    .overflow-hidden {
        overflow: visible !important;
    }

    /* Truncate text should show fully */
    .truncate {
        overflow: visible !important;
        text-overflow: clip !important;
        white-space: normal !important;
    }

    /* Ensure min-width doesn't break layout */
    .min-w-0 {
        min-width: auto !important;
    }

    /* Z-index reset */
    * {
        z-index: auto !important;
    }

    /* Fixed/absolute positioning - reset to static */
    .fixed,
    .absolute {
        position: static !important;
    }
}
</style>

@endsection
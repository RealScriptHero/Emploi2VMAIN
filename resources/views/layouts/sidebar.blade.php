<aside class="fixed left-0 top-0 bg-[#1e293b] flex flex-col shadow-lg z-40 overflow-hidden -translate-x-full transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    :style="`top: ${sidebarTop}px; height: ${sidebarHeight}; width: 17rem;`"
    x-cloak>
    {{-- Logo / Brand --}}
    
    {{-- User Profile --}}
    

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto sidebar-scroll" x-data="{
        emploiOpen: {{ request()->routeIs('emploi.*') ? 'true' : 'false' }},
        gestionOpen: {{ request()->routeIs('stage.*', 'absence.*', 'reports.*') ? 'true' : 'false' }},
        ressourcesOpen: {{ request()->routeIs('formateurs.*', 'groupes.*', 'salles.*', 'centres.*', 'modules.*') ? 'true' : 'false' }}
    }">
        <div class="px-3 py-2 mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('Navigation') }}</p>
        </div>

        {{-- Home --}}
        <a href="{{ route('home') }}" @click="sidebarOpen = false"
            class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium text-sm">{{ __('Home') }}</span>
        </a>

        {{-- Emploi du temps Dropdown --}}
        <div class="mb-1">
            <button @click="emploiOpen = !emploiOpen"
                class="w-full group flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('emploi.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium text-sm">{{ __('Timetable') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': emploiOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="emploiOpen" x-collapse class="ml-4 mt-1 space-y-1">
                <a href="{{ route('emploi.global') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('emploi.global') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Group timetable') }}</span>
                </a>
                <a href="{{ route('emploi.formateur') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('emploi.formateur') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Trainer timetable') }}</span>
                </a>
            </div>
        </div>

        {{-- Gestion Dropdown --}}
        <div class="mb-1">
            <button @click="gestionOpen = !gestionOpen"
                class="w-full group flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('stage.*', 'absence.*', 'reports.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-medium text-sm">{{ __('Management') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': gestionOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="gestionOpen" x-collapse class="ml-4 mt-1 space-y-1">
                <a href="{{ route('stage.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('stage.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Internships') }}</span>
                </a>
                <a href="{{ route('absence.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('absence.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Absences') }}</span>
                </a>
                <a href="{{ route('reports.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Reports') }}</span>
                </a>
            </div>
        </div>

        {{-- Ressources Dropdown --}}
        <div class="mb-1">
            <button @click="ressourcesOpen = !ressourcesOpen"
                class="w-full group flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('formateurs.*', 'groupes.*', 'salles.*', 'centres.*', 'modules.*', 'avancement.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium text-sm">{{ __('Resources') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': ressourcesOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="ressourcesOpen" x-collapse class="ml-4 mt-1 space-y-1">
                <a href="{{ route('formateurs.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('formateurs.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Trainers') }}</span>
                </a>
                <a href="{{ route('groupes.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('groupes.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Groups') }}</span>
                </a>
                <a href="{{ route('salles.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('salles.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Rooms') }}</span>
                </a>
                <a href="{{ route('centres.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('centres.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Centers') }}</span>
                </a>
                <a href="{{ route('modules.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('modules.*') ? 'bg-blue-500 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-white' }}">
                    <span class="text-sm">{{ __('Modules') }}</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Bottom Actions --}}
    <div class="px-3 py-4 border-t border-gray-700">
        <a href="{{ route('parametres.index') }}" @click="sidebarOpen = false"
            class="group flex items-center gap-3 px-3 py-2.5 mb-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parametres.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium text-sm">{{ __('Settings') }}</span>
        </a>
    </div>
</aside>

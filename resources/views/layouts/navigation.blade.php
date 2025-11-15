<nav x-data="{ open: false }" class="bg-white border-b border-[#0093DD]/20 shadow-sm py-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('admin.dashboard') }}">
                    {{-- PERBAIKAN: Logo dikecilkan agar pas di dalam navbar h-16 (64px) --}}
                    <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" class="block h-12 w-auto">
                </a>
            </div>

            <div class="flex flex-1 justify-end">
                <div class="hidden space-x-4 sm:-my-px sm:flex">
                    @php
                        // Logic styling ini sudah bagus
                        $baseClass = 'flex items-center gap-x-2 rounded-lg px-4 py-1 text-sm font-semibold transition-all duration-200';
                        $inactiveClass = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
                        $activeClass = 'bg-[#0093DD] text-white shadow-sm';
                    @endphp

                    {{-- Link Dashboard --}}
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                        class="{{ $baseClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                        <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Link Konten --}}
                    @can('view content')
                    <x-nav-link :href="route('admin.contents.index')" :active="request()->routeIs('admin.contents.*')"
                        class="{{ $baseClass }} {{ request()->routeIs('admin.contents.*') ? $activeClass : $inactiveClass }}">
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                        {{ __('Konten') }}
                    </x-nav-link>
                    @endcan

                    {{-- Menu Pengaturan: HANYA Super Admin --}}
                    @can('view settings')
                    <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')"
                        class="{{ $baseClass }} {{ request()->routeIs('admin.settings.index') ? $activeClass : $inactiveClass }}">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                        {{ __('Pengaturan') }}
                    </x-nav-link>
                    @endcan
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            {{-- Tombol trigger ini sudah bagus --}}
                            <button class="inline-flex items-center px-3 py-2 rounded-md text-[#0093DD] hover:text-[#EB891C] bg-white border border-[#0093DD]/30 font-semibold focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- PERBAIKAN: Gunakan warna netral untuk link biasa --}}
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:bg-gray-100">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                {{-- PERBAIKAN: Gunakan warna 'danger' (merah/oranye) untuk aksi logout --}}
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:bg-red-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#0093DD] hover:text-[#EB891C] hover:bg-[#0093DD]/10 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PERBAIKAN TOTAL: Menu mobile kini konsisten dengan desktop --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-[#0093DD]/10">
        <div class="pt-2 pb-3 space-y-1">
            @php
                // Kita gunakan logic yang sama untuk mobile
                $mobileBase = 'flex items-center gap-3 pl-3 pr-4 py-2 border-l-4 font-medium transition';
                $mobileInactive = 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300';
                $mobileActive = 'border-[#0093DD] bg-[#0093DD]/10 text-[#0093DD]';
            @endphp
            
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                class="{{ request()->routeIs('admin.dashboard') ? $mobileActive : $mobileInactive }}">
                <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('admin.contents.index')" :active="request()->routeIs('admin.contents.*')"
                class="{{ request()->routeIs('admin.contents.*') ? $mobileActive : $mobileInactive }}">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                {{ __('Konten') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')"
                class="{{ request()->routeIs('admin.settings') ? $mobileActive : $mobileInactive }}">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                {{ __('Pengaturan') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-[#0093DD]/10">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- PERBAIKAN: Warna disamakan dgn dropdown desktop --}}
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-700 hover:bg-gray-100">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 hover:bg-red-50">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
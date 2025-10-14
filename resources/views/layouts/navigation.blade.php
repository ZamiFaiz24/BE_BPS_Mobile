<nav x-data="{ open: false }" class="bg-white border-b border-[#0093DD]/20 shadow-sm py-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" class="block h-[76px] w-auto">
                </a>
            </div>

            <!-- Navbar Links & Dropdown -->
            <div class="flex flex-1 justify-end">
                <div class="hidden space-x-4 sm:-my-px sm:flex">
                    @php
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
                    <x-nav-link :href="route('admin.contents.index')" :active="request()->routeIs('admin.contents.*')"
                        class="{{ $baseClass }} {{ request()->routeIs('admin.contents.*') ? $activeClass : $inactiveClass }}">
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                        {{ __('Konten') }}
                    </x-nav-link>

                    {{-- Link Pengaturan --}}
                    <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')"
                        class="{{ $baseClass }} {{ request()->routeIs('admin.settings') ? $activeClass : $inactiveClass }}">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                        {{ __('Pengaturan') }}
                    </x-nav-link>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
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
                            <x-dropdown-link :href="route('profile.edit')" class="text-[#0093DD] hover:text-[#EB891C]">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-[#EB891C] hover:text-[#68B92E]">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
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

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-[#0093DD]/10">
        <div class="pt-2 pb-3 space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-[#0093DD] hover:text-[#EB891C] font-semibold transition flex items-center">
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.contents.index')" :active="request()->routeIs('admin.contents.*')" class="text-[#68B92E] hover:text-[#0093DD] font-semibold transition flex items-center">
                {{ __('Konten') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" class="text-[#0093DD] hover:text-[#EB891C] font-semibold transition flex items-center">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5 mr-2" />
                {{ __('Pengaturan') }}
            </x-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#0093DD]/10">
            <div class="px-4">
                <div class="font-medium text-base text-[#0093DD]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#EB891C]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-[#0093DD] hover:text-[#EB891C]">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-[#EB891C] hover:text-[#68B92E]">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

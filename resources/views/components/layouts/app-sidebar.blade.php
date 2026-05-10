<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#DCC7A1]">
        <div class="min-h-screen lg:flex">
            <flux:sidebar sticky collapsible="mobile" class="border-e border-stone-300 bg-[#6B5A43] lg:min-h-screen">
                <flux:sidebar.header>
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <flux:sidebar.collapse class="lg:hidden" />
                </flux:sidebar.header>

                <flux:sidebar.nav>
                    <flux:sidebar.group :heading="__('Platform')" class="grid">
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="document-text" href="#" wire:navigate>
                            {{ __('Daftar Pesanan') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="archive-box" href="{{ route('stok-harga') }}" wire:navigate>
                            {{ __('Stok & Harga') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="cog-6-tooth" href="#" wire:navigate>
                            {{ __('Pengaturan Warung') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="users" :href="route('kategori')" :current="request()->routeIs('kategori')" wire:navigate>
                            {{ __('Kategori Produk') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                </flux:sidebar.nav>

                <flux:spacer />

                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </flux:sidebar>

            <main class="flex-1 min-w-0">
                <!-- Mobile User Menu -->
                <flux:header class="lg:hidden">
                    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

                    <flux:spacer />

                    <flux:dropdown position="top" align="end">
                        <flux:profile
                            :initials="auth()->user()->initials()"
                            icon-trailing="chevron-down"
                        />

                        <flux:menu>
                            <flux:menu.radio.group>
                                <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                        <flux:avatar
                                            :name="auth()->user()->name"
                                            :initials="auth()->user()->initials()"
                                        />

                                        <div class="grid flex-1 text-start text-sm leading-tight">
                                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                    {{ __('Settings') }}
                                </flux:menu.item>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item
                                    as="button"
                                    type="submit"
                                    icon="arrow-right-start-on-rectangle"
                                    class="w-full cursor-pointer"
                                    data-test="logout-button"
                                >
                                    {{ __('Log out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </flux:header>

                <div class="p-6 lg:p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>

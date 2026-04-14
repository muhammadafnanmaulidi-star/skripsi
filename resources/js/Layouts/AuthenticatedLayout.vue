<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const isSidebarOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex h-screen bg-slate-50 font-sans overflow-hidden">
        <!-- SIDEBAR -->
        <aside 
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 md:w-72 lg:w-64 bg-white text-slate-600 flex flex-col h-screen font-sans border-r border-slate-200 shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <!-- HEADER SIDEBAR -->
            <div class="p-6 flex items-center justify-between gap-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-200">
                        U
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-none">User Panel</h1>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Utara Coffee</p>
                    </div>
                </div>
                <!-- MOBILE CLOSE BUTTON -->
                <button 
                    @click="isSidebarOpen = false"
                    class="lg:hidden p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all border border-slate-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- NAVIGATION -->
            <nav class="mt-6 flex-1 px-4 overflow-y-auto custom-scrollbar">
                <ul class="space-y-1.5">
                    <li>
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                            :class="route().current('dashboard')
                                ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-100'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="text-sm">Dashboard</span>
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('orders.create')"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                            :class="route().current('orders.create')
                                ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-100'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span class="text-sm">Pesan Menu</span>
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('orders.index')"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                            :class="route().current('orders.index')
                                ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-100'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="text-sm">Riwayat Pesanan</span>
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                            :class="route().current('profile.edit')
                                ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-100'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="text-sm">Profil Saya</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- LOGOUT -->
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                <button
                    @click="logout"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300 text-sm font-black border border-slate-200 shadow-sm hover:shadow-lg hover:shadow-rose-100 active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    KELUAR
                </button>
            </div>
        </aside>

        <!-- CONTENT AREA -->
        <main class="flex-1 min-w-0 overflow-y-auto text-slate-800 flex flex-col bg-slate-50">
            <!-- TOP BAR -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <!-- HAMBURGER -->
                    <button 
                        @click="isSidebarOpen = true"
                        class="lg:hidden p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-90"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>

                    <div class="overflow-hidden">
                        <slot name="header">
                            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight truncate">Dashboard User</h1>
                        </slot>
                    </div>
                </div>

                <div class="flex items-center gap-3 md:gap-4 shrink-0">
                    <div class="text-right hidden sm:block" v-if="user">
                        <p class="text-sm font-black text-slate-800 leading-none">{{ user?.name }}</p>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-tighter mt-1">Customer Member</p>
                    </div>
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-black border-2 border-white shadow-lg shadow-emerald-100 ring-1 ring-emerald-50" v-if="user">
                        {{ user?.name?.charAt(0).toUpperCase() }}
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <div class="p-4 md:p-8 flex-1 max-w-[1600px] w-full mx-auto">
                <!-- Flash Messages -->
                <div v-if="page.props.flash.success" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[2rem] flex items-center gap-4 animate-bounce-short shadow-xl shadow-emerald-50">
                    <div class="p-2 bg-emerald-500 text-white rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-sm font-black uppercase tracking-tight">{{ page.props.flash.success }}</span>
                </div>

                <div v-if="page.props.flash.error" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center gap-4 animate-shake shadow-xl shadow-rose-50">
                    <div class="p-2 bg-rose-500 text-white rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-black uppercase tracking-tight">{{ page.props.flash.error }}</span>
                        <p class="text-[10px] opacity-70 mt-0.5 font-bold italic" v-if="page.props.flash.error.includes('Unauthorized')">
                            Tip: Periksa konfigurasi API Midtrans Anda.
                        </p>
                    </div>
                </div>

                <slot />
            </div>
        </main>

        <!-- OVERLAY MOBILE -->
        <div 
            v-if="isSidebarOpen" 
            @click="isSidebarOpen = false"
            class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-40 transition-all duration-300"
        ></div>
    </div>
</template>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e2e8f0; border-radius: 20px; }

@keyframes bounce-short {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
}
.animate-bounce-short { animation: bounce-short 1s ease-in-out infinite; }

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}
.animate-shake { animation: shake 0.2s ease-in-out infinite; }
</style>

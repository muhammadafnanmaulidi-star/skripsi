<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
  foodsCount: { type: Number, default: 0 },
  drinksCount: { type: Number, default: 0 },
  totalSubcategories: { type: Number, default: 0 }
})
</script>

<template>
  <Head title="Dashboard" />
  
  <AuthenticatedLayout>
    <template #header>
        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Dashboard User</h1>
        <p class="text-xs md:text-sm text-slate-500">Overview aktivitas Anda</p>
    </template>

    <div class="max-w-7xl mx-auto pb-12">
        <!-- WELCOME CARD -->
        <div class="bg-emerald-600 rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 text-white shadow-2xl shadow-emerald-200 mb-8 md:mb-12 relative overflow-hidden group">
          <div class="relative z-10">
            <span class="inline-block px-4 py-1.5 bg-emerald-500/50 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4 border border-emerald-400/30">Welcome Back</span>
            <h2 class="text-3xl md:text-5xl font-black mb-4 leading-tight">Selamat Datang,<br class="sm:hidden" /> <span class="text-emerald-200">{{ user?.name?.split(' ')[0] }}!</span> 👋</h2>
            <p class="text-emerald-50 text-sm md:text-xl max-w-xl font-medium leading-relaxed opacity-90">
              Mau pesan kopi atau camilan apa hari ini? Nikmati layanan terbaik dari Utara Coffee dengan menu pilihan spesial.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <Link :href="route('orders.create')" class="bg-white text-emerald-700 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-xl shadow-emerald-900/20 active:scale-95">
                    Mulai Pesan
                </Link>
            </div>
          </div>
          <!-- Decorative circle -->
          <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
          <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-emerald-400 opacity-20 rounded-full blur-3xl"></div>
        </div>

        <!-- STATS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-12">
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 transition-all hover:shadow-xl hover:shadow-slate-100 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center transition-all group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs text-slate-400 font-black uppercase tracking-widest mb-1">Menu Makanan</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ foodsCount }} <span class="text-xs text-slate-300 ml-1">Item</span></h3>
                </div>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 transition-all hover:shadow-xl hover:shadow-slate-100 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center transition-all group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs text-slate-400 font-black uppercase tracking-widest mb-1">Menu Minuman</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ drinksCount }} <span class="text-xs text-slate-300 ml-1">Item</span></h3>
                </div>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 transition-all hover:shadow-xl hover:shadow-slate-100 group sm:col-span-2 lg:col-span-1">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center transition-all group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs text-slate-400 font-black uppercase tracking-widest mb-1">Total Kategori</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ totalSubcategories }} <span class="text-xs text-slate-300 ml-1">Jenis</span></h3>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <h3 class="font-black text-slate-800 mb-6 text-xl md:text-2xl tracking-tight hidden sm:block">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Link :href="route('orders.create')" class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-100 transition-all group overflow-hidden relative active:scale-[0.98]">
                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="p-5 bg-emerald-50 text-emerald-600 rounded-3xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner group-hover:shadow-emerald-200">
                        <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-800 group-hover:text-emerald-700 transition-colors">Buat Pesanan Baru</h4>
                        <p class="text-slate-400 font-medium mt-2 leading-relaxed">Lihat menu terbaru kami dan mulai memesan racikan spesial hari ini.</p>
                    </div>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700 blur-2xl"></div>
            </Link>

            <Link :href="route('orders.index')" class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-100 transition-all group overflow-hidden relative active:scale-[0.98]">
                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="p-5 bg-blue-50 text-blue-600 rounded-3xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner group-hover:shadow-blue-200">
                        <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-800 group-hover:text-blue-700 transition-colors">Riwayat Pesanan</h4>
                        <p class="text-slate-400 font-medium mt-2 leading-relaxed">Pantau status pesanan terakhirmu atau lihat kembali favorit lamamu.</p>
                    </div>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700 blur-2xl"></div>
            </Link>
        </div>
    </div>
  </AuthenticatedLayout>
</template>

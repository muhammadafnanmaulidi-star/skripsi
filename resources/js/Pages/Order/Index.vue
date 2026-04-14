<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    orders: Object
});

const page = usePage();

const getStatusClass = (status) => {
    switch (status) {
        case 'proses': return 'bg-amber-50 text-amber-600 border-amber-100';
        case 'success': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'cancel': return 'bg-rose-50 text-rose-600 border-rose-100';
        default: return 'bg-slate-50 text-slate-600 border-slate-100';
    }
};

const loadSnapScript = () => {
    return new Promise((resolve) => {
        if (window.snap) {
            resolve(true);
            return;
        }
        const script = document.createElement('script');
        const isProduction = page.props.midtrans_is_production;
        script.src = isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        script.setAttribute('data-client-key', page.props.midtrans_client_key);
        script.onload = () => resolve(true);
        script.onerror = () => resolve(false);
        document.head.appendChild(script);
    });
};

const payWithSnap = async (token, retries = 10) => {
    if (!window.snap) {
        console.log(`Waiting for Midtrans Snap script... (${retries} retries left)`);
        const loaded = await loadSnapScript();
        if (!loaded && retries > 0) {
            setTimeout(() => payWithSnap(token, retries - 1), 1000);
            return;
        }
    }

    if (window.snap) {
        window.snap.pay(token, {
            onSuccess: (result) => { 
                window.location.reload(); 
            },
            onPending: (result) => { 
                console.log('pending', result); 
            },
            onError: (result) => { 
                console.log('error', result); 
                alert('Gagal memproses pembayaran dengan Midtrans. Silakan coba lagi atau hubungi admin.');
            },
            onClose: () => { 
                console.log('customer closed the popup');
            }
        });
    } else {
        console.error('Midtrans Snap script failed to load after retries.');
    }
};

onMounted(async () => {
    if (page.props.flash.snap_token) {
        await payWithSnap(page.props.flash.snap_token);
    } 
});
</script>

<template>
    <Head title="Riwayat Pesanan" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Riwayat Pesanan Saya</h1>
            <p class="text-xs md:text-sm text-slate-500">Pantau status pesanan dan rincian pembayaran Anda</p>
        </template>

        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden min-h-[400px]">
                <!-- Empty State -->
                <div v-if="orders.data.length === 0" class="text-center py-24 px-6 flex flex-col items-center gap-6">
                    <div class="w-32 h-32 bg-slate-50 rounded-[2.5rem] flex items-center justify-center text-slate-200 border border-slate-100 rotate-3 shadow-inner">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-slate-800 font-black text-2xl tracking-tight">Belum Ada Pesanan</p>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2 max-w-xs mx-auto leading-relaxed">Nikmati kopi terbaik kami hari ini dan mulailah pengalaman rasa yang tak terlupakan.</p>
                    </div>
                    <Link :href="route('orders.create')" class="mt-4 bg-emerald-600 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 active:scale-95 text-xs">
                        Pesan Menu Sekarang
                    </Link>
                </div>
                
                <div v-else>
                    <!-- Mobile List (Show on extra small and small) -->
                    <div class="md:hidden divide-y divide-slate-100">
                        <div v-for="order in orders.data" :key="order.id" class="p-6 bg-white hover:bg-slate-50 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex flex-col">
                                    <span class="font-mono font-black text-emerald-600">#{{ order.id }}</span>
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ new Date(order.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) }}</span>
                                </div>
                                <span :class="['px-3 py-1 rounded-full text-[9px] font-black border uppercase tracking-widest shadow-sm', getStatusClass(order.status)]">
                                    {{ order.status }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                                <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between gap-4">
                                    <span class="text-xs font-bold text-slate-600 truncate capitalize">{{ item.menu?.name || 'Menu dihapus' }}</span>
                                    <span class="text-[10px] font-black text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-100">x{{ item.quantity }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ order.payment_method }} • Meja {{ order.table_number || '-' }}</span>
                                    <span class="text-lg font-black text-slate-800 mt-0.5">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</span>
                                </div>
                                
                                <button 
                                     v-if="order.status === 'proses' && order.payment_method !== 'cash' && order.snap_token"
                                     @click="payWithSnap(order.snap_token)"
                                     class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 active:scale-95 transition-all"
                                 >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Bayar
                                </button>
                            </div>

                            <!-- Mobile Reason Pembatalan -->
                            <div v-if="order.status === 'cancel' && order.cancel_reason" class="mt-4 p-3 bg-rose-50 border border-rose-100 rounded-xl">
                                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1">Alasan Pembatalan</p>
                                <p class="text-xs text-rose-600 font-bold leading-relaxed">{{ order.cancel_reason }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Table (Show on medium and above) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400">
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">ID Order</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Detail Pesanan</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Total Tagihan</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50/50 transition-all group">
                                    <td class="px-8 py-8 items-start">
                                        <div class="flex flex-col">
                                            <span class="font-mono font-black text-slate-800 group-hover:text-emerald-600 transition-colors">#{{ order.id }}</span>
                                            <span class="text-[10px] text-slate-400 mt-2 font-black uppercase tracking-widest">{{ new Date(order.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <div class="space-y-2">
                                            <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between gap-8 max-w-xs">
                                                <span class="text-sm font-black text-slate-700 truncate capitalize">{{ item.menu?.name || 'Menu dihapus' }}</span>
                                                <span class="text-[10px] font-black text-slate-400 shrink-0 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">x{{ item.quantity }}</span>
                                            </div>
                                            <div class="pt-3 mt-3 border-t border-slate-50 flex items-center gap-3">
                                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">{{ order.payment_method }}</span>
                                                <span class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.2em] bg-indigo-50/50 px-2.5 py-1 rounded-lg">Meja {{ order.table_number || '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <span class="text-xl font-black text-slate-800 tracking-tighter">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</span>
                                    </td>
                                    <td class="px-8 py-8">
                                        <div class="flex flex-col gap-3">
                                            <div class="flex items-center gap-3">
                                                <span :class="['px-5 py-2 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-sm', getStatusClass(order.status)]">
                                                    {{ order.status }}
                                                </span>
                                                <button 
                                                     v-if="order.status === 'proses' && order.payment_method !== 'cash' && order.snap_token"
                                                     @click="payWithSnap(order.snap_token)"
                                                     class="p-2.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all shadow-sm hover:shadow-lg hover:shadow-emerald-100"
                                                     title="Bayar Sekarang"
                                                 >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                </button>
                                            </div>
                                            <!-- Desktop Reason Pembatalan -->
                                            <div v-if="order.status === 'cancel' && order.cancel_reason" class="mt-1 px-4 py-3 bg-rose-50 border border-rose-100 rounded-2xl max-w-xs">
                                                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1.5">Alasan Pembatalan</p>
                                                <p class="text-xs text-rose-600 font-bold leading-relaxed">{{ order.cancel_reason }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.vertical-top { vertical-align: top; }
</style>

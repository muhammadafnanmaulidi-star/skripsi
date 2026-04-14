<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const isSidebarOpen = ref(false);

const props = defineProps({
    orders: Object
});

// Modal cancel reason
const showCancelModal = ref(false);
const cancelReason = ref('');
const pendingCancel = ref({ orderId: null });

const onStatusChange = (orderId, newStatus) => {
    if (newStatus === 'cancel') {
        // Tampilkan modal untuk isi alasan
        pendingCancel.value = { orderId };
        cancelReason.value = '';
        showCancelModal.value = true;
    } else {
        router.put(`/admin/orders/${orderId}/status`, { status: newStatus });
    }
};

const confirmCancel = () => {
    router.put(`/admin/orders/${pendingCancel.value.orderId}/status`, {
        status: 'cancel',
        cancel_reason: cancelReason.value
    }, {
        onSuccess: () => {
            showCancelModal.value = false;
            cancelReason.value = '';
        }
    });
};

const closeCancelModal = () => {
    showCancelModal.value = false;
    cancelReason.value = '';
};

const deleteOrder = (orderId) => {
    if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
        router.delete(`/admin/orders/${orderId}`);
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'proses': return 'bg-blue-100 text-blue-800';
        case 'success': return 'bg-emerald-100 text-emerald-800';
        case 'cancel': return 'bg-rose-100 text-rose-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Kelola Pesanan" />
    <div class="flex h-screen bg-gray-100 font-sans overflow-hidden">
        <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

        <main class="flex-1 min-w-0 overflow-y-auto">
            <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center mb-6 md:mb-8">
                <div class="flex items-center gap-4">
                    <button @click="isSidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                    <h1 class="text-xl md:text-3xl font-bold text-gray-800">Kelola Pesanan</h1>
                </div>

                <!-- LOGOUT MOBILE -->
                <button 
                  @click="router.post('/logout')"
                  class="lg:hidden flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[10px] font-black uppercase hover:bg-rose-100 transition-colors shadow-sm"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                  Out
                </button>
            </header>

            <div class="px-4 md:px-8 pb-8">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-gray-700">ID & Tanggal</th>
                                    <th class="px-6 py-4 font-bold text-gray-700">Pelanggan</th>
                                    <th class="px-6 py-4 font-bold text-gray-700">Detail Pesanan</th>
                                    <th class="px-6 py-4 font-bold text-gray-700">Pembayaran</th>
                                    <th class="px-6 py-4 font-bold text-gray-700">Status</th>
                                    <th class="px-6 py-4 font-bold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold">#{{ order.id }}</div>
                                        <div class="text-xs text-gray-500">{{ new Date(order.created_at).toLocaleString('id-ID') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold">{{ order.user?.name }}</div>
                                        <div class="text-xs text-gray-500">{{ order.user?.email }}</div>
                                        <div class="text-xs text-gray-500">{{ order.user?.phone_number }}</div>
                                        <div class="mt-2 text-xs font-bold px-2 py-1 inline-block bg-indigo-50 text-indigo-700 rounded-md border border-indigo-100">
                                            Meja: {{ order.table_number || '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <ul class="text-sm">
                                            <li v-for="item in order.items" :key="item.id">
                                                {{ item.menu?.name }} ({{ item.quantity }}x)
                                            </li>
                                        </ul>
                                        <div class="mt-1 font-bold text-emerald-600">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold uppercase border" :class="order.payment_method === 'cash' ? 'bg-gray-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200'">
                                            {{ order.payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span 
                                            class="text-xs rounded-full px-3 py-1 font-bold border-0 shadow-sm"
                                            :class="getStatusClass(order.status)"
                                        >
                                            <template v-if="order.status === 'proses'">Menunggu Pembayaran</template>
                                            <template v-else-if="order.status === 'success'">Lunas (Sukses)</template>
                                            <template v-else-if="order.status === 'cancel'">Dibatalkan</template>
                                            <template v-else>{{ order.status }}</template>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button @click="deleteOrder(order.id)" class="text-red-500 hover:text-red-700 transition p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- MODAL ALASAN PEMBATALAN -->
        <div v-if="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-gray-800">Batalkan Pesanan</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi alasan pembatalan agar pelanggan mengetahui penyebabnya.</p>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pembatalan</label>
                    <textarea
                        v-model="cancelReason"
                        rows="3"
                        placeholder="Contoh: Stok menu habis, mohon pesan menu lain..."
                        class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-rose-300 focus:border-rose-400 resize-none"
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1">Opsional — jika dikosongkan, tidak ada keterangan yang ditampilkan.</p>
                </div>
                <div class="p-6 pt-0 flex gap-3">
                    <button
                        @click="confirmCancel"
                        class="flex-1 bg-rose-600 text-white py-3 rounded-xl font-bold hover:bg-rose-700 transition"
                    >
                        Ya, Batalkan Pesanan
                    </button>
                    <button
                        @click="closeCancelModal"
                        class="px-6 bg-gray-100 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-200 transition"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

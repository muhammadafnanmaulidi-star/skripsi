<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    menus: Array,
    isMember: Boolean
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const form = useForm({
    items: [],
    payment_method: 'cash',
    table_number: '',
    total_price: 0
});

const cart = ref([]);
const selectedCategory = ref('all');
const isCartOpenMobile = ref(false);

// Modifikasi 1: Urutkan menu agar yang diskon berada di atas
const filteredMenus = computed(() => {
    let list = props.menus;
    if (selectedCategory.value !== 'all') {
        list = list.filter(menu => menu.category_id === Number(selectedCategory.value));
    }
    // Urutkan berdasarkan diskon persen (yang lebih besar di atas)
    return [...list].sort((a, b) => (b.discount_percent || 0) - (a.discount_percent || 0));
});

const discountedMenus = computed(() => filteredMenus.value.filter(m => m.discount_percent > 0));

const addToCart = (menu) => {
    if (!menu.is_available) return;
    const finalPrice = menu.discount_percent > 0 ? menu.discounted_price : menu.price;
    const item = cart.value.find(i => i.id === menu.id);
    if (item) {
        item.quantity++;
    } else {
        cart.value.push({
            id: menu.id,
            name: menu.name,
            price: Number(finalPrice),
            original_price: Number(menu.price),
            discount_percent: menu.discount_percent || 0,
            quantity: 1
        });
    }
};

const removeFromCart = (index) => cart.value.splice(index, 1);

const updateQuantity = (id, delta) => {
    const item = cart.value.find(i => i.id === id);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) cart.value = cart.value.filter(i => i.id !== id);
    }
};

const totalItems = computed(() => cart.value.reduce((total, item) => total + item.quantity, 0));
const totalPrice = computed(() => cart.value.reduce((total, item) => total + (item.price * item.quantity), 0));

const submit = () => {
    if (cart.value.length === 0) return;
    if (!form.table_number) {
        alert('Silakan pilih nomor meja Anda terlebih dahulu.');
        return;
    }
    form.items = cart.value;
    form.total_price = totalPrice.value;
    form.post(route('orders.store'));
};
</script>

<template>
    <Head title="Pesan Menu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Pesan Menu</h1>
                    <p class="text-[10px] md:text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Pilih menu favoritmu</p>
                </div>
                <!-- Status Member -->
                <div v-if="isMember" class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-2xl w-fit">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Member Aktif - Diskon Tersedia</span>
                </div>
                <div v-else class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-100 rounded-2xl w-fit">
                    <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                    <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Guest - Daftar Member untuk Diskon</span>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 max-w-[1600px] mx-auto pb-24 lg:pb-12">
            <!-- Menu Grid -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Category Filter Tabs - Scrollable on mobile -->
                <div class="flex overflow-x-auto pb-2 pt-1 no-scrollbar gap-2 px-1">
                    <button @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-500 hover:text-slate-800 border-slate-200'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all uppercase tracking-wider whitespace-nowrap border shrink-0">Semua</button>
                    <button @click="selectedCategory = 1" :class="selectedCategory === 1 ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-500 hover:text-slate-800 border-slate-200'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all uppercase tracking-wider whitespace-nowrap border shrink-0">Makanan</button>
                    <button @click="selectedCategory = 2" :class="selectedCategory === 2 ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-500 hover:text-slate-800 border-slate-200'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all uppercase tracking-wider whitespace-nowrap border shrink-0">Minuman</button>
                </div>

                <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-sm border border-slate-200 p-4 md:p-8 min-h-[600px]">
                    <div class="flex justify-between items-center mb-8 md:mb-12">
                        <div>
                            <h3 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-3">
                                <span class="p-2 bg-emerald-50 rounded-xl hidden sm:inline-flex">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </span>
                                {{ selectedCategory === 'all' ? 'Katalog Utama' : (selectedCategory === 1 ? 'Menu Makanan' : 'Menu Minuman') }}
                            </h3>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-1 font-bold italic">Menampilkan {{ filteredMenus.length }} hidangan istimewa</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-8">
                        <div
                            v-for="menu in filteredMenus"
                            :key="menu.id"
                            :class="[
                                'group rounded-[2rem] border transition-all duration-500 overflow-hidden flex flex-col',
                                menu.stock > 0
                                    ? 'bg-white border-slate-100 hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-100/50'
                                    : 'bg-slate-50 border-slate-200 opacity-70 cursor-not-allowed'
                            ]"
                        >
                            <!-- Image Header -->
                            <div class="relative aspect-video sm:aspect-[4/3] overflow-hidden bg-slate-50">
                                <img 
                                    :src="menu.image_url || 'https://placehold.co/600x400/f8fafc/cbd5e1?text=' + menu.name" 
                                    :class="[
                                        'w-full h-full object-cover transition-transform duration-700',
                                        menu.stock > 0 ? 'group-hover:scale-110' : 'grayscale scale-105'
                                    ]"
                                    :style="{ 
                                        objectPosition: menu.image_position || 'center',
                                        transform: menu.stock > 0 ? `scale(${menu.image_zoom || 1})` : undefined
                                    }"
                                    alt="Menu Image"
                                />

                                <!-- Badge Diskon -->
                                <div v-if="menu.stock > 0 && menu.discount_percent > 0" class="absolute top-4 left-4">
                                    <span class="bg-rose-500 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg shadow-rose-500/40 uppercase tracking-tighter">
                                        🔥 {{ menu.discount_percent }}% OFF
                                    </span>
                                </div>

                                <!-- Overlay & Badge HABIS / STOK -->
                                <div v-if="menu.stock <= 0" class="absolute inset-0 bg-slate-900/40 flex items-center justify-center">
                                    <div class="bg-white/90 backdrop-blur-sm px-5 py-2.5 rounded-2xl shadow-xl border border-slate-200">
                                        <p class="text-slate-700 font-black text-[10px] tracking-widest uppercase flex items-center gap-2">
                                            😔 Habis
                                        </p>
                                    </div>
                                </div>
                                <div v-else-if="menu.stock <= 10" class="absolute top-4 right-4 group-hover:scale-110 transition-transform duration-500">
                                    <span class="bg-amber-100/90 backdrop-blur-md text-amber-700 text-[9px] font-black px-3 py-1.5 rounded-full border border-amber-200 shadow-sm uppercase tracking-tighter">
                                        ⏳ Stok: {{ menu.stock }}
                                    </span>
                                </div>

                                <!-- Tombol tambah deskop -->
                                <button 
                                    v-if="menu.stock > 0"
                                    @click="addToCart(menu)" 
                                    class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-emerald-600 text-white hidden md:flex items-center justify-center hover:bg-emerald-700 active:scale-90 transition-all shadow-xl shadow-emerald-200 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                            </div>

                            <!-- Content -->
                            <div class="p-5 md:p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <h4 :class="['font-black text-base md:text-lg leading-tight transition-colors', menu.stock > 0 ? 'text-slate-800 group-hover:text-emerald-700' : 'text-slate-400']">{{ menu.name }}</h4>
                                    <p class="text-[10px] md:text-xs text-slate-400 mt-1.5 line-clamp-2 leading-relaxed font-medium" v-if="menu.description">{{ menu.description }}</p>
                                </div>
                                <div class="mt-auto flex justify-between items-center">
                                    <div v-if="menu.stock > 0 && menu.discount_percent > 0">
                                        <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-2">
                                            <span class="text-lg md:text-xl font-black text-emerald-600">Rp {{ Number(menu.discounted_price).toLocaleString('id-ID') }}</span>
                                            <span class="text-[10px] font-bold text-slate-300 line-through">Rp {{ Number(menu.price).toLocaleString('id-ID') }}</span>
                                        </div>
                                    </div>
                                    <span v-else-if="menu.stock > 0" class="text-lg md:text-xl font-black text-emerald-600">Rp {{ Number(menu.price).toLocaleString('id-ID') }}</span>
                                    <span v-else class="text-sm font-bold text-slate-400 line-through">Rp {{ Number(menu.price).toLocaleString('id-ID') }}</span>
                                    
                                    <!-- Tombol tambah mobile -->
                                    <button 
                                        v-if="menu.stock > 0"
                                        @click="addToCart(menu)" 
                                        class="md:hidden w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center active:bg-emerald-600 active:text-white transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Section (Cart) -->
            <div 
                class="xl:col-span-1 fixed inset-x-0 bottom-0 z-[60] lg:static lg:z-auto transition-transform duration-500 transform"
                :class="[isCartOpenMobile ? 'translate-y-0' : 'translate-y-full lg:translate-y-0']"
            >
                <!-- Desktop mt centered, mobile full-screen relative -->
                <div class="bg-white rounded-t-[2.5rem] lg:rounded-[2.5rem] shadow-2xl lg:shadow-sm p-6 md:p-8 lg:sticky lg:top-24 lg:mt-16 text-slate-800 border-t border-x lg:border border-slate-200 h-[85vh] lg:h-auto lg:max-h-[calc(100vh-8rem)] flex flex-col overflow-hidden">
                    
                    <!-- Header Cart Mobile -->
                    <div class="flex items-center justify-between mb-6 lg:hidden shrink-0">
                        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto absolute top-3 left-1/2 -translate-x-1/2"></div>
                        <h3 class="text-lg font-black mt-2">Detail Pesanan</h3>
                        <button @click="isCartOpenMobile = false" class="p-2 bg-slate-100 rounded-full text-slate-400 mt-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <h3 class="text-xl font-black mb-6 hidden lg:flex items-center gap-3 shrink-0">
                        <span class="p-2 bg-emerald-50 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </span>
                        Keranjang Anda
                    </h3>

                    <div v-if="cart.length === 0" class="flex-1 flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                             <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-slate-400 font-bold italic text-sm tracking-wide">Belum ada menu yang dipilih</p>
                    </div>

                    <div v-else class="flex-1 overflow-hidden flex flex-col">
                        <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar mb-6">
                            <div v-for="(item, index) in cart" :key="index" class="flex justify-between items-center p-3 sm:p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-emerald-100 transition-all">
                                <div class="flex-1 min-w-0 mr-4">
                                    <p class="text-xs sm:text-sm font-black text-slate-800 leading-tight truncate mb-1">{{ item.name }}</p>
                                    <p class="text-[10px] text-emerald-600 font-black tracking-widest uppercase">Rp {{ item.price.toLocaleString('id-ID') }}</p>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                                    <div class="flex items-center bg-white rounded-xl border border-slate-200 p-0.5 sm:p-1 shadow-sm">
                                        <button @click="updateQuantity(item.id, -1)" class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center text-slate-400 hover:text-emerald-600 transition-colors font-black text-sm">-</button>
                                        <span class="px-2 sm:px-3 text-xs font-black text-slate-800">{{ item.quantity }}</span>
                                        <button @click="updateQuantity(item.id, 1)" class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center text-emerald-600 hover:text-emerald-500 transition-colors font-black text-sm">+</button>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-rose-300 hover:text-rose-600 transition-colors p-1">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 shrink-0">
                            <div class="flex justify-between items-end mb-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">Total Bayar</span>
                                    <span class="text-[9px] text-emerald-600 font-bold uppercase tracking-tighter">Incl. tax 10%</span>
                                </div>
                                <span class="text-2xl md:text-3xl font-black text-slate-800 tracking-tighter">Rp {{ totalPrice.toLocaleString('id-ID') }}</span>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Nomor Meja</p>
                                        <select v-model="form.table_number" class="w-full bg-slate-50 border-slate-200 text-slate-700 rounded-xl py-3 px-4 text-xs font-black focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                                            <option value="" disabled selected>Pilih Meja</option>
                                            <option v-for="n in 25" :key="n" :value="n">Meja {{ n }}</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Pembayaran</p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="form.payment_method = 'cash'" :class="form.payment_method === 'cash' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-50' : 'bg-slate-50 text-slate-400'" class="flex-1 py-3 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all border border-transparent">
                                                Tunai
                                            </button>
                                            <button type="button" @click="form.payment_method = 'qris'" :class="form.payment_method === 'qris' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-50' : 'bg-slate-50 text-slate-400'" class="flex-1 py-3 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all border border-transparent">
                                                Digital
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button @click="submit" :disabled="form.processing || cart.length === 0" class="w-full bg-emerald-600 text-white py-4 md:py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 disabled:bg-slate-100 disabled:text-slate-300 disabled:shadow-none text-xs sm:text-sm">
                                    {{ form.processing ? 'Sedang Memproses...' : 'Konfirmasi Pesanan' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Cart Summary for Mobile -->
        <div v-if="cart.length > 0 && !isCartOpenMobile" class="fixed inset-x-0 bottom-6 px-6 z-50 lg:hidden pointer-events-none">
            <button 
                @click="isCartOpenMobile = true"
                class="w-full bg-emerald-600 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between animate-slide-up pointer-events-auto"
            >
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-emerald-600 shadow-sm">{{ totalItems }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-100">Review Pesanan</p>
                        <p class="text-sm font-black">Rp {{ totalPrice.toLocaleString('id-ID') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-widest">LIHAT DETAIL</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </div>
            </button>
        </div>

        <!-- Overlay for Mobile Cart -->
        <div 
            v-if="isCartOpenMobile" 
            @click="isCartOpenMobile = false"
            class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[55] transition-opacity duration-300"
        ></div>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.05); border-radius: 20px; }

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

@keyframes slide-up {
    from { transform: translateY(100%) scale(0.9); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}
.animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>

<template>
  <div class="flex h-screen bg-slate-50 font-sans overflow-hidden">
    <!-- SIDEBAR -->
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />
    
    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-y-auto text-slate-800">
      <!-- TOP BAR -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <!-- HAMBURGER -->
          <button 
            @click="isSidebarOpen = true"
            class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>

          <div>
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-xs md:text-sm text-slate-500 hidden sm:block">Overview statistik dan aktivitas terbaru</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <div class="text-right hidden md:block">
            <p class="text-sm font-bold text-slate-700">Admin User</p>
            <p class="text-xs text-slate-500">Administrator</p>
          </div>
          <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border border-slate-300">
            <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" class="w-full h-full object-cover">
          </div>
        </div>
      </header>

      <div class="p-4 md:p-8 max-w-7xl mx-auto">
        <!-- WELCOME CARD -->
        <div class="bg-indigo-600 rounded-2xl p-6 md:p-8 text-white shadow-xl shadow-indigo-200 mb-8 relative overflow-hidden">
          <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h2>
            <p class="text-indigo-100 max-w-2xl text-base md:text-lg">
              Kelola semua data menu, kategori, dan konten website Utara Coffee dari satu tempat.
            </p>
          </div>
          <!-- Decorative circles -->
          <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-2xl"></div>
        </div>

        <!-- SALES CHART -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="font-bold text-slate-800 text-lg">Grafik Penjualan</h3>
              <p class="text-xs text-slate-500 font-medium">Data pendapatan 6 bulan terakhir</p>
            </div>
            <div class="p-2 bg-emerald-50 rounded-lg">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
          </div>
          <div class="h-[300px] w-full relative">
            <canvas ref="salesChartRef"></canvas>
          </div>
        </div>

        <!-- STATS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          
          <!-- Foods Card -->
          <div 
            @click="navigateTo('/admin/menu?tab=foods')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Foods</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ foodsCount }}</h3>
            <p class="text-sm text-slate-500 font-medium">Total Menu Makanan</p>
          </div>

          <!-- Drinks Card -->
          <div 
            @click="navigateTo('/admin/menu?tab=drinks')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Drinks</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ drinksCount }}</h3>
            <p class="text-sm text-slate-500 font-medium">Total Menu Minuman</p>
          </div>

          <!-- Subcategories Card -->
          <div 
            @click="navigateTo('/admin/subcategories')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-amber-50 rounded-lg text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Kategori</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ totalSubcategories }}</h3>
            <p class="text-sm text-slate-500 font-medium">Total Subkategori</p>
          </div>

          <!-- Post Moderation Card -->
          <div 
            @click="navigateTo('/admin/posts')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-pink-50 rounded-lg text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Moderasi</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">Post</h3>
            <p class="text-sm text-slate-500 font-medium">Cek Postingan Baru</p>
          </div>

        </div>

        <!-- NAVIGATION CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Banner Slider Card -->
          <div 
            @click="navigateTo('/admin/banners')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-purple-50 rounded-lg text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Content</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">Banner</h3>
            <p class="text-sm text-slate-500 font-medium">Kelola Banner Slider</p>
          </div>

          <!-- Community Card -->
          <div 
            @click="navigateTo('/admin/community')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-orange-50 rounded-lg text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Community</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">Tetangga</h3>
            <p class="text-sm text-slate-500 font-medium">Kelola Foto Komunitas</p>
          </div>

          <!-- Crew Card -->
          <div 
            @click="navigateTo('/admin/crews')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-cyan-50 rounded-lg text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Crew</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">Crew</h3>
            <p class="text-sm text-slate-500 font-medium">Kelola Data Pegawai</p>
          </div>

          <!-- Member/Loyalty Card -->
          <div 
            @click="navigateTo('/admin/members')"
            class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="p-3 bg-rose-50 rounded-lg text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-full">Loyalty</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-800 mb-1">Promo & Royalti</h3>
            <p class="text-sm text-slate-500 font-medium">Kelola Poin & Member</p>
          </div>
        </div>



        <!-- RECENT ACTIVITY (Existing) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
          <h3 class="font-bold text-slate-800 mb-4">Aktivitas Terbaru</h3>
          <div class="space-y-4">
            <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-lg">
              <div class="w-2 h-2 rounded-full bg-green-500"></div>
              <p class="text-sm text-slate-600">Statistik penjualan telah diperbarui secara real-time.</p>
              <span class="ml-auto text-xs text-slate-400">Baru saja</span>
            </div>
            <div v-if="props.foodsCount > 0" class="flex items-center gap-4 p-3 bg-white rounded-lg border border-slate-100">
              <div class="w-2 h-2 rounded-full bg-blue-500"></div>
              <p class="text-sm text-slate-600">Total {{ props.foodsCount }} menu makanan terdaftar di sistem.</p>
              <span class="ml-auto text-xs text-slate-400">System</span>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import AdminSidebar from "@/Layouts/AdminSidebar.vue";
import Chart from 'chart.js/auto';

const isSidebarOpen = ref(false);
const salesChartRef = ref(null);

const props = defineProps({
  foodsCount: {type: Number,default: 0},
  drinksCount: {type: Number,default: 0},
  totalSubcategories: { type: Number, default: 0 },
  salesData: { type: Array, default: () => [] }
})

const navigateTo = (url) => {
  router.visit(url)
}

onMounted(() => {
  if (salesChartRef.value) {
    const ctx = salesChartRef.value.getContext('2d');
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: props.salesData.map(d => d.label),
        datasets: [{
          label: 'Total Penjualan (Rp)',
          data: props.salesData.map(d => d.value),
          borderColor: '#10b981', // Tailwind emerald-500
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 3,
          pointBackgroundColor: '#fff',
          pointBorderColor: '#10b981',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: '#1e293b',
            titleFont: { weight: 'bold' },
            padding: 12,
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                }
                return label;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            display: false // Sembunyikan garis y untuk tampilan minimalis jika mau, atau set true
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  }
})
</script>
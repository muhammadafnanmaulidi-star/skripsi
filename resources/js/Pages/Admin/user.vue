<template>
  <div class="flex h-screen bg-gray-100 font-sans overflow-hidden">
    <!-- SIDEBAR -->
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />
 
    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-y-auto">
      <!-- TOP BAR -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center mb-6 md:mb-8">
        <div class="flex items-center gap-4">
          <!-- HAMBURGER -->
          <button 
            @click="isSidebarOpen = true"
            class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>

          <div>
            <h1 class="text-xl md:text-3xl font-bold text-gray-800">Kelola Pengguna</h1>
            <p class="text-xs md:text-sm text-gray-500 hidden sm:block">
              Selamat datang di halaman Kelola Pengguna
            </p>
          </div>
        </div>
      </header>

      <div class="px-4 md:px-8 pb-8">
        <!-- USERS SECTION -->
        <div class="p-6 bg-white rounded-xl shadow-lg">
          <h2 class="text-2xl font-bold mb-4 text-purple-600">Manajemen Pengguna</h2>
          <p class="text-gray-700 mb-6">
            Di sini Anda dapat melihat, menambah, mengedit, dan menghapus data pengguna (admin, kasir, dll.).
          </p>

          <!-- Development Notice -->
          <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-600 rounded-lg">
            <h3 class="text-lg font-bold text-indigo-700 mb-2">🚀 Fitur Sedang Dikembangkan</h3>
            <p class="text-gray-700">
              Fitur manajemen pengguna akan segera tersedia. Anda akan dapat menambah, mengedit, dan menghapus pengguna sistem dengan lebih mudah.
            </p>
            <div class="mt-4 flex flex-col sm:flex-row flex-wrap gap-3">
              <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm">Fitur tambah pengguna</span>
              </div>
              <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm">Fitur edit pengguna</span>
              </div>
              <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm">Fitur hapus pengguna</span>
              </div>
            </div>
          </div>

          <!-- Quick Links -->
          <div class="mt-8 pt-6 border-t">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Menu Cepat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <Link
                href="/admin"
                class="p-4 bg-indigo-100 hover:bg-indigo-200 rounded-lg transition border border-indigo-300 text-indigo-700 font-semibold text-center sm:text-left"
              >
                ← Kembali ke Dashboard
              </Link>
              <Link
                href="/admin/menu?tab=foods"
                class="p-4 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition border border-emerald-300 text-emerald-700 font-semibold text-center sm:text-left"
              >
                Kelola Foods →
              </Link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const isSidebarOpen = ref(false);

function logout() {
  router.post('/logout')
}
</script>
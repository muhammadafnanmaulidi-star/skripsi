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
            <h1 class="text-xl md:text-3xl font-bold text-gray-800">Kelola Banner</h1>
            <p class="text-xs md:text-sm text-gray-500 hidden sm:block">Upload dan kelola banner halaman utama</p>
          </div>
        </div>
      </header>

      <div class="px-4 md:px-8 pb-8">

      <!-- UPLOAD FORM -->
      <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
        <h3 class="text-lg font-bold mb-4">Tambah Banner Baru</h3>
        <form @submit.prevent="submit" class="flex flex-col md:flex-row gap-4 items-stretch md:items-end">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul (Opsional)</label>
            <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" placeholder="Contoh: Promo Merdeka">
          </div>
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
            <input @change="handleFileChange" type="file" accept="image/*" class="w-full border rounded px-1.5 py-1.5 text-sm file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            <div v-if="form.errors.image" class="text-red-500 text-sm mt-1">{{ form.errors.image }}</div>
          </div>
          <button type="submit" :disabled="form.processing" class="bg-emerald-600 text-white px-6 py-2.5 rounded font-bold hover:bg-emerald-700 transition disabled:opacity-50">
            Upload
          </button>
        </form>
      </div>

      <!-- BANNER LIST -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="banner in banners" :key="banner.id" class="bg-white rounded-xl shadow overflow-hidden group relative">
          <img :src="banner.image" class="w-full h-48 object-cover">
          <div class="p-4">
            <h4 class="font-bold text-gray-800">{{ banner.title || 'Tanpa Judul' }}</h4>
            <p class="text-xs text-gray-500 mt-1">Diupload: {{ new Date(banner.created_at).toLocaleDateString() }}</p>
          </div>
          
          <!-- DELETE BUTTON OVERLAY -->
          <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
            <button @click="deleteBanner(banner.id)" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
              Hapus
            </button>
          </div>
        </div>
      </div>
      
      <div v-if="banners.length === 0" class="text-center py-12 text-gray-500">
        Belum ada banner yang diupload.
      </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const isSidebarOpen = ref(false);

const props = defineProps({
  banners: Array,
});

const form = useForm({
  title: '',
  image: null,
});

const handleFileChange = (e) => {
  form.image = e.target.files[0];
};

const submit = () => {
  form.post('/admin/banners', {
    onSuccess: () => {
      form.reset();
      // Reset file input manually if needed
    },
  });
};

const deleteBanner = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus banner ini?')) {
    router.delete(`/admin/banners/${id}`);
  }
};
</script>

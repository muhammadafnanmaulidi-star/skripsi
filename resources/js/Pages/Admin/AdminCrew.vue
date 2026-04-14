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
            <h1 class="text-xl md:text-3xl font-bold text-gray-800">Kelola Crew</h1>
            <p class="text-xs md:text-sm text-gray-500 hidden sm:block">
              Kelola foto crew untuk halaman About Us
            </p>
          </div>
        </div>
        
        <!-- UPLOAD BUTTON -->
        <button 
          @click="showUploadModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white p-2 md:px-6 md:py-3 rounded-lg font-bold transition flex items-center gap-2 shadow-md"
        >
          <svg class="w-5 h-5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          <span class="hidden md:inline">Upload Foto Crew</span>
        </button>
      </header>

      <div class="px-4 md:px-8 pb-8">

      <!-- STATS -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Total Crew</p>
              <p class="text-3xl font-bold text-gray-800">{{ localCrews.length }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Aktif</p>
              <p class="text-3xl font-bold text-green-600">{{ activeCount }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Tidak Aktif</p>
              <p class="text-3xl font-bold text-gray-400">{{ inactiveCount }}</p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
          </div>
        </div>
      </div>

      <!-- CREW GRID -->
      <div v-if="localCrews.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="crew in localCrews" 
          :key="crew.id" 
          class="bg-white rounded-xl overflow-hidden shadow-md border border-gray-200 flex flex-col group hover:shadow-xl transition-all"
        >
          <!-- Image -->
          <div class="h-48 bg-gray-50 flex items-center justify-center relative group cursor-pointer overflow-hidden" @click="openPreview(crew)">
            <img 
              :src="crew.image_url" 
              class="max-h-full w-auto object-contain p-2 transition-transform duration-300 group-hover:scale-105"
              :alt="crew.name || 'Crew member'"
            />
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
              <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            
            <!-- Status Badge -->
            <div class="absolute top-2 right-2">
              <span 
                class="px-2 py-1 rounded text-xs font-bold shadow-sm"
                :class="crew.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'"
              >
                {{ crew.is_active ? 'Aktif' : 'Tidak Aktif' }}
              </span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-4 flex-1 flex flex-col">
            <p class="text-sm font-semibold text-gray-800 mb-1 truncate">
              {{ crew.name || 'Tanpa Nama' }}
            </p>
            <p class="text-xs text-gray-500 mb-2 truncate">
              {{ crew.position || 'Crew Member' }}
            </p>
            
            <p class="text-xs text-gray-400 mb-4">
              ID: #{{ crew.id }}
            </p>

            <!-- Actions -->
            <div class="mt-auto pt-4 border-t flex gap-2">
              <!-- Edit Button -->
              <button 
                @click="openEditModal(crew)"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-bold transition"
              >
                ✏️ Edit
              </button>
              
              <!-- Toggle Active -->
              <button 
                @click="toggleActive(crew)"
                :disabled="processing === crew.id"
                class="flex-1 py-2 rounded-lg text-sm font-bold transition"
                :class="crew.is_active 
                  ? 'bg-gray-200 hover:bg-gray-300 text-gray-700' 
                  : 'bg-green-600 hover:bg-green-700 text-white'"
              >
                {{ processing === crew.id ? '...' : (crew.is_active ? '🔒 Nonaktifkan' : '✓ Aktifkan') }}
              </button>
              
              <!-- Delete Button -->
              <button 
                @click="deleteCrew(crew)"
                :disabled="processing === crew.id"
                class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition"
              >
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- EMPTY STATE -->
      <div v-else class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <p class="text-gray-500 text-lg mb-4">Belum ada data crew</p>
        <button 
          @click="showUploadModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition"
        >
          Upload Crew Pertama
        </button>
      </div>

      </div>
    </main>

    <!-- UPLOAD MODAL -->
    <div 
      v-if="showUploadModal" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeUploadModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <div class="flex justify-between items-start mb-4 gap-4">
          <h2 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight">Upload Foto Crew</h2>
          <button 
            @click="closeUploadModal"
            class="text-gray-400 hover:text-gray-600 text-3xl shrink-0 -mt-1"
          >
            ×
          </button>
        </div>

        <form @submit.prevent="uploadCrew">
          <!-- Image Upload -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Pilih Foto
            </label>
            <input 
              type="file"
              @change="handleFileSelect"
              accept="image/*"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            
            <!-- Image Preview -->
            <div v-if="uploadPreview" class="mt-4 bg-[#fcf6e5] rounded-lg p-2 flex justify-center">
              <img :src="uploadPreview" class="h-48 w-auto object-contain rounded-lg border border-white/50 shadow-sm"/>
            </div>
          </div>

          <!-- Name -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Nama
            </label>
            <input 
              v-model="uploadForm.name"
              @input="(e) => { const val = e.target.value.replace(/[0-9]/g, ''); e.target.value = val; uploadForm.name = val; }"
              maxlength="50"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="contoh: Ahmad"
            />
          </div>

          <!-- Position -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Jabatan / Posisi
            </label>
            <input 
              v-model="uploadForm.position"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="contoh: Head Chef"
            />
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <button 
              type="button"
              @click="closeUploadModal"
              class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-bold transition"
            >
              Batal
            </button>
            <button 
              type="submit"
              :disabled="uploading"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-bold transition disabled:opacity-50"
            >
              {{ uploading ? 'Mengupload...' : 'Upload' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- EDIT MODAL -->
    <div 
      v-if="showEditModal" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeEditModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-start mb-4 gap-4">
          <h2 class="text-xl md:text-2xl font-bold text-gray-800 leading-tight">Edit Crew</h2>
          <button 
            @click="closeEditModal"
            class="text-gray-400 hover:text-gray-600 text-3xl shrink-0 -mt-1"
          >
            ×
          </button>
        </div>

        <form @submit.prevent="saveEdit">
          <!-- Name -->
          <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Nama
            </label>
            <input 
              v-model="editForm.name"
              @input="(e) => { const val = e.target.value.replace(/[0-9]/g, ''); e.target.value = val; editForm.name = val; }"
              maxlength="50"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Position -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Jabatan / Posisi
            </label>
            <input 
              v-model="editForm.position"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- RE-UPLOAD PHOTO -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Ganti Foto (Opsional)
            </label>
            <input 
              type="file"
              @change="handleEditFileSelect"
              accept="image/*"
              class="w-full border border-gray-300 rounded-lg p-2 text-sm"
            />
            <div v-if="editPreview" class="mt-4 bg-gray-50 rounded-lg p-2 flex justify-center border border-dashed border-gray-300">
               <img :src="editPreview" class="h-32 w-auto object-contain rounded shadow-sm"/>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <button 
              type="button"
              @click="closeEditModal"
              class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-bold transition"
            >
              Batal
            </button>
            <button 
              type="submit"
              :disabled="saving"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-bold transition disabled:opacity-50"
            >
              {{ saving ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- PREVIEW MODAL -->
    <div 
      v-if="showPreview" 
      class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4"
      @click.self="closePreview"
    >
      <div class="max-w-4xl w-full">
        <div class="flex justify-end mb-4">
          <button 
            @click="closePreview"
            class="text-white hover:text-gray-300 text-3xl"
          >
            ×
          </button>
        </div>
        <img 
          :src="previewCrew?.image_url" 
          class="w-full max-h-[80vh] object-contain rounded-lg"
          :alt="previewCrew?.name"
        />
        <p class="text-white text-center mt-4 text-xl font-bold">
          {{ previewCrew?.name }}
        </p>
        <p class="text-gray-300 text-center text-lg">
          {{ previewCrew?.position }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminSidebar from "@/Layouts/AdminSidebar.vue";
import axios from 'axios';

const isSidebarOpen = ref(false);

const props = defineProps({
  crews: {
    type: Array,
    default: () => []
  }
});

const localCrews = ref([...props.crews]);

// Upload modal state
const showUploadModal = ref(false);
const uploadForm = ref({
  image: null,
  name: '',
  position: ''
});
const uploadPreview = ref(null);
const uploading = ref(false);

// Edit modal state
const showEditModal = ref(false);
const editingCrew = ref(null);
const editForm = ref({
  name: '',
  position: '',
  image: null
});
const editPreview = ref(null);
const saving = ref(false);

// Preview modal state
const showPreview = ref(false);
const previewCrew = ref(null);

// Processing state
const processing = ref(null);

// Computed
const activeCount = computed(() => {
  return localCrews.value.filter(c => c.is_active).length;
});

const inactiveCount = computed(() => {
  return localCrews.value.filter(c => !c.is_active).length;
});

// Upload functions
const handleFileSelect = (event) => {
  const file = event.target.files[0];
  if (file) {
    uploadForm.value.image = file;
    const reader = new FileReader();
    reader.onload = (e) => uploadPreview.value = e.target.result;
    reader.readAsDataURL(file);
  }
};

const handleEditFileSelect = (event) => {
  const file = event.target.files[0];
  if (file) {
    editForm.value.image = file;
    const reader = new FileReader();
    reader.onload = (e) => editPreview.value = e.target.result;
    reader.readAsDataURL(file);
  }
};

const uploadCrew = async () => {
  if (!uploadForm.value.image) return;
  uploading.value = true;
  try {
    const formData = new FormData();
    formData.append('image', uploadForm.value.image);
    formData.append('name', uploadForm.value.name);
    formData.append('position', uploadForm.value.position);

    const response = await axios.post('/admin/crews', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    localCrews.value.unshift(response.data.crew);
    closeUploadModal();
    alert('Crew berhasil ditambahkan!');
  } catch (error) {
    alert('Gagal menambah crew');
  } finally {
    uploading.value = false;
  }
};

const closeUploadModal = () => {
  showUploadModal.value = false;
  uploadForm.value = { image: null, name: '', position: '' };
  uploadPreview.value = null;
};

// Edit functions
const openEditModal = (crew) => {
  editingCrew.value = crew;
  editForm.value = {
    name: crew.name || '',
    position: crew.position || '',
    image: null
  };
  editPreview.value = crew.image_url;
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  editingCrew.value = null;
  editPreview.value = null;
};

const saveEdit = async () => {
  if (!editingCrew.value) return;
  saving.value = true;
  try {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', editForm.value.name);
    formData.append('position', editForm.value.position);
    if (editForm.value.image) {
      formData.append('image', editForm.value.image);
    }

    const response = await axios.post(`/admin/crews/${editingCrew.value.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    const index = localCrews.value.findIndex(c => c.id === editingCrew.value.id);
    if (index !== -1) {
      localCrews.value[index].name = editForm.value.name;
      localCrews.value[index].position = editForm.value.position;
      if (response.data.crew && response.data.crew.image_url) {
        localCrews.value[index].image_url = response.data.crew.image_url;
        localCrews.value[index].image = response.data.crew.image;
      }
    }
    closeEditModal();
    alert('Data crew berhasil diupdate!');
  } catch (error) {
    alert('Gagal update data crew');
  } finally {
    saving.value = false;
  }
};

const toggleActive = async (crew) => {
  processing.value = crew.id;
  try {
    await axios.put(`/admin/crews/${crew.id}`, { is_active: !crew.is_active });
    const index = localCrews.value.findIndex(c => c.id === crew.id);
    if (index !== -1) {
      localCrews.value[index].is_active = !localCrews.value[index].is_active;
    }
  } catch (error) {
    alert('Gagal mengubah status');
  } finally {
    processing.value = null;
  }
};

const deleteCrew = async (crew) => {
  if (!confirm(`Hapus crew "${crew.name || 'ini'}"?`)) return;
  processing.value = crew.id;
  try {
    await axios.delete(`/admin/crews/${crew.id}`);
    const index = localCrews.value.findIndex(c => c.id === crew.id);
    if (index !== -1) localCrews.value.splice(index, 1);
    alert('Crew berhasil dihapus!');
  } catch (error) {
    alert('Gagal menghapus crew');
  } finally {
    processing.value = null;
  }
};

const openPreview = (crew) => {
  previewCrew.value = crew;
  showPreview.value = true;
};

const closePreview = () => {
  showPreview.value = false;
  previewCrew.value = null;
};
</script>

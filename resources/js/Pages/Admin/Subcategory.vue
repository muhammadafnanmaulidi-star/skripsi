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
            <h1 class="text-xl md:text-3xl font-bold text-gray-800">Kelola Subkategori</h1>
            <p class="text-xs md:text-sm text-gray-500 hidden sm:block">Tambah, ubah, dan hapus subkategori berdasarkan kategori.</p>
          </div>
        </div>
      </header>

      <div class="px-4 md:px-8 pb-8">

      <div class="bg-white p-6 rounded-xl shadow-lg">

        <div v-for="category in categories" :key="category.id" class="mb-6 border rounded-lg overflow-hidden">

          <button
            @click="toggleCategory(category.id)"
            class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 hover:bg-gray-200"
          >
            <span class="font-semibold">{{ category.name }}</span>
            <span>{{ openedCategory === category.id ? "▲" : "▼" }}</span>
          </button>

          <div v-if="openedCategory === category.id" class="p-4">

            <div class="flex mb-3">
              <input
                v-model="newSub.name"
                type="text"
                placeholder="Nama subkategori"
                class="border px-3 py-2 rounded-l w-64"
              />
              <button
                @click="addSubcategory(category.id)"
                class="bg-green-600 text-white px-4 py-2 rounded-r"
              >
                Tambah
              </button>
            </div>

            <div
              v-for="sub in category.subcategories"
              :key="sub.id"
              class="flex justify-between items-center p-2 border rounded mb-2"
            >
              <span>{{ sub.name }}</span>

              <div class="flex gap-2">
                <button @click="openEdit(sub)" class="px-3 py-1 bg-blue-500 text-white rounded">
                  Edit
                </button>

                <button @click="deleteSubcategory(sub.id)" class="px-3 py-1 bg-red-600 text-white rounded">
                  Hapus
                </button>
              </div>
            </div>

          </div>
        </div>

      </div>

      <!-- EDIT MODAL -->
      <div v-if="editModal" class="fixed inset-0 bg-black/50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
          <h2 class="text-lg font-semibold mb-3">Edit Subkategori</h2>

          <input
            v-model="editSub.name"
            type="text"
            class="w-full border px-3 py-2 rounded mb-4"
          />

          <div class="flex justify-end gap-2">
            <button @click="editModal = false" class="px-4 py-2 bg-gray-300 rounded">
              Batal
            </button>

            <button @click="updateSubcategory" class="px-4 py-2 bg-blue-600 text-white rounded">
              Simpan
            </button>
          </div>
        </div>
      </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const isSidebarOpen = ref(false);

const props = defineProps({
  categories: Array,
});

const openedCategory = ref(null);
const newSub = ref({ name: "" });

const editModal = ref(false);
const editSub = ref({ id: null, name: "" });

const toggleCategory = (id) => {
  openedCategory.value = openedCategory.value === id ? null : id;
};

const addSubcategory = (category_id) => {
  if (!newSub.value.name.trim()) return;

  router.post("/admin/subcategories", {
    category_id,
    name: newSub.value.name,
  }, {
    onSuccess: () => newSub.value.name = ""
  });
};

const openEdit = (sub) => {
  editSub.value = { ...sub };
  editModal.value = true;
};

const updateSubcategory = () => {
  router.put(`/admin/subcategories/${editSub.value.id}`, {
    name: editSub.value.name,
  }, {
    onSuccess: () => editModal.value = false
  });
};

const deleteSubcategory = (id) => {
  if (!confirm("Yakin ingin menghapus subkategori ini?")) return;
  router.delete(`/admin/subcategories/${id}`);
};
</script>

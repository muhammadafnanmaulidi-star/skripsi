<template>
  <div class="flex h-screen bg-slate-50 font-sans overflow-hidden">
    <!-- SIDEBAR -->
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-y-auto text-slate-800">
      <!-- HEADER -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
          <div>
             <h1 class="text-xl md:text-2xl font-bold text-slate-800">Kelola Blog</h1>
             <p class="text-xs md:text-sm text-slate-500 hidden sm:block">Daftar artikel blog yang diterbitkan</p>
          </div>
        </div>
      </header>

      <div class="p-4 md:p-8 max-w-7xl mx-auto">
         <!-- ACTION BAR -->
        <div class="flex justify-end mb-6">
          <Link href="/admin/blogs/create" class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tulis Artikel Baru</span>
          </Link>
        </div>

        <!-- BLOG LIST -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                <tr>
                   <th class="px-6 py-5">Gambar</th>
                   <th class="px-6 py-5">Judul & Slug</th>
                   <th class="px-6 py-5">Tanggal Posting</th>
                   <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-if="blogs.length === 0">
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada artikel yang dibuat.</td>
                </tr>
                <tr v-for="blog in blogs" :key="blog.id" class="group hover:bg-slate-50/50 transition">
                  <td class="px-6 py-4 w-32">
                    <img v-if="blog.image" :src="blog.image" class="w-20 h-20 object-cover rounded-xl border border-slate-200 shadow-sm group-hover:scale-105 transition duration-300" />
                    <div v-else class="w-20 h-20 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] text-slate-400">Tanpa Gambar</div>
                  </td>
                  <td class="px-6 py-4">
                      <p class="font-bold text-slate-800 text-base mb-1 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ blog.title }}</p>
                      <p class="text-xs text-slate-400 font-medium font-mono bg-slate-50 inline-block px-2 py-0.5 rounded border border-slate-100">{{ blog.slug }}</p>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-medium">
                    {{ new Date(blog.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                       <Link :href="`/admin/blogs/${blog.id}/edit`" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit Artikel">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                       </Link>
                       <button @click="deleteBlog(blog.id)" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-all font-bold" title="Hapus Artikel">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                       </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminSidebar from '@/Layouts/AdminSidebar.vue';

const props = defineProps({
  blogs: { type: Array, default: () => [] }
});

const isSidebarOpen = ref(false);

const deleteBlog = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus artikel blog ini? Tindakan ini tidak dapat dibatalkan.')) {
        router.delete(`/admin/blogs/${id}`);
    }
};
</script>

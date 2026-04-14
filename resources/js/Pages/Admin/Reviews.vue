<template>
  <div class="flex h-screen bg-gray-50 font-sans overflow-hidden">
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

    <main class="flex-1 min-w-0 overflow-y-auto">
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
          <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Kelola Review</h1>
            <p class="text-xs text-gray-500 hidden sm:block">Setujui atau tolak review dari kustomer</p>
          </div>
        </div>
      </header>

      <div class="p-4 md:p-8">
        <!-- Filter Tabs -->
        <div class="flex gap-2 mb-6">
          <button 
            v-for="status in ['pending', 'approved', 'rejected']" 
            :key="status"
            @click="activeStatus = status"
            :class="[
              'px-4 py-2 rounded-lg font-bold text-sm transition-all capitalize',
              activeStatus === status ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
            ]"
          >
            {{ status }} ({{ reviewsByStatus(status).length }})
          </button>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                  <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kustomer</th>
                  <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Rating & Pesan</th>
                  <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="review in filteredReviews" :key="review.id" class="hover:bg-gray-50/50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <img :src="review.avatar_url || '/orang1.png'" class="h-10 w-10 rounded-full object-cover border border-gray-100" />
                      <div>
                        <div class="font-bold text-gray-900">{{ review.name }}</div>
                        <div class="text-xs text-gray-400">{{ review.title || 'Kustomer' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center text-amber-500 mb-1">
                      <span v-for="i in 5" :key="i" class="text-lg">
                        {{ i <= review.rating ? '★' : '☆' }}
                      </span>
                      <span class="ml-2 text-xs font-bold text-gray-500">{{ review.rating }}</span>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2 max-w-md">{{ review.message }}</p>
                  </td>
                  <td class="px-6 py-4">
                    <span :class="[
                      'px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider',
                      statusClass(review.status)
                    ]">
                      {{ review.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                       <button 
                        v-if="review.status !== 'approved'" 
                        @click="updateStatus(review, 'approved')" 
                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" 
                        title="Setujui"
                       >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                       </button>
                       <button 
                        v-if="review.status !== 'rejected'" 
                        @click="updateStatus(review, 'rejected')" 
                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" 
                        title="Tolak"
                       >
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                       </button>
                       <button @click="deleteReview(review)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Permanen">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                       </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredReviews.length === 0">
                  <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                    Tidak ada review dengan status {{ activeStatus }}.
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
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const props = defineProps({
  reviews: Array
});

const isSidebarOpen = ref(false);
const activeStatus = ref('pending');

const reviewsByStatus = (status) => {
  return props.reviews.filter(r => r.status === status);
};

const filteredReviews = computed(() => {
  return reviewsByStatus(activeStatus.value);
});

const statusClass = (status) => {
  switch (status) {
    case 'approved': return 'bg-emerald-100 text-emerald-700';
    case 'rejected': return 'bg-amber-100 text-amber-700';
    default: return 'bg-blue-100 text-blue-700';
  }
};

const updateStatus = (review, status) => {
  if (confirm(`Apakah Anda yakin ingin mengubah status review ini menjadi ${status}?`)) {
    router.put(`/admin/reviews/${review.id}`, { status }, {
      preserveScroll: true,
      onSuccess: () => {
        // Handle success if needed
      }
    });
  }
};

const deleteReview = (review) => {
  if (confirm('Apakah Anda yakin ingin menghapus review ini secara permanen?')) {
    router.delete(`/admin/reviews/${review.id}`, {
      preserveScroll: true
    });
  }
};
</script>

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
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Promo & Royalti</h1>
            <p class="text-xs text-gray-500 hidden sm:block">Manajemen poin dan royalti kustomer Utara</p>
          </div>
        </div>
        
        <button @click="openAddModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-2 shadow-sm text-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Tambah Member
        </button>
      </header>

      <div class="p-4 md:p-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
             <div>
               <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Total Akun</p>
               <p class="text-2xl font-black text-slate-800">{{ usersList.length }}</p>
             </div>
             <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
             </div>
          </div>
          <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
             <div>
               <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Member Aktif</p>
               <p class="text-2xl font-black text-emerald-600">{{ usersList.filter(u => u.is_member).length }}</p>
             </div>
             <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
             </div>
          </div>
          <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
             <div>
               <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Pengajuan Baru</p>
               <p class="text-2xl font-black text-amber-600">{{ usersList.filter(u => u.member_status === 'pending').length }}</p>
             </div>
             <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
             </div>
          </div>
          <div class="relative group">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-indigo-500 transition-colors">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama/HP..."
              class="block w-full h-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm text-sm"
            />
          </div>
        </div>

        <!-- Integrated Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                  <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Kustomer</th>
                  <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Info Kontak</th>
                   <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                   <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Registrasi</th>
                   <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Poin</th>
                   <th class="px-6 py-5 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Manajemen</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="u in filteredUsers" :key="u.id" class="hover:bg-indigo-50/20 transition-all group">
                  <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-sm group-hover:bg-indigo-100 group-hover:text-indigo-500 transition-colors">
                        {{ u.name.charAt(0) }}
                      </div>
                      <div>
                        <div class="font-black text-gray-800 text-sm tracking-tight">{{ u.name }}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">User ID: #{{ u.id }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-5">
                    <div class="text-sm font-bold text-gray-600">{{ u.phone_number || '-' }}</div>
                    <div class="text-xs text-gray-400">{{ u.email }}</div>
                  </td>
                   <td class="px-6 py-5">
                    <div v-if="u.is_member">
                       <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-widest">
                         <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                         Member Aktif
                       </span>
                    </div>
                    <div v-else-if="u.member_status === 'pending'">
                       <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-widest">
                         <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                         Menunggu
                       </span>
                    </div>
                    <div v-else>
                       <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-widest">
                         Bukan Member
                       </span>
                    </div>
                  </td>
                  <td class="px-6 py-5 text-center">
                    <div v-if="u.is_member" class="flex items-center justify-center gap-2">
                       <span class="text-sm font-black text-indigo-600">{{ u.points }}</span>
                       <span v-if="u.points >= 9" class="animate-bounce text-sm">🎁</span>
                    </div>
                    <span v-else class="text-gray-300 font-bold">-</span>
                  </td>
                  <td class="px-6 py-5 text-right">
                    <div class="flex justify-end items-center gap-2">
                      <!-- Actions for Member -->
                      <template v-if="u.is_member">
                         <button @click="openAdjustFromUser(u)" class="p-2.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Tambah Poin">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                         </button>
                         <button v-if="u.points >= 9" @click="openRedeemFromUser(u)" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black transition shadow-lg shadow-emerald-100 uppercase tracking-widest">
                           Redeem
                         </button>
                         <button @click="revokeMember(u)" class="p-2.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus Status Member">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                         </button>
                      </template>
                      <template v-else-if="u.member_status === 'pending'">
                         <button @click="approveMember(u)" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-[10px] font-black transition shadow-lg shadow-emerald-100 uppercase tracking-wider">
                           Terima
                         </button>
                         <button @click="rejectMember(u)" class="bg-rose-100 text-rose-600 hover:bg-rose-200 px-4 py-2 rounded-xl text-[10px] font-black transition uppercase tracking-wider">
                           Tolak
                         </button>
                      </template>
                      <!-- Actions for Non-Member -->
                      <template v-else>
                         <button @click="promoteToMember(u)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black transition shadow-lg shadow-indigo-100 uppercase tracking-wider">
                           Jadikan Member
                         </button>
                      </template>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredUsers.length === 0">
                   <td colspan="5" class="px-6 py-20 text-center">
                    <div class="max-w-xs mx-auto text-gray-400 font-medium">
                       <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                       <p>Kustomer tidak ditemukan.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- MODAL: ADD/EDIT MEMBER -->
    <div v-if="showMemberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
           <h3 class="text-lg font-bold text-gray-800">{{ isEditing ? 'Edit Member' : 'Tambah Member Baru' }}</h3>
           <button @click="closeMemberModal" class="text-gray-400 hover:text-gray-600 transition text-2xl">×</button>
        </div>
        <form @submit.prevent="saveMember" class="p-6 space-y-4">
           <div>
             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
             <input v-model="memberForm.name" @input="(e) => { const val = e.target.value.replace(/[0-9]/g, ''); e.target.value = val; memberForm.name = val; }" maxlength="50" type="text" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Contoh: Ahmad Subardjo"/>
           </div>
           <div>
             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Handphone</label>
             <input v-model="memberForm.phone" @input="(e) => { const val = e.target.value.replace(/[^0-9]/g, ''); e.target.value = val; memberForm.phone = val; }" maxlength="20" type="tel" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Contoh: 08123456789"/>
           </div>
           <div v-if="!isEditing">
             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Poin Awal (Opsional)</label>
             <input v-model="memberForm.points" type="number" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="0"/>
           </div>
           <div class="pt-4 flex gap-3">
             <button type="button" @click="closeMemberModal" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition">Batal</button>
             <button type="submit" :disabled="processing" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition disabled:opacity-50">
                {{ processing ? 'Menyimpan...' : 'Simpan Member' }}
             </button>
           </div>
        </form>
      </div>
    </div>

    <!-- MODAL: ADJUST POINTS -->
    <div v-if="showAdjustModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100">
           <h3 class="text-lg font-bold text-gray-800">Atur Poin: {{ currentMember?.name }}</h3>
        </div>
        <div class="p-6 space-y-6 text-center">
            <div class="flex items-center justify-center gap-6">
               <button @click="pointAction = -1" :class="pointAction === -1 ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-500'" class="w-12 h-12 rounded-full flex items-center justify-center text-2xl font-bold transition">
                 -
               </button>
               <div class="text-4xl font-black text-gray-800">{{ pointDelta }}</div>
               <button @click="pointAction = 1" :class="pointAction === 1 ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-500'" class="w-12 h-12 rounded-full flex items-center justify-center text-2xl font-bold transition">
                 +
               </button>
            </div>
            <div class="flex justify-center gap-3">
               <button @click="pointDelta = Math.max(1, pointDelta - 1)" class="px-3 py-1 bg-gray-50 rounded text-xs font-bold">- 1</button>
               <button @click="pointDelta = pointDelta + 1" class="px-3 py-1 bg-gray-50 rounded text-xs font-bold">+ 1</button>
               <button @click="pointDelta = pointDelta + 5" class="px-3 py-1 bg-gray-50 rounded text-xs font-bold">+ 5</button>
            </div>
            <p class="text-xs text-gray-400">Poin sekarang: {{ currentMember?.points }} → Akan menjadi: {{ (currentMember?.points || 0) + (pointAction * pointDelta) }}</p>
            <div class="pt-4 flex gap-3">
               <button @click="showAdjustModal = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition">Batal</button>
               <button @click="updatePoints" :disabled="processing" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition disabled:opacity-50">
                  {{ processing ? '...' : 'Konfirmasi' }}
               </button>
            </div>
        </div>
      </div>
    </div>

    <!-- MODAL: REDEEM -->
    <div v-if="showRedeemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="p-6 text-center space-y-4">
           <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full mx-auto flex items-center justify-center text-3xl mb-2">🎁</div>
           <h3 class="text-xl font-black text-gray-900 leading-tight">Redeem Minuman Gratis?</h3>
           <p class="text-sm text-gray-500">Member <b>{{ currentMember?.name }}</b> memiliki {{ currentMember?.points }} poin.</p>
           
           <div class="bg-gray-50 p-4 rounded-xl space-y-3">
              <label class="block text-xs font-bold text-gray-400 uppercase">Jumlah Minuman Gratis</label>
              <div class="flex items-center justify-center gap-4">
                 <button @click="redeemCount = Math.max(1, redeemCount - 1)" class="w-10 h-10 bg-white border border-gray-200 rounded-lg shadow-sm font-bold text-gray-600">-</button>
                 <span class="text-2xl font-black text-gray-800">{{ redeemCount }}</span>
                 <button @click="redeemCount = Math.min(Math.floor(currentMember?.points / 9), redeemCount + 1)" class="w-10 h-10 bg-white border border-gray-200 rounded-lg shadow-sm font-bold text-gray-600">+</button>
              </div>
              <p class="text-xs font-bold text-indigo-600">Total Poin yang akan dipotong: {{ redeemCount * 9 }}</p>
           </div>

           <div class="pt-4 flex gap-3">
             <button @click="showRedeemModal = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition text-sm">Batal</button>
             <button @click="confirmRedeem" :disabled="processing" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition text-sm shadow-lg shadow-emerald-100 disabled:opacity-50">
                {{ processing ? '...' : 'Konfirmasi Redeem' }}
             </button>
           </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminSidebar from "@/Layouts/AdminSidebar.vue";
import axios from 'axios';

const props = defineProps({
  usersList: Array,
  members: Array // original members for direct sync if needed
});

const isSidebarOpen = ref(false);
const searchQuery = ref('');
const localUsers = ref([...props.usersList]);
const processing = ref(false);

const filteredUsers = computed(() => {
  let list = [...localUsers.value];
  
  // Filter berdasarkan search
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    list = list.filter(u => 
      u.name.toLowerCase().includes(q) || 
      u.email.toLowerCase().includes(q) || 
      (u.phone_number && u.phone_number.includes(q))
    );
  }
  
  // Sortir: Member Aktif paling atas, sisanya berdasarkan ID terbaru (akun baru)
  return list.sort((a, b) => {
    if (a.is_member !== b.is_member) {
      return a.is_member ? -1 : 1; 
    }
    return b.id - a.id;
  });
});

const promoteToMember = async (user) => {
  if (!confirm(`Aktifkan status member untuk ${user.name}?`)) return;
  processing.value = true;
  try {
    const resp = await axios.post('/admin/members', {
       name: user.name,
       phone: user.phone_number,
       points: 0
    });
    
    const idx = localUsers.value.findIndex(u => u.id === user.id);
    if (idx !== -1) {
       localUsers.value[idx].is_member = true;
       localUsers.value[idx].member_status = 'approved';
       localUsers.value[idx].member_id = resp.data.member.id;
       localUsers.value[idx].points = 0;
    }
    alert(`${user.name} sekarang adalah member!`);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menjadikan member');
  } finally {
    processing.value = false;
  }
};

const revokeMember = async (user) => {
  if (!confirm(`Berhentikan status member ${user.name}? Akun kustomer TIDAK akan dihapus, namun data poin akan hilang.`)) return;
  processing.value = true;
  try {
    await axios.delete(`/admin/members/${user.member_id}`);
    const idx = localUsers.value.findIndex(u => u.id === user.id);
    if (idx !== -1) {
       localUsers.value[idx].is_member = false;
       localUsers.value[idx].member_status = null;
       localUsers.value[idx].member_id = null;
       localUsers.value[idx].points = 0;
    }
    alert(`Status member ${user.name} telah dinonaktifkan.`);
  } catch (err) {
    alert('Gagal memberhentikan member');
  } finally {
    processing.value = false;
  }
};

const approveMember = async (user) => {
  if (!confirm(`Setujui pengajuan member untuk ${user.name}?`)) return;
  processing.value = true;
  try {
    const resp = await axios.patch(`/admin/members/${user.member_id}/approve`);
    const idx = localUsers.value.findIndex(u => u.id === user.id);
    if (idx !== -1) {
       localUsers.value[idx].is_member = true;
       localUsers.value[idx].member_status = 'approved';
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyetujui member');
  } finally {
    processing.value = false;
  }
};

const rejectMember = async (user) => {
  if (!confirm(`Tolak pengajuan member untuk ${user.name}?`)) return;
  processing.value = true;
  try {
    const resp = await axios.patch(`/admin/members/${user.member_id}/reject`);
    const idx = localUsers.value.findIndex(u => u.id === user.id);
    if (idx !== -1) {
       localUsers.value[idx].is_member = false;
       localUsers.value[idx].member_status = null;
       localUsers.value[idx].member_id = null;
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menolak member');
  } finally {
    processing.value = false;
  }
};

const openAdjustFromUser = (user) => {
  const memberObj = { id: user.member_id, name: user.name, points: user.points };
  openAdjustModal(memberObj);
};

const openRedeemFromUser = (user) => {
  const memberObj = { id: user.member_id, name: user.name, points: user.points };
  openRedeemModal(memberObj);
};

// Member Modal State
const showMemberModal = ref(false);
const isEditing = ref(false);
const memberForm = ref({
  id: null,
  name: '',
  phone: '',
  points: 0
});

const openAddModal = () => {
  isEditing.value = false;
  memberForm.value = { name: '', phone: '', points: 0 };
  showMemberModal.value = true;
};

const openEditModal = (member) => {
  isEditing.value = true;
  memberForm.value = { ...member };
  showMemberModal.value = true;
};

const closeMemberModal = () => {
  showMemberModal.value = false;
  memberForm.value = { name: '', phone: '', points: 0 };
};

const saveMember = async () => {
  processing.value = true;
  try {
    if (isEditing.value) {
      const resp = await axios.put(`/admin/members/${memberForm.value.id}`, memberForm.value);
      // Update data di localUsers
      const idx = localUsers.value.findIndex(u => u.member_id === memberForm.value.id);
      if (idx !== -1) {
         localUsers.value[idx].name = resp.data.member.name;
         localUsers.value[idx].phone_number = resp.data.member.phone;
         localUsers.value[idx].points = resp.data.member.points;
      }
    } else {
      const resp = await axios.post('/admin/members', memberForm.value);
      // Jika nomor HP cocok dengan salah satu kustomer di list, update statusnya
      const idx = localUsers.value.findIndex(u => u.phone_number === resp.data.member.phone);
      if (idx !== -1) {
         localUsers.value[idx].is_member = true;
         localUsers.value[idx].member_status = 'approved';
         localUsers.value[idx].member_id = resp.data.member.id;
         localUsers.value[idx].points = resp.data.member.points;
      }
    }
    closeMemberModal();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan member');
  } finally {
    processing.value = false;
  }
};

const deleteMember = async (member) => {
  if (!confirm(`Hapus member ${member.name}? Data poin akan hilang.`)) return;
  processing.value = true;
  try {
    await axios.delete(`/admin/members/${member.id}`);
    localMembers.value = localMembers.value.filter(m => m.id !== member.id);
    alert('Member berhasil dihapus.');
  } catch (err) {
    alert('Gagal menghapus member.');
  } finally {
    processing.value = false;
  }
};

// Point Adjustment Modal
const showAdjustModal = ref(false);
const currentMember = ref(null);
const pointDelta = ref(1);
const pointAction = ref(1); // 1 for add, -1 for subtract

const openAdjustModal = (member) => {
  currentMember.value = member;
  pointDelta.value = 1;
  pointAction.value = 1;
  showAdjustModal.value = true;
};

const updatePoints = async () => {
  processing.value = true;
  const newPoints = currentMember.value.points + (pointAction.value * pointDelta.value);
  try {
    const resp = await axios.put(`/admin/members/${currentMember.value.id}`, { points: newPoints });
    
    // Update di localUsers agar tabel langsung update
    const userIdx = localUsers.value.findIndex(u => u.member_id === currentMember.value.id);
    if (userIdx !== -1) {
       localUsers.value[userIdx].points = resp.data.member.points;
    }
    
    showAdjustModal.value = false;
  } catch (err) {
    alert('Gagal update poin');
  } finally {
    processing.value = false;
  }
};

// Redeem Modal
const showRedeemModal = ref(false);
const redeemCount = ref(1);

const openRedeemModal = (member) => {
  currentMember.value = member;
  redeemCount.value = 1;
  showRedeemModal.value = true;
};

const confirmRedeem = async () => {
  processing.value = true;
  try {
    const resp = await axios.post(`/admin/members/${currentMember.value.id}/redeem`, { count: redeemCount.value });
    
    // Update di localUsers agar tabel langsung update
    const userIdx = localUsers.value.findIndex(u => u.member_id === currentMember.value.id);
    if (userIdx !== -1) {
       localUsers.value[userIdx].points = resp.data.member.points;
    }
    
    showRedeemModal.value = false;
    alert(`Berhasil redeem ${redeemCount.value} minuman gratis!`);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal redeem poin');
  } finally {
    processing.value = false;
  }
};
</script>

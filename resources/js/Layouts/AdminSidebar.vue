<template>
  <transition name="sidebar">
    <aside 
      v-if="showSidebar"
      class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white text-slate-600 flex flex-col h-screen lg:h-screen h-[100dvh] font-sans border-r border-slate-200 shadow-2xl lg:shadow-none transition-transform duration-300"
      :class="isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <!-- HEADER -->
      <div class="p-6 flex items-center justify-between gap-3 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200">
            U
          </div>
          <div>
            <h1 class="text-lg font-bold text-slate-800 tracking-tight">Admin Panel</h1>
            <p class="text-xs text-slate-500 font-medium">Utara Coffee</p>
          </div>
        </div>

        <!-- MOBILE CLOSE BUTTON -->
        <button 
          @click="$emit('close')"
          class="lg:hidden p-2 rounded-lg bg-slate-50 text-slate-500 hover:text-indigo-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

    <!-- NAVIGATION -->
    <nav class="mt-6 flex-1 px-3 overflow-y-auto custom-scrollbar">
      <ul class="space-y-1">

        <!-- DASHBOARD -->
        <li>
          <Link
            href="/admin"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute === '/admin'
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute === '/admin' ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="text-sm">Dashboard</span>
          </Link>
        </li>

        <!-- SECTION LABEL -->


        <!-- Kelola Kategori -->
        <li>
          <Link
            href="/admin/subcategory"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.startsWith('/admin/subcategory')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.startsWith('/admin/subcategory') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span class="text-sm">Kategori Menu</span>
          </Link>
        </li>

        <!-- Kelola Pesanan -->
        <li>
          <Link
            href="/admin/orders"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.startsWith('/admin/orders')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.startsWith('/admin/orders') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-sm">Kelola Pesanan</span>
          </Link>
        </li>

        <!-- Kelola Menu (Dropdown) -->
        <li>
          <button
            @click="menuOpen = !menuOpen"
            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/menu')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/menu') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
              <span class="text-sm">Daftar Menu</span>
            </div>
            <svg 
              class="w-4 h-4 transition-transform duration-200"
              :class="{'rotate-180': menuOpen, 'text-indigo-600': currentRoute.includes('/admin/menu'), 'text-slate-400': !currentRoute.includes('/admin/menu')}"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <!-- SUBMENU -->
          <div 
            v-show="menuOpen"
            class="mt-1 ml-4 pl-4 border-l border-slate-200 space-y-1 overflow-hidden transition-all duration-300"
          >
            <Link
              href="/admin/menu?tab=foods"
              class="block px-3 py-2 rounded-lg text-sm transition-colors"
              :class="tab === 'foods' ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
            >
              Foods
            </Link>
            <Link
              href="/admin/menu?tab=drinks"
              class="block px-3 py-2 rounded-lg text-sm transition-colors"
              :class="tab === 'drinks' ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
            >
              Drinks
            </Link>
          </div>
        </li>

        <!-- SECTION LABEL -->


        <!-- Kelola Blog -->
        <li>
          <Link
            href="/admin/blogs"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/blogs')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <!-- Document/Article Icon -->
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/blogs') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <span class="text-sm">Kelola Blog</span>
          </Link>
        </li>


        <!-- Kelola Banner -->
        <li>
          <Link
            href="/admin/banners"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/banners')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/banners') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm">Banner Slider</span>
          </Link>
        </li>

        <!-- Moderasi Postingan -->
        <li>
          <Link
            href="/admin/posts"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/posts')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/posts') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            <span class="text-sm">Moderasi Post</span>
          </Link>
        </li>

        <!-- Kelola Review -->
        <li>
          <Link
            href="/admin/reviews"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/reviews')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/reviews') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <span class="text-sm">Kelola Review</span>
          </Link>
        </li>

        <!-- Kelola Komunitas -->
        <li>
          <Link
            href="/admin/community"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/community')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/community') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="text-sm">Kelola Komunitas</span>
          </Link>
        </li>

        <!-- Kelola Crew -->
        <li>
          <Link
            href="/admin/crews"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute.includes('/admin/crews')
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute.includes('/admin/crews') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="text-sm">Kelola Crew</span>
          </Link>
        </li>

        <!-- Kelola Pengguna -->


        <!-- Kelola Member -->
        <li>
          <Link
            href="/admin/members"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group"
            :class="currentRoute === '/admin/members'
              ? 'bg-indigo-50 text-indigo-700 font-bold'
              : 'hover:bg-slate-50 hover:text-slate-900'"
          >
            <svg class="w-5 h-5 transition-colors" :class="currentRoute === '/admin/members' ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.33 0-7 1.17-7 3.5V16h14v-2.5c0-2.33-4.67-3.5-7-3.5z"></path></svg>
            <span class="text-sm">Promo & Royalti</span>
          </Link>
        </li>

      </ul>
    </nav>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-slate-200 bg-slate-50">
      <form @submit.prevent="logout" class="w-full">
        <button
          type="submit"
          class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white text-red-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-all duration-200 text-sm font-bold border border-slate-200 shadow-sm hover:shadow"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
          Logout
        </button>
      </form>
    </div>
  </aside>
  </transition>

  <!-- OVERLAY FOR MOBILE -->
  <div 
    v-if="isOpen" 
    @click="$emit('close')"
    class="lg:hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 transition-opacity"
  ></div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";

const props = defineProps({
  isOpen: { type: Boolean, default: false }
});

defineEmits(['close']);

const page = usePage();
const currentRoute = page.url;
const tab = new URLSearchParams(window.location.search).get("tab");

// Auto-open menu if current route is related to menu
const menuOpen = ref(currentRoute.includes('/admin/menu'));

const showSidebar = ref(true); // Always true for now as transition handles visibility

const logout = () => router.post("/logout");
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}

/* Sidebar Transition */
.sidebar-enter-active, .sidebar-leave-active {
  transition: transform 0.3s ease;
}
.sidebar-enter-from, .sidebar-leave-to {
  transform: translateX(-100%);
}
</style>

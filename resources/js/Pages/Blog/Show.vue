<template>
  <div class="min-h-screen bg-white flex flex-col">
    <!-- Navbar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur shadow-sm border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 md:px-6 h-20 flex items-center justify-between">
        <Link href="/" class="flex items-center gap-2 md:gap-3">
          <img :src="utaraImg" alt="Utara" class="h-10 md:h-12 w-auto rounded-xl shadow" />
        </Link>
        
        <!-- Desktop Nav (Reused from Blog.vue) -->
        <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-gray-700">
          <Link href="/" class="hover:text-emerald-700 transition">Home</Link>
          <a href="/#menu" class="hover:text-emerald-700 transition">Menu</a>
          <a href="/#tetangga" class="hover:text-emerald-700 transition">Community</a>
          <Link href="/promo-loyalty" class="hover:text-emerald-700 transition">Promo & Loyalty</Link>
          <Link href="/about" class="hover:text-emerald-700 transition">About Us</Link>
          <Link href="/blog" class="text-emerald-700 border-b-2 border-emerald-700 pb-1">Blog</Link>
          <a href="/#contact" class="hover:text-emerald-700 transition">Contact Us</a>
        </nav>

        <div class="flex items-center gap-2 md:gap-3">
            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 text-gray-600">
              <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <Link href="/login" class="hidden sm:inline-block px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold hover:border-emerald-600 hover:text-emerald-700 transition">Login</Link>
            <a href="https://wa.me/6281215246678?text=Halo%20Kopi%20Utara" target="_blank" class="px-4 md:px-5 py-2 rounded-full bg-emerald-600 text-white text-sm md:text-base font-semibold shadow hover:bg-emerald-700 transition">Reservasi</a>
        </div>
      </div>

       <!-- Mobile Menu Overlay -->
       <transition 
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div v-if="isMobileMenuOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-xl overflow-hidden absolute w-full top-20 left-0">
          <nav class="flex flex-col p-4 gap-4 text-gray-800 font-bold">
            <Link @click="isMobileMenuOpen = false" href="/" class="p-2 hover:bg-emerald-50 rounded-lg">Home</Link>
            <a @click="isMobileMenuOpen = false" href="/#menu" class="p-2 hover:bg-emerald-50 rounded-lg">Menu</a>
            <a @click="isMobileMenuOpen = false" href="/#tetangga" class="p-2 hover:bg-emerald-50 rounded-lg">Community</a>
            <Link @click="isMobileMenuOpen = false" href="/promo-loyalty" class="p-2 hover:bg-emerald-50 rounded-lg">Promo & Loyalty</Link>
            <Link @click="isMobileMenuOpen = false" href="/about" class="p-2 hover:bg-emerald-50 rounded-lg">About Us</Link>
            <Link @click="isMobileMenuOpen = false" href="/blog" class="p-2 hover:bg-emerald-50 rounded-lg text-emerald-600">Blog</Link>
            <a @click="isMobileMenuOpen = false" href="/#contact" class="p-2 hover:bg-emerald-50 rounded-lg">Contact Us</a>
            <Link @click="isMobileMenuOpen = false" href="/login" class="p-2 bg-gray-100 rounded-lg text-center">Login</Link>
          </nav>
        </div>
      </transition>
    </header>

    <div class="h-20"></div>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 py-8 md:py-12">
        
        <!-- Top Navigation -->
        <div class="flex justify-between items-center mb-8 text-xs font-bold tracking-wider text-gray-800">
            <Link href="/blog" class="flex items-center hover:text-emerald-700">
                <span class="mr-2">←</span> KEMBALI
            </Link>
            <span>KATEGORI</span>
        </div>

        <!-- Title / Header -->
        <h1 class="text-4xl md:text-6xl font-black text-[#0f3d2e] leading-tight mb-8 font-serif">
            {{ blog.title }}
        </h1>

        <!-- Content Top -->
        <div class="grid md:grid-cols-3 gap-8 mb-12">
             <!-- Empty space or introduction could go here if layout requires, but image suggests simple flow -->
             <!-- Actually image suggests full width or floating sections, let's keep it simple first -->
             <div class="md:col-span-3">
                 <p class="text-gray-600 leading-relaxed mb-6">{{ blog.excerpt || blog.content ? blog.content.substring(0, 150) : '' }}...</p>
             </div>
        </div>

        <!-- Hero Image -->
        <div class="w-full aspect-video overflow-hidden mb-12">
             <img :src="blog.image || '/blog1.jpg'" :alt="blog.title" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-700" />
        </div>

        <!-- Meta & Content Wrapper -->
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Sidebar: Author & Share -->
            <div class="md:col-span-1 border-t border-b md:border-t-0 md:border-b-0 border-gray-200 py-4 md:py-0">
                <div class="flex flex-col gap-6 sticky top-24">
                    <div class="flex items-center gap-3">
                        <img :src="utaraImg" class="w-10 h-10 rounded-full border border-gray-200 p-1" />
                        <div>
                             <p class="font-bold text-sm text-[#0f3d2e]">{{ blog.author?.name || 'Utara' }}</p>
                             <p class="text-xs text-gray-500">Author</p>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Date</span>
                            <span class="font-medium">{{ formatDate(blog.created_at) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Read</span>
                            <span class="font-medium">1484</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Share</span>
                            <div class="flex gap-2">
                                <a href="#" class="hover:text-emerald-600"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="hover:text-emerald-600"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="md:col-span-3">
                <article class="prose prose-emerald max-w-none text-gray-800">
                    <div v-html="blog.content" class="leading-relaxed"></div>
                    
                    <!-- Fallback if content is empty (though should not happen) -->
                    <p v-if="!blog.content" class="italic text-gray-400">Belum ada konten untuk artikel ini.</p>
                </article>
            </div>
        </div>

    </main>

    <!-- Footer (Same as Blog.vue) -->
    <footer class="bg-white border-t border-gray-200 py-12 mt-auto">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 items-start">
          <div class="space-y-6">
            <img :src="logoUtaraImg" alt="UTARA" class="h-32 md:h-44 w-auto" />
            
            <div class="space-y-1">
              <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                Tempatnya cerita, tawa, dan kopi yang diseduh dengan hati.
              </p>
              <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                Kami hadir di belakang kampus untuk menemani tugas, obrolan, dan pagi yang hangat.
              </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1 border-2 border-black rounded-xl overflow-hidden shadow-lg">
                <a href="https://wa.me/6281215246678?text=Halo%20Kopi%20Utara" target="_blank" class="block bg-sky-400 text-white font-bold text-center py-3.5 text-sm md:text-base hover:bg-sky-500 transition-all">Kontak Whatsapp / Reservasi</a>
                <div class="bg-white text-gray-900 font-bold text-center py-3.5 border-t-2 border-black text-base md:text-lg">081215246678</div>
              </div>
              <div class="flex-1 border-2 border-black rounded-xl overflow-hidden shadow-lg">
                <div class="bg-emerald-500 text-white font-bold text-center py-3.5 text-sm md:text-base">Sosial Media</div>
                <div class="bg-white py-3 px-4 border-t-2 border-black flex items-center justify-center gap-4">
                  <a href="https://instagram.com/utara.yk" target="_blank" class="flex items-center gap-2 hover:scale-110 transition-transform">
                    <div class="bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 p-1.5 rounded-lg">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                      </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-sm">Utara.yk</span>
                  </a>
                  <a href="https://tiktok.com/@utara.yk" target="_blank" class="flex items-center gap-2 hover:scale-110 transition-transform">
                    <div class="bg-black p-1.5 rounded-lg">
                      <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                      </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-sm">Utara.yk</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg overflow-hidden border-2 border-black">
            <div class="bg-gray-50 px-4 py-2 border-b-2 border-black">
              <h3 class="text-gray-800 font-bold text-center text-sm flex items-center justify-center gap-2">
                <span class="text-red-500">📍</span>
                <span>Jl. Kemuning, Tlogo, Tamantirto (<span class="font-bold">Belakang Unires Putra UMY</span>)</span>
              </h3>
            </div>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666666666666!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890"
              width="100%"
              height="250"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  blog: {
    type: Object,
    required: true,
    default: () => ({
        title: 'Title',
        content: 'Content...',
        created_at: new Date()
    })
  }
});

const isMobileMenuOpen = ref(false);
const utaraImg = '/utara' + '.jpg';
const logoUtaraImg = '/logoutara' + '.png';

const formatDate = (dateString) => {
    if(!dateString) return '16. March 2022';
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-GB', options);
};
</script>

<style scoped>
/* Add any specific styles here, mainly relying on Tailwind */
</style>

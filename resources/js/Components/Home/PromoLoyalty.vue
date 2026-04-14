<template>
  <div class="min-h-screen bg-stone-50 flex flex-col">
    <!-- Navbar sama seperti Home -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur shadow-sm border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 md:px-6 h-20 flex items-center justify-between">
        <Link href="/" class="flex items-center gap-2 md:gap-3">
          <img :src="utaraJpg" alt="Utara" class="h-10 md:h-12 w-auto rounded-xl shadow" />
        </Link>
        
        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-gray-700">
          <Link href="/" class="hover:text-emerald-700 transition">Home</Link>
          <a href="/#menu" class="hover:text-emerald-700 transition">Menu</a>
          <a href="/#tetangga" class="hover:text-emerald-700 transition">Community</a>
          <Link href="/promo-loyalty" class="text-emerald-700 border-b-2 border-emerald-700 pb-1">Promo & Loyalty</Link>
          <Link href="/about" class="hover:text-emerald-700 transition">About Us</Link>
          <Link href="/blog" class="hover:text-emerald-700 transition">Blog</Link>
          <a href="/#contact" class="hover:text-emerald-700 transition">Contact Us</a>
        </nav>

        <div class="flex items-center gap-2 md:gap-3">
          <!-- Mobile Menu Toggle -->
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
            <Link @click="isMobileMenuOpen = false" href="/promo-loyalty" class="p-2 hover:bg-emerald-50 rounded-lg text-emerald-600">Promo & Loyalty</Link>
            <Link @click="isMobileMenuOpen = false" href="/about" class="p-2 hover:bg-emerald-50 rounded-lg">About Us</Link>
            <Link @click="isMobileMenuOpen = false" href="/blog" class="p-2 hover:bg-emerald-50 rounded-lg">Blog</Link>
            <a @click="isMobileMenuOpen = false" href="/#contact" class="p-2 hover:bg-emerald-50 rounded-lg">Contact Us</a>
            <Link @click="isMobileMenuOpen = false" href="/login" class="p-2 bg-gray-100 rounded-lg text-center">Login</Link>
          </nav>
        </div>
      </transition>
    </header>



    <!-- Spacer untuk navbar fixed -->
    <div class="h-24"></div>

    <!-- Hero / Form Section dengan Full Width -->
    <section class="bg-[#0f3d2e] text-center text-pink-100 py-14 px-4">
      <div class="max-w-4xl mx-auto space-y-7">
        <div>
          <h1 class="text-4xl md:text-5xl font-black text-pink-200 mb-2 tracking-tight">
            Cek Loyalty Card Kamu
          </h1>
          <p class="text-lg md:text-xl font-semibold">Kamu • Bisa • Ngopi • Gratis</p>
        </div>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 justify-center">
          <button
            @click="showModal = true"
            class="bg-black text-white px-6 py-3 rounded-md font-semibold shadow hover:bg-gray-900 transition"
          >
            Buat Akun
          </button>
          <div class="flex items-center bg-black/80 text-white rounded-md shadow overflow-hidden w-full md:w-auto">
            <input
              v-model="searchPhone"
              type="tel"
              placeholder="Masukkan Nomor handphone"
              class="flex-1 bg-transparent placeholder-gray-400 px-4 py-3 outline-none text-xs md:text-sm md:min-w-[340px]"
              @keyup.enter="searchMember"
            />
            <button 
              @click="searchMember"
              :disabled="searching"
              class="bg-white text-black px-4 py-3 flex items-center justify-center hover:bg-gray-200 transition disabled:opacity-50"
            >
              <span v-if="searching" class="animate-spin mr-1">⌛</span>
              <span v-else>🔍</span>
            </button>
          </div>
        </div>

        <!-- Poin Notification (Better UI) -->
        <transition name="fade">
          <div v-if="searchResult" class="max-w-xl mx-auto mb-10 text-left animate-fade-in">
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-2xl border-[3px] border-black flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
              <div class="absolute -right-6 -top-6 w-24 h-24 bg-pink-100 rounded-full opacity-50"></div>
              
              <!-- Left: Points Badge -->
              <div class="flex-shrink-0 w-24 h-24 md:w-32 md:h-32 bg-[#0f3d2e] rounded-2xl flex flex-col items-center justify-center text-white border-[3px] border-black shadow-[4px_4px_0_0_#000]">
                <span class="text-4xl md:text-5xl font-black">{{ searchResult.points }}</span>
                <span class="text-[10px] md:text-xs font-bold uppercase tracking-widest mt-1">Poin</span>
              </div>

              <!-- Right: Message -->
              <div class="flex-1 text-center md:text-left">
                <h4 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 leading-tight">
                  Halo, {{ searchResult.name }}! <span class="inline-block animate-bounce">👋</span>
                </h4>
                
                <p v-if="searchResult.points >= 9" class="text-gray-700 font-bold text-sm md:text-base leading-relaxed">
                  Wah keren! Kamu punya <span class="text-emerald-600 font-black">{{ Math.floor(searchResult.points / 9) }}</span> minuman gratis siap ditukar. 
                  Tunjukin ke barista ya! 
                </p>
                <p v-else class="text-gray-700 font-bold text-sm md:text-base leading-relaxed">
                  Semangat! Kamu butuh <span class="text-pink-500 font-black text-lg">{{ 9 - searchResult.points }}</span> poin lagi buat dapetin <span class="italic text-black underline decoration-pink-300 decoration-4">Kopi Gratis</span> pertama kamu. ☕
                </p>
              </div>
            </div>
          </div>
        </transition>

        <!-- Stamp Card -->
        <div class="max-w-5xl mx-auto bg-pink-200 border-[3px] border-black rounded-lg shadow-xl">
          <div class="border-b-[3px] border-black px-8 py-6 text-center">
            <h3 class="text-5xl md:text-6xl font-black text-[#0f3d2e]">SIP & SAVE!</h3>
            <p class="text-lg md:text-xl text-[#0f3d2e] mt-2 font-medium">
              Buy 9 cups of coffee, get the 10th one FREE.
            </p>
          </div>

          <div class="px-4 md:px-10 py-6 md:py-10">
            <div class="grid grid-cols-5 gap-3 md:gap-6 justify-items-center">
              <!-- 9 Pink Circles -->
              <div
                v-for="n in 9"
                :key="n"
                class="h-10 w-10 sm:h-16 sm:w-16 md:h-20 md:w-20 rounded-full shadow-inner border-2 transition-all duration-500 overflow-hidden flex items-center justify-center"
                :class="currentStamps >= n ? 'bg-[#0f3d2e] border-black' : 'bg-pink-300 border-pink-400/70'"
              >
                <!-- Stamp Logo inside if filled -->
                <span v-if="currentStamps >= n" class="text-white font-black text-xl md:text-3xl">北</span>
              </div>
              
              <!-- 10th Badge with Logo (Standardized Size) -->
              <div
                class="h-10 w-10 sm:h-16 sm:w-16 md:h-20 md:w-20 rounded-full shadow-inner border-2 transition-all duration-500 overflow-hidden flex items-center justify-center"
                :class="currentStamps >= 10 ? 'bg-[#0f3d2e] border-black' : 'bg-pink-300 border-pink-400/70'"
              >
                <!-- Logo and Extra Points (Standard Style) -->
                <div class="flex flex-col items-center justify-center text-white" v-if="currentStamps >= 10">
                  <span v-if="searchResult && searchResult.points > 9" class="font-black text-lg sm:text-2xl md:text-3xl leading-none">
                    +{{ searchResult.points - 9 }}
                  </span>
                  <span v-else class="font-black text-lg sm:text-2xl md:text-3xl leading-none">北</span>
                </div>
                <!-- Default Logo if not full -->
                <span v-else class="text-[#0f3d2e]/40 font-black text-xl md:text-3xl">北</span>
              </div>
            </div>
          </div>

          <div class="border-t-[3px] border-black px-8 py-6 text-center">
            <p v-if="currentStamps >= 10" class="text-base md:text-lg text-[#0f3d2e] font-black animate-bounce">
              YEAY! Kamu punya {{ Math.floor(currentStamps / 10) }} free menu minuman! Tunjukkan ke Barista.
            </p>
            <p v-else class="text-base md:text-lg text-[#0f3d2e] font-medium">
              Collect 9 coffee stamps, and your next brew is on us
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="location" class="py-20 bg-white">
      <div class="w-full px-8 md:px-12 lg:px-16">
        <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-start">
          <!-- LEFT SIDE: UTARA LOGO & BUTTONS -->
          <div class="space-y-6">
            <!-- UTARA Logo -->
            <div class="mb-4">
              <img 
                :src="logoUtaraPng" 
                alt="UTARA Logo" 
                class="h-32 md:h-44 lg:h-48 w-auto object-contain"
              />
            </div>

            <!-- Tagline -->
            <p class="text-gray-700 text-sm md:text-base leading-relaxed">
              Tempatnya cerita, tawa, dan kopi yang diseduh dengan hati.<br />
              Kami hadir di belakang kampus untuk menemani tugas, obrolan, dan pagi yang hangat.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
              <!-- WhatsApp / Reservasi Button with Phone Number -->
              <div class="flex-1 border-[3px] border-black rounded-xl overflow-hidden shadow-lg">
                <a 
                  href="https://wa.me/6281215246678?text=Halo%20Kopi%20Utara" 
                  target="_blank"
                  class="block bg-sky-400 text-white font-bold text-center py-3.5 text-sm hover:bg-sky-500 transition-all"
                >
                  Kontak Whatsapp / Reservasi
                </a>
                <div class="bg-white text-gray-900 font-bold text-center py-3.5 border-t-[3px] border-black text-base">
                  081215246678
                </div>
              </div>

              <!-- Social Media Button with Usernames -->
              <div class="flex-1 border-[3px] border-black rounded-xl overflow-hidden shadow-lg">
                <div class="bg-emerald-500 text-white font-bold text-center py-3.5 text-sm">
                  Sosial Media
                </div>
                <div class="bg-white py-3 px-3 border-t-[3px] border-black flex items-center justify-center gap-3">
                  <a 
                    href="https://instagram.com/utara.yk" 
                    target="_blank" 
                    class="flex items-center gap-1.5 hover:scale-110 transition-transform"
                  >
                    <div class="bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 p-1.5 rounded-md">
                      <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                      </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-xs">Utara.yk</span>
                  </a>
                  <a 
                    href="https://tiktok.com/@utara.yk" 
                    target="_blank" 
                    class="flex items-center gap-1.5 hover:scale-110 transition-transform"
                  >
                    <div class="bg-gray-900 p-1.5 rounded-md">
                      <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                      </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-xs">Utara.yk</span>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- RIGHT SIDE: MAP -->
          <div class="bg-white rounded-xl overflow-hidden shadow-xl border-[3px] border-black">
            <div class="bg-white px-5 py-3.5 border-b-[3px] border-black">
              <h3 class="text-gray-900 font-bold text-sm flex items-center justify-center gap-2">
                <span class="text-red-500 text-base">📍</span>
                <span>Jl. Kemuning, Tlogo, Tamantirto <span class="font-normal">(Belakang Unires Putra UMY)</span></span>
              </h3>
            </div>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666666666666!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890"
              width="100%"
              height="350"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Popup -->
    <Transition name="modal">
      <div 
        v-if="showModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4"
        @click.self="showModal = false"
      >
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full relative overflow-hidden">
          <!-- Close Button -->
          <button 
            @click="showModal = false"
            class="absolute top-5 right-5 z-10 text-gray-500 hover:text-gray-800 transition-colors duration-200 bg-white/80 backdrop-blur-sm rounded-full p-2 hover:bg-gray-100"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Modal Content -->
          <div class="p-10 text-center">
            <!-- Header Title -->
            <h3 class="text-2xl font-black text-gray-900 mb-6">Mau buat akun?</h3>
            
            <!-- Image Mas Aziz with decorative background -->
            <div class="mb-6 flex justify-center relative">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-emerald-100 rounded-full blur-3xl opacity-30 scale-75"></div>
              <img 
                :src="masAzizPng" 
                alt="Mas Aziz" 
                class="w-56 h-auto object-contain relative z-10 drop-shadow-2xl"
              />
            </div>

            <!-- Main Title -->
            <h4 class="text-3xl font-black text-gray-900 mb-4 leading-tight">
              Mau buat akun?
            </h4>
            
            <!-- Description Text -->
            <p class="text-gray-700 text-lg mb-8 leading-relaxed max-w-sm mx-auto font-medium">
              Bilang ke mas barista nya ya, mau buat membership!
            </p>

            <!-- Button with gradient -->
            <button 
              @click="showModal = false"
              class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold text-base px-10 py-3 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
            >
              Mengerti
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

// Asset paths
const utaraJpg = '/utara.jpg';
const logoUtaraPng = '/logoutara.png';
const masAzizPng = '/Masaziz.png';

// State untuk modal
const showModal = ref(false);
const isMobileMenuOpen = ref(false);

// State untuk pencarian
const searchPhone = ref('');
const searching = ref(false);
const searchResult = ref(null);

// Menghitung jumlah stempel yang harus diisi (maks 10 untuk free menu)
const currentStamps = computed(() => {
  if (!searchResult.value) return 0;
  // Jika poin 32, stamps = 32 % 10 = 2? 
  // Atau sisa dari 9? 
  // Sesuai permintaan: "jika sudah melakukan pembelian 9 kali, dia akan mendapatkan free 1 menu"
  // Jadi beli ke-10 free. Stempel 1-9 adalah pembelian. Ke-10 adalah free.
  // Poin di backend adalah jumlah pembelian. 
  // Jika poin 9, berarti sudah beli 9 kali. Maka stempel 1-9 terisi.
  // Jika poin 10, stempel 1-9 terisi + stempel ke-10 (hadiah) terisi.
  const points = searchResult.value.points;
  // Kita visualisasikan siklus 10.
  // Jika poin 12, berarti 1 kali free sudah lewat, sekarang di stempel ke-2 untuk siklus berikutnya.
  // Namun user bilang "jika sudah 32 point... redem satu kali, maka dia akan mengurangi 9".
  // Ini berarti poin bertindak sebagai "currency". 
  // Untuk visualisasi stempel, kita tunjukkan progres menuju 9 poin. 
  // Jika poin >= 9, maka tunjukkan stempel 1-9 penuh + ke-10 penuh.
  if (points >= 9) return 10;
  return points;
});

// Methods
const searchMember = async () => {
  if (!searchPhone.value) return;
  searching.value = true;
  searchResult.value = null;
  
  try {
    const response = await axios.post('/members/search', { phone: searchPhone.value });
    if (response.data.success) {
      searchResult.value = response.data.member;
    }
  } catch (error) {
    if (error.response?.status === 404) {
      alert('Nomor HP tidak terdaftar sebagai member.');
    } else {
      alert('Terjadi kesalahan saat mencari member.');
    }
  } finally {
    searching.value = false;
  }
};

const openWhatsApp = () => {
  window.open('https://wa.me/6281215246678', '_blank');
};

const openInstagram = () => {
  window.open('https://instagram.com/utara.yk', '_blank');
};

const openTiktok = () => {
  window.open('https://tiktok.com/@utara.yk', '_blank');
};

const openMaps = () => {
  window.open('https://maps.google.com/?q=Utara+Coffee', '_blank');
};
</script>

<style scoped>
/* Modal Transition Animations */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .bg-white,
.modal-leave-active .bg-white {
  transition: transform 0.3s ease;
}

.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
  transform: scale(0.9);
}
</style>


<template>
  <div class="min-h-screen bg-[#f6f7f9] text-gray-900">
    <Header 
      :scrolled="scrolled" 
      :isMobileMenuOpen="isMobileMenuOpen"
      @toggleMobileMenu="isMobileMenuOpen = !isMobileMenuOpen"
    />

    <main class="pt-16 pb-20 space-y-0">
      <!-- GRID BCK1/BCK2 -->
      <section class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen">
        <div class="grid md:grid-cols-2 grid-rows-2 gap-0">
          <div
            class="min-h-[320px] md:min-h-[440px] lg:min-h-[520px] bg-left bg-cover w-full"
            style="background-image: url('/bck1.jpg'); background-color: #dfe7ea; background-position: left center;"
          ></div>

          <div class="bg-[#8fd0d8] flex flex-col justify-center items-center text-center px-8 py-12 w-full">
            <h2 class="text-3xl md:text-4xl font-black font-serif text-[#1e272b] mb-4 leading-snug">
              Tempat Nyaman untuk<br />Ngopi &amp; Nugas
            </h2>
            <p class="text-gray-800 max-w-lg leading-relaxed text-sm md:text-base">
              Kopi Utara adalah ruang santai di belakang kampus tempat cerita, ide, dan tawa bertemu dalam satu meja.
            </p>
          </div>

          <div class="bg-[#99d4de] flex flex-col justify-center items-center text-center px-8 py-12 w-full">
            <h3 class="text-3xl font-black font-serif text-[#1e272b] mb-6 leading-snug">
              Rumah Kedua untuk<br />Semua
            </h3>
            <p class="text-gray-900 max-w-lg leading-relaxed text-sm md:text-base">
              Dari mahasiswa yang kejar deadline hingga teman ngobrol santai, kami ingin jadi tempat kamu kembali setiap hari.
            </p>
          </div>

          <div
            class="min-h-[320px] md:min-h-[440px] lg:min-h-[520px] bg-right bg-cover w-full"
            style="background-image: url('/bck1.jpg'); background-color: #dfe7ea; background-position: right center;"
          ></div>
        </div>
      </section>

      <!-- PHILOSOPHY SECTION -->
      <section class="w-full">
        <!-- Header -->
        <div class="bg-black py-10 text-center">
          <h2 class="text-3xl md:text-5xl font-serif font-black text-yellow-400 flex items-center justify-center gap-4 tracking-wide">
            <span class="text-green-500 text-4xl transform -rotate-12">🌱</span>
            Filosofi & Nilai Kami
          </h2>
        </div>

        <!-- Columns -->
        <div class="grid grid-cols-1 md:grid-cols-3">
          <!-- Column 1 -->
          <div class="bg-[#6B82F8] p-12 md:p-16 text-center flex flex-col items-center justify-center min-h-[350px]">
            <h3 class="text-3xl font-serif font-black mb-6 text-black tracking-tight">Kopi untuk Semua</h3>
            <p class="text-gray-900 text-lg leading-relaxed max-w-xs font-medium">
              Kami menghadirkan kopi dan minuman segar dengan harga yang ramah kantong mahasiswa tanpa mengorbankan rasa dan kualitas.
            </p>
          </div>

          <!-- Column 2 -->
          <div class="bg-[#FF99D6] p-12 md:p-16 text-center flex flex-col items-center justify-center min-h-[350px]">
            <h3 class="text-3xl font-serif font-black mb-6 text-black tracking-tight">Ruang untuk Terhubung</h3>
            <p class="text-gray-900 text-lg leading-relaxed max-w-xs font-medium">
              Kopi Utara dirancang sebagai tempat yang hidup: bisa buat kerja, main kartu, ngobrol, curhat, atau sekadar duduk menikmati suasana.
            </p>
          </div>

          <!-- Column 3 -->
          <div class="bg-[#5BC0F8] p-12 md:p-16 text-center flex flex-col items-center justify-center min-h-[350px]">
            <h3 class="text-3xl font-serif font-black mb-6 text-black tracking-tight">Lokal & Autentik</h3>
            <p class="text-gray-900 text-lg leading-relaxed max-w-xs font-medium">
              Rasa yang jujur, pelayanan yang tulus, dan vibe yang hangat. Itu yang ingin kami jaga dalam setiap seduhan dan kunjungan.
            </p>
          </div>
        </div>
      </section>

      <!-- MOSAIC SECTION -->
      <section class="w-full">
        <!-- Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
          <div class="bg-[#FFD700] flex items-center justify-center p-12 md:p-20 text-center order-2 md:order-1">
            <h3 class="text-4xl md:text-6xl font-serif font-black text-black leading-tight">
              Kopi susu sebagai menu favorit
            </h3>
          </div>
          <div class="h-[500px] md:h-auto order-1 md:order-2">
            <img src="/public/bck4.jpg" class="w-full h-full object-cover object-left" alt="Kopi Susu" />
          </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
          <div class="h-[500px] md:h-auto">
            <img src="/public/bck5.jpg" class="w-full h-full object-cover" alt="Tempat Nyaman" />
          </div>
          <div class="bg-[#6EE7B7] flex items-center justify-center p-12 md:p-20 text-center">
            <h3 class="text-4xl md:text-6xl font-serif font-black text-black leading-tight">
              Tempat yang nyaman buat nugas, meeting kecil, dan santai sore
            </h3>
          </div>
        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
          <div class="bg-[#F9A8D4] flex items-center justify-center p-12 md:p-20 text-center order-2 md:order-1">
            <h3 class="text-4xl md:text-6xl font-serif font-black text-black leading-tight">
              Signature refreshment
            </h3>
          </div>
          <div class="h-[500px] md:h-auto order-1 md:order-2">
            <img src="/public/bck6.png" class="w-full h-full object-cover" alt="Signature Refreshment" />
          </div>
        </div>

        <!-- Row 4 -->
        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
          <div class="h-[500px] md:h-auto">
            <img src="/public/bck7.png" class="w-full h-full object-cover" alt="Burger & Snack" />
          </div>
          <div class="bg-[#F5E6CA] flex items-center justify-center p-12 md:p-20 text-center">
            <h3 class="text-4xl md:text-6xl font-serif font-black text-black leading-tight">
              Burger & snack untuk teman ngopi
            </h3>
          </div>
        </div>
      </section>
    <section class="relative py-20 px-6 flex items-center justify-center min-h-[500px]">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0 z-0">
          <img src="/public/bck8.png" class="w-full h-full object-cover blur-sm" alt="Background">
          <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Content Card -->
        <div class="relative z-10 bg-white rounded-3xl p-10 md:p-14 max-w-4xl text-center shadow-2xl">
          <h2 class="text-3xl md:text-4xl font-serif font-black text-black mb-6">Dari Kami, Untuk Kamu</h2>
          <p class="text-gray-800 text-lg leading-relaxed">
            Kopi Utara ingin kamu merasa diterima seperti teman. Setiap kali kamu datang, kami ingin kamu pulang dengan perasaan lebih baik entah karena kopinya enak, suasananya nyaman, atau karena kamu menemukan momen kecil yang berarti di sini.
          </p>
        </div>
      </section>
      <!-- HERO BEIGE + CREW -->
      <section class="bg-[#FDF6E3] border-t-[10px] border-[#2f2a24] py-16 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center">
          <h1 class="text-4xl md:text-5xl font-black text-black mb-12 font-serif tracking-tight">These are men who cooked.</h1>
          
          <div class="flex flex-col items-center gap-y-12 select-none max-w-7xl mx-auto">
            <!-- ROW 1 (Max 4) -->
            <div v-if="crewPhotos.length > 0" class="flex flex-wrap justify-center items-end gap-x-4 md:gap-x-8 gap-y-8 md:gap-y-10 transition-all">
              <div
                v-for="crew in crewPhotos.slice(0, 4)"
                :key="crew.id"
                class="relative group transition-transform hover:scale-105 md:hover:scale-110 hover:z-10 duration-300 flex flex-col items-center w-[40%] sm:w-auto"
                @click="toggleCrew(crew.id)"
              >
                <img
                  :src="crew.image_url"
                  :alt="crew.name || 'Crew'"
                  class="h-32 sm:h-48 md:h-64 w-auto object-contain transition-all duration-500 mb-2 crew-photo-outline"
                  :class="{ 'scale-105 md:scale-110 z-10': activeCrewId === crew.id }"
                  draggable="false"
                />
                <div 
                  class="mt-1 md:mt-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center"
                  :class="{ 'md:opacity-100': activeCrewId === crew.id }"
                >
                  <p class="text-[10px] md:text-sm font-black text-black uppercase tracking-wider">{{ crew.name }}</p>
                  <p class="text-[8px] md:text-[10px] font-bold text-gray-600 uppercase">{{ crew.position }}</p>
                </div>
              </div>
            </div>

            <!-- ROW 2 (Max 2) -->
            <div v-if="crewPhotos.length > 4" class="flex flex-wrap justify-center items-end gap-x-4 md:gap-x-8 gap-y-8 md:gap-y-10 transition-all">
              <div
                v-for="crew in crewPhotos.slice(4, 6)"
                :key="crew.id"
                class="relative group transition-transform hover:scale-105 md:hover:scale-110 hover:z-10 duration-300 flex flex-col items-center w-[40%] sm:w-auto"
                @click="toggleCrew(crew.id)"
              >
                <img
                  :src="crew.image_url"
                  :alt="crew.name || 'Crew'"
                  class="h-32 sm:h-48 md:h-64 w-auto object-contain transition-all duration-500 mb-2 crew-photo-outline"
                  :class="{ 'scale-105 md:scale-110 z-10': activeCrewId === crew.id }"
                  draggable="false"
                />
                <div 
                  class="mt-1 md:mt-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center"
                  :class="{ 'md:opacity-100': activeCrewId === crew.id }"
                >
                  <p class="text-[10px] md:text-sm font-black text-black uppercase tracking-wider">{{ crew.name }}</p>
                  <p class="text-[8px] md:text-[10px] font-bold text-gray-600 uppercase">{{ crew.position }}</p>
                </div>
              </div>
            </div>

            <!-- ROW 3 (Max 3) -->
            <div v-if="crewPhotos.length > 6" class="flex flex-wrap justify-center items-end gap-x-4 md:gap-x-8 gap-y-8 md:gap-y-10 transition-all">
              <div
                v-for="crew in crewPhotos.slice(6, 9)"
                :key="crew.id"
                class="relative group transition-transform hover:scale-105 md:hover:scale-110 hover:z-10 duration-300 flex flex-col items-center w-[40%] sm:w-auto"
                @click="toggleCrew(crew.id)"
              >
                <img
                  :src="crew.image_url"
                  :alt="crew.name || 'Crew'"
                  class="h-32 sm:h-48 md:h-64 w-auto object-contain transition-all duration-500 mb-2 crew-photo-outline"
                  :class="{ 'scale-105 md:scale-110 z-10': activeCrewId === crew.id }"
                  draggable="false"
                />
                <div 
                  class="mt-1 md:mt-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center"
                  :class="{ 'md:opacity-100': activeCrewId === crew.id }"
                >
                  <p class="text-[10px] md:text-sm font-black text-black uppercase tracking-wider">{{ crew.name }}</p>
                  <p class="text-[8px] md:text-[10px] font-bold text-gray-600 uppercase">{{ crew.position }}</p>
                </div>
              </div>
            </div>

            <!-- REST (if any) -->
            <div v-if="crewPhotos.length > 9" class="flex flex-wrap justify-center items-end gap-x-4 md:gap-x-8 gap-y-8 md:gap-y-10 transition-all">
              <div
                v-for="crew in crewPhotos.slice(9)"
                :key="crew.id"
                class="relative group transition-transform hover:scale-105 md:hover:scale-110 hover:z-10 duration-300 flex flex-col items-center w-[40%] sm:w-auto"
                @click="toggleCrew(crew.id)"
              >
                <img
                  :src="crew.image_url"
                  :alt="crew.name || 'Crew'"
                  class="h-32 sm:h-48 md:h-64 w-auto object-contain transition-all duration-500 mb-2 crew-photo-outline"
                  :class="{ 'scale-105 md:scale-110 z-10': activeCrewId === crew.id }"
                  draggable="false"
                />
                <div 
                  class="mt-1 md:mt-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-center"
                  :class="{ 'md:opacity-100': activeCrewId === crew.id }"
                >
                  <p class="text-[10px] md:text-sm font-black text-black uppercase tracking-wider">{{ crew.name }}</p>
                  <p class="text-[8px] md:text-[10px] font-bold text-gray-600 uppercase">{{ crew.position }}</p>
                </div>
              </div>
            </div>

            <!-- FALLBACK IF EMPTY -->
            <template v-if="crewPhotos.length === 0">
              <div class="flex flex-wrap justify-center items-end gap-x-8 gap-y-10">
                <div
                  v-for="(person, idx) in fallbackEmployees"
                  :key="'fallback-'+idx"
                  class="relative group transition-transform hover:scale-110 hover:z-10 duration-300 flex flex-col items-center"
                >
                  <img
                    :src="person.photo"
                    :alt="person.name"
                    class="h-40 md:h-64 w-auto object-contain filter drop-shadow-[0_0_8px_rgba(255,255,255,1)]"
                    draggable="false"
                  />
                </div>
              </div>
            </template>
          </div>
        </div>
      </section>

      <!-- BRAND CARD + MAP -->
      <LocationSection :logoUtara="logoUtara" />
    </main>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { Header, LocationSection } from '@/Components';

export default {
  name: 'AboutPage',
  components: { Link, Header, LocationSection },
  props: {
    employees: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      isMobileMenuOpen: false,
      scrolled: false,
      crewPhotos: [],
      activeCrewId: null,
      logoUtara: '/logo' + 'utara.png',


      mosaicBlocks: [
        {
          title: 'Kopi susu sebagai menu favorit',
          color: 'bg-[#ffd93b] text-gray-900',
          textSize: 'text-3xl font-black'
        },
        {
          image: '/public/hero-cafelux.jpg',
          position: 'center'
        },
        {
          image: '/public/Banner1.jpg',
          position: 'center'
        },
        {
          title: 'Burger & snack untuk teman ngopi',
          color: 'bg-[#f5e9c4] text-gray-900',
          textSize: 'text-2xl font-black'
        },
      ],
      fallbackEmployees: [
        { name: 'Barista Utara', position: 'Head Barista', photo: '/orang1.png', hashtag: '#teamutara' },
        { name: 'Crew Dapur', position: 'Cook', photo: '/orang2.png', hashtag: '#freshburger' },
        { name: 'Service Lead', position: 'Front Desk', photo: '/orang3.png', hashtag: '#hospitality' },
        { name: 'Brew Specialist', position: 'Brewer', photo: '/orang4.png', hashtag: '#brewwithheart' },
      ],
    };
  },
  mounted() {
    this.fetchCrewPhotos();
    window.addEventListener('scroll', this.handleScroll);
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  computed: {
    displayEmployees() {
      if (this.employees && this.employees.length) {
        return this.employees.map((emp, idx) => ({
          name: emp.name || this.fallbackEmployees[idx % this.fallbackEmployees.length].name,
          position: emp.position || '',
          hashtag: emp.hashtag || '',
          photo: this.resolvePhoto(emp, idx),
        }));
      }
      return this.fallbackEmployees;
    },
  },
  methods: {
    async fetchCrewPhotos() {
      try {
        const response = await fetch('/crew/photos');
        if (response.ok) {
          this.crewPhotos = await response.json();
        }
      } catch (error) {
        console.error('Failed to fetch crew photos:', error);
      }
    },
    handleScroll() {
      this.scrolled = window.scrollY > 50;
    },
    toggleCrew(id) {
      if (this.activeCrewId === id) {
        this.activeCrewId = null;
      } else {
        this.activeCrewId = id;
      }
    },
    resolvePhoto(emp, idx) {
      const fallbackPhotos = ['/public/orang1.png', '/public/orang2.png', '/public/orang3.png', '/public/orang4.png'];
      const photo = emp?.photo;
      if (photo) {
        if (photo.startsWith('http')) return photo;
        if (photo.startsWith('/')) return photo;
        return `/storage/${photo}`;
      }
      return fallbackPhotos[idx % fallbackPhotos.length];
    },
  },
};
</script>

<style scoped>
.crew-photo-outline {
  filter: drop-shadow(2px 2px 0 white) drop-shadow(-2px -2px 0 white) drop-shadow(2px -2px 0 white) drop-shadow(-2px 2px 0 white);
}

@media (min-width: 768px) {
  .crew-photo-outline {
    filter: drop-shadow(4px 4px 0 white) drop-shadow(-4px -4px 0 white) drop-shadow(4px -4px 0 white) drop-shadow(-4px 4px 0 white);
  }
}
</style>
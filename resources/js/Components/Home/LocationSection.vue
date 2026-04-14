<script setup>
defineProps({
  logoUtara: String
})
</script>

<template>
   <section id="location" class="py-10 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <!-- MOBILE LAYOUT (Vertical Stack) -->
      <div class="flex flex-col md:grid md:grid-cols-2 gap-8 md:gap-12 items-center md:items-start text-center md:text-left">
        
        <!-- HEADER GROUP -->
        <div class="w-full space-y-6 flex flex-col items-center md:items-start">
          <!-- UTARA Logo -->
          <img 
            :src="logoUtara" 
            alt="UTARA Logo" 
            class="h-12 md:h-48 w-auto object-contain"
          />

          <!-- Tagline -->
          <div class="space-y-4 md:space-y-1 px-2 md:px-0">
            <p class="text-gray-900 md:text-gray-700 text-sm md:text-lg leading-relaxed font-medium md:font-normal">
              Tempatnya cerita, tawa, dan kopi yang diseduh dengan hati. Kami hadir di belakang kampus untuk menemani tugas, obrolan, dan pagi yang hangat.
            </p>
            <p class="hidden md:block text-gray-700 text-base md:text-lg leading-relaxed">
              Kami hadir di belakang kampus untuk menemani tugas, obrolan, dan pagi yang hangat.
            </p>
          </div>

          <!-- DESKTOP BUTTONS (Hidden on Mobile) -->
          <div class="hidden md:flex flex-col sm:flex-row gap-4 w-full max-w-2xl">
            <!-- WhatsApp -->
            <div class="flex-1 border-2 border-black rounded-xl overflow-hidden shadow-lg">
              <a href="https://wa.me/6281215246678?text=Halo%20Kopi%20Utara" target="_blank" class="block bg-sky-400 text-white font-bold text-center py-3.5 hover:bg-sky-500 transition-all">
                Kontak Whatsapp / Reservasi
              </a>
              <div class="bg-white text-gray-900 font-bold text-center py-3.5 border-t-2 border-black text-lg">
                081215246678
              </div>
            </div>
            <!-- Social -->
            <div class="flex-1 border-2 border-black rounded-xl overflow-hidden shadow-lg">
              <div class="bg-emerald-500 text-white font-bold text-center py-3.5">Sosial Media</div>
              <div class="bg-white py-3 px-4 border-t-2 border-black flex items-center justify-center gap-4">
                <a href="https://instagram.com/utara.yk" target="_blank" class="hover:scale-110 transition-transform"><span class="font-bold text-gray-900 text-sm">Utara.yk</span></a>
                <a href="https://tiktok.com/@utara.yk" target="_blank" class="hover:scale-110 transition-transform"><span class="font-bold text-gray-900 text-sm">Utara.yk</span></a>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT SIDE: MAP & MOBILE BUTTONS -->
        <div class="w-full space-y-6">
          
          <!-- MAP CONTAINER -->
          <div class="bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] md:rounded-xl overflow-hidden">
            <div class="bg-white px-4 py-3 border-b-2 border-black flex items-center justify-center gap-2">
              <span class="text-red-500 text-lg">📍</span>
              <h3 class="text-black font-bold text-center text-xs md:text-sm leading-tight">
                Jl. Kemuning, Tlogo, Tamantirto (Belakang Unires Putra UMY)
              </h3>
            </div>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666666666666!2d106.816666!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDknMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890"
              width="100%"
              height="250"
              class="md:h-[350px]"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
            />
          </div>

          <!-- MOBILE BUTTONS (Stacked) -->
          <div class="md:hidden space-y-4">
             <!-- WA -->
            <div class="border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
              <a href="https://wa.me/6281215246678" target="_blank" class="block bg-[#5ac8fa] text-black font-medium text-center py-2 text-sm border-b-2 border-black">
                Kontak Whatsapp / Reservasi
              </a>
              <div class="bg-white text-black font-bold text-center py-2 text-lg tracking-wide">
                081215246678
              </div>
            </div>

            <!-- SOCMED -->
            <div class="border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
              <div class="bg-[#86efac] text-black font-bold text-center py-2 text-sm border-b-2 border-black">
                Sosial Media
              </div>
              <div class="bg-white py-2 flex items-center justify-center gap-6">
                <a href="https://instagram.com/utara.yk" target="_blank" class="flex items-center gap-2">
                  <div class="bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                  </div>
                  <span class="font-bold text-black">Utara.yk</span>
                </a>
                <a href="https://tiktok.com/@utara.yk" target="_blank" class="flex items-center gap-2">
                   <div class="bg-black p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                    </svg>
                  </div>
                   <span class="font-bold text-black">Utara.yk</span>
                </a>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
      <!-- FOOTER COPYRIGHT -->
      <div class="mt-12 md:mt-0 text-center md:hidden pb-4">
         <p class="text-xs text-gray-500">
           © 2024 Utara-YK. All Rights Reserved.
         </p>
      </div>
    </div>
  </section>
</template>

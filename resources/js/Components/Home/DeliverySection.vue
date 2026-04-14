<template>
  <div class="text-center py-6 md:py-14 bg-black">
    <p class="text-white mb-4 md:mb-8 tracking-wide text-xs md:text-sm px-4">
      Ngopi atau makan, bisa dari rumah. Yuk pesan sekarang!
    </p>

    <!-- Horizontal row -->
    <div class="flex flex-row flex-nowrap md:flex-wrap justify-center items-center gap-3 md:gap-8 px-4">
      <!-- GOFOOD -->
      <a 
        href="https://YOUR_GOFOOD_LINK" 
        target="_blank"
        class="bg-white px-2 py-1.5 md:px-6 md:py-3 border-l-4 border-b-4 border-t-0 border-r-0 border-red-500 md:border-2 md:border-red-500 rounded-none md:rounded-xl shadow-none md:shadow-lg hover:md:shadow-red-500/40 transition shrink-0 flex-1 md:flex-none md:w-auto"
      >
        <img src="/public/gofood-logo.png" class="h-5 md:h-12 mx-auto">
      </a>

      <!-- GRABFOOD -->
      <a 
        href="https://YOUR_GRABFOOD_LINK" 
        target="_blank"
        class="bg-white px-2 py-1.5 md:px-6 md:py-3 border-l-4 border-b-4 border-t-0 border-r-0 border-green-500 md:border-2 md:border-green-500 rounded-none md:rounded-xl shadow-none md:shadow-lg hover:md:shadow-green-500/40 transition shrink-0 flex-1 md:flex-none md:w-auto"
      >
        <img src="/public/grabfood-logo.png" class="h-5 md:h-12 mx-auto">
      </a>

      <!-- SHOPEEFOOD -->
      <a 
        href="https://YOUR_SHOPEEFOOD_LINK" 
        target="_blank"
        class="bg-white px-2 py-1.5 md:px-6 md:py-3 border-l-4 border-b-4 border-t-0 border-r-0 border-orange-500 md:border-2 md:border-orange-500 rounded-none md:rounded-xl shadow-none md:shadow-lg hover:md:shadow-orange-500/40 transition shrink-0 flex-1 md:flex-none md:w-auto"
      >
        <img src="/public/shopeefood-logo.png" class="h-5 md:h-12 mx-auto">
      </a>
    </div>
  </div>
</template>


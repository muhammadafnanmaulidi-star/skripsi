<script setup>
defineProps({
  bannerImages: Array,
  onScrollDragStart: Function,
  onScrollDragMove: Function,
  onScrollDragEnd: Function,
  onBannerWheel: Function
})

const bannerScroll = null // Will be handled via ref in parent or locally
</script>

<template>
  <section id="home" class="relative hover:cursor-grab active:cursor-grabbing h-[300px] md:h-[500px] overflow-hidden mt-20">
    <div 
      class="absolute inset-0 overflow-x-auto overflow-y-hidden flex items-center justify-start banner-scroll select-none scroll-smooth"
      ref="scrollContainer"
      @mousedown="$emit('scrollDragStart', $event)"
      @mousemove="$emit('scrollDragMove', $event)"
      @mouseup="$emit('scrollDragEnd', $event)"
      @mouseleave="$emit('scrollDragEnd', $event)"
      @wheel="$emit('bannerWheel', $event)"
      style="scrollbar-width: none; -ms-overflow-style: none;"
    >
      <div class="flex items-center">
        <div 
          v-for="(img, idx) in bannerImages"
          :key="img + idx"
          class="relative shrink-0 overflow-hidden shadow-2xl rounded-lg"
        >
          <img 
            :src="img" 
            class="h-[300px] md:h-[500px] w-auto object-cover" 
            draggable="false"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.banner-scroll {
  touch-action: pan-x;
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.banner-scroll::-webkit-scrollbar {
  display: none;
}
</style>

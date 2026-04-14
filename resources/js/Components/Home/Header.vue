<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Logo } from '@/Components'

defineProps({
  scrolled: Boolean,
  isMobileMenuOpen: Boolean
})

const navigateToSection = (section, path) => {
  const currentPath = window.location.pathname
  const isHomeRelated = currentPath === '/' || currentPath === '/home' || currentPath === '/menu' || currentPath === '/tetangga'
  
  if (isHomeRelated) {
    // URL update without data loading
    setActive(section)
    window.history.pushState({}, '', path)
    
    const targetId = section === 'home' ? 'home' : (section === 'menu' ? 'menu' : 'tetangga')
    const el = document.getElementById(targetId)
    if (el) {
      const headerOffset = 80
      const elementPosition = el.getBoundingClientRect().top
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset
      
      // Delay 350ms before scrolling
      setTimeout(() => {
        smoothScrollTo(offsetPosition, 350)
      }, 350)
    }
    if (props.isMobileMenuOpen) emit('toggleMobileMenu')
  } else {
    // Normal inertia visit for different pages
    emit('toggleMobileMenu')
  }
}

// Custom Smooth Scroll Function
const smoothScrollTo = (targetPosition, duration) => {
  const startPosition = window.pageYOffset
  const distance = targetPosition - startPosition
  let startTime = null

  const animation = (currentTime) => {
    if (startTime === null) startTime = currentTime
    const timeElapsed = currentTime - startTime
    const run = ease(timeElapsed, startPosition, distance, duration)
    window.scrollTo(0, run)
    if (timeElapsed < duration) requestAnimationFrame(animation)
  }

  // Ease in-out quadratic
  const ease = (t, b, c, d) => {
    t /= d / 2
    if (t < 1) return c / 2 * t * t + b
    t--
    return -c / 2 * (t * (t - 2) - 1) + b
  }

  requestAnimationFrame(animation)
}

// Check if we should use Link or a custom handler
const isSamePage = (path) => {
  const currentPath = window.location.pathname
  const homePaths = ['/', '/home', '/menu', '/tetangga']
  return homePaths.includes(currentPath) && homePaths.includes(path)
}

const emit = defineEmits(['toggleMobileMenu'])
const activeSection = ref('')

const updateActiveSection = () => {
  const path = window.location.pathname
  if (path === '/' || path === '/home') activeSection.value = 'home'
  else if (path === '/menu') activeSection.value = 'menu'
  else if (path === '/tetangga') activeSection.value = 'tetangga'
  else if (path === '/promo-loyalty') activeSection.value = 'promo'
  else if (path === '/about') activeSection.value = 'about'
  else if (path === '/blog') activeSection.value = 'blog'
}

onMounted(() => {
  updateActiveSection()
})

const setActive = (section) => {
  activeSection.value = section
}
</script>

<template>
  <header :class="['fixed top-0 inset-x-0 z-50 transition-all duration-300', scrolled ? 'bg-white/90 backdrop-blur shadow-sm border-b' : 'bg-transparent']">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 h-16 sm:h-20 flex items-center justify-between">
      <Logo />
      
      <!-- Desktop Nav -->
      <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-gray-700">
        <template v-for="link in [
          { name: 'Home', section: 'home', path: '/home' },
          { name: 'Menu', section: 'menu', path: '/menu' },
          { name: 'Community', section: 'tetangga', path: '/tetangga' }
        ]" :key="link.section">
          <button 
            v-if="isSamePage(link.path)"
            @click="navigateToSection(link.section, link.path)"
            :class="['transition pb-1 font-semibold text-sm', activeSection === link.section ? 'text-emerald-700 border-b-2 border-emerald-700' : 'hover:text-emerald-700']"
          >
            {{ link.name }}
          </button>
          <Link 
            v-else
            :href="link.path"
            @click="setActive(link.section)"
            :class="['transition pb-1', activeSection === link.section ? 'text-emerald-700 border-b-2 border-emerald-700' : 'hover:text-emerald-700']"
          >
            {{ link.name }}
          </Link>
        </template>
        <Link 
          href="/promo-loyalty" 
          @click="setActive('promo')"
          :class="['transition pb-1', $page.url === '/promo-loyalty' ? 'text-emerald-700 border-b-2 border-emerald-700' : 'hover:text-emerald-700']"
        >Promo & Royalti</Link>
        <Link 
          href="/about" 
          @click="setActive('about')"
          :class="['transition pb-1', $page.url === '/about' ? 'text-emerald-700 border-b-2 border-emerald-700' : 'hover:text-emerald-700']"
        >About Us</Link>
        <Link 
          href="/blog" 
          @click="setActive('blog')"
          :class="['transition pb-1', $page.url === '/blog' ? 'text-emerald-700 border-b-2 border-emerald-700' : 'hover:text-emerald-700']"
        >Blog</Link>
      </nav>

      <div class="flex items-center gap-2 md:gap-3">
        <!-- Mobile Menu Toggle -->
        <button @click="$emit('toggleMobileMenu')" class="lg:hidden p-1.5 sm:p-2 text-gray-600">
          <svg v-if="!isMobileMenuOpen" class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="5" width="4" height="4" rx="1"></rect>
            <rect x="10" y="5" width="4" height="4" rx="1"></rect>
            <rect x="17" y="5" width="4" height="4" rx="1"></rect>
            <rect x="3" y="12" width="4" height="4" rx="1"></rect>
            <rect x="10" y="12" width="4" height="4" rx="1"></rect>
            <rect x="17" y="12" width="4" height="4" rx="1"></rect>
            <rect x="3" y="19" width="4" height="4" rx="1"></rect>
            <rect x="10" y="19" width="4" height="4" rx="1"></rect>
            <rect x="17" y="19" width="4" height="4" rx="1"></rect>
          </svg>
          <svg v-else class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <Link 
          href="/login" 
          class="hidden sm:inline-block px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold hover:border-emerald-600 hover:text-emerald-700 transition"
        >
          Login
        </Link>

        <!-- Reservation Button -->
        <a href="https://wa.me/6281215246678?text=Halo%20Kopi%20Utara" target="_blank" class="px-2 py-1 bg-[#5ac8fa] text-black text-[10px] uppercase tracking-wide font-bold border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-transform sm:px-4 sm:py-2 sm:bg-[#5ac8fa] sm:text-black sm:text-sm sm:border-2 sm:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] sm:rounded-none sm:hover:translate-y-0.5 sm:transition md:px-5 md:text-base">
          <span class="sm:hidden">Resv</span>
          <span class="hidden sm:inline">Reservation</span>
        </a>
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <transition 
      enter-active-class="transition duration-500 ease-in-out"
      enter-from-class="-translate-y-full opacity-100"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-500 ease-in-out"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="-translate-y-full opacity-100"
    >
      <div v-if="isMobileMenuOpen" class="lg:hidden fixed inset-0 bg-white z-[60] flex flex-col h-screen">
        <!-- Mobile Header inside Menu -->
        <div class="h-16 px-3 flex items-center justify-between border-b border-gray-100">
          <Logo />
          <div class="flex items-center gap-2">
            <button @click="$emit('toggleMobileMenu')" class="p-1.5 text-black hover:bg-gray-100 rounded-full transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
            <a href="https://wa.me/6281215246678" target="_blank" class="px-3 py-1.5 bg-[#5ac8fa] text-black text-xs font-bold border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-transform">
              Reservation
            </a>
          </div>
        </div>

        <!-- Mobile Nav Links -->
        <nav class="flex flex-col px-8 py-4 flex-1 overflow-y-auto">
          <template v-for="(link, idx) in [
            { name: 'Home', section: 'home', path: '/home' },
            { name: 'Menu', section: 'menu', path: '/menu' },
            { name: 'Community', section: 'tetangga', path: '/tetangga' },
            { name: 'Promo & Loyalty', section: 'promo', path: '/promo-loyalty' },
            { name: 'About Us', section: 'about', path: '/about' },
            { name: 'Blog', section: 'blog', path: '/blog' }
          ]" :key="link.section">
            <div class="flex flex-col group">
              <button
                v-if="isSamePage(link.path)"
                @click="navigateToSection(link.section, link.path)"
                :class="[
                  'py-5 text-lg font-bold transition-colors text-left',
                  activeSection === link.section ? 'text-[#014133]' : 'text-gray-500 group-hover:text-[#014133]'
                ]"
              >
                {{ link.name }}
              </button>
              <Link 
                v-else
                :href="link.path"
                @click="$emit('toggleMobileMenu'); setActive(link.section)"
                :class="[
                  'py-5 text-lg font-bold transition-colors text-left',
                  activeSection === link.section ? 'text-[#014133]' : 'text-gray-500 group-hover:text-[#014133]'
                ]"
              >
                {{ link.name }}
              </Link>
              <div class="border-t-2 border-dashed border-gray-200 w-full group-hover:border-gray-400 transition-colors"></div>
            </div>
          </template>
        </nav>

        <!-- Mobile Auth Button -->
        <div class="px-8 py-6 border-t border-gray-100 flex flex-col gap-3">
          <Link 
            href="/login" 
            class="w-full py-3 text-center rounded-full bg-emerald-600 text-white font-bold"
          >
            Login / Register
          </Link>
        </div>

        <!-- Mobile Footer -->
        <div class="p-8 text-center bg-transparent">
          <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
            © 2024 Utara-YK. All Rights Reserved.
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

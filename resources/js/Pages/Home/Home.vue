<script setup>
import { reactive, onMounted, onBeforeUnmount, ref, computed, watch, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { 
  Header, Hero, MenuSection, DeliverySection, 
  CommunitySection, CustomerReviewSection, LocationSection 
} from '@/Components'

const props = defineProps({
  categories: { type: Array, default: () => [] },
  banners:    { type: Array, default: () => [] }
})

const state = reactive({
  scrolled: false,
  isMobileMenuOpen: false,
  bannerImages: props.banners.length
    ? props.banners.map(b => b.image)
    : ['/Banner1.jpg', '/Banner2.jpg', '/Banner3.jpg'],
  isDragging: false,
  dragStartX: 0,
  dragStartScroll: 0,
  selectedFoodSubId: null,
  selectedDrinkSubId: null,
  selectedFoodMenuId: null,
  selectedDrinkMenuId: null,
  logoUtara: '/logoutara.png'
})

const bannerScrollRef = ref(null)

// Computed untuk Makanan & Minuman agar Reaktif
const foodCategory = computed(() => props.categories.find(c => c.id === 1) || { subcategories: [] })
const drinkCategory = computed(() => props.categories.find(c => c.id === 2) || { subcategories: [] })

const currentFoodSub = computed(() => foodCategory.value.subcategories.find(s => s.id === state.selectedFoodSubId))
const foodMenus = computed(() => currentFoodSub.value?.menus || [])
const selectedFoodMenu = computed(() => foodMenus.value.find(m => m.id === state.selectedFoodMenuId) || foodMenus.value[0])

const currentDrinkSub = computed(() => drinkCategory.value.subcategories.find(s => s.id === state.selectedDrinkSubId))
const drinkMenus = computed(() => currentDrinkSub.value?.menus || [])
const selectedDrinkMenu = computed(() => drinkMenus.value.find(m => m.id === state.selectedDrinkMenuId) || drinkMenus.value[0])

// Event Handlers
const handleScroll = () => { state.scrolled = window.scrollY > 50 }

const initializeSelections = () => {
  const f = foodCategory.value.subcategories[0]
  if (f) { 
    state.selectedFoodSubId = f.id
    state.selectedFoodMenuId = f.menus[0]?.id
  }
  const d = drinkCategory.value.subcategories[0]
  if (d) { 
    state.selectedDrinkSubId = d.id
    state.selectedDrinkMenuId = d.menus[0]?.id
  }
}

const onScrollDragStart = e => {
  const el = e.currentTarget
  state.isDragging = true; state.dragStartX = e.clientX; state.dragStartScroll = el.scrollLeft
  if (e.target.setPointerCapture) e.target.setPointerCapture(e.pointerId)
}
const onScrollDragMove = e => {
  if (!state.isDragging) return
  e.currentTarget.scrollLeft = state.dragStartScroll - (e.clientX - state.dragStartX)
}
const onScrollDragEnd = e => {
  state.isDragging = false
  if (e?.target?.releasePointerCapture) e.target.releasePointerCapture(e.pointerId)
}

const formatPrice = p => typeof p === 'number' ? (p >= 1000 ? (p/1000).toFixed(0)+'k' : 'Rp '+p) : 'N/A'

const scrollToSection = () => {
  const path = window.location.pathname
  let targetId = ''
  if (path === '/home' || path === '/') targetId = 'home'
  else if (path === '/menu') targetId = 'menu'
  else if (path === '/tetangga') targetId = 'tetangga'

  if (targetId) {
    const el = document.getElementById(targetId)
    if (el) {
      const headerOffset = 80
      const elementPosition = el.getBoundingClientRect().top
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset

      window.scrollTo({
        top: offsetPosition,
        behavior: 'auto'
      })
    }
  }
}

const page = usePage()
watch(() => page.url, () => {
  nextTick(() => {
    setTimeout(scrollToSection, 50)
  })
})

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  initializeSelections()
  setTimeout(scrollToSection, 100)
})
onBeforeUnmount(() => window.removeEventListener('scroll', handleScroll))
</script>

<template>
  <div class="min-h-screen bg-stone-50 flex flex-col">
    <Header 
      :scrolled="state.scrolled" 
      :isMobileMenuOpen="state.isMobileMenuOpen"
      @toggleMobileMenu="state.isMobileMenuOpen = !state.isMobileMenuOpen"
    />

    <main>
      <Hero 
        :bannerImages="state.bannerImages"
        @scrollDragStart="onScrollDragStart"
        @scrollDragMove="onScrollDragMove"
        @scrollDragEnd="onScrollDragEnd"
      />

      <MenuSection 
        :foodCategory="foodCategory"
        :drinkCategory="drinkCategory"
        :selectedFoodSubcategory="state.selectedFoodSubId"
        :selectedDrinkSubcategory="state.selectedDrinkSubId"
        :foodMenus="foodMenus"
        :drinkMenus="drinkMenus"
        :selectedFoodMenu="selectedFoodMenu"
        :selectedDrinkMenu="selectedDrinkMenu"
        :selectedFoodImage="selectedFoodMenu?.image || `${$page.props.asset_url}burger.jpg`"
        :selectedDrinkImage="selectedDrinkMenu?.image || `${$page.props.asset_url}utara.jpg`"
        :formatPrice="formatPrice"
        @selectFoodSubcategory="id => { state.selectedFoodSubId = id; state.selectedFoodMenuId = foodCategory.subcategories.find(s=>s.id===id)?.menus[0]?.id }"
        @selectDrinkSubcategory="id => { state.selectedDrinkSubId = id; state.selectedDrinkMenuId = drinkCategory.subcategories.find(s=>s.id===id)?.menus[0]?.id }"
        @selectFoodMenu="m => state.selectedFoodMenuId = m.id"
        @selectDrinkMenu="m => state.selectedDrinkMenuId = m.id"
      />

      <DeliverySection />
      <CommunitySection />
      <CustomerReviewSection />
      <LocationSection :logoUtara="state.logoUtara" />
    </main>
  </div>
</template>

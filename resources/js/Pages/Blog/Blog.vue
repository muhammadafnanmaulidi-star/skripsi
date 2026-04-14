<template>
  <div class="min-h-screen bg-white flex flex-col">
    <!-- Navbar -->
    <Header 
      :scrolled="scrolled" 
      :isMobileMenuOpen="isMobileMenuOpen"
      @toggleMobileMenu="isMobileMenuOpen = !isMobileMenuOpen"
    />

    <!-- Spacer untuk navbar fixed -->
    <div class="h-20"></div>

    <!-- News Ticker -->
    <div class="bg-emerald-700 text-white py-2 overflow-hidden">
      <div class="flex items-center gap-4 animate-marquee whitespace-nowrap">
        <span class="bg-emerald-900 px-3 py-1 text-xs font-bold rounded">NEWS TICKER</span>
        <span class="mx-4">»»»</span>
        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
        <span class="mx-4">»»»</span>
        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
        <span class="mx-4">»»»</span>
        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
      <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Hero Section -->
        <section class="mb-8">
          <!-- Title + Content Grid -->
          <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Left: Large Title -->
            <div>
              <h1 class="text-5xl md:text-7xl font-black text-[#0f3d2e] leading-none tracking-tight" style="font-family: 'Noto Sans', sans-serif;">
                KOPI<br/>
                DIPAGI<br/>
                HARI
              </h1>
            </div>
            
            <!-- Right: Description & Meta -->
            <div class="flex flex-col justify-center">
              <p class="text-gray-700 text-sm leading-relaxed mb-6">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.
              </p>
              
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-6 text-xs text-gray-600">
                  <span><strong>Post By</strong> Utara</span>
                  <span><strong>Date</strong> 16. March 2022</span>
                  <span><strong>Duration</strong> 1 Min</span>
                </div>
                
                <Link 
                  :href="'/blog/' + (featuredBlog?.slug || '#')"
                  class="px-4 py-2 border border-gray-400 rounded text-xs font-semibold text-gray-700 hover:bg-gray-100 transition"
                >
                  BACA LEBIH
                </Link>
              </div>
            </div>
          </div>
          
          <!-- Full Width Image -->
          <div class="w-full overflow-hidden rounded-lg">
            <img 
              :src="blog1Img" 
              alt="KOPI DIPAGI HARI" 
              class="w-full h-[400px] md:h-[600px] object-cover"
            />
          </div>
        </section>

        <!-- Blog List (Blog 2-7) -->
        <div class="space-y-0 mt-12">
          <article 
            v-for="(blog, index) in otherBlogs" 
            :key="blog.id"
            class="flex flex-col md:flex-row gap-6 py-6 border-t border-gray-200"
          >
            <!-- Left: Square Image -->
            <div class="md:w-40 flex-shrink-0">
              <img 
                :src="blog.image" 
                :alt="blog.title" 
                class="w-full md:w-40 h-40 object-cover"
              />
            </div>
            
            <!-- Right: Content -->
            <div class="flex-1 flex flex-col justify-between">
              <div>
                <!-- Title -->
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ blog.title }}</h2>
                
                <!-- Description -->
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                  {{ blog.excerpt }}
                </p>
              </div>
              
              <!-- Bottom: Meta + Tag -->
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-6 text-xs text-gray-500">
                  <span><strong class="text-gray-700">Post By</strong> {{ blog.author }}</span>
                  <span><strong class="text-gray-700">Date</strong> {{ blog.date }}</span>
                  <span><strong class="text-gray-700">Read</strong> {{ blog.duration }}</span>
                </div>
                
                <!-- Tag Button replaced with Read Detail -->
                <Link 
                  :href="'/blog/' + blog.slug"
                  class="px-3 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-600 hover:bg-emerald-600 hover:text-white transition uppercase"
                >
                  BACA DETAIL
                </Link>
              </div>
            </div>
          </article>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <!-- Footer -->
    <LocationSection :logoUtara="logoUtaraImg" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Header, LocationSection } from '@/Components';

const props = defineProps({
  blogs: {
    type: Object,
    default: () => ({ data: [] })
  }
});

const isMobileMenuOpen = ref(false);
const scrolled = ref(false);

const handleScroll = () => {
  scrolled.value = window.scrollY > 50;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll);
});

// Image paths (using string concatenation to avoid Vite import analysis)
const utaraImg = '/utara' + '.jpg';
const blog1Img = '/blog1' + '.jpg';
const logoUtaraImg = '/logoutara' + '.png';

// Static blog data using blog1.jpg - blog7.jpg
const staticBlogs = [
  {
    id: 1,
    title: 'KOPI DIPAGI HARI',
    slug: 'kopi-dipagi-hari',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sed enim ut sem viverra aliquet eget sit amet. Ornare arcu dui vivamus arcu felis bibendum ut.',
    image: '/blog1' + '.jpg',
    author: 'Admin',
    date: '16 March 2022',
    duration: '2 Min',
    featured: true
  },
  {
    id: 2,
    title: 'Hope dies last',
    slug: 'hope-dies-last',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog2' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'ART',
    featured: false
  },
  {
    id: 3,
    title: 'The best art museums',
    slug: 'the-best-art-museums',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog3' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'SCULPTURES',
    featured: false
  },
  {
    id: 4,
    title: 'The devil is the details',
    slug: 'the-devil-is-the-details',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog4' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'ART',
    featured: false
  },
  {
    id: 5,
    title: 'An indestructible hope',
    slug: 'an-indestructible-hope',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog5' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'ART',
    featured: false
  },
  {
    id: 6,
    title: 'Street art festival',
    slug: 'street-art-festival',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog6' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'STREET ART',
    featured: false
  },
  {
    id: 7,
    title: 'Through the eyes of street artists',
    slug: 'through-the-eyes-of-street-artists',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
    image: '/blog7' + '.jpg',
    author: 'Utara',
    date: '16. March 2022',
    duration: '1 Min',
    tag: 'STREET ART',
    featured: false
  }
];

// Use backend data if available, otherwise use static data
const blogList = computed(() => {
  if (props.blogs?.data?.length > 0) {
    return props.blogs.data;
  }
  return staticBlogs;
});

const featuredBlog = computed(() => blogList.value[0]);
const otherBlogs = computed(() => blogList.value.slice(1));
</script>

<style scoped>
@keyframes marquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

.animate-marquee {
  animation: marquee 20s linear infinite;
}
</style>

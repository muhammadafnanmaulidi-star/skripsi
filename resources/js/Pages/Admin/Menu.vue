<template>
  <div class="flex h-screen bg-gray-100 font-sans overflow-hidden">
    <!-- SIDEBAR -->
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-y-auto">
      <!-- TOP BAR -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center mb-6 md:mb-8">
        <div class="flex items-center gap-4">
          <!-- HAMBURGER -->
          <button 
            @click="isSidebarOpen = true"
            class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>

          <div>
            <h1 class="text-xl md:text-3xl font-bold text-gray-800">{{ currentTitle }}</h1>
            <p class="text-xs md:text-sm text-gray-500 hidden sm:block">Selamat datang di {{ currentTitle }}</p>
          </div>
        </div>

        <!-- LOGOUT MOBILE -->
        <button 
          @click="logout"
          class="lg:hidden flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[10px] font-black uppercase hover:bg-rose-100 transition-colors shadow-sm"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
          Out
        </button>
      </header>

      <div class="px-4 md:px-8 pb-8">

      <!-- FOODS SECTION -->
      <div v-if="tab === 'foods'">
        <div class="flex flex-wrap gap-2 mb-6">
          <button
            @click="openFoodsForm"
            class="px-5 py-2 bg-emerald-600 text-white font-black rounded-lg hover:bg-emerald-700 transition duration-150 shadow-md uppercase text-xs"
          >
            + Tambah Menu Foods
          </button>
          <button
            @click="resetAllStocks"
            class="px-5 py-2 bg-indigo-600 text-white font-black rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md uppercase text-xs flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Reset Semua Stok ke 50
          </button>
        </div>

        <!-- Form Tambah/Edit Foods -->
        <div v-if="foodsForm" class="bg-white p-6 rounded-xl shadow-xl mb-8 border border-emerald-200">
          <h3 class="text-xl font-bold mb-4 text-emerald-600">{{ editingFoodId ? 'Edit Menu Foods' : 'Form Tambah Menu Foods' }}</h3>
          <form @submit.prevent="submitFoods">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Pilih Subcategory</label>
                <select
                  v-model="foods.subcategory_id"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                  required
                >
                  <option value="">-- Pilih Subcategory --</option>
                  <option v-for="sub in foodsSubcategories" :key="sub.id" :value="sub.id">
                    {{ sub.name }}
                  </option>
                </select>
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Nama Menu</label>
                <input
                  v-model="foods.name"
                  type="text"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                  required
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Harga (Rupiah)</label>
                <input
                  v-model.number="foods.price"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                  required
                  min="0"
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Diskon Member (%)</label>
                <input
                  v-model.number="foods.discount_percent"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                  min="0"
                  max="100"
                  placeholder="0"
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Gambar (Optional)</label>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleFoodsImage"
                  class="w-full border border-gray-300 p-3 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                />
                <p v-if="foods.image && typeof foods.image === 'object'" class="text-sm text-gray-600 mt-1">
                  File selected: {{ foods.image.name }}
                </p>
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Stok Menu</label>
                <input
                  v-model.number="foods.stock"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                  required
                  min="0"
                  placeholder="0"
                />
              </div>

            </div>

            <div class="mb-6">
              <label class="block font-semibold mb-1 text-gray-700">Deskripsi</label>
              <textarea
                v-model="foods.description"
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                rows="3"
              ></textarea>
            </div>

            <div class="flex gap-3">
              <button
                type="submit"
                :disabled="loading"
                class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 disabled:bg-gray-400 transition shadow-md"
              >
                {{ loading ? "Menyimpan..." : editingFoodId ? "Update Foods" : "Simpan Foods" }}
              </button>
              <button
                type="button"
                @click="closeFoodsForm"
                class="px-6 py-3 bg-gray-400 text-white font-bold rounded-lg hover:bg-gray-500 transition shadow-md"
              >
                Batal
              </button>
            </div>
          </form>
        </div>

        <!-- List Foods -->
        <div>
          <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Makanan ({{ foodsMenus.length }})</h2>
          <div v-if="foodsMenus.length === 0" class="text-gray-500 p-6 bg-white rounded-xl shadow-md text-center">
            <p>Tidak ada data makanan yang tersedia saat ini.</p>
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div
              v-for="food in foodsMenus"
              :key="food.id"
              class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden border border-gray-200 relative group"
            >
              <!-- Image Container (Clickable for Adjust) -->
              <div 
                @click="openAdjustModal(food)"
                class="w-full h-40 bg-emerald-100 flex items-center justify-center overflow-hidden cursor-move relative group/img"
              >
                <img
                    v-if="food.image"
                    :src="food.image"
                    alt="Food Image"
                    :class="['w-full h-full object-cover pointer-events-none transition-transform', food.stock <= 0 && 'grayscale opacity-60']"
                    :style="{ 
                      objectPosition: food.image_position || 'center',
                      transform: `scale(${food.image_zoom || 1})`
                    }"
                />
                <img
                    v-else
                    src="https://placehold.co/200x160/10b981/ffffff?text=FOOD"
                    alt="No Image"
                    :class="['w-full h-full object-cover', food.stock <= 0 && 'grayscale opacity-60']"
                />
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity flex-col">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                  <span class="text-white text-[10px] font-bold uppercase mt-1">Sesuaikan Posisi</span>
                </div>
                <!-- Badge status habis (Dihapus karena sudah ada info stok) -->
              </div>

              <!-- Content -->
              <div class="p-4">
                <h3 :class="['font-bold text-lg line-clamp-2', food.stock > 0 ? 'text-gray-800' : 'text-gray-400']">{{ food.name }}</h3>
                <p :class="['font-bold text-xl mt-2', food.stock > 0 ? 'text-emerald-600' : 'text-gray-400 line-through']">Rp {{ food.price.toLocaleString('id-ID') }}</p>
                <div class="mt-1 flex gap-2 flex-wrap">
                  <span v-if="food.discount_percent > 0" class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-rose-100 text-rose-600 border border-rose-200">
                    OFF {{ food.discount_percent }}%
                  </span>
                  <span :class="['text-[10px] font-bold uppercase px-2 py-0.5 rounded-full border', food.stock > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-500 border-red-200']">
                    {{ food.stock > 0 ? `Stok: ${food.stock}` : 'Habis' }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mt-3 line-clamp-3">{{ food.description || 'Tidak ada deskripsi.' }}</p>
              </div>

              <!-- Action Buttons -->
              <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                <button
                  @click="editFood(food)"
                  class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition shadow-lg"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button
                  @click="deleteFood(food.id)"
                  class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition shadow-lg"
                  title="Hapus"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- DRINKS SECTION -->
      <div v-if="tab === 'drinks'">
        <div class="flex flex-wrap gap-2 mb-6">
          <button
            @click="openDrinksForm"
            class="px-5 py-2 bg-blue-600 text-white font-black rounded-lg hover:bg-blue-700 transition duration-150 shadow-md uppercase text-xs"
          >
            + Tambah Menu Drinks
          </button>
          <button
            @click="resetAllStocks"
            class="px-5 py-2 bg-indigo-600 text-white font-black rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md uppercase text-xs flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Reset Semua Stok ke 50
          </button>
        </div>

        <!-- Form Tambah/Edit Drinks -->
        <div v-if="drinksForm" class="bg-white p-6 rounded-xl shadow-xl mb-8 border border-blue-200">
          <h3 class="text-xl font-bold mb-4 text-blue-600">{{ editingDrinkId ? 'Edit Menu Drinks' : 'Form Tambah Menu Drinks' }}</h3>
          <form @submit.prevent="submitDrinks">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Pilih Subcategory</label>
                <select
                  v-model="drinks.subcategory_id"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">-- Pilih Subcategory --</option>
                  <option v-for="sub in drinksSubcategories" :key="sub.id" :value="sub.id">
                    {{ sub.name }}
                  </option>
                </select>
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Nama Menu</label>
                <input
                  v-model="drinks.name"
                  type="text"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  required
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Harga (Rupiah)</label>
                <input
                  v-model.number="drinks.price"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  required
                  min="0"
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Diskon Member (%)</label>
                <input
                  v-model.number="drinks.discount_percent"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  min="0"
                  max="100"
                  placeholder="0"
                />
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Gambar (Optional)</label>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleDrinksImage"
                  class="w-full border border-gray-300 p-3 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                />
                <p v-if="drinks.image && typeof drinks.image === 'object'" class="text-sm text-gray-600 mt-1">
                  File selected: {{ drinks.image.name }}
                </p>
              </div>

              <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Stok Menu</label>
                <input
                  v-model.number="drinks.stock"
                  type="number"
                  class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  required
                  min="0"
                  placeholder="0"
                />
              </div>

            </div>

            <div class="mb-6">
              <label class="block font-semibold mb-1 text-gray-700">Deskripsi</label>
              <textarea
                v-model="drinks.description"
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                rows="3"
              ></textarea>
            </div>

            <div class="flex gap-3">
              <button
                type="submit"
                :disabled="loading"
                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 disabled:bg-gray-400 transition shadow-md"
              >
                {{ loading ? "Menyimpan..." : editingDrinkId ? "Update Drinks" : "Simpan Drinks" }}
              </button>
              <button
                type="button"
                @click="closeDrinksForm"
                class="px-6 py-3 bg-gray-400 text-white font-bold rounded-lg hover:bg-gray-500 transition shadow-md"
              >
                Batal
              </button>
            </div>
          </form>
        </div>

        <!-- List Drinks -->
        <div>
          <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Minuman ({{ drinksMenus.length }})</h2>
          <div v-if="drinksMenus.length === 0" class="text-gray-500 p-6 bg-white rounded-xl shadow-md text-center">
            <p>Tidak ada data minuman yang tersedia saat ini.</p>
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div
              v-for="drink in drinksMenus"
              :key="drink.id"
              class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden border border-gray-200 relative group"
            >
              <!-- Image Container (Clickable for Adjust) -->
              <div 
                @click="openAdjustModal(drink)"
                class="w-full h-40 bg-blue-100 flex items-center justify-center overflow-hidden cursor-move relative group/img"
              >
               <img
                    v-if="drink.image"
                    :src="drink.image"
                    alt="Drink Image"
                    :class="['w-full h-full object-cover pointer-events-none transition-transform', drink.stock <= 0 && 'grayscale opacity-60']"
                    :style="{ 
                      objectPosition: drink.image_position || 'center',
                      transform: `scale(${drink.image_zoom || 1})`
                    }"
                    />
                    <img
                    v-else
                    src="https://placehold.co/200x160/3b82f6/ffffff?text=DRINK"
                    alt="No Image"
                    :class="['w-full h-full object-cover', drink.stock <= 0 && 'grayscale opacity-60']"
                />
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity flex-col">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                  <span class="text-white text-[10px] font-bold uppercase mt-1">Sesuaikan Posisi</span>
                </div>
                <!-- Badge status habis (Dihapus karena sudah ada info stok) -->
              </div>

              <!-- Content -->
              <div class="p-4">
                <h3 :class="['font-bold text-lg line-clamp-2', drink.stock > 0 ? 'text-gray-800' : 'text-gray-400']">{{ drink.name }}</h3>
                <p :class="['font-bold text-xl mt-2', drink.stock > 0 ? 'text-blue-600' : 'text-gray-400 line-through']">Rp {{ drink.price.toLocaleString('id-ID') }}</p>
                <div class="mt-1 flex gap-2 flex-wrap">
                  <span v-if="drink.discount_percent > 0" class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-rose-100 text-rose-600 border border-rose-200">
                    OFF {{ drink.discount_percent }}%
                  </span>
                  <span :class="['text-[10px] font-bold uppercase px-2 py-0.5 rounded-full border', drink.stock > 0 ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-red-50 text-red-500 border-red-200']">
                    {{ drink.stock > 0 ? `Stok: ${drink.stock}` : 'Habis' }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mt-3 line-clamp-3">{{ drink.description || 'Tidak ada deskripsi.' }}</p>
              </div>

              <!-- Action Buttons -->
              <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                <button
                  @click="editDrink(drink)"
                  class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition shadow-lg"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button
                  @click="deleteDrink(drink.id)"
                  class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition shadow-lg"
                  title="Hapus"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </main>

    <!-- POPUP NOTIFIKASI -->
    <div
      v-if="showPopup"
      :class="[
        'fixed bottom-6 right-6 px-5 py-3 rounded-xl shadow-2xl z-[110] transition-transform duration-300 transform ease-out',
        popupType === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white',
      ]"
      style="min-width: 250px;"
    >
      {{ popup }}
    </div>
  </div>

  <!-- MODAL ADJUST IMAGE POSITION -->
  <div v-if="adjustModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl">
      <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div>
          <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Sesuaikan Posisi Foto</h3>
          <p class="text-sm text-gray-500 font-medium">Klik dan geser foto untuk mengatur tampilan ke user</p>
        </div>
        <button @click="closeAdjustModal" class="p-2 hover:bg-gray-200 rounded-full transition-colors text-gray-500">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <div class="p-8 flex flex-col items-center">
        <!-- FRAME SIMULASI USER (4:3) -->
        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Simulasi Tampilan Website</div>
        <div 
          class="relative w-full aspect-[4/3] max-w-[500px] border-[6px] border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden cursor-move touch-none"
          :style="{ backgroundColor: adjustData.category_id === 1 ? '#78ccf9' : '#99f6c4' }"
          @pointerdown="startDragging"
        >
            <img 
              ref="adjustImgRef"
              :src="adjustData.image" 
              class="w-full h-full object-cover pointer-events-none select-none transition-transform duration-75"
              :style="{ 
                objectPosition: currentPosition,
                transform: `scale(${zoom})`
              }"
              alt="Preview"
            >
          <!-- CROSSHAIR OVERLAY -->
          <div class="absolute inset-0 pointer-events-none border-2 border-dashed border-white/30 flex items-center justify-center">
            <div class="w-full h-[1px] bg-white/20"></div>
            <div class="h-full w-[1px] bg-white/20 absolute"></div>
          </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-[550px]">
           <div class="flex flex-col gap-2">
             <label class="text-[10px] font-black text-gray-500 uppercase tracking-wider">Perbesar (Zoom)</label>
             <input type="range" v-model="zoom" min="1" max="3" step="0.01" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black">
           </div>
           <div class="flex flex-col gap-2">
             <label class="text-[10px] font-black text-gray-500 uppercase tracking-wider">Posisi Vertikal</label>
             <input type="range" v-model="posY" min="0" max="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black">
           </div>
           <div class="flex flex-col gap-2">
             <label class="text-[10px] font-black text-gray-500 uppercase tracking-wider">Posisi Horizontal</label>
             <input type="range" v-model="posX" min="0" max="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black">
           </div>
        </div>
        
        <div class="mt-6 flex gap-4 text-[10px] font-bold text-gray-400 uppercase italic">
          <span>Zoom: {{ Math.round(zoom * 100) }}%</span>
          <span>Posisi: {{ posX }}% , {{ posY }}%</span>
        </div>
      </div>

      <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
        <button 
          @click="savePosition" 
          :disabled="loading"
          class="flex-1 bg-black text-white font-black py-4 rounded-xl hover:bg-gray-800 transition-all shadow-lg active:scale-95 disabled:bg-gray-400 uppercase tracking-wider"
        >
          {{ loading ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
        <button 
          @click="closeAdjustModal"
          class="px-8 bg-white text-gray-600 font-bold border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition-all uppercase tracking-wider text-sm"
        >
          Batal
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import AdminSidebar from "@/Layouts/AdminSidebar.vue";

const isSidebarOpen = ref(false);

const props = defineProps({
  foodsMenus: { type: Array, default: () => [] },
  drinksMenus: { type: Array, default: () => [] },
  tab: { type: String, default: 'foods' },
  foodsSubcategories: { type: Array, default: () => [] },
  drinksSubcategories: { type: Array, default: () => [] }
})

const tab = ref(props.tab || 'foods')
const loading = ref(false)

// Popup notification
const showPopup = ref(false)
const popup = ref('')
const popupType = ref('success')

// Image Adjustment
const adjustModal = ref(false)
const adjustData = ref({ id: null, image: '', category_id: null })
const posX = ref(50)
const posY = ref(50)
const zoom = ref(1)
const isDragging = ref(false)

const currentPosition = computed(() => `${posX.value}% ${posY.value}%`)

const currentTitle = computed(() => {
  if (tab.value === 'foods') return 'Kelola Menu - Foods'
  if (tab.value === 'drinks') return 'Kelola Menu - Drinks'
  return 'Kelola Menu'
})

function notify(msg, type = 'success') {
  popup.value = msg
  popupType.value = type
  showPopup.value = true
  console.log(`[Notification] ${type.toUpperCase()}: ${msg}`)
  // Tambah alert jika user masih tidak melihat popup (opsional, tapi lebih aman)
  // alert(msg) 
  setTimeout(() => (showPopup.value = false), 4000)
}

/* FOODS */
const foodsForm = ref(false)
const editingFoodId = ref(null)
const foods = ref({ name: '', price: null, discount_percent: 0, description: '', subcategory_id: '', image: null })

function openFoodsForm() {
  editingFoodId.value = null
  foods.value = { name: '', price: null, discount_percent: 0, stock: 50, description: '', subcategory_id: '', image_position: 'center', image: null }
  foodsForm.value = true
}

function closeFoodsForm() {
  foodsForm.value = false
  foods.value = { name: '', price: null, description: '', subcategory_id: '', image_position: 'center', image: null }
}

function editFood(food) {
  editingFoodId.value = food.id
  foods.value = {
    name: food.name,
    price: food.price,
    description: food.description,
    subcategory_id: food.subcategory_id,
    image_position: food.image_position || 'center',
    image: null,
    discount_percent: food.discount_percent || 0,
    stock: food.stock || 0
  }
  foodsForm.value = true
}

function handleFoodsImage(e) {
  const file = e.target.files ? e.target.files[0] : null
  if (file && file.size <= 10485760) foods.value.image = file
  else if (file) notify('❌ Gambar terlalu besar! Maks @10MB', 'error')
}

function submitFoods() {
  if (!foods.value.name || !foods.value.price || !foods.value.subcategory_id) {
    notify('Mohon lengkapi data!', 'error')
    return
  }
  loading.value = true
  const data = new FormData()
  data.append('name', foods.value.name)
  data.append('category_id', 1)
  data.append('subcategory_id', foods.value.subcategory_id)
  data.append('price', Number(foods.value.price))
  data.append('description', foods.value.description || '')
  data.append('image_position', foods.value.image_position || 'center')
  data.append('stock', parseInt(foods.value.stock) || 0)
  if (foods.value.discount_percent !== null) data.append('discount_percent', parseInt(foods.value.discount_percent) || 0)
  if (foods.value.image) data.append('image', foods.value.image)
  if (editingFoodId.value) data.append('_method', 'PUT')

  router.post(editingFoodId.value ? `/admin/menu/${editingFoodId.value}` : '/admin/menu/store', data, {
    onSuccess: () => { loading.value = false; notify('Sukses!'); closeFoodsForm(); router.reload() },
    onError: (errors) => { 
      loading.value = false; 
      const firstError = Object.values(errors)[0];
      notify('❌ ' + (firstError || 'Gagal!'), 'error');
    }
  })
}

function deleteFood(id) {
  if (confirm('Hapus?')) router.delete(`/admin/menu/${id}`, { onSuccess: () => router.reload() })
}

function toggleFoodAvailability(food) {
  router.patch(`/admin/menu/${food.id}/toggle-availability`, {}, {
    onSuccess: () => { notify(food.stock > 0 ? '⛔ Menu ditandai Habis' : '✅ Stok Menu diisi 50'); router.reload() },
    onError: () => notify('❌ Gagal mengubah status!', 'error')
  })
}

/* DRINKS */
const drinksForm = ref(false)
const editingDrinkId = ref(null)
const drinks = ref({ name: '', price: null, discount_percent: 0, description: '', subcategory_id: '', image: null })

function openDrinksForm() {
  editingDrinkId.value = null
  drinks.value = { name: '', price: null, discount_percent: 0, stock: 50, description: '', subcategory_id: '', image_position: 'center', image: null }
  drinksForm.value = true
}

function closeDrinksForm() {
  drinksForm.value = false
  drinks.value = { name: '', price: null, description: '', subcategory_id: '', image_position: 'center', image: null }
}

function editDrink(drink) {
  editingDrinkId.value = drink.id
  drinks.value = {
    name: drink.name,
    price: drink.price,
    description: drink.description,
    subcategory_id: drink.subcategory_id,
    image_position: drink.image_position || 'center',
    image: null,
    discount_percent: drink.discount_percent || 0,
    stock: drink.stock || 0
  }
  drinksForm.value = true
}

function handleDrinksImage(e) {
  const file = e.target.files ? e.target.files[0] : null
  if (file && file.size <= 10485760) drinks.value.image = file
  else if (file) notify('❌ Gambar terlalu besar!', 'error')
}

function submitDrinks() {
  loading.value = true
  const data = new FormData()
  data.append('name', drinks.value.name)
  data.append('category_id', 2)
  data.append('subcategory_id', drinks.value.subcategory_id)
  data.append('price', Number(drinks.value.price))
  data.append('description', drinks.value.description || '')
  data.append('image_position', drinks.value.image_position || 'center')
  data.append('stock', parseInt(drinks.value.stock) || 0)
  if (drinks.value.discount_percent !== null) data.append('discount_percent', parseInt(drinks.value.discount_percent) || 0)
  if (drinks.value.image) data.append('image', drinks.value.image)
  if (editingDrinkId.value) data.append('_method', 'PUT')

  router.post(editingDrinkId.value ? `/admin/menu/${editingDrinkId.value}` : '/admin/menu/store', data, {
    onSuccess: () => { loading.value = false; notify('Sukses!'); closeDrinksForm(); router.reload() },
    onError: (errors) => { 
      loading.value = false; 
      const firstError = Object.values(errors)[0];
      notify('❌ ' + (firstError || 'Gagal!'), 'error');
    }
  })
}

function deleteDrink(id) {
  if (confirm('Hapus?')) router.delete(`/admin/menu/${id}`, { onSuccess: () => router.reload() })
}

function toggleDrinkAvailability(drink) {
  router.patch(`/admin/menu/${drink.id}/toggle-availability`, {}, {
    onSuccess: () => { notify(drink.stock > 0 ? '⛔ Menu ditandai Habis' : '✅ Stok Menu diisi 50'); router.reload() },
    onError: () => notify('❌ Gagal mengubah status!', 'error')
  })
}

function logout() { router.post('/logout') }

function resetAllStocks() {
  if (confirm('Yakin ingin meriset SEMUA stok makanan & minuman menjadi 50 sekaligus?')) {
    loading.value = true
    router.post('/admin/menu/reset-all', {}, {
      onSuccess: () => {
        loading.value = false
        notify('✅ Semua menu berhasil diisi ulang menjadi 50!')
        router.reload()
      },
      onError: () => {
        loading.value = false
        notify('❌ Gagal meriset stok!', 'error')
      }
    })
  }
}

/* IMAGE ADJUST LOGIC */
function openAdjustModal(item) {
  if (!item.image) return
  adjustData.value = { id: item.id, image: item.image, category_id: item.category_id }
  if (item.image_position && item.image_position.includes('%')) {
    const parts = item.image_position.split(' ')
    posX.value = parseInt(parts[0]) || 50
    posY.value = parseInt(parts[1]) || 50
  } else {
    const map = { top: 0, bottom: 100, center: 50, left: 0, right: 100 }
    posX.value = map[item.image_position] !== undefined ? map[item.image_position] : 50
    posY.value = map[item.image_position] !== undefined ? map[item.image_position] : 50
    if (item.image_position === 'top') { posX.value = 50; posY.value = 0 }
    if (item.image_position === 'bottom') { posX.value = 50; posY.value = 100 }
  }
  
  // Parse Zoom if exists (stored in a separate way or just default)
  // For now let's just use it as 1, or we could extend the schema.
  // BUT the user wants free movement. Let's keep zoom for now.
  zoom.value = item.image_zoom || 1
  
  adjustModal.value = true
}

function closeAdjustModal() { adjustModal.value = false }

function startDragging(e) {
  isDragging.value = true
  const rect = e.currentTarget.getBoundingClientRect()
  const move = (m) => {
    posX.value = Math.max(0, Math.min(100, Math.round(((m.clientX - rect.left) / rect.width) * 100)))
    posY.value = Math.max(0, Math.min(100, Math.round(((m.clientY - rect.top) / rect.height) * 100)))
  }
  const up = () => { isDragging.value = false; window.removeEventListener('pointermove', move); window.removeEventListener('pointerup', up) }
  window.addEventListener('pointermove', move)
  window.addEventListener('pointerup', up)
}

function savePosition() {
  loading.value = true
  const positionStr = `${posX.value}% ${posY.value}%`
  const menu = [...props.foodsMenus, ...props.drinksMenus].find(m => m.id === adjustData.value.id)
  
  router.put(`/admin/menu/${adjustData.value.id}`, {
    category_id: menu.category_id,
    subcategory_id: menu.subcategory_id,
    name: menu.name,
    price: menu.price,
    description: menu.description || '',
    image_position: positionStr,
    image_zoom: zoom.value,
    stock: menu.stock ?? 0,
    discount_percent: menu.discount_percent ?? 0,
  }, {
    onSuccess: () => { 
      loading.value = false
      notify('✅ Berhasil menyimpan posisi foto!')
      closeAdjustModal() 
    },
    onError: (err) => { 
      loading.value = false
      notify('❌ Gagal menyimpan!', 'error')
      console.error(err)
    }
  })
}
</script>
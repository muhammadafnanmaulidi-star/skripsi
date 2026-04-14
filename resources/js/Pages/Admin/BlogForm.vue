<template>
  <div class="flex h-screen bg-slate-50 font-sans overflow-hidden">
    <!-- SIDEBAR -->
    <AdminSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- CONTENT -->
    <main class="flex-1 min-w-0 overflow-y-auto text-slate-800">
      <!-- HEADER -->
      <header class="bg-white border-b border-slate-200 px-4 md:px-8 py-4 sticky top-0 z-30 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
          <div>
             <h1 class="text-xl md:text-2xl font-bold text-slate-800">{{ isEditing ? 'Edit Blog' : 'Tambah Blog Baru' }}</h1>
             <p class="text-xs md:text-sm text-slate-500 hidden sm:block">{{ isEditing ? 'Perbarui artikel blog yang sudah ada' : 'Buat artikel blog baru untuk website' }}</p>
          </div>
        </div>
        <Link href="/admin/blogs" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          <span class="text-sm font-medium">Kembali ke Daftar</span>
        </Link>
      </header>

      <div class="p-4 md:p-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <form @submit.prevent="submit" class="p-6 md:p-8 space-y-8">
             <!-- Image Upload -->
             <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Gambar Sampul</label>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl text-center hover:border-indigo-500 transition-colors relative cursor-pointer group overflow-hidden bg-slate-50" @click="$refs.fileInput.click()">
                    <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
                    
                    <div v-if="previewImage" class="w-full h-80 relative flex items-center justify-center group-hover:opacity-90 transition">
                         <img :src="previewImage" class="w-full h-full object-cover" />
                         <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white font-bold bg-black/50 px-4 py-2 rounded-full text-sm">Ubah Gambar</span>
                         </div>
                    </div>

                    <div v-else class="py-20">
                        <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 text-slate-400 group-hover:text-indigo-500 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Klik untuk unggah gambar sampul</p>
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, atau WEBP (Maks. 2MB)</p>
                    </div>
                </div>
                <p v-if="form.errors.image" class="text-red-500 text-xs mt-2 font-medium">{{ form.errors.image }}</p>
             </div>

             <!-- Title -->
             <div>
                 <label class="block text-sm font-bold text-slate-700 mb-2">Judul Artikel</label>
                 <input 
                    v-model="form.title" 
                    type="text" 
                    placeholder="Contoh: Kopi yang Cocok untuk Sore Hari" 
                    class="w-full px-4 py-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 border-slate-300 text-lg font-semibold placeholder-slate-400 transition" 
                 />
                 <p v-if="form.errors.title" class="text-red-500 text-xs mt-2 font-medium">{{ form.errors.title }}</p>
             </div>

             <!-- Content (CKEditor) -->
             <div>
                 <label class="block text-sm font-bold text-slate-700 mb-3">Konten Artikel</label>
                 <div class="ck-content-wrapper">
                    <textarea id="editor" ref="contentArea">{{ form.content }}</textarea>
                 </div>
                 <div class="flex items-center gap-2 mt-3 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs font-medium">Editor ini mendukung format visual (B, I, Link, List, dll).</p>
                 </div>
                 <p v-if="form.errors.content" class="text-red-500 text-xs mt-2 font-medium">{{ form.errors.content }}</p>
             </div>
            
            <div class="flex justify-end gap-4 pt-8 border-t border-slate-100 mt-8 relative z-10">
                <Link href="/admin/blogs" class="px-6 py-3 rounded-xl text-slate-600 font-bold hover:bg-slate-100 transition">Batal</Link>
                <button 
                  type="submit" 
                  :disabled="form.processing" 
                  class="px-8 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ isEditing ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}
                </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminSidebar from '@/Layouts/AdminSidebar.vue';

const props = defineProps({
  blog: { type: Object, default: null },
  isEditing: { type: Boolean, default: false }
});

const isSidebarOpen = ref(false);
const previewImage = ref(props.blog?.image || null);
const editorInstance = ref(null);

const form = useForm({
  id: props.blog?.id || null,
  title: props.blog?.title || '',
  content: props.blog?.content || '',
  image: null,
});


// Load CKEditor from CDN
const loadScript = (src) => {
    return new Promise((resolve, reject) => {
        if (window.ClassicEditor) return resolve();
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

onMounted(async () => {
    try {
        await loadScript('https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js');
        const ClassicEditor = window.ClassicEditor;
        
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
            })
            .then(editor => {
                editorInstance.value = editor;
                
                // Set initial data
                editor.setData(form.content);
                
                // Sync data on change
                editor.model.document.on('change:data', () => {
                    form.content = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    } catch (e) {
        console.error("Failed to load CKEditor", e);
    }
});

onBeforeUnmount(() => {
    if (editorInstance.value) {
        try {
            const destroyPromise = editorInstance.value.destroy();
            if (destroyPromise && typeof destroyPromise.catch === 'function') {
                destroyPromise.catch(e => console.log('CKEditor destroy clean-up:', e));
            }
        } catch (e) {
            console.log('CKEditor destroy clean-up:', e);
        }
    }
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    // Safely get content from CKEditor
    try {
        if (editorInstance.value) {
            form.content = editorInstance.value.getData();
        }
    } catch (e) {
        console.warn('CKEditor getData failed, using existing form.content:', e);
    }

    if (props.isEditing) {
        const formData = new FormData();
        formData.append('title', form.title);
        formData.append('content', form.content);
        
        if (form.image) {
            formData.append('image', form.image);
        }

        const blogId = form.id || props.blog?.id;
        
        router.post(`/admin/blogs/${blogId}`, formData, {
            forceFormData: true,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    } else {
        form.post('/admin/blogs', {
            forceFormData: true
        });
    }
};
</script>

<style>
.ck-editor__editable {
    min-height: 400px;
    border-radius: 0 0 12px 12px !important;
}
.ck-editor__top {
    border-radius: 12px 12px 0 0 !important;
    overflow: hidden;
}

/* Fix CKEditor body-wrapper blocking clicks on elements outside the editor */
.ck-body-wrapper {
    pointer-events: none !important;
}
.ck-body-wrapper .ck-balloon-panel,
.ck-body-wrapper .ck-toolbar {
    pointer-events: auto !important;
}

/* Ensure button area is above any CKEditor overlays */
.ck-content-wrapper {
    position: relative;
    z-index: 1;
}
</style>

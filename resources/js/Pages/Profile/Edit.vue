<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const memberStatus = computed(() => page.props.auth.member_status);

const applyMemberForm = useForm({});

const applyMember = () => {
    applyMemberForm.post(route('member.apply'), {
        preserveScroll: true
    });
};

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Profil</h1>
            <p class="text-xs md:text-sm text-slate-500">Kelola informasi akun dan keamanan Anda</p>
        </template>

        <div class="max-w-7xl mx-auto space-y-8">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
                <div class="max-w-xl">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
                <div class="max-w-xl">
                    <UpdatePasswordForm />
                </div>
            </div>

            <div class="bg-rose-50 p-6 md:p-8 rounded-2xl shadow-sm border border-rose-100 transition-all hover:shadow-md">
                <div class="max-w-xl text-rose-800">
                    <DeleteUserForm />
                </div>
            </div>

            <!-- MEMBERSHIP CARD -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 md:p-8 rounded-2xl shadow-sm border border-indigo-100 transition-all hover:shadow-md">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-black text-indigo-900">Program Membership</h2>
                            <p class="mt-1 text-sm text-indigo-600 font-medium">
                                Bergabung menjadi member untuk menikmati berbagai keuntungan eksklusif dan diskon spesial.
                            </p>
                        </header>

                        <div class="mt-6">
                            <div v-if="memberStatus === 'approved'" class="bg-white p-5 rounded-2xl border border-emerald-100 flex flex-col sm:flex-row items-center gap-4 group hover:border-emerald-200 transition-all shadow-sm">
                                <div class="w-14 h-14 bg-emerald-500 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg shadow-emerald-100 group-hover:scale-110 transition-transform">
                                    ✓
                                </div>
                                <div class="text-center sm:text-left">
                                    <h3 class="font-black text-slate-800 tracking-tight text-lg">Status: Member Aktif</h3>
                                    <p class="text-xs text-emerald-600 font-bold uppercase tracking-widest mt-1">Gunakan diskon di menu pesanan!</p>
                                    <p class="text-[10px] text-slate-400 mt-2 italic font-medium">Klik menu Promo & Loyalty untuk melihat poin stempel kopi gratis Anda.</p>
                                </div>
                            </div>
                            
                            <div v-else-if="memberStatus === 'pending'" class="bg-white p-5 rounded-2xl border border-amber-100 flex flex-col sm:flex-row items-center gap-4 shadow-sm transition-all animate-pulse">
                                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center font-black text-2xl">
                                    ⏳
                                </div>
                                <div class="text-center sm:text-left">
                                    <h3 class="font-black text-slate-800 tracking-tight text-lg">Status: Menunggu Persetujuan</h3>
                                    <p class="text-xs text-amber-600 font-bold uppercase tracking-widest mt-1">Mohon tunggu, admin sedang memverifikasi profil Anda.</p>
                                </div>
                            </div>
                            
                            <div v-else-if="!user?.phone_number" class="bg-rose-50 p-4 rounded-xl border border-rose-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center font-black">!</div>
                                <p class="text-xs text-rose-700 font-bold leading-relaxed">Peringatan: Isi Nomor Handphone di atas untuk mendaftar Member.</p>
                            </div>

                            <div v-else>
                                <form @submit.prevent="applyMember" class="flex flex-col gap-4">
                                    <div class="bg-indigo-600/5 p-4 rounded-xl border border-indigo-100 border-dashed">
                                        <ul class="text-[10px] md:text-xs text-indigo-700 font-medium space-y-2">
                                            <li class="flex items-center gap-2">✨ Akses diskon khusus member untuk semua menu</li>
                                            <li class="flex items-center gap-2">🎁 Kumpulkan stamp untuk kopi GRATIS ke-10</li>
                                        </ul>
                                    </div>
                                    <button 
                                        type="submit" 
                                        :disabled="applyMemberForm.processing"
                                        class="w-full bg-indigo-600 text-white px-6 py-4 rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 active:scale-95 disabled:opacity-50 text-xs"
                                    >
                                        {{ applyMemberForm.processing ? 'Mengirim Pengajuan...' : 'Ajukan Membership Sekarang' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

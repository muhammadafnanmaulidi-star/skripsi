<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const filterNumeric = (e) => {
    const val = e.target.value.replace(/\D/g, '');
    e.target.value = val;
    form.phone_number = val;
};

const filterAlpha = (e) => {
    const val = e.target.value.replace(/[0-9]/g, '');
    e.target.value = val;
    form.name = val;
};
</script>

<template>
  <div class="min-h-screen bg-stone-50 flex flex-col items-center justify-center px-4 py-12">
    <Head title="Register" />
    
    <div class="login-logo-wrapper mb-10">
      <Link href="/">
        <img :src="'/logoutara.png'" alt="Utara" class="h-16 md:h-20 w-auto" />
      </Link>
    </div>

    <!-- Register Box -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 relative">
      <!-- Back Link -->
      <Link 
        href="/login" 
        class="absolute top-6 left-6 flex items-center gap-1.5 text-sm font-semibold text-gray-400 hover:text-emerald-600 transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Login
      </Link>

      <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center mt-4">Create Account</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <!-- Name -->
        <div>
          <label for="name" class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
          <input
            id="name"
            type="text"
            v-model="form.name"
            @input="filterAlpha"
            required
            maxlength="50"
            autofocus
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.name" class="text-red-600 mt-1 text-sm">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            maxlength="50"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.email" class="text-red-600 mt-1 text-sm">{{ form.errors.email }}</p>
        </div>

        <!-- Phone Number -->
        <div>
          <label for="phone_number" class="block text-gray-700 font-semibold mb-1">No. WhatsApp</label>
          <input
            id="phone_number"
            type="text"
            v-model="form.phone_number"
            @input="filterNumeric"
            required
            maxlength="20"
            placeholder="08xxxxxxxxxx"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.phone_number" class="text-red-600 mt-1 text-sm">{{ form.errors.phone_number }}</p>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            required
            maxlength="50"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.password" class="text-red-600 mt-1 text-sm">{{ form.errors.password }}</p>
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-gray-700 font-semibold mb-1">Konfirmasi Password</label>
          <input
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            required
            maxlength="50"
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.password_confirmation" class="text-red-600 mt-1 text-sm">{{ form.errors.password_confirmation }}</p>
        </div>

        <div class="pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg py-3 transition disabled:opacity-50"
          >
            Register Now
          </button>
        </div>

        <div class="text-center text-sm text-gray-600">
          Already have an account? 
          <Link :href="route('login')" class="text-emerald-600 font-bold hover:underline">Log in</Link>
        </div>
      </form>
    </div>

    <p class="mt-8 text-gray-600 text-center text-sm">
      &copy; 2025 Utara Coffee. All rights reserved.
    </p>
  </div>
</template>

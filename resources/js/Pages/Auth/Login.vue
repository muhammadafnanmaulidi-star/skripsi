<script setup>
import { Link, Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <div class="min-h-screen bg-stone-50 flex flex-col items-center justify-center px-4">
    <Head title="Log in" />
    <div class="login-logo-wrapper mb-10">
      <Link href="/">
        <img :src="'/logoutara.png'" alt="Utara" class="h-16 md:h-20 w-auto" />
      </Link>
    </div>

    <!-- Login Box -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 relative">
      <!-- Back Link -->
      <Link 
        href="/" 
        class="absolute top-6 left-6 flex items-center gap-1.5 text-sm font-semibold text-gray-400 hover:text-emerald-600 transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back
      </Link>

      <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Welcome Back</h2>

      <div v-if="form.errors.email || form.errors.password" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-600 text-center font-bold">
          {{ form.errors.email || form.errors.password || 'Login gagal, periksa kembali akun Anda.' }}
        </p>
      </div>
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.email" class="text-red-600 mt-1 text-sm">{{ form.errors.email }}</p>
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            required
            autocomplete="current-password"
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <p v-if="form.errors.password" class="text-red-600 mt-1 text-sm">{{ form.errors.password }}</p>
        </div>

        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-gray-700">
            <input type="checkbox" v-model="form.remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" />
            Remember me
          </label>
          <Link href="#" class="text-sm text-emerald-600 hover:text-emerald-800">Forgot Password?</Link>
        </div>

        <div class="flex gap-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg py-3 transition disabled:opacity-50"
          >
            Log in
          </button>
          <Link
            :href="route('register')"
            class="flex-1 bg-white border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-lg py-3 transition text-center"
          >
            Register
          </Link>
        </div>
      </form>
    </div>

    <p class="mt-8 text-gray-600 text-center text-sm">
      &copy; 2025 Utara Coffee. All rights reserved.
    </p>
  </div>
</template>

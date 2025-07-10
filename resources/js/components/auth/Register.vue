<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-primaryDark via-primaryLight to-white p-6">
    <div class="bg-white rounded-2xl shadow-lg flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
      <div class="w-full md:w-1/2 p-8">
        <h2 class="text-3xl font-bold text-center mb-6 text-primaryDark">Buat Akun ITQOM</h2>

        <div v-if="errors.length > 0" class="bg-red-100 text-red-800 px-4 py-2 rounded-lg text-sm mb-4">
          <ul class="list-disc pl-4">
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
        </div>

        <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm mb-4">
          {{ successMessage }}
        </div>

        <form @submit.prevent="submitForm" class="space-y-5">
          <div>
            <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Full Name</label>
            <input v-model="form.name" type="text" id="name" required
              class="w-full px-4 py-2 border border-primaryDark rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <div>
            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email Address</label>
            <input v-model="form.email" type="email" id="email" required
              class="w-full px-4 py-2 border border-primaryDark rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <div>
            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
            <input v-model="form.password" type="password" id="password" required
              class="w-full px-4 py-2 border border-primaryDark rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <div>
            <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" id="password_confirmation" required
              class="w-full px-4 py-2 border border-primaryDark rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <button type="submit"
            class="w-full bg-primaryDark hover:bg-primaryLight text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            Register
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          Already have an account?
          <router-link to="/login" class="text-primaryDark hover:underline">Login here</router-link>
        </p>
      </div>

      <div class="hidden md:flex w-full md:w-1/2 bg-primaryLight items-center justify-center">
        <div class="text-center">
          <dotlottie-player
            src="https://lottie.host/75527203-b54b-4fb8-b563-ceb48f97aa81/XwGMfC6z0x.lottie"
            background="transparent"
            speed="1"
            style="width: 350px; height: 350px"
            loop
            autoplay></dotlottie-player>
          <p class="mt-4 text-primaryDark font-semibold">Welcome to Our Community!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: { name: '', email: '', password: '', password_confirmation: '' },
      errors: [],
      successMessage: ''
    };
  },
  methods: {
    async submitForm() {
      this.errors = [];
      try {
        const response = await axios.post('http://localhost:8000/api/register', this.form);
        this.successMessage = 'Registration successful!';
        setTimeout(() => this.$router.push('/login'), 2000);
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = Object.values(error.response.data.errors).flat();
        } else {
          this.errors = ['Registration failed'];
        }
      }
    }
  }
};
</script>

<style scoped>
</style>

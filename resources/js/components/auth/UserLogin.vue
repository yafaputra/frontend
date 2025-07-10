<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-primaryLight via-white to-primaryDark p-6">
    <div class="bg-white rounded-xl shadow-2xl flex w-full max-w-3xl overflow-hidden">
      <div class="hidden md:flex w-1/2 bg-primaryDark items-center justify-center">
        <div class="text-center">
          <dotlottie-player
            src="https://lottie.host/da3cda18-274e-4907-b0c7-710b6d159bf2/I88cIwlTEp.lottie"
            background="transparent"
            speed="1"
            style="width: 450px; height: 450px"
            loop
            autoplay></dotlottie-player>
          <p class="mt-4 text-white font-semibold">Welcome Back!</p>
        </div>
      </div>

      <div class="w-full md:w-1/2 p-10">
        <h2 class="text-3xl font-bold text-center mb-6 text-primaryDark">Login to Your Account</h2>

        <form @submit.prevent="submitForm" class="space-y-5">
          <div>
            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email Address</label>
            <input v-model="form.email" type="email" id="email" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <div>
            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
            <input v-model="form.password" type="password" id="password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaryDark focus:outline-none">
          </div>

          <button type="submit"
            class="w-full bg-primaryDark hover:bg-primaryLight text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            Login
          </button>

          <div v-if="errorMessage" class="mt-4 text-red-600 text-sm text-center">
            {{ errorMessage }}
          </div>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          Don't have an account?
          <router-link to="/register" class="text-primaryDark hover:underline">Register here</router-link>
        </p>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: { email: '', password: '' },
      errorMessage: ''
    };
  },
  methods: {
    async submitForm() {
      this.errorMessage = '';
      try {
        const response = await axios.post('http://localhost:8000/api/login', this.form);

        // Simpan token dan data user
        localStorage.setItem('authToken', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user)); // Simpan objek user

        // Dispatch event custom untuk memberitahu Navbar bahwa localStorage telah diupdate
        window.dispatchEvent(new Event('localStorageUpdated'));

        this.$router.push('/dashboard');
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Login failed';
      }
    }
  }
};
</script>

<style scoped>
/* Tambahkan custom style jika diperlukan */
</style>

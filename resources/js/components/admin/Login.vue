<template>
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <div v-if="loginError" class="alert alert-danger">
          {{ loginError }}
        </div>
        <form @submit.prevent="login">
          <div class="input-group mb-3">
            <input v-model="email" type="email" class="form-control" placeholder="Email" />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input v-model="password" type="password" class="form-control" placeholder="Password" />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" />
                <label for="remember">Remember Me</label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

        <p class="mb-1">
          <a href="#">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="#" class="text-center">Register a new membership</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
// Impor axios
import axios from 'axios';


export default {
  data() {
    return {
      email: '',
      password: '',
      // Tambahkan state untuk menangani error
      loginError: ''
    };
  },
  methods: {
    async login() {
      this.loginError = '';
      if (!this.email || !this.password) {
        this.loginError = "Email dan password tidak boleh kosong.";
        return;
      }
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password
        });
        const token = response.data.token;
        localStorage.setItem('authToken', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        this.$router.push('/dashboard');
      } catch (error) {
        console.error('Login gagal:', error.response?.data);
        this.loginError = 'Email atau password yang Anda masukkan salah.';
      }
    }
  }
}
</script>
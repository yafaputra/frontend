import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Navbar from './components/Navbar.vue';
import HomePage from './components/HomePage.vue';
import FooterBar from './components/Footer.vue';
import Produk from './components/Produk.vue';
import Tentang from './components/Tentang.vue';

const app = createApp(App); // 
app.component('navbar', Navbar);
app.component('home-page', HomePage);
app.component('footer-bar', FooterBar);
app.component('produk', Produk);
app.component('tentang', Tentang);
app.use(router);
app.mount('#app');

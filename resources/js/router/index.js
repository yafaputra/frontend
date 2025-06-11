import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/HomePage.vue';
import Produk from '../components/Produk.vue';
import Tentang from '../components/Tentang.vue';

const routes = [
  { path: '/', component: HomePage },
  { path: '/produk', component: Produk },
  { path: '/tentang', component: Tentang },
  // Tambah jika ada lainnya
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

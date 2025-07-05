import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/homepage/HomePage.vue';
import Course from '../components/course/Course.vue';
import Course_Description from '../components/course/Course_Description.vue';
import learnmore from '../components/learnmore/learnmore.vue';
import Profil_Pengguna from '../components/user/Profil_Pengguna.vue';
import Dashboard from '../components/user/Dashboard.vue';
import Reward from '../components/user/Reward.vue';
import About from '../components/About.vue';
import Login from '../components/admin/Login.vue';




const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/Course',name: Course , component: Course},
  { path: '/Course_Description', name: Course_Description, component: Course_Description },
  { path: '/learnmore', name: learnmore, component: learnmore },
  { path  : '/Profil_Pengguna', name: Profil_Pengguna, component: Profil_Pengguna },
  { path: '/Dashboard', name: Dashboard, component: Dashboard },
  { path: '/Reward', name: Reward, component: Reward },
  { path: '/About', name: About, component: About },
  {
    path: '/admin/Login',
    name: 'AdminLogin',
    component: () => import('../components/admin/Login.vue')
  }
  // Tambah rute lainnya di sini

  // Tambah jika ada lainnya
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

import { createRouter, createWebHistory } from 'vue-router';

import HomePage from '../components/homepage/HomePage.vue';
import Course from '../components/course/Course.vue';
import Course_Description from '../components/course/Course_Description.vue';
import LearnMore from '../components/learnmore/learnmore.vue';
import Profil_Pengguna from '../components/user/Profil_Pengguna.vue';
import Dashboard from '../components/user/Dashboard.vue';
import Reward from '../components/user/Reward.vue';
import About from '../components/About.vue';

import AdminLogin from '../components/admin/Login.vue'; // Ganti nama alias agar tidak konflik
import UserLogin from '../components/auth/UserLogin.vue';
import Register from '../components/auth/Register.vue';

const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/Course', name: 'Course', component: Course },
  { path: '/Course_Description', name: 'CourseDescription', component: Course_Description },
  { path: '/learnmore', name: 'LearnMore', component: LearnMore },
  { path: '/Profil_Pengguna', name: 'ProfilPengguna', component: Profil_Pengguna },
  { path: '/Dashboard', name: 'Dashboard', component: Dashboard },
  { path: '/Reward', name: 'Reward', component: Reward },
  { path: '/About', name: 'About', component: About },

  // Rute User Login dan Register
  { path: '/login', name: 'UserLogin', component: UserLogin ,     meta: { hideLayout: true } // Tambahkan ini
},
  { path: '/register', name: 'Register', component: Register,      meta: { hideLayout: true } // Tambahkan ini
 },

  // Rute Admin Login
  { path: '/admin/login', name: 'AdminLogin', component: AdminLogin },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

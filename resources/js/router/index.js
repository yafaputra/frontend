import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/homepage/HomePage.vue';
import Mentoring from '../components/Mentoring.vue';
import Course from '../components/Course.vue';
import Event from '../components/event/Event.vue';
import Event_Description from '../components/event/Event_Description.vue';
import Profil_consultant from '../components/mentoring/Profil_consultant.vue';
import Mentoring_Mendaftar from '../components/mentoring/Mentoring_Mendaftar.vue';
import Course_Description from '../components/course/Course_Description.vue';
import learnmore from '../components/learnmore/learnmore.vue';
import Profil_Pengguna from '../components/user/Profil_Pengguna.vue';
import Dashboard from '../components/user/Dashboard.vue';
import Reward from '../components/user/Reward.vue';
import Tanya_Mentor from '../components/user/Tanyamentor.vue';

const routes = [
  { path: '/',name: HomePage ,component: HomePage },
  { path: '/Mentoring',name: Mentoring , component: Mentoring},
  { path: '/Course',name: Course , component: Course},
  { path: '/Event',name: Event , component: Event},
  { path: '/Event_Description', name: Event_Description, component: Event_Description },
  { path: '/Profil_consultant', name: Profil_consultant, component: Profil_consultant },
  { path: '/Mentoring_Mendaftar', name: Mentoring_Mendaftar, component: Mentoring_Mendaftar },
  { path: '/Course_Description', name: Course_Description, component: Course_Description },
  { path: '/learnmore', name: learnmore, component: learnmore },
  { path  : '/Profil_Pengguna', name: Profil_Pengguna, component: Profil_Pengguna },
  { path: '/Dashboard', name: Dashboard, component: Dashboard },
  { path: '/Reward', name: Reward, component: Reward },
  { path: '/Tanya_Mentor', name: Tanya_Mentor, component: Tanya_Mentor },
  // Tambah rute lainnya di sini

  // Tambah jika ada lainnya
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

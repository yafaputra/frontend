import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/homepage/HomePage.vue';
import Mentoring from '../components/Mentoring.vue';
import Course from '../components/Course.vue';
import Event from '../components/event/Event.vue';

const routes = [
  { path: '/',name: HomePage ,component: HomePage },
  { path: '/Mentoring',name: Mentoring , component: Mentoring},
  { path: '/Course',name: Course , component: Course},
  { path: '/Event',name: Event , component: Event},
  // Tambah jika ada lainnya
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

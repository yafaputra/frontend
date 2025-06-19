import { createApp } from 'vue';
import App from './app.vue';
import 'swiper/css/bundle';
import Swiper from 'swiper/bundle';
import router from './router';
import Navbar from './components/Navbar.vue';
import HomePage from './components/homepage/HomePage.vue';
import FooterBar from './components/Footer.vue';
import Mentoring from './components/Mentoring.vue';
import Course from './components/Course.vue';
import Event from './components/event/Event.vue';

const app = createApp(App); // 
app.component('navbar', Navbar);
app.component('home-page', HomePage);
app.component('footer-bar', FooterBar);
app.component('mentoring', Mentoring);
app.component('course', Course);
app.component('event', Event);
app.config.globalProperties.$eventBus = app
app.use(router);
app.mount('#app');

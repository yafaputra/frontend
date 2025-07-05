import { createApp } from 'vue';
import App from './app.vue';
import 'swiper/css/bundle';
import Swiper from 'swiper/bundle';
import router from './router';
import Navbar from './components/Navbar.vue';
import HomePage from './components/homepage/HomePage.vue';
import FooterBar from './components/Footer.vue';
import Course from './components/course/Course.vue';
import About from './components/About.vue';

const app = createApp(App); // 
app.component('navbar', Navbar);
app.component('home-page', HomePage);
app.component('footer-bar', FooterBar);
app.component('course', Course);
app.component('about', About);
app.config.globalProperties.$eventBus = app
app.use(router);
app.mount('#app');

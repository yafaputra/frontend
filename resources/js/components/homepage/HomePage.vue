<template>
  <div class="homepage-container">
    <!-- Page 1 - Hero Section -->
    <section class="homepage" id="home" data-aos="fade-up">
      <div class="homepage-text">
        <h1>Develop your skills in a new and unique way</h1>
        <p>Discover the best coding course for your kids. Learn Coding from basic</p>
      </div>
      <div class="homepage-img md:w-1/2 flex justify-center">
        <img class="homepage-img" src="/public/image/homepage-img.png" alt="HomePage">
      </div>
    </section>

    <!-- Page 2 - Partners Section -->
    <div class="partners-section text-center mt-8">
      <p class="text-gray-700 text-lg font-medium mb-6">Dunia Coding telah bekerja sama dengan</p>
      <div class="flex flex-wrap justify-center items-center gap-6 mb-4">
        <img src="/public/image/maze.png" alt="Maze" class="h-8 md:h-10 max-w-[100px] object-contain">
        <img src="/public/image/dropbox.png" alt="Dropbox" class="h-8 md:h-10 max-w-[100px] object-contain">
        <img src="/public/image/webflow.png" alt="WebFlow" class="h-8 md:h-10 max-w-[100px] object-contain">
      </div>

      <p class="text-gray-700 text-base">Dan 100+ Tech Company Lainnya</p>
    </div>

    <section class="masking bg-white min-h-screen flex items-center justify-center" id="masking" data-aos="fade-up">
      <div class="container mx-auto px-4 flex flex-col-reverse lg:flex-row items-center justify-between gap-12">
        <!-- Text Section -->
        <div class="w-full lg:w-1/2 text-center lg:text-left">
          <h1 class="text-3xl md:text-5xl font-bold mb-6">Exclusive Mentoring</h1>
          <p class="text-gray-700 text-base md:text-lg leading-relaxed">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
            laborum.
          </p>
        </div>
        <!-- Image Section -->
        <div class="w-full lg:w-1/2 flex justify-center">
          <img src="/public/image/masking.png" alt="Masking" class="max-w-full h-auto lg:max-w-[80%]">
        </div>
      </div>
    </section>

    <!-- Page 3 - Online Course Section -->
    <section class="event-section" data-aos="fade-up">
      <div class="left-content">
        <button class="badge">Our Product</button>
        <h2 class="title">Online <span class="highlight">Course</span></h2>
        <p class="description">
          Dapatkan fleksibilitas penuh dengan video tutorial dan e-book yang bisa diakses kapanpun, lengkap dan selalu
          relevan dengan industri.
        </p>
        <div class="features">
          <ul>
            <li>✔ Flexible Access</li>
            <li>✔ E-Book</li>
            <li>✔ File Project</li>
          </ul>
          <ul>
            <li>✔ E-Certificate</li>
            <li>✔ Exclusive Community</li>
            <li>✔ Free Consultation</li>
          </ul>
        </div>

        <button class="explore-btn" @click="navigateToCourse">Explore All Course</button>
      </div>

      <!-- Image Slider - Menggunakan data dari database -->
      <div class="md:w-1/2 flex justify-center mt-12">
        <div class="swiper mySwiper w-full max-w-md md:max-w-lg">
          <div class="swiper-wrapper">
            <div v-for="(slide, index) in courseSlides" :key="index" class="swiper-slide">
              <img :src="slide.image" :alt="slide.alt" class="rounded-xl w-full h-auto object-cover">
            </div>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </section>

    <!-- Best Course Choices Section - Menggunakan data dari database -->
    <section class="Best-Course-Choices p-10" data-aos="fade-up">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Best Course Choices</h2>
        <a href="/course" class="text-purple-600 font-semibold hover:underline">Lihat Selengkapnya</a>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-8">
        <p class="text-gray-500">Loading courses...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-8">
        <p class="text-red-500">{{ error }}</p>
        <button @click="fetchCourses" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
          Try Again
        </button>
      </div>

      <!-- Course Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <router-link
          v-for="course in bestCourses"
          :key="course.id"
          :to="`/Course_Description/${course.id}`"
          class="block"
        >
          <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4 h-full flex flex-col">
            <img :src="course.image" :alt="course.title" class="rounded-xl mb-3 object-cover w-full aspect-video">
            <span v-if="course.daysLeft" class="text-red-500 text-sm font-semibold">{{ course.daysLeft }}</span>
            <h3 class="font-bold mt-1 mb-2">{{ course.title }}</h3>
            <p v-if="course.instructor" class="text-sm text-gray-600 mb-1">Instruktur: {{ course.instructor }}</p>
            <p v-if="course.duration" class="text-sm text-gray-600 mb-1">{{ course.duration }}</p>
            <p v-if="course.category" class="text-sm text-blue-600 mb-1">{{ course.category }}</p>
            <div class="mt-auto">
              <p v-if="course.original" class="text-sm text-red-500 line-through">Rp. {{ course.original }}</p>
              <span class="text-green-600 font-semibold">
                {{ course.price === 'Gratis' ? 'Gratis' : `Rp. ${course.price}` }}
              </span>
            </div>
          </div>
        </router-link>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Homepage',
  data() {
    return {
      loading: false,
      error: null,
      courses: [], // Data dari database
      courseSlides: [
        { image: '/image/buildwebsite.png', alt: 'Build Website Course' },
        { image: '/image/creative.png', alt: 'Creative Course' },
        { image: '/image/learning-illustration.png', alt: 'Learning Illustration' },
      ],
      // Data statis sebagai fallback jika database kosong
      fallbackCourses: [
        {
          id: 1,
          title: 'Full Stack Mobile Development',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis',
          instructor: 'John Doe',
          duration: '8 minggu',
          category: 'Fullstack Development'
        },
        {
          id: 2,
          title: 'Complete Flutter Development',
          subtitle: 'Build Event Apps',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis',
          instructor: 'Jane Smith',
          duration: '6 minggu',
          category: 'Mobile Development'
        },
        {
          id: 3,
          title: 'Fundamental ReactJS untuk pemula',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis',
          instructor: 'Mike Johnson',
          duration: '4 minggu',
          category: 'Web Programming'
        },
        {
          id: 4,
          title: 'Membangun Aplikasi Dating Real-Time dengan Flutter & Firebase',
          image: '/image/devfest-stockholm.png',
          daysLeft: '4 Hari Lagi',
          price: 'Gratis',
          instructor: 'Sarah Wilson',
          duration: '10 minggu',
          category: 'Mobile Development'
        }
      ]
    }
  },
  computed: {
    bestCourses() {
      // Ambil 4 course terbaik dari database atau fallback
      const coursesToShow = this.courses.length > 0 ? this.courses : this.fallbackCourses;
      return coursesToShow.slice(0, 4);
    }
  },
  methods: {
    async fetchCourses() {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.get('http://localhost:8000/api/courses');
        this.courses = response.data;

        // Update course slides jika ada data gambar dari database
        if (this.courses.length > 0) {
          this.courseSlides = this.courses.slice(0, 3).map(course => ({
            image: course.image,
            alt: course.title
          }));
        }
      } catch (error) {
        console.error('Gagal mengambil data kursus:', error);
        this.error = 'Gagal memuat data kursus. Silakan coba lagi.';

        // Gunakan data fallback jika terjadi error
        this.courses = this.fallbackCourses;
      } finally {
        this.loading = false;
      }
    },

    navigateToCourse() {
      this.$router.push('/course');
    }
  },

  async mounted() {
    // Fetch data dari database saat component dimount
    await this.fetchCourses();

    // Initialize AOS (Animate On Scroll)
    this.$nextTick(() => {
      if (typeof AOS !== 'undefined') {
        AOS.init({
          duration: 1000,
          once: true
        });
      }

      // Initialize Swiper dengan delay untuk memastikan DOM sudah siap
      setTimeout(() => {
        if (typeof Swiper !== 'undefined') {
          new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
              delay: 3000,
              disableOnInteraction: false,
            },
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
              crossFade: true,
            },
          });
        }
      }, 100);
    });
  }
}
</script>

<style lang="scss" src="../../../sass/homepage.scss"></style>

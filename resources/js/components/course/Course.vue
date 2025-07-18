<template>
  <div>
    <!-- Hero Section -->
    <section id="home" class="relative bg-gradient-to-br from-[#564AB1] via-[#6B5CE7] to-[#8B7CF6] text-white pt-26 pb-20 px-4 sm:px-6 overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-xl"></div>
        <div class="absolute bottom-20 right-20 w-24 h-24 bg-white rounded-full blur-xl"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-white rounded-full blur-xl"></div>
      </div>

      <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between relative z-10">
        <!-- Hero Text -->
        <div class="lg:w-1/2 mb-8 lg:mb-0 text-center lg:text-left">
          <div class="inline-block bg-white/20 backdrop-blur-sm px-3 py-2 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium mb-4 sm:mb-6">
            🚀 Platform Pembelajaran Terdepan
          </div>
          <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 sm:mb-6 leading-tight">
            Buka
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">
              Potensimu!
            </span>
          </h1>
          <p class="mb-6 sm:mb-8 text-base sm:text-lg lg:text-xl text-gray-200 leading-relaxed max-w-lg mx-auto lg:mx-0">
            Telusuri kursus menarik dan interaktif bersama kami. Bergabunglah dengan ribuan siswa yang telah mengembangkan skill mereka.
          </p>
          <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start w-full max-w-md mx-auto lg:mx-0" v-if="!isLoggedIn">
            <router-link to="/register" class="bg-white text-[#564AB1] px-6 py-3 sm:px-8 sm:py-4 rounded-xl sm:rounded-2xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg text-center text-sm sm:text-base">
              Get Started
            </router-link>
            <router-link to="/learnmore" class="border-2 border-white px-6 py-3 sm:px-8 sm:py-4 rounded-xl sm:rounded-2xl font-semibold hover:bg-white hover:text-[#564AB1] transition-all duration-300 transform hover:scale-105 backdrop-blur-sm text-center text-sm sm:text-base">
              Learn More
            </router-link>
          </div>
          <div v-else class="flex justify-center lg:justify-start">
            <button @click="scrollToCourses" class="bg-white text-[#564AB1] px-6 py-3 sm:px-8 sm:py-4 rounded-xl sm:rounded-2xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg text-sm sm:text-base">
              Jelajahi Kursus
            </button>
          </div>
        </div>

        <!-- Hero Image Slider -->
        <div class="lg:w-1/2 flex justify-center w-full">
          <div class="swiper mySwiper w-full max-w-sm sm:max-w-md lg:max-w-lg relative">
            <div class="swiper-wrapper">
              <!-- Slide 1 -->
              <div class="swiper-slide">
                <div class="relative group">
                  <img src="/public/image/buildwebsite.png" alt="Build Website" class="rounded-2xl sm:rounded-3xl w-full h-[250px] sm:h-[300px] object-cover shadow-2xl transform group-hover:scale-105 transition-transform duration-500">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-2xl sm:rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
              </div>
              <!-- Slide 2 -->
              <div class="swiper-slide">
                <div class="relative group">
                  <img src="/public/image/creative.png" alt="Creative Design" class="rounded-2xl sm:rounded-3xl w-full h-[250px] sm:h-[300px] object-cover shadow-2xl transform group-hover:scale-105 transition-transform duration-500">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-2xl sm:rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
              </div>
              <!-- Slide 3 -->
              <div class="swiper-slide">
                <div class="relative group">
                  <img src="/public/image/learning-illustration.png" alt="Learning" class="rounded-2xl sm:rounded-3xl w-full h-[250px] sm:h-[300px] object-cover shadow-2xl transform group-hover:scale-105 transition-transform duration-500">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent rounded-2xl sm:rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
              </div>
            </div>
            <!-- Custom Navigation -->
            <div class="swiper-pagination !bottom-2 sm:!bottom-4"></div>
            <div class="swiper-button-next !text-white !right-2 sm:!right-4 !top-1/2 !w-10 !h-10 sm:!w-12 sm:!h-12 bg-white/20 !rounded-full backdrop-blur-sm hover:bg-white/30 transition-all duration-300"></div>
            <div class="swiper-button-prev !text-white !left-2 sm:!left-4 !top-1/2 !w-10 !h-10 sm:!w-12 sm:!h-12 bg-white/20 !rounded-full backdrop-blur-sm hover:bg-white/30 transition-all duration-300"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Course Section -->
    <section id="courses" ref="coursesSection" class="py-16 sm:py-20 px-4 sm:px-6 bg-gray-50">
      <div class="container mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Jelajahi
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#564AB1] to-[#8B7CF6]">
              Kursus Kami
            </span>
          </h2>
          <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto px-4">
            Pilih dari berbagai kategori kursus yang dirancang untuk mengembangkan skill profesional Anda
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 sm:gap-8">
          <!-- Sidebar Kategori -->
          <div class="lg:col-span-1 mb-6 lg:mb-0">
            <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-lg border border-gray-100 lg:sticky lg:top-6">
              <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6 text-gray-900 flex items-center">
                <span class="bg-gradient-to-r from-[#564AB1] to-[#8B7CF6] w-2 h-6 sm:h-8 rounded-full mr-3"></span>
                Filter Kategori
              </h3>
              <div class="space-y-2 sm:space-y-3">
                <label class="flex items-center p-2 sm:p-3 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 group">
                  <input
                    type="radio"
                    name="kategori"
                    value="Web Programming"
                    v-model="selectedCategory"
                    class="sr-only"
                  />
                  <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-gray-300 group-hover:border-[#564AB1] transition-colors duration-200 flex items-center justify-center mr-3">
                    <div class="w-2 h-2 rounded-full bg-[#564AB1] opacity-0 group-hover:opacity-100 transition-opacity duration-200" :class="{'!opacity-100': selectedCategory === 'Web Programming'}"></div>
                  </div>
                  <span class="text-gray-700 font-medium text-sm sm:text-base">Web Programming</span>
                </label>

                <label class="flex items-center p-2 sm:p-3 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 group">
                  <input
                    type="radio"
                    name="kategori"
                    value="Fullstack Development"
                    v-model="selectedCategory"
                    class="sr-only"
                  />
                  <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-gray-300 group-hover:border-[#564AB1] transition-colors duration-200 flex items-center justify-center mr-3">
                    <div class="w-2 h-2 rounded-full bg-[#564AB1] opacity-0 group-hover:opacity-100 transition-opacity duration-200" :class="{'!opacity-100': selectedCategory === 'Fullstack Development'}"></div>
                  </div>
                  <span class="text-gray-700 font-medium text-sm sm:text-base">Fullstack Development</span>
                </label>

                <label class="flex items-center p-2 sm:p-3 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 group">
                  <input
                    type="radio"
                    name="kategori"
                    value="Backend Development"
                    v-model="selectedCategory"
                    class="sr-only"
                  />
                  <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-gray-300 group-hover:border-[#564AB1] transition-colors duration-200 flex items-center justify-center mr-3">
                    <div class="w-2 h-2 rounded-full bg-[#564AB1] opacity-0 group-hover:opacity-100 transition-opacity duration-200" :class="{'!opacity-100': selectedCategory === 'Backend Development'}"></div>
                  </div>
                  <span class="text-gray-700 font-medium text-sm sm:text-base">Backend Development</span>
                </label>

                <label class="flex items-center p-2 sm:p-3 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 group">
                  <input
                    type="radio"
                    name="kategori"
                    value="UI/UX"
                    v-model="selectedCategory"
                    class="sr-only"
                  />
                  <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-gray-300 group-hover:border-[#564AB1] transition-colors duration-200 flex items-center justify-center mr-3">
                    <div class="w-2 h-2 rounded-full bg-[#564AB1] opacity-0 group-hover:opacity-100 transition-opacity duration-200" :class="{'!opacity-100': selectedCategory === 'UI/UX'}"></div>
                  </div>
                  <span class="text-gray-700 font-medium text-sm sm:text-base">UI/UX Design</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Course Cards -->
          <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
              <router-link
                v-for="(course, index) in filteredCourses"
                :key="course.id"
                :to="`/Course_Description/${course.id}`"
                class="group block bg-white rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-[#564AB1]/20 transform hover:scale-105"
              >
                <div class="relative overflow-hidden">
                  <img
                    :src="course.image"
                    :alt="course.title"
                    class="w-full h-40 sm:h-48 object-cover group-hover:scale-110 transition-transform duration-700"
                  >
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div class="absolute top-3 right-3 sm:top-4 sm:right-4 bg-white/90 backdrop-blur-sm px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs font-semibold text-[#564AB1] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    {{ course.category }}
                  </div>
                </div>

                <div class="p-4 sm:p-6">
                  <h3 class="font-bold text-lg sm:text-xl mb-2 text-gray-900 group-hover:text-[#564AB1] transition-colors duration-300 line-clamp-2">
                    {{ course.title }}
                  </h3>

                  <div class="flex items-center mb-2 text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">{{ course.instructor }}</span>
                  </div>

                  <div class="flex items-center mb-4 text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">{{ course.duration }}</span>
                  </div>

                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-gray-500 line-through">Rp. {{ course.original }}</p>
                      <p class="text-xl sm:text-2xl font-bold text-[#564AB1]">Rp. {{ course.price }}</p>
                    </div>
                  </div>
                </div>
              </router-link>
            </div>

            <!-- Empty State -->
            <div v-if="filteredCourses.length === 0" class="text-center py-12 sm:py-16">
              <div class="text-gray-400 mb-4">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <h3 class="text-lg sm:text-xl font-semibold text-gray-600 mb-2">Tidak ada kursus ditemukan</h3>
              <p class="text-gray-500 text-sm sm:text-base px-4">Coba pilih kategori yang berbeda atau hubungi kami untuk kursus khusus.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';
import Swiper from 'swiper';
import 'swiper/swiper-bundle.css';

export default {
  name: 'Course',
  data() {
    return {
      selectedCategory: '',
      courses: [],
      isLoggedIn: false,
      userName: '',
      avatarUrl: '',
      swiper: null
    };
  },
  computed: {
    filteredCourses() {
      if (!this.selectedCategory) return this.courses;
      return this.courses.filter(course => course.category === this.selectedCategory);
    }
  },
  methods: {
    async fetchCourses() {
      try {
        const response = await axios.get('http://localhost:8000/api/courses');
        this.courses = response.data;
      } catch (error) {
        console.error('Gagal mengambil data kursus:', error);
      }
    },
    checkLoginStatus() {
      const authToken = localStorage.getItem('authToken');
      const userData = localStorage.getItem('user');

      if (authToken && userData) {
        try {
          const user = JSON.parse(userData);
          this.isLoggedIn = true;
          this.userName = user.name;
          if (user.avatar) {
            this.avatarUrl = `/storage/${user.avatar}`;
          }
        } catch (e) {
          console.error("Gagal mengurai data pengguna:", e);
          this.isLoggedIn = false;
          this.userName = '';
        }
      } else {
        this.isLoggedIn = false;
        this.userName = '';
      }
    },
    scrollToCourses() {
      const element = this.$refs.coursesSection;
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
      }
    },
    initSwiper() {
      this.swiper = new Swiper('.mySwiper', {
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
          dynamicBullets: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
          crossFade: true,
        },
        speed: 800,
      });
    }
  },
  mounted() {
    this.fetchCourses();
    this.checkLoginStatus();

    // Tambahkan event listener untuk perubahan pada localStorage
    window.addEventListener('localStorageUpdated', this.checkLoginStatus);

    this.$nextTick(() => {
      setTimeout(() => {
        this.initSwiper();
      }, 100);
    });
  },
  beforeUnmount() {
    // Hapus event listener ketika komponen di-unmount
    window.removeEventListener('localStorageUpdated', this.checkLoginStatus);

    // Destroy Swiper instance
    if (this.swiper) {
      this.swiper.destroy();
      this.swiper = null;
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom Swiper Styles */
.swiper-pagination-bullet {
  background: rgba(255, 255, 255, 0.5) !important;
  opacity: 1 !important;
}

.swiper-pagination-bullet-active {
  background: white !important;
  transform: scale(1.2);
}

.swiper-button-next:after,
.swiper-button-prev:after {
  font-size: 14px !important;
  font-weight: bold;
}

@media (min-width: 640px) {
  .swiper-button-next:after,
  .swiper-button-prev:after {
    font-size: 16px !important;
  }
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}
</style>

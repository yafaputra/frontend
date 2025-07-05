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

      <!-- Image Slider -->
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

    <!-- Best Course Choices Section -->
    <section class="Best-Course-Choices p-10" data-aos="fade-up">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Best Course Choices</h2>
        <a href="/course" class="text-purple-600 font-semibold hover:underline">Lihat Selengkapnya</a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a v-for="course in bestCourses" :key="course.id" href="/course_description" class="block">
          <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-4 h-full flex flex-col">
            <img :src="course.image" :alt="course.title" class="rounded-xl mb-3">
            <span v-if="course.daysLeft" class="text-red-500 text-sm font-semibold">{{ course.daysLeft }}</span>
            <h3 class="font-bold mt-1 mb-2">{{ course.title }}</h3>
            <h4 v-if="course.subtitle" class="font-bold mt-1 mb-2">{{ course.subtitle }}</h4>
            <span class="text-green-600 font-semibold">{{ course.price }}</span>
          </div>
        </a>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'Homepage',
  data() {
    return {
      courseSlides: [
        { image: '/image/buildwebsite.png', alt: 'Build Website Course' },
        { image: '/image/creative.png', alt: 'Creative Course' },
      ],
      bestCourses: [
        {
          id: 1,
          title: 'Full Stack Mobile Development',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis'
        },
        {
          id: 2,
          title: 'Complete Flutter Development :',
          subtitle: 'Build Event Apps',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis'
        },
        {
          id: 3,
          title: 'Fundamental ReactJS untuk pemula',
          image: '/image/devfest-stockholm.png',
          price: 'Gratis'
        },
        {
          id: 4,
          title: 'Membangun Aplikasi Dating Real-Time dengan Flutter & Firebase',
          image: '/image/devfest-stockholm.png',
          daysLeft: '4 Hari Lagi',
          price: 'Gratis'
        }
      ]
    }
  },
  methods: {
    navigateToCourse() {
      // Handle navigation to course page
    }
  },
  mounted() {
    // Initialize AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 1000,
        once: true
      })
    }

    // Initialize Swiper if available
    if (typeof Swiper !== 'undefined') {
      new Swiper('.mySwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      })
    }
  }
}
</script>
<style lang="scss" src="../../../sass/homepage.scss"></style>
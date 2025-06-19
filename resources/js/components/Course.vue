<template>
  <div>
    <!-- Hero Section -->
    <section id="home" class="bg-[#564AB1] text-white py-16 px-6">
      <div class="container mx-auto flex flex-col md:flex-row items-center justify-between">
        <!-- Teks Hero -->
        <div class="md:w-1/2 mb-8 md:mb-0 text-center md:text-left" data-aos="fade-right" data-aos-duration="1000">
          <h2 class="text-4xl font-bold mb-4 leading-tight">Buka Potensimu!</h2>
          <p class="mb-6 text-lg text-gray-300">Telusuri kursus menarik dan interaktif bersama kami.</p>
          <div class="space-x-4">
            <router-link to="/register" class="bg-white text-indigo-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
              Get Started
            </router-link>
            <router-link to="/learnmore" class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-800 transition">
              Learn More
            </router-link>
          </div>
        </div>

        <!-- Slider Gambar -->
        <div class="md:w-1/2 flex justify-center mt-12" data-aos="fade-left" data-aos-duration="1000">
          <div class="swiper mySwiper w-full max-w-md md:max-w-lg">
            <div class="swiper-wrapper">
              <!-- Slide 1 -->
              <div class="swiper-slide">
                <img src="/public/image/buildwebsite.png" alt="Slide 1" class="rounded-xl w-full h-[500px] object-cover">
              </div>
              <!-- Slide 2 -->
              <div class="swiper-slide">
                <img src="/public/image/creative.png" alt="Slide 2" class="rounded-xl w-full h-[500px] object-cover">
              </div>
              <!-- Slide 3 -->
              <div class="swiper-slide">
                <img src="/public/image/learning-illustration.png" alt="Slide 3" class="rounded-xl w-full h-[500px] object-cover">
              </div>
            </div>
            <!-- Navigasi -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Course Section -->
    <section id="class" class="py-16 px-6">
      <h3 id="judulcourse" class="text-2xl font-semibold mb-8 text-center text-black" data-aos="fade-up" data-aos-duration="800">Semua Kursus</h3>
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Kategori -->
        <div class="lg:col-span-1 mb-6 lg:mb-0 bg-white p-4 rounded-xl border border-gray-300 shadow-sm self-start" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
          <h4 class="text-lg font-semibold mb-4 bg-gray-100 p-2 rounded-md text-center">Kategori</h4>
          <ul class="space-y-2 bg-gray-50 p-3 rounded-md border border-gray-200">
            <li class="flex items-center space-x-2">
              <input 
                type="radio" 
                id="web" 
                name="kategori" 
                value="Web Programming" 
                v-model="selectedCategory"
                class="text-purple-600 focus:ring-purple-500" 
              />
              <label for="web" class="text-gray-700">Web Programming</label>
            </li>
            <li class="flex items-center space-x-2">
              <input 
                type="radio" 
                id="fullstack" 
                name="kategori" 
                value="Fullstack Development" 
                v-model="selectedCategory"
                class="text-purple-600 focus:ring-purple-500" 
              />
              <label for="fullstack" class="text-gray-700">Fullstack Development</label>
            </li>
            <li class="flex items-center space-x-2">
              <input 
                type="radio" 
                id="backend" 
                name="kategori" 
                value="Backend Development" 
                v-model="selectedCategory"
                class="text-purple-600 focus:ring-purple-500" 
              />
              <label for="backend" class="text-gray-700">Backend Development</label>
            </li>
            <li class="flex items-center space-x-2">
              <input 
                type="radio" 
                id="uiux" 
                name="kategori" 
                value="UI/UX" 
                v-model="selectedCategory"
                class="text-purple-600 focus:ring-purple-500" 
              />
              <label for="uiux" class="text-gray-700">UI/UX</label>
            </li>
            <li class="flex items-center space-x-2">
              <button 
                @click="clearFilter"
                class="w-full mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm"
              >
                Tampilkan Semua
              </button>
            </li>
          </ul>
        </div>

        <!-- Kartu Kursus -->
        <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <router-link 
            v-for="(course, index) in filteredCourses" 
            :key="course.id"
            :to="`/course_description/${course.id}`"
            class="block bg-white rounded-xl shadow p-4 hover:shadow-lg transition-all duration-300 transform hover:scale-110 active:scale-95"
            :data-aos="'fade-up'"
            :data-aos-duration="800"    
          >
            <img 
              :src="course.image" 
              :alt="course.title" 
              class="rounded-lg mb-3 object-cover w-full aspect-video"
            >
            <h3 class="font-semibold text-lg">{{ course.title }}</h3>
            <p class="text-sm text-gray-600 mb-1">Instruktur: {{ course.instructor }}</p>
            <p class="text-sm text-gray-600 mb-1">{{ course.duration }}</p>
            <p class="text-sm text-red-500 line-through">Rp. {{ course.original }}</p>
            <p class="text-blue-700 font-bold text-lg">Rp. {{ course.price }}</p>
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'Course',
  data() {
    return {
      selectedCategory: '',
      lastChecked: null,
      courses: [
        {
          id: 1,
          title: 'Full Stack Mobile Development',
          instructor: 'John Doe',
          duration: '10 jam 30 menit • 25 video',
          original: '150.000',
          price: '120.000',
          image: '/image/devfest-stockholm.png',
          category: 'Fullstack Development'
        },
        {
          id: 2,
          title: 'Complete Flutter Development',
          instructor: 'Jane Smith',
          duration: '8 jam 45 menit • 20 video',
          original: '200.000',
          price: '180.000',
          image: '/image/devfest-stockholm.png',
          category: 'Fullstack Development'
        },
        {
          id: 3,
          title: 'Fundamental ReactJS untuk pemula',
          instructor: 'Mike Johnson',
          duration: '6 jam 15 menit • 15 video',
          original: '100.000',
          price: '80.000',
          image: '/image/devfest-stockholm.png',
          category: 'Web Programming'
        },
        {
          id: 4,
          title: 'Backend API Development',
          instructor: 'Sarah Wilson',
          duration: '12 jam 20 menit • 30 video',
          original: '180.000',
          price: '150.000',
          image: '/image/devfest-stockholm.png',
          category: 'Backend Development'
        },
        {
          id: 5,
          title: 'UI/UX Design Fundamentals',
          instructor: 'Alex Brown',
          duration: '9 jam 10 menit • 22 video',
          original: '160.000',
          price: '140.000',
          image: '/image/devfest-stockholm.png',
          category: 'UI/UX'
        }
      ]
    }
  },
  computed: {
    filteredCourses() {
      if (!this.selectedCategory) return this.courses;
      return this.courses.filter(course => course.category === this.selectedCategory);
    },
    courseTitle() {
      return this.selectedCategory || 'Semua Kursus';
    }
  },
  methods: {
    clearFilter() {
      this.selectedCategory = '';
      this.lastChecked = null;
    },
    handleCategoryChange(category) {
      // Jika kategori yang sama diklik lagi, hapus centang
      if (this.lastChecked === category) {
        this.selectedCategory = '';
        this.lastChecked = null;
      } else {
        // Jika kategori baru dipilih, simpan sebagai lastChecked
        this.selectedCategory = category;
        this.lastChecked = category;
      }
    }
  },
  mounted() {
    this.$nextTick(() => {
      setTimeout(() => {
        if (typeof Swiper !== 'undefined') {
          new Swiper('.mySwiper', {
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

  <template>
  <div>
    <!-- Hero Section -->
    <section id="home" class="bg-[#564AB1] text-white py-16 px-6">
      <div class="container mx-auto flex flex-col md:flex-row items-center justify-between">
        <!-- Teks Hero -->
        <div class="md:w-1/2 mb-8 md:mb-0 text-center md:text-left">
          <h2 class="text-4xl font-bold mb-4 leading-tight">Buka Potensimu!</h2>
          <p class="mb-6 text-lg text-gray-300">Telusuri kursus menarik dan interaktif bersama kami.</p>
          <div class="space-x-4">
            <a href="/register" class="bg-white text-indigo-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
              Get Started
            </a>
            <a href="/learnmore" class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-800 transition">
              Learn More
            </a>
          </div>
        </div>

        <!-- Slider Gambar -->
        <div class="md:w-1/2 flex justify-center mt-12">
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
      <h3 id="judulcourse" class="text-2xl font-semibold mb-8 text-center text-black">Semua Kursus</h3>
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Kategori -->
        <div class="lg:col-span-1 mb-6 lg:mb-0 bg-white p-4 rounded-xl border border-gray-300 shadow-sm self-start">
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
          </ul>
        </div>

        <!-- Kartu Kursus -->
        <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <router-link 
            v-for="(course, index) in filteredCourses" 
            :key="course.id"
            :to="`/Course_Description/${course.id}`"
            class="block bg-white rounded-xl shadow p-4 hover:shadow-lg transition-all duration-300 transform hover:scale-110 active:scale-95"
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
import axios from 'axios';

export default {
  name: 'Course',
  data() {
    return {
      selectedCategory: '',
      courses: []  // Kosongkan karena akan diisi dari database
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
    }
  },
  mounted() {
    this.fetchCourses(); // Panggil saat component sudah dimount

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

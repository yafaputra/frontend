<template>
  <div class="bg-gray-50 min-h-screen pt-24">
    <!-- Loading State -->
    <div v-if="loading" class="max-w-7xl mx-auto px-4 py-6 text-center">
      <p>Memuat data kursus...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 py-6 text-center">
      <p class="text-red-600">{{ error }}</p>
      <button @click="fetchCourseData" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Coba Lagi
      </button>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-7xl mx-auto px-4 py-6 flex flex-col lg:flex-row gap-8">
      <!-- Bagian Kiri (Detail Kursus) -->
      <div class="flex-1 space-y-6">
        <!-- Header dengan Border -->
        <div class="bg-white border-2 border-gray-300 rounded-lg p-6 shadow-sm">
          <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4 leading-tight">
            {{ courseData.title || 'Judul Tidak Tersedia' }}
          </h1>
          <div v-if="courseData.tag" class="mb-2">
            <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-2 rounded-full border-2 border-blue-300">
              {{ courseData.tag }}
            </span>
          </div>
        </div>

        <!-- Overview Section dengan Border yang Lebih Tegas -->
        <section class="bg-white border-3 border-purple-400 rounded-lg p-8 shadow-md">
          <div class="border-b-2 border-purple-200 pb-3 mb-4">
            <h3 class="text-xl font-bold text-[#5C52A8]">Overview</h3>
          </div>
          <div class="prose prose-lg text-gray-700 leading-relaxed border-l-4 border-purple-300 pl-4"
               v-html="courseData.overview || 'Tidak ada deskripsi'">
          </div>
        </section>
      </div>

      <!-- Bagian Kanan (Sidebar) -->
      <aside class="w-full lg:w-[380px] flex-shrink-0 space-y-6">
        <!-- Card Harga & Beli dengan Border yang Lebih Jelas -->
        <div class="bg-white border-3 border-purple-400 rounded-xl p-6 shadow-md">
          <div class="border-2 border-gray-200 rounded-lg p-2 mb-4">
            <img :src="courseImageUrl"
                 alt="Cover Kursus"
                 class="w-full h-40 object-cover rounded-lg border-2 border-purple-200">
          </div>

          <div class="mb-4 pb-4">
            <div class="text-3xl font-bold text-gray-800 mb-1">
              Rp {{ formatPrice(courseData.price) }}
            </div>
            <div v-if="courseData.price_discount"
                 class="text-lg line-through text-gray-400">
              Rp {{ formatPrice(courseData.price_discount) }}
            </div>
          </div>

          <button @click="buyCourse"
                  :disabled="loading || processingPayment"
                  class="w-full bg-[#5C52A8] hover:bg-[#4a4190] text-white py-3 rounded-lg font-semibold text-base transition-colors duration-200 disabled:opacity-50 shadow-sm border-2 border-[#5C52A8] hover:border-[#4a4190]">
            {{ processingPayment ? 'Memproses Pembayaran...' : loading ? 'Memuat...' : 'Beli Course' }}
          </button>
        </div>

        <!-- Card Instruktur dengan Border -->
        <div class="bg-white border-3 border-purple-400 rounded-xl p-6 shadow-md" v-if="courseData.instructor_name">
          <div class="border-b-2 border-purple-200 pb-3 mb-4">
            <h4 class="font-bold text-lg text-[#5C52A8]">Instruktur</h4>
          </div>
          <div class="flex items-center gap-4 border-l-4 border-purple-300 pl-4">
            <img :src="instructorImageUrl"
                 class="w-16 h-16 rounded-full object-cover border-3 border-[#5C52A8]">
            <div>
              <h4 class="font-bold text-lg text-gray-800">{{ courseData.instructor_name }}</h4>
              <p class="text-sm text-gray-600">
                {{ courseData.instructor_position || 'Pengajar' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Card Fitur Kursus dengan Border yang Lebih Jelas -->
        <div class="bg-white border-3 border-purple-400 rounded-xl p-6 shadow-md">
          <div class="border-b-2 border-purple-200 pb-3 mb-4">
            <h4 class="font-bold text-lg text-[#5C52A8]">This Course Include</h4>
          </div>
          <ul class="space-y-4">
            <li class="flex items-center gap-4 border-l-3 border-purple-200 pl-3 py-2" v-if="courseData.video_count">
              <div class="flex items-center justify-center w-8 h-8 bg-[#5C52A8] bg-opacity-10 rounded-full border-2 border-[#5C52A8] border-opacity-30">
                <i class="fas fa-video text-[#5C52A8] text-sm"></i>
              </div>
              <span class="text-gray-700 font-medium">{{ courseData.video_count }}</span>
            </li>
            <li class="flex items-center gap-4 border-l-3 border-purple-200 pl-3 py-2" v-if="courseData.duration">
              <div class="flex items-center justify-center w-8 h-8 bg-[#5C52A8] bg-opacity-10 rounded-full border-2 border-[#5C52A8] border-opacity-30">
                <i class="fas fa-clock text-[#5C52A8] text-sm"></i>
              </div>
              <span class="text-gray-700 font-medium">{{ courseData.duration }} Jam</span>
            </li>
            <li class="flex items-center gap-4 border-l-3 border-purple-200 pl-3 py-2">
              <div class="flex items-center justify-center w-8 h-8 bg-[#5C52A8] bg-opacity-10 rounded-full border-2 border-[#5C52A8] border-opacity-30">
                <i class="fas fa-file-alt text-[#5C52A8] text-sm"></i>
              </div>
              <span class="text-gray-700 font-medium">File Projek</span>
            </li>
            <li v-for="(feature, index) in courseData.features"
                :key="index"
                class="flex items-center gap-4 border-l-3 border-purple-200 pl-3 py-2">
              <div class="flex items-center justify-center w-8 h-8 bg-[#5C52A8] bg-opacity-10 rounded-full border-2 border-[#5C52A8] border-opacity-30">
                <i class="fas fa-check text-[#5C52A8] text-sm"></i>
              </div>
              <span class="text-gray-700 font-medium">{{ typeof feature === 'string' ? feature : feature.feature_name || 'Fitur' }}</span>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CourseDescription',
  data() {
    return {
      courseData: {
        id: null,
        title: null,
        tag: null,
        overview: null,
        image_url: null,
        thumbnail: null,
        price: 0,
        price_discount: null,
        instructor_name: null,
        instructor_position: null,
        instructor_image_url: null,
        video_count: 0,
        duration: 0,
        features: []
      },
      loading: true,
      error: null,
      processingPayment: false,
      midtransLoaded: false
    };
  },
  computed: {
    courseId() {
      return this.$route.params.id;
    },
    courseImageUrl() {
      const path = this.courseData.image_url;
      if (path) {
        if (path.startsWith('/storage/') || path.startsWith('http')) {
          return path;
        }
        return `/storage/${path}`;
      }
      return '/images/default.jpg';
    },
    instructorImageUrl() {
      const path = this.courseData.instructor_image_url;
      if (path) {
        if (path.startsWith('/storage/') || path.startsWith('http')) {
          return path;
        }
        return `/storage/${path}`;
      }
      return '/images/default-instructor.jpg';
    },
    // Check if user is logged in
    isAuthenticated() {
      return !!localStorage.getItem('token');
    }
  },
  async mounted() {
    console.log('Course ID from route:', this.courseId);
    if (this.courseId) {
      await this.fetchCourseData();
    } else {
      this.error = 'ID kursus tidak ditemukan';
      this.loading = false;
    }
    this.loadMidtransScript();
  },
  methods: {
    formatPrice(value) {
      if (value === null || value === undefined) return '0';
      return new Intl.NumberFormat('id-ID').format(Number(value));
    },

    async fetchCourseData() {
      this.loading = true;
      this.error = null;

      try {
        console.log(`Fetching course data for ID: ${this.courseId}`);

        const response = await axios.get(`http://localhost:8000/api/courses/${this.courseId}`);
        console.log('Course data response:', response.data);

        if (response.status !== 200) {
          throw new Error(`Failed to fetch course data: ${response.status}`);
        }

        this.courseData = {
          id: response.data.id,
          title: response.data.title,
          tag: response.data.tag,
          overview: response.data.overview,
          image_url: response.data.image_url,
          thumbnail: response.data.thumbnail,
          price: response.data.price,
          price_discount: response.data.price_discount,
          instructor_name: response.data.instructor_name,
          instructor_position: response.data.instructor_position,
          instructor_image_url: response.data.instructor_image_url,
          video_count: response.data.video_count,
          duration: response.data.duration,
          features: response.data.features || []
        };

        console.log('Course data loaded:', this.courseData);
      } catch (error) {
        console.error('Error fetching course data:', error);
        this.error = error.response?.data?.message || 'Gagal memuat data kursus. Silakan coba lagi.';
      } finally {
        this.loading = false;
      }
    },

    loadMidtransScript() {
      return new Promise((resolve, reject) => {
        if (window.snap) {
          this.midtransLoaded = true;
          resolve();
          return;
        }

        const script = document.createElement('script');
        script.src = 'https://app.sandbox.midtrans.com/snap/snap.js'; // Sandbox
        // Untuk production gunakan: 'https://app.midtrans.com/snap/snap.js'
        script.setAttribute('data-client-key', 'Mid-client-UyQhhqiodzZuSBpA');

        script.onload = () => {
          this.midtransLoaded = true;
          console.log('Midtrans script loaded successfully');
          resolve();
        };

        script.onerror = () => {
          console.error('Failed to load Midtrans script');
          reject(new Error('Failed to load Midtrans script'));
        };

        document.head.appendChild(script);
      });
    },

    async buyCourse() {
      // Check if user is authenticated
      if (!this.isAuthenticated) {
        alert('Silakan login terlebih dahulu untuk membeli kursus');
        this.$router.push('/login');
        return;
      }

      // Check if Midtrans is loaded
      if (!this.midtransLoaded) {
        try {
          await this.loadMidtransScript();
        } catch (error) {
          alert('Gagal memuat sistem pembayaran. Silakan refresh halaman dan coba lagi.');
          return;
        }
      }

      this.processingPayment = true;

      try {
        // Get token from localStorage
        const token = localStorage.getItem('token');

        // Create payment request
        const paymentData = {
          course_id: this.courseData.id,
          amount: this.courseData.price
        };

        console.log('Creating payment with data:', paymentData);

        // Call backend to create snap token
        const response = await axios.post('http://localhost:8000/api/checkout', paymentData, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        console.log('Payment response:', response.data);

        if (response.data.snap_token) {
          // Open Midtrans payment popup
          window.snap.pay(response.data.snap_token, {
            onSuccess: (result) => {
              console.log('Payment success:', result);
              alert('Pembayaran berhasil! Terima kasih telah membeli kursus ini.');
              // Redirect to course or dashboard
              this.$router.push('/dashboard');
            },
            onPending: (result) => {
              console.log('Payment pending:', result);
              alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
            },
            onError: (result) => {
              console.error('Payment error:', result);
              alert('Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
            },
            onClose: () => {
              console.log('Payment popup closed');
              // User closed the payment popup
            }
          });
        } else {
          throw new Error('Gagal mendapatkan token pembayaran');
        }

      } catch (error) {
        console.error('Error processing payment:', error);

        if (error.response?.status === 401) {
          alert('Sesi Anda telah berakhir. Silakan login kembali.');
          localStorage.removeItem('token');
          this.$router.push('/login');
        } else if (error.response?.status === 422) {
          alert('Data tidak valid: ' + (error.response.data.message || 'Periksa kembali data Anda'));
        } else {
          alert('Gagal memproses pembayaran: ' + (error.response?.data?.message || error.message));
        }
      } finally {
        this.processingPayment = false;
      }
    }
  }
};
</script>

<style scoped>
.prose {
  max-width: none;
}

.prose h1, .prose h2, .prose h3, .prose h4 {
  color: #1f2937;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.75;
}

.prose ul {
  margin: 1rem 0;
}

.prose li {
  margin-bottom: 0.5rem;
}

/* Custom border classes untuk memastikan border terlihat jelas */
.border-3 {
  border-width: 3px;
}

.border-l-3 {
  border-left-width: 3px;
}

.border-l-4 {
  border-left-width: 4px;
}
</style>

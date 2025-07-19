<template>
  <div class="bg-white min-h-screen pt-24">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col lg:flex-row gap-8">
      <div class="flex-1 space-y-6">
        <div>
          <h1 class="text-2xl font-bold text-[#3b3b98] mb-2">
            {{ courseData.title || 'No Title' }}
          </h1>
        </div>
        <div>
          <span class="inline-block text-[#5c4ac7] text-[18px] font-semibold px-2 py-[2px] rounded">
            {{ courseData.tag || 'No Tag' }}
          </span>
        </div>
        <section class="border rounded-md p-4 text-[13px] max-w-9/10">
          <h3 class="text-[#3b3b98] font-semibold text-[15px] mb-2">Overview</h3>
          <p class="text-[17px]" v-html="courseData.overview || 'No Overview'"></p>
        </section>
      </div>
      <aside class="w-full lg:w-[320px] flex-shrink-0 space-y-6">
        <div class="border rounded-md p-6 flex flex-col items-center gap-4">
          <div class="w-full aspect-video mb-2">
            <img class="w-full h-full object-cover rounded-lg"
                 :src="courseImageUrl"
                 alt="Course image" />
          </div>
          <div class="w-full">
            <p class="text-[17px] font-semibold">
              Rp {{ formatPrice(courseData.price || 0) }}
            </p>
            <p v-if="courseData.price_discount" class="text-[14px] line-through text-[#9ca3af]">
              Rp {{ formatPrice(courseData.price_discount) }}
            </p>
          </div>
          <button @click="buyCourse"
                  :data-course-id="courseData.id"
                  class="w-full bg-[#564AB1] text-white text-[15px] font-semibold py-3 rounded-lg">
            Beli Course
          </button>
        </div>
        <div class="border rounded-md p-4 flex items-center gap-4 text-[13px]">
          <img class="w-16 h-16 rounded-full object-cover" 
               :src="getInstructorImageUrl(courseData.instructor_image_url)" 
               alt="Instructor Image">
          <div class="flex flex-col">
            <span class="font-semibold text-[14px]">
              {{ courseData.instructor_name || 'No Name' }}
            </span>
            <span class="text-[#6b7280]">
              {{ courseData.instructor_position || 'No Position' }}
            </span>
          </div>
        </div>
        <div class="border rounded-md p-4 text-[13px]">
          <p class="font-semibold mb-3 text-[14px]">This Course Include</p>
          <ul class="space-y-2">
            <li class="flex items-center gap-2">
              <i class="far fa-file-video text-[#5c4ac7]"></i>
              {{ courseData.video_count || 0 }} Video
            </li>
            <li class="flex items-center gap-2">
              <i class="far fa-clock text-[#5c4ac7]"></i>
              {{ courseData.duration || 0 }} Jam
            </li>
            <template v-if="courseData.features && courseData.features.length > 0">
              <li v-for="(feature, index) in courseData.features" :key="index" class="flex items-center gap-2">
                <i class="far fa-check-circle text-[#5c4ac7]"></i>
                {{ feature.value || '-' }}
              </li>
            </template>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    // Menerima courseId dari Blade/Laravel untuk mengambil data
    courseId: {
      type: [String, Number], // Bisa string jika dari URL, atau Number
      required: true
    },
    // Midtrans Client Key tetap diterima sebagai prop
    midtransClientKey: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      // Ini adalah tempat data kursus akan disimpan setelah di-fetch
      courseData: {
        id: null,
        title: null,
        tag: null,
        overview: null,
        image_url: null, // Ini akan dipetakan dari 'image' di model Course
        price: 0,
        price_discount: null,
        instructor_name: null,
        instructor_position: null,
        instructor_image_url: null,
        video_count: 0,
        duration: 0,
        features: [] // Ini sudah di-cast array di model CourseDescription
      },
      loading: true, // Untuk indikator loading
      error: null    // Untuk pesan error
    };
  },
  computed: {
    courseImageUrl() {
      // Menggunakan this.courseData.image_url karena data ada di `data()`
      const path = this.courseData.image_url;
      if (path) {
        return `/storage/${path}`;
      }
      return '/images/default.jpg'; // Path default jika tidak ada gambar
    }
  },
  async mounted() {
    // Panggil method untuk mengambil data saat komponen dimuat
    await this.fetchCourseData();

    // Memuat script Midtrans setelah data kursus diambil (opsional, bisa juga di luar `mounted` jika sudah ada di Blade)
    if (typeof snap === 'undefined' && this.midtransClientKey) {
      const script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
      script.setAttribute('data-client-key', this.midtransClientKey);
      document.body.appendChild(script);
      script.onload = () => {
        console.log('Midtrans Snap script loaded.');
      };
      script.onerror = (e) => {
        console.error('Failed to load Midtrans Snap script:', e);
        alert('Gagal memuat script pembayaran. Mohon coba refresh halaman.');
      };
    }
  },
  methods: {
    formatPrice(value) {
      if (value === null || value === undefined) return '0';
      return new Intl.NumberFormat('id-ID').format(Number(value));
    },
    getInstructorImageUrl(path) {
      if (path) {
        return `/storage/${path}`;
      }
      return '/images/default-instructor.jpg'; // Default path jika tidak ada gambar instruktur
    },
    
    // Method baru untuk mengambil data kursus dari API Laravel
    async fetchCourseData() {
      this.loading = true;
      this.error = null; // Reset error state

      try {
        // Ganti '/api/courses' dengan endpoint API yang sesuai di Laravel kamu
        // Misalnya, jika rute API kamu adalah `/api/course/{id}`
        // Ini akan memanggil API Controller Laravel yang mengembalikan data Course + CourseDescription
        const response = await fetch(`/api/course/${this.courseId}`);

        if (!response.ok) {
          const errorText = await response.text();
          throw new Error(`Gagal mengambil data kursus: ${response.status} ${response.statusText} - ${errorText}`);
        }

        const data = await response.json();
        // Asumsi struktur data JSON dari API sudah "diratakan"
        // yaitu semua properti (dari Course dan CourseDescription) ada di level atas objek data
        this.courseData = {
          id: data.id,
          title: data.title,
          tag: data.tag,
          overview: data.overview,
          image_url: data.image_url, // Ini dari kolom 'image' di model Course
          price: data.price,
          price_discount: data.price_discount,
          instructor_name: data.instructor_name,
          instructor_position: data.instructor_position,
          instructor_image_url: data.instructor_image_url,
          video_count: data.video_count,
          duration: data.duration,
          features: data.features || [] // Pastikan fitur adalah array
        };
      } catch (error) {
        console.error('Error fetching course data:', error);
        this.error = 'Gagal memuat data kursus. Silakan coba lagi.';
        alert(this.error);
      } finally {
        this.loading = false;
      }
    },

    async buyCourse() {
      if (!this.courseData.id) {
        alert('Course ID tidak ditemukan. Data kursus mungkin belum dimuat.');
        return;
      }

      console.log('Button clicked, courseId:', this.courseData.id);

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                                  ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                  : '';

        // Pastikan endpoint ini mengembalikan snapToken
        // Umumnya, request pembayaran adalah POST
        const response = await fetch(`/payment/${this.courseData.id}`, {
          method: 'POST', 
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          // Jika ada data tambahan yang perlu dikirim ke backend untuk pembayaran, tambahkan di body:
          // body: JSON.stringify({ /* data pembayaran tambahan */ })
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to get Snap Token');
        }

        const data = await response.json();
        console.log('Response:', data);

        if (data.snapToken) {
          if (typeof snap !== 'undefined') {
            snap.pay(data.snapToken, {
              onSuccess: function(result) {
                alert('Pembayaran Berhasil! ID Transaksi: ' + result.transaction_id);
                console.log(result);
                window.location.href = '/payment/success'; // Redirect ke halaman sukses
              },
              onPending: function(result) {
                alert('Pembayaran Tertunda! ID Transaksi: ' + result.transaction_id);
                console.log(result);
                // Pertimbangkan redirect ke halaman pending atau tetap di sini dengan pesan
              },
              onError: function(result) {
                alert('Pembayaran Gagal! Error: ' + result.status_message);
                console.log(result);
                // Pertimbangkan redirect ke halaman error atau tetap di sini dengan pesan
              },
              onClose: function() {
                alert('Anda menutup popup pembayaran.');
              }
            });
          } else {
            alert('Midtrans Snap script belum dimuat. Mohon refresh halaman.');
            console.error('Objek Midtrans Snap tidak terdefinisi.');
          }
        } else {
          console.error('Tidak ada snapToken dalam respons:', data);
          alert('Gagal mendapatkan token pembayaran. Silakan coba lagi.');
        }
      } catch (error) {
        console.error('Error selama proses pembayaran:', error);
        alert('Terjadi kesalahan saat memproses pembayaran: ' + error.message);
      }
    }
  }
};
</script>

<style scoped>
/* Gaya CSS tetap sama */
</style>
<template>
  <div class="bg-white min-h-screen pt-24">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col lg:flex-row gap-8">
      <div class="flex-1 space-y-6">
        <div>
          <h1 class="text-2xl font-bold text-[#3b3b98] mb-2">
            {{ typeof courseDescription.title === 'string' ? courseDescription.title : 'No Title' }}
          </h1>
        </div>
        <div>
          <span class="inline-block text-[#5c4ac7] text-[18px] font-semibold px-2 py-[2px] rounded">
            {{ typeof courseDescription.tag === 'string' ? courseDescription.tag : 'No Tag' }}
          </span>
        </div>
        <section class="border rounded-md p-4 text-[13px] max-w-9/10">
          <h3 class="text-[#3b3b98] font-semibold text-[15px] mb-2">Overview</h3>
          <p class="text-[17px]" v-html="typeof courseDescription.overview === 'string' ? courseDescription.overview : 'No Overview'"></p>
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
              Rp {{ formatPrice(typeof courseDescription.price === 'number' ? courseDescription.price : 0) }}
            </p>
            <p v-if="courseDescription.price_discount && typeof courseDescription.price_discount === 'number'" class="text-[14px] line-through text-[#9ca3af]">
              Rp {{ formatPrice(courseDescription.price_discount) }}
            </p>
          </div>
          <button @click="buyCourse"
                  :data-course-id="courseDescription.id"
                  class="w-full bg-[#564AB1] text-white text-[15px] font-semibold py-3 rounded-lg">
            Beli Course
          </button>
        </div>
        <div class="border rounded-md p-4 flex items-center gap-4 text-[13px]">
          <img class="w-16 h-16 rounded-full object-cover" 
               :src="getInstructorImageUrl(courseDescription.instructor_image_url)" 
               alt="Instructor Image">
          <div class="flex flex-col">
            <span class="font-semibold text-[14px]">
              {{ typeof courseDescription.instructor_name === 'string' ? courseDescription.instructor_name : 'No Name' }}
            </span>
            <span class="text-[#6b7280]">
              {{ typeof courseDescription.instructor_position === 'string' ? courseDescription.instructor_position : 'No Position' }}
            </span>
          </div>
        </div>
        <div class="border rounded-md p-4 text-[13px]">
          <p class="font-semibold mb-3 text-[14px]">This Course Include</p>
          <ul class="space-y-2">
            <li class="flex items-center gap-2">
              <i class="far fa-file-video text-[#5c4ac7]"></i>
              {{ courseDescription.video_count || 0 }} Video
            </li>
            <li class="flex items-center gap-2">
              <i class="far fa-clock text-[#5c4ac7]"></i>
              {{ courseDescription.duration || 0 }} Jam
            </li>
            <template v-if="courseDescription.features && courseDescription.features.length > 0">
              <li v-for="(feature, index) in courseDescription.features" :key="index" class="flex items-center gap-2">
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
    courseDescription: {
      type: Object,
      required: true,
      default: () => ({
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
      })
    },
    midtransClientKey: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      // Sample data for demonstration
      courseDescription: {
        id: 12345,
        title: 'Complete Web Development Bootcamp 2025',
        tag: 'Development',
        overview: 'Learn to code and become a full-stack web developer with HTML, CSS, JavaScript, Node.js, Express, MongoDB, and more! This comprehensive bootcamp covers everything you need to know to build modern web applications from scratch.',
        image_url: 'courses/web-dev-bootcamp.jpg', // Assuming this path exists in your storage
        thumbnail: 'courses/web-dev-bootcamp-thumb.jpg',
        price: 999000,
        price_discount: 799000,
        instructor_name: 'Angela Yu',
        instructor_position: 'Lead Instructor at App Brewery',
        instructor_image_url: 'instructors/angela-yu.jpg', // Assuming this path exists in your storage
        video_count: 85,
        duration: 45,
        features: [
          { value: 'Full Lifetime Access' },
          { value: 'Certificate of Completion' },
          { value: 'Downloadable Resources' }
        ]
      },
      midtransClientKey: 'YOUR_MIDTRANS_CLIENT_KEY' // Replace with your actual Midtrans Client Key (e.g., from .env)
    };
  },
  computed: {
    courseImageUrl() {
      const path = this.courseDescription.image_url || this.courseDescription.thumbnail;
      if (path) {
        return `/storage/${path}`;
      }
      return '/images/default.jpg'; // Path default if no image
    }
  },
  mounted() {
    if (typeof snap === 'undefined' && this.midtransClientKey) {
      const script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
      script.setAttribute('data-client-key', this.midtransClientKey);
      document.body.appendChild(script);
      script.onload = () => {
        console.log('Midtrans Snap script loaded.');
      };
    }
  },
  methods: {
    formatPrice(value) {
      if (value === null || value === undefined) return '0';
      return new Intl.NumberFormat('id-ID').format(value);
    },
    getInstructorImageUrl(path) {
      if (path) {
        return `/storage/${path}`;
      }
      return '/images/default-instructor.jpg'; // Default path if no instructor image
    },
    async buyCourse() {
      if (!this.courseDescription.id) {
        alert('Course ID tidak ditemukan.');
        return;
      }

      console.log('Button clicked, courseId:', this.courseDescription.id);

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                               ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                               : '';

        const response = await fetch(`/payment/${this.courseDescription.id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
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
                alert('Payment Success! Transaction ID: ' + result.transaction_id);
                console.log(result);
                window.location.href = '/payment/success';
              },
              onPending: function(result) {
                alert('Payment Pending! Transaction ID: ' + result.transaction_id);
                console.log(result);
              },
              onError: function(result) {
                alert('Payment Failed! Error: ' + result.status_message);
                console.log(result);
              },
              onClose: function() {
                alert('Anda menutup popup pembayaran.');
              }
            });
          } else {
            alert('Midtrans Snap script belum dimuat. Mohon refresh halaman.');
            console.error('Midtrans Snap object is not defined.');
          }
        } else {
          console.error('No snapToken in response:', data);
          alert('Gagal mendapatkan token pembayaran. Silakan coba lagi.');
        }
      } catch (error) {
        console.error('Error during payment process:', error);
        alert('Terjadi kesalahan saat memproses pembayaran: ' + error.message);
      }
    }
  }
};
</script>

<style scoped>
/* You can add custom component styles here if not fully using Tailwind */
/* Ensure custom colors like #3b3b98, #5c4ac7, #564AB1, #9ca3af */
/* are defined in your Tailwind configuration or directly in CSS */

/* Example color definition in tailwind.config.js: */
/*
module.exports = {
  theme: {
    extend: {
      colors: {
        'dark-purple-text': '#3b3b98',
        'light-purple-accent': '#5c4ac7',
        'btn-bg-purple': '#564AB1',
        'gray-line-through': '#9ca3af',
        // ... other colors if any
      }
    }
  }
}
*/

/* Font Awesome (far fa-file-video, far fa-clock, far fa-check-circle)
   needs to be imported globally in your Vue project.
   Example: in index.html or via an npm package like vue-fontawesome.
*/
</style>
<template>
  <div>
    <section class="bg-primary-light text-white px-[5%] py-25 flex justify-between items-center flex-wrap gap-8">
      <div class="max-w-lg" data-aos="fade-right" data-aos-duration="1000">
        <h1 class="text-4xl font-bold mb-5 leading-tight">Get Closer with Mentoring</h1>
        <p class="mb-8 opacity-90 text-lg leading-relaxed">Nikmati pengalaman belajar yang intensif dan private melalui
          program Mentoring kami. Semua dilakukan secara privat dengan mentor berpengalaman, memastikan setiap sesi
          dirancang khusus untuk kebutuhan dan tujuanmu</p>
        <div class="flex gap-4">
          <router-link to="/register"
            class="px-7 py-3 rounded-md bg-white text-[#564AB1] font-medium transition-all duration-300 hover:bg-gray-100 hover:-translate-y-0.5">Get
            Started</router-link>
          <router-link to="/learnmore"
            class="px-7 py-3 rounded-md border-2 border-white text-white font-medium transition-all duration-300 hover:bg-white/10">Learn
            More</router-link>
        </div>
      </div>
      <div
        class="w-[630px] h-[380px] bg-[#7C72C3] rounded-xl overflow-hidden mx-auto shadow-lg flex flex-col justify-between pb-4"
        data-aos="fade-left" data-aos-duration="1000">
        <div v-for="(slide, index) in slides" :key="index"
          :class="['slide', { 'hidden': currentSlide !== index, 'active': currentSlide === index }]"
          class="mt-5 text-center">
          <img :src="slide.image" :alt="slide.alt" class="w-[576px] h-[324px] mx-auto rounded-xl object-cover" />
        </div>

        <div class="flex justify-center gap-2.5 mt-4">
          <button v-for="(_, index) in slides" :key="index" @click="currentSlide = index" type="button"
            class="carousel-btn w-4 h-4 rounded-full border-2 border-white transition-transform hover:scale-110"
            :class="{ 'bg-white': currentSlide === index, 'bg-transparent': currentSlide !== index }"
            :aria-label="'Go to slide ' + (index + 1)">
          </button>
        </div>
      </div>
    </section>

    <section class="bg-white px-[5%] py-12 flex-1">
      <div class="bg-white py-6 px-[0%]" data-aos="fade-up" data-aos-duration="800">
        <div class="flex flex-wrap gap-4 justify-start">
          <button @click="activeSection = 'mentoring'"
            :class="['px-6 py-3 rounded-full font-medium transition-all duration-300',
              activeSection === 'mentoring' ? 'text-white bg-[#564AB1] hover:bg-[#473D94]' : 'border-2 border-[#564AB1] text-[#564AB1] hover:bg-[#564AB1]/5']">
            Exclusive Mentoring
          </button>
          <button @click="activeSection = 'consultan'"
            :class="['px-6 py-3 rounded-full font-medium transition-all duration-300',
              activeSection === 'consultan' ? 'text-white bg-[#564AB1] hover:bg-[#473D94]' : 'border-2 border-[#564AB1] text-[#564AB1] hover:bg-[#564AB1]/5']">
            Consultan
          </button>
          <button @click="activeSection = 'allmentor'"
            :class="['px-6 py-3 rounded-full font-medium transition-all duration-300',
              activeSection === 'allmentor' ? 'text-white bg-[#564AB1] hover:bg-[#473D94]' : 'border-2 border-[#564AB1] text-[#564AB1] hover:bg-[#564AB1]/5']">
            All mentor
          </button>
        </div>
      </div>

      <!-- Mentoring Section -->
      <div v-show="activeSection === 'mentoring'" class="flex flex-wrap md:flex-nowrap gap-8">
        <div class="flex-1">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
            <div v-for="(mentoring, index) in mentorings" :key="mentoring.id"
              class="w-full bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
              :data-aos="'fade-up'" :data-aos-delay="index * 200" data-aos-duration="800">
              <router-link :to="'/mentoring/' + mentoring.id">
                <img :src="mentoring.image" :alt="mentoring.title" class="w-full h-[230px] object-cover">
                <div class="p-5">
                  <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ mentoring.title }}</h2>
                  <p v-for="(chapter, index) in mentoring.description.split('\n')" :key="index" v-if="chapter.trim()"
                    class="text-sm text-gray-600 mb-4">{{ chapter }}</p>

                  <h3 class="text-xl font-semibold mb-2 text-gray-800">Investasi Murah</h3>
                  <p v-if="mentoring.price_normal" class="text-sm text-gray-600 mb-4 line-through">
                    Rp. {{ formatPrice(mentoring.price_normal) }}
                  </p>
                  <p v-if="mentoring.price_discount" class="text-sm text-[#1A5F2A] mb-4 font-medium">
                    Rp. {{ formatPrice(mentoring.price_discount) }}
                  </p>
                  <p class="text-purple-600 font-bold text-base inline-block py-1.5">Lihat Detail</p>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Consultan Section -->
      <div v-show="activeSection === 'consultan'" class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center">
          <div v-for="(consultant, index) in consultants" :key="consultant.id"
            class="w-full max-w-sm bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
            :data-aos="'fade-up'" :data-aos-delay="index * 150" data-aos-duration="800">
            <router-link to="/profil_consultan">
              <img :src="consultant.image" :alt="consultant.name" class="w-full h-48 object-cover rounded-t-xl" />
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 mentor-name">{{ consultant.name }}</h3>
                <div class="mt-3 space-y-2">
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 mentor-job">{{ consultant.job }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 mentor-exp">{{ consultant.experience }}</span>
                  </div>
                </div>
              </div>
            </router-link>
          </div>
        </div>
      </div>

      <!-- All Mentor Section -->
      <div v-show="activeSection === 'allmentor'" class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center">
          <div v-for="(mentor, index) in allMentors" :key="mentor.id"
            class="w-full max-w-sm bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
            :data-aos="'fade-up'" :data-aos-delay="index * 150" data-aos-duration="800">
            <router-link to="/profil_consultan">
              <img :src="mentor.image" :alt="mentor.name" class="w-full h-60 object-cover rounded-t-xl" />
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 mentor-name">{{ mentor.name }}</h3>
                <div class="mt-3 space-y-2">
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 mentor-job">{{ mentor.job }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 mentor-exp">{{ mentor.experience }}</span>
                  </div>
                </div>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'Mentoring',
  data() {
    return {
      currentSlide: 0,
      activeSection: 'mentoring',
      slides: [
        {
          id: 1,
          title: 'Mentoring Program',
          description: 'Program mentoring yang dirancang untuk membantu Anda mencapai tujuan karir',
          image: '/image/Mentoring.jpg'
        },
        {
          id: 2,
          title: 'Career Development',
          description: 'Kembangkan karir Anda dengan bimbingan dari mentor berpengalaman',
          image: '/image/Mentoring-2.jpg'
        },
        {
          id: 3,
          title: 'Personal Growth',
          description: 'Tingkatkan kemampuan dan kepercayaan diri Anda melalui mentoring',
          image: '/image/Mentoring-3.jpg'
        }
      ],
      consultants: [
        {
          id: 1,
          name: 'Sarah Johnson',
          category: 'Career Development',
          job: 'Senior Software Engineer',
          experience: '8+ years experience',
          image: '/image/adithya.jpeg'
        },
        {
          id: 2,
          name: 'Michael Chen',
          category: 'Product Management',
          job: 'Product Manager',
          experience: '5+ years experience',
          image: '/image/alfian.jpeg'
        }
      ],
      allMentors: [
        {
          id: 1,
          name: 'David Wilson',
          category: 'Software Development',
          job: 'Tech Lead',
          experience: '10+ years experience',
          image: '/image/ahmad.jpeg'
        },
        {
          id: 2,
          name: 'Lisa Anderson',
          category: 'UX Design',
          job: 'Senior UX Designer',
          experience: '7+ years experience',
          image: '/image/hajisodikin.jpg'
        }
      ]
    }
  },
  mounted() {
    this.startAutoSlide();
  },
  methods: {
    startAutoSlide() {
      setInterval(() => {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
      }, 3000); // Ganti slide tiap 3 detik
    }
  }

}
</script>
<style lang="scss" src="../../sass/mentoring.scss"></style>

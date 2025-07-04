<template>
  <section class="bg-gray-100 text-textDark leading-relaxed">
    <div class="max-w-screen-xl mx-auto px-4 relative">
      <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ errorMessage }}
      </div>
      <div v-if="errors.length > 0" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul class="list-disc list-inside">
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </div>

      <div class="flex flex-col sm:flex-row items-start pt-8 gap-4">
        <div class="flex-shrink-0">
          <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white bg-gray-100 overflow-hidden flex items-center justify-center">
            <img :src="consultant.profile_image_url" :alt="consultant.name" class="w-full h-full object-cover">
          </div>
        </div>

        <div class="flex-1 w-full">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div class="flex-1">
              <h1 class="text-xl sm:text-2xl font-bold mb-1">{{ consultant.name }}</h1>
              <p class="text-textLight text-sm">{{ consultant.full_position }}</p>
              <div v-if="consultant.specialty" class="inline-block bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full mt-2">
                {{ consultant.specialty }}
              </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <button class="border border-accent text-accent py-2 px-4 rounded text-sm">Share</button>
              <div class="flex gap-2 justify-center sm:justify-start">
                <a v-if="consultant.instagram_url" :href="consultant.instagram_url" target="_blank" aria-label="Instagram" class="w-8 h-8 rounded-full flex items-center justify-center text-primary bg-gray-100 border border-gray-300">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                  </svg>
                </a>
                <a v-if="consultant.linkedin_url" :href="consultant.linkedin_url" target="_blank" aria-label="LinkedIn" class="w-8 h-8 rounded-full flex items-center justify-center text-primary bg-gray-100 border border-gray-300">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 border-b border-gray-300 overflow-x-auto">
        <ul class="flex whitespace-nowrap">
          <li class="mr-4 sm:mr-6">
            <a href="#" class="block py-2 text-primary font-medium relative after:content-[''] after:absolute after:bottom-[-1px] after:left-0 after:w-full after:h-0.5 after:bg-primary text-sm">Overview</a>
          </li>
          <li class="mr-4 sm:mr-6">
            <a href="#" class="block py-2 text-textLight text-sm">Course</a>
          </li>
          <li class="mr-4 sm:mr-6">
            <a href="#" class="block py-2 text-textLight text-sm">Event</a>
          </li>
          <li class="mr-4 sm:mr-6">
            <a href="#" class="block py-2 text-textLight text-sm">Portfolio</a>
          </li>
          <li class="mr-4 sm:mr-6">
            <a href="#" class="block py-2 text-textLight text-sm">Certification</a>
          </li>
        </ul>
      </div>

      <div class="py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 order-2 lg:order-1">
            <div class="mb-8">
              <h2 class="text-lg font-semibold mb-4">About Me</h2>
              <p class="mb-6 text-sm leading-relaxed">
                {{ consultant.bio || 'Belum ada deskripsi yang tersedia.' }}
              </p>
              
              <div v-if="consultant.total_reviews > 0" class="flex items-center gap-4 mb-4">
                <div class="flex items-center gap-1">
                  <span class="text-yellow-500">⭐</span>
                  <span class="font-semibold">{{ consultant.formatted_rating }}</span>
                  <span class="text-gray-500 text-sm">({{ consultant.total_reviews }} reviews)</span>
                </div>
              </div>
            </div>

            <div v-if="consultant.experiences && consultant.experiences.length > 0" class="mb-8">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Experiences</h2>
                <a href="#" class="text-accent text-sm">View More</a>
              </div>

              <div class="space-y-4">
                <div v-for="(experience, index) in consultant.experiences" :key="index" class="p-4 border border-gray-300 rounded-lg bg-white">
                  <h3 class="font-semibold mb-1 text-sm sm:text-base">{{ experience.position || 'Position' }}</h3>
                  <div class="text-textLight text-sm flex flex-col sm:flex-row sm:items-center mb-1">
                    <span>{{ experience.company || 'Company' }}</span>
                  </div>
                  <div class="text-textLight text-xs">
                    {{ experience.start_date || '' }} - {{ experience.end_date || 'Sekarang' }}
                  </div>
                </div>
              </div>
            </div>

            <div v-if="consultant.educations && consultant.educations.length > 0" class="mb-8">
              <h2 class="text-lg font-semibold mb-4">Education</h2>
              <div class="space-y-4">
                <div v-for="(education, index) in consultant.educations" :key="index" class="p-4 border border-gray-300 rounded-lg bg-white flex">
                  <div class="w-12 h-12 bg-gray-100 flex items-center justify-center mr-4 border border-gray-300 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
                      <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-semibold mb-1 text-sm sm:text-base">{{ education.institution || 'Institution' }}</h3>
                    <p class="text-textLight text-sm mb-1">{{ education.degree || 'Degree' }}</p>
                    <div class="text-textLight text-xs">
                      {{ education.start_year || '' }} - {{ education.end_year || 'Sekarang' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="consultant.skills && consultant.skills.length > 0" class="mb-8">
              <h2 class="text-lg font-semibold mb-4">Skills</h2>
              <div class="flex flex-wrap gap-2">
                <span v-for="(skill, index) in consultant.skills" :key="index" class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">{{ skill }}</span>
              </div>
            </div>
          </div>

          <div class="order-1 lg:order-2">
            <div class="mb-6">
              <h3 class="text-lg font-semibold mb-4">Location</h3>
              <div class="flex items-center">
                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center mr-2 text-red-600">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                  </svg>
                </div>
                <div class="text-sm">{{ consultant.location || 'Tidak ditentukan' }}</div>
              </div>
            </div>

            <div v-if="consultant.phone || consultant.email" class="mb-6">
              <h3 class="text-lg font-semibold mb-4">Contact</h3>
              <div class="space-y-2">
                <div v-if="consultant.email" class="flex items-center text-sm">
                  <span class="w-4 h-4 mr-2">📧</span>
                  <span>{{ consultant.email }}</span>
                </div>
                <div v-if="consultant.phone" class="flex items-center text-sm">
                  <span class="w-4 h-4 mr-2">📱</span>
                  <span>{{ consultant.phone }}</span>
                </div>
              </div>
            </div>

            <div class="bg-[#5E50A1] text-white p-6 rounded-lg">
              <h2 class="text-lg font-semibold mb-4">Booking sesi konsultasimu sekarang!</h2>
              <p class="text-sm mb-4 leading-relaxed">Kamu dapat melakukan konsultasi secara 1 on 1 bersama mentor berpengalaman.</p>
              <div class="mb-4 text-sm">
                <div class="flex justify-between">
                  <span>Hourly Rate:</span>
                  <span class="font-semibold">Rp {{ formatCurrency(consultant.hourly_rate) }}</span>
                </div>
              </div>
              <div class="space-y-2">
                <button @click="bookNow" class="bg-white text-[#5E50A1] font-medium py-3 px-4 rounded w-full text-sm">Booking Sekarang</button>
                <button @click="tryForFree" class="bg-transparent border border-white text-white font-medium py-3 px-4 rounded w-full text-sm">Coba Gratis</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  // `props` to receive consultant data from the parent component
  props: {
    consultant: {
      type: Object,
      required: true,
      default: () => ({
        profile_image_url: '',
        name: 'Nama Konsultan',
        full_position: 'Posisi Konsultan',
        specialty: '',
        instagram_url: '',
        linkedin_url: '',
        bio: '',
        total_reviews: 0,
        formatted_rating: '0.0',
        experiences: [],
        educations: [],
        skills: [],
        location: '',
        email: '',
        phone: '',
        hourly_rate: 0
      })
    }
  },
  data() {
    return {
      // Sample data for demonstration
      consultant: {
        profile_image_url: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Example image URL
        name: 'Dr. Sarah Cahaya',
        full_position: 'Senior Data Scientist & AI Ethics Consultant',
        specialty: 'Artificial Intelligence, Machine Learning, Data Strategy',
        instagram_url: 'https://www.instagram.com/sarahcahaya_ai',
        linkedin_url: 'https://www.linkedin.com/in/sarahcahaya',
        bio: 'Dr. Sarah Cahaya is a highly accomplished Senior Data Scientist with over 10 years of experience in developing and deploying AI solutions across various industries, including finance, healthcare, and e-commerce. She holds a Ph.D. in Computer Science from the University of Gadjah Mada and is passionate about promoting ethical AI development and responsible data practices. Sarah has led numerous successful data projects, optimizing business processes, predicting market trends, and enhancing customer experiences through advanced analytics and machine learning algorithms. She is also a mentor and speaker, sharing her expertise on topics like MLOps, explainable AI, and building scalable data infrastructures.',
        total_reviews: 128,
        formatted_rating: '4.9',
        experiences: [
          {
            position: 'Senior Data Scientist',
            company: 'Tech Solutions Indonesia',
            start_date: 'Januari 2020',
            end_date: 'Sekarang'
          },
          {
            position: 'Data Scientist Lead',
            company: 'Innovate Digital',
            start_date: 'Maret 2017',
            end_date: 'Desember 2019'
          },
          {
            position: 'Junior Data Analyst',
            company: 'StatPro Consulting',
            start_date: 'Juli 2014',
            end_date: 'Februari 2017'
          }
        ],
        educations: [
          {
            institution: 'Universitas Gadjah Mada',
            degree: 'Ph.D. in Computer Science',
            start_year: '2010',
            end_year: '2014'
          },
          {
            institution: 'Institut Teknologi Bandung',
            degree: 'Master of Science in Artificial Intelligence',
            start_year: '2008',
            end_year: '2010'
          },
          {
            institution: 'Universitas Indonesia',
            degree: 'Sarjana Komputer (S.Kom.)',
            start_year: '2004',
            end_year: '2008'
          }
        ],
        skills: [
          'Python', 'Machine Learning', 'Deep Learning', 'SQL', 'Big Data', 'Cloud Computing (AWS/GCP)', 'Data Visualization', 'Statistical Analysis', 'AI Ethics', 'NLP'
        ],
        location: 'Yogyakarta, Indonesia',
        email: 'sarah.cahaya@example.com',
        phone: '+62 812-3456-7890',
        hourly_rate: 750000
      },
      successMessage: '',
      errorMessage: '',
      errors: []
    };
  },
  methods: {
    formatCurrency(value) {
      if (value === null || value === undefined) return '0';
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    bookNow() {
      // Logic for the "Book Now" process
      alert('Fitur booking sekarang akan segera diimplementasikan!');
      // Example: could navigate to a booking page or open a modal
    },
    tryForFree() {
      // Logic for the "Try for Free" process
      alert('Fitur coba gratis akan segera diimplementasikan!');
      // Example: could navigate to a free trial registration form
    },
    // Methods to display success/error messages (optional, can be replaced by a global notification system)
    showSuccess(message) {
      this.successMessage = message;
      setTimeout(() => this.successMessage = '', 3000);
    },
    showError(message) {
      this.errorMessage = message;
      setTimeout(() => this.errorMessage = '', 3000);
    },
    showValidationErrors(errorsArray) {
      this.errors = errorsArray;
      setTimeout(() => this.errors = [], 5000);
    }
  },
  mounted() {
    // You can add logic here if needed after the component is mounted
    // For example, fetching data from an API if props are not sufficient
  }
};
</script>

<style scoped>
/* You can add custom component styles here if not fully using Tailwind */
/* Tailwind styles are globally imported in your Vue project */
</style>
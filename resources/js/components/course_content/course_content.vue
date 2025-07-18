<template>
  <div class="bg-white">
    <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-white shadow z-50 border-b border-gray-300">
      <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">{{ courseTitle }}</h1>
      </div>
    </header>

    <!-- Sidebar (Progress & List Materi) -->
    <div class="fixed top-0 left-0 h-full w-72 bg-white pt-20 px-6 pb-20 shadow-lg z-40 hidden md:block">
      <!-- Progress Bar -->
      <div class="w-full py-4">
        <div class="flex justify-between mb-1">
          <span class="text-sm font-medium text-gray-700">Progress</span>
          <span class="text-sm font-medium text-gray-700">{{ progress }}% selesai</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
          <div
            class="bg-purple-600 h-3 rounded-full transition-all duration-300"
            :style="{ width: progress + '%' }"
          ></div>
        </div>
      </div>

      <!-- List Materi tanpa dropdown -->
      <div class="mt-8">
        <h2 class="font-bold text-gray-800 text-base mb-4">Materi</h2>
        <ul class="border-l-2 pl-4 text-black text-sm ps-8 space-y-2">
          <li v-for="materi in materis" :key="materi.slug">
            <button
              type="button"
              @click="showContent(materi.slug)"
              class="flex items-center gap-2 w-full text-left py-2 hover:underline"
            >
              <span class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
              {{ materi.judul }}
            </button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Mobile List Materi (offcanvas) -->
    <div
      class="relative z-40 md:hidden"
      role="dialog"
      aria-modal="true"
      v-show="showMobileMenu"
    >
      <div class="fixed inset-0 bg-black/25" aria-hidden="true" @click="toggleMobileMenu"></div>
      <div class="fixed inset-0 z-40 flex">
        <div class="relative ml-auto flex size-full max-w-xs flex-col overflow-y-auto bg-white pb-6 pt-4 shadow-xl">
          <div class="flex items-center justify-between px-4">
            <h2 class="text-lg font-medium text-gray-900">Materi</h2>
            <button
              type="button"
              class="relative -mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:outline-none"
              @click="toggleMobileMenu"
            >
              <span class="sr-only">Close menu</span>
              <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <ul class="mt-4 px-2 py-3 font-medium text-gray-900 space-y-2">
            <li v-for="materi in materis" :key="materi.slug">
              <button
                type="button"
                @click="showContent(materi.slug); toggleMobileMenu()"
                class="block w-full text-left px-2 py-3 hover:underline"
              >
                {{ materi.judul }}
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Hamburger for mobile -->
    <button
      class="fixed top-4 right-4 z-50 md:hidden bg-white rounded-full p-2 shadow"
      @click="toggleMobileMenu"
    >
      <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Main Content -->
    <main class="pt-24 md:pl-80 pb-24 px-4 min-h-screen bg-white">
      <div class="max-w-3xl mx-auto border border-gray-300 rounded-lg shadow-lg p-6 bg-white">
        <div
          v-for="(materi, index) in materis"
          :key="materi.slug"
          :id="'content-' + materi.slug"
          class="materi-content"
          :class="{ 'hidden': currentContent !== materi.slug }"
        >
          <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">{{ materi.judul }}</h2>
          <div class="text-justify text-gray-700 leading-relaxed" v-html="materi.konten"></div>
        </div>
      </div>
    </main>

    <!-- Navbar di bagian bawah -->
    <div class="fixed bottom-0 left-0 w-full bg-white shadow-inner border-t border-gray-300 z-50">
      <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="text-gray-600 text-sm">
          © 2025 Belajar Laravel
        </div>
        <button
          class="flex items-center gap-2 bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded"
          @click="nextContent"
        >
          Lanjutkan
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseContent',
  data() {
    return {
      showMobileMenu: false,
      currentContent: '',
      progress: 0,
      completedMateris: [],
      courseTitle: 'Judul Kursus',
      materis: [
        {
          slug: 'pengenalan-laravel',
          judul: 'Pengenalan Laravel',
          konten: '<p>Ini adalah konten pengenalan Laravel...</p>'
        },
        {
          slug: 'instalasi-laravel',
          judul: 'Instalasi Laravel',
          konten: '<p>Ini adalah konten instalasi Laravel...</p>'
        },
        {
          slug: 'routing-laravel',
          judul: 'Routing di Laravel',
          konten: '<p>Ini adalah konten routing Laravel...</p>'
        }
      ]
    }
  },
  mounted() {
    // Set konten pertama sebagai default
    if (this.materis.length > 0) {
      this.currentContent = this.materis[0].slug;
    }
    this.updateProgress();
  },
  methods: {
    showContent(slug) {
      this.currentContent = slug;

      // Tambahkan ke completed jika belum ada
      if (!this.completedMateris.includes(slug)) {
        this.completedMateris.push(slug);
        this.updateProgress();
      }
    },
    toggleMobileMenu() {
      this.showMobileMenu = !this.showMobileMenu;
    },
    updateProgress() {
      if (this.materis.length > 0) {
        this.progress = Math.round((this.completedMateris.length / this.materis.length) * 100);
      }
    },
    nextContent() {
      const currentIndex = this.materis.findIndex(materi => materi.slug === this.currentContent);
      if (currentIndex < this.materis.length - 1) {
        const nextSlug = this.materis[currentIndex + 1].slug;
        this.showContent(nextSlug);
      }
    }
  },
  props: {
    courseDescription: {
      type: Object,
      default: () => ({
        title: 'Belajar Laravel'
      })
    },
    initialMateris: {
      type: Array,
      default: () => []
    }
  },
  watch: {
    courseDescription: {
      handler(newVal) {
        this.courseTitle = newVal?.title || 'Judul Kursus';
      },
      immediate: true
    },
    initialMateris: {
      handler(newVal) {
        if (newVal && newVal.length > 0) {
          this.materis = newVal;
          this.currentContent = newVal[0].slug;
        }
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
/* Tambahan styling jika diperlukan */
.materi-content {
  transition: opacity 0.3s ease-in-out;
}

.materi-content.hidden {
  display: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .md\:pl-80 {
    padding-left: 0;
  }
}
</style>

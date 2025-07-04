<template>
  <div>
    <section class="bg-[#3A2F91] text-white px-[5%] py-25 flex justify-between items-center flex-wrap gap-8">
      <div class="max-w-lg">
        <h1 class="text-4xl font-bold mb-5 leading-tight">{{ mentoring.title }}</h1>
        <p class="mb-8 opacity-90 text-lg leading-relaxed">{{ mentoring.short_description }}</p>
        <div class="text-2xl font-bold text-green-300 mb-2 py-2">
          <template v-if="mentoring.discounted_price < mentoring.original_price">
            <del class="text-gray-300 text-2xl mr-2">Rp {{ formatPrice(mentoring.original_price) }}</del> 
            Rp {{ formatPrice(mentoring.discounted_price) }}
          </template>
          <template v-else>
            Rp {{ formatPrice(mentoring.original_price) }}
          </template>
        </div>
        <div class="flex gap-4">
          <a href='/payment' class="px-7 py-3 rounded-md bg-white text-[#564AB1] font-medium transition-all duration-300 hover:bg-gray-100 hover:-translate-y-0.5">Daftar Sekarang</a>
        </div>
      </div>
      
      <div class="mt-5 text-center">
        <img 
          class="w-[576px] h-[280px] mx-auto rounded-xl object-cover" 
          :src="getMentoringImagePath(mentoring.image_path)" 
          alt="Mentoring Image" 
        />
      </div>
    </section>

    <section class="bg-gray-50 text-gray-800 font-sans py-16">
      <div class="max-w-5xl mx-auto p-5">
        <div class="text-center mb-10">
          <h1 class="text-3xl font-bold text-purple-800 mb-5">Siapa yang Cocok Mengikuti Program Ini?</h1>
          <p class="text-lg">Program ini dirancang khusus untuk kamu yang ingin mempercepat perjalanan karimu:</p>
        </div>

        <div v-if="mentoring.target_audience && mentoring.target_audience.length > 0" class="flex flex-col md:flex-row gap-5 mb-10">
          <div v-for="(audience, index) in mentoring.target_audience" :key="index" class="bg-white rounded-lg p-5 shadow flex-1 flex flex-col">
            <h2 class="text-xl font-bold text-purple-800 mt-0">{{ audience.item }}</h2>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow mb-8">
          <span class="inline-block bg-purple-800 text-white font-bold px-5 py-2 rounded-full mb-4">Tentang Program</span>
          <h2 class="text-2xl font-bold text-purple-800 mt-2">Apa itu Program "{{ mentoring.title }}"?</h2>
          <div class="mt-4 leading-relaxed prose" v-html="mentoring.about_program"></div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow mb-8">
          <span class="inline-block bg-purple-800 text-white font-bold px-5 py-2 rounded-full mb-4">Materi</span>
          <h2 class="text-2xl font-bold text-purple-800 mt-2">Materi Apa Saja yang Akan Kamu Pelajari?</h2>
          <p class="mt-4 leading-relaxed">Kurikulum Exclusive Mentoring kami dirancang secara cermat untuk memenuhi kebutuhan industri sambil memastikan kamu mengembangkan keterampilan yang relevan dan praktis.</p>
          <p class="mt-3 leading-relaxed">Kamu akan melalui beberapa fase yang terbagi dalam chapter yang bisa kamu dalami dan mewujudkan materi yang kamu kuasai dengan kebutuhanmu, seperti portofolio dalam kurun {{ mentoring.duration_months }} bulan penuhmu.</p>
          
          <div class="mt-6 pl-5">
            <template v-if="mentoring.basic_materials && mentoring.basic_materials.length > 0">
              <h3 class="text-xl font-bold text-purple-800">Materi Dasar:</h3>
              <ul class="list-disc pl-5 mt-3 space-y-2">
                <li v-for="(material, index) in mentoring.basic_materials" :key="index">{{ material.item }}</li>
              </ul>
            </template>
            
            <template v-if="mentoring.intermediate_materials && mentoring.intermediate_materials.length > 0">
              <h3 class="text-xl font-bold text-purple-800 mt-6">Materi Menengah:</h3>
              <ul class="list-disc pl-5 mt-3 space-y-2">
                <li v-for="(material, index) in mentoring.intermediate_materials" :key="index">{{ material.item }}</li>
              </ul>
            </template>
            
            <template v-if="mentoring.advanced_materials && mentoring.advanced_materials.length > 0">
              <h3 class="text-xl font-bold text-purple-800 mt-6">Materi Lanjutan:</h3>
              <ul class="list-disc pl-5 mt-3 space-y-2">
                <li v-for="(material, index) in mentoring.advanced_materials" :key="index">{{ material.item }}</li>
              </ul>
            </template>
          </div>
        </div>
        
        <div v-if="mentoring.benefits && mentoring.benefits.length > 0" class="bg-white rounded-lg p-6 shadow mb-8">
          <h2 class="text-2xl font-bold text-purple-800">Manfaat Program</h2>
          <ul class="list-disc pl-8 mt-4 space-y-2">
            <li v-for="(benefit, index) in mentoring.benefits" :key="index">{{ benefit.item }}</li>
          </ul>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow mb-8">
          <h2 class="text-2xl font-bold text-purple-800">Daftar Sekarang</h2>
          <p class="mt-4">Tempat terbatas! Kami hanya menerima {{ mentoring.max_participants }} peserta per batch untuk memastikan kualitas mentoring.</p>
          <a :href="'/payment/' + mentoring.id" class="bg-purple-800 text-white font-bold px-5 py-2 rounded-full inline-block mt-4 cursor-pointer hover:bg-purple-900 transition-colors">Daftar Program</a>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: {
    mentoring: {
      type: Object,
      required: true,
      default: () => ({
        title: 'Judul Mentoring Default',
        short_description: 'Deskripsi singkat program mentoring.',
        original_price: 0,
        discounted_price: 0,
        image_path: 'image/default.jpg', // Default image
        target_audience: [],
        about_program: '<p>Tentang program ini.</p>',
        duration_months: 0,
        basic_materials: [],
        intermediate_materials: [],
        advanced_materials: [],
        benefits: [],
        max_participants: 0,
        id: null // Untuk URL pembayaran
      })
    }
  },
  methods: {
    // Fungsi untuk memformat harga menjadi format Rupiah
    formatPrice(value) {
      if (value === null || value === undefined) return '0';
      return new Intl.NumberFormat('id-ID').format(value);
    },
    // Fungsi untuk mendapatkan path gambar yang benar
    getMentoringImagePath(path) {
      // Logic from Laravel Blade: Str::startsWith($mentoring->image_path, 'image/') ? asset($mentoring->image_path) : asset('storage/' . $mentoring->image_path)
      // Di Vue.js, `asset()` tidak ada. Kita perlu memastikan URL gambar sudah benar dari backend
      // atau membangun URL relatif/absolut yang tepat di frontend.
      // Asumsi: Backend akan memberikan URL gambar yang sudah lengkap.
      // Jika image_path masih merupakan path lokal (misal: 'image/foo.jpg' atau 'storage/bar.png'),
      // Anda perlu mengkonfigurasi server web (misal Nginx/Apache) atau Vite/Webpack untuk menyajikan aset tersebut.
      // Contoh sederhana:
      if (path && path.startsWith('http')) {
        return path; // Jika sudah URL lengkap
      } else if (path && path.startsWith('image/')) {
        return `/images/${path.replace('image/', '')}`; // Asumsi gambar di folder public/images
      } else if (path && path.startsWith('storage/')) {
        return `/storage/${path.replace('storage/', '')}`; // Asumsi symlink storage ke public
      }
      return '/images/default.jpg'; // Gambar default jika path tidak valid
    }
  }
};
</script>

<style scoped>
/* Anda dapat menambahkan gaya khusus komponen di sini jika tidak menggunakan Tailwind sepenuhnya */
/* Pastikan warna kustom seperti bg-[#3A2F91], text-[#564AB1], text-purple-800, text-green-300 */
/* telah didefinisikan dalam konfigurasi Tailwind Anda atau langsung dalam kode CSS */

/* Contoh penyesuaian jika warna tidak ada di tailwind.config.js: */
/* .bg-\[\#3A2F91\] {
  background-color: #3A2F91;
}
.text-\[\#564AB1\] {
  color: #564AB1;
}
.text-purple-800 {
  color: #581C87; // Contoh nilai hex untuk purple-800
}
.text-green-300 {
  color: #B2F5EA; // Contoh nilai hex untuk green-300
} */

/* Untuk `prose` class, jika Anda menggunakan @tailwindcss/typography plugin, pastikan sudah diinstal */
/* npm install -D @tailwindcss/typography */
/* Kemudian tambahkan ke tailwind.config.js plugins */
/*
module.exports = {
  // ...
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
*/
</style>
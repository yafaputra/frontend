<template>
  <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-black min-h-screen flex flex-col font-sans text-white">
    <!-- Hero Section -->
    <section class="pt-32 pb-16 px-6 max-w-7xl mx-auto w-full">
      <h1 class="font-serif text-4xl md:text-5xl font-bold mb-8 text-gold text-center drop-shadow-lg">Tentang Parfum Elegance</h1>
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
          <p class="text-lg text-gray-300 leading-relaxed">
            Parfum Elegance adalah merek parfum premium yang lahir dari dedikasi untuk menghadirkan aroma berkualitas tinggi dengan sentuhan modern dan elegan. Kami percaya bahwa wewangian adalah ekspresi kepribadian yang dapat meningkatkan kepercayaan diri dan meninggalkan kesan mendalam.
          </p>
          <p class="text-lg text-gray-300 leading-relaxed">
            Setiap produk kami dirancang dengan teliti menggunakan bahan-bahan pilihan dan teknologi terkini untuk menghasilkan parfum yang tahan lama, unik, dan memikat.
          </p>
        </div>
        <div class="relative">
          <img 
            src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80" 
            alt="Parfum Elegance Collection" 
            class="rounded-3xl shadow-2xl border-4 border-gold/30"
          />
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 px-6 bg-gradient-to-r from-black/80 via-[#232946]/90 to-black/80">
      <div class="max-w-7xl mx-auto">
        <h2 class="font-serif text-3xl font-bold mb-12 text-gold text-center">Nilai-Nilai Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gold/20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="font-serif text-xl font-semibold text-gold mb-2">Kualitas Premium</h3>
            <p class="text-gray-400">Menggunakan bahan-bahan terbaik untuk hasil yang memuaskan</p>
          </div>
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gold/20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="font-serif text-xl font-semibold text-gold mb-2">Inovasi</h3>
            <p class="text-gray-400">Terus berinovasi untuk menghadirkan produk terbaik</p>
          </div>
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gold/20 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="font-serif text-xl font-semibold text-gold mb-2">Ketepatan Waktu</h3>
            <p class="text-gray-400">Pelayanan cepat dan tepat untuk kepuasan pelanggan</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 px-6">
      <div class="max-w-7xl mx-auto">
        <h2 class="font-serif text-3xl font-bold mb-12 text-gold text-center">Tim Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <img 
              src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80" 
              alt="CEO" 
              class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-gold/30"
            />
            <h3 class="font-serif text-xl font-semibold text-gold mb-1">Ahmad Rizki</h3>
            <p class="text-gray-400 mb-4">CEO & Founder</p>
            <p class="text-gray-300 text-sm">Pengalaman lebih dari 10 tahun di industri parfum</p>
          </div>
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <img 
              src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80" 
              alt="Perfumer" 
              class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-gold/30"
            />
            <h3 class="font-serif text-xl font-semibold text-gold mb-1">Sarah Wijaya</h3>
            <p class="text-gray-400 mb-4">Master Perfumer</p>
            <p class="text-gray-300 text-sm">Ahli dalam menciptakan aroma unik dan memikat</p>
          </div>
          <div class="bg-gradient-to-br from-[#232946] to-[#16161a] p-6 rounded-3xl text-center">
            <img 
              src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80" 
              alt="Marketing Director" 
              class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-gold/30"
            />
            <h3 class="font-serif text-xl font-semibold text-gold mb-1">Budi Santoso</h3>
            <p class="text-gray-400 mb-4">Marketing Director</p>
            <p class="text-gray-300 text-sm">Strategi pemasaran yang inovatif dan efektif</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "Tentang"
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap');

.font-serif { font-family: 'Playfair Display', serif; }
.font-sans { font-family: 'Inter', sans-serif; }
.bg-gold { background-color: #e5c07b; }
.text-gold { color: #e5c07b; }
.border-gold { border-color: #e5c07b; }
</style>


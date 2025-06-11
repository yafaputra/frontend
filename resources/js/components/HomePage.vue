<template>
  <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-black min-h-screen flex flex-col font-sans text-white">
    <!-- Hero Section -->
    <section class="flex flex-col-reverse md:flex-row items-center justify-between gap-10 pt-32 pb-16 px-6 max-w-7xl mx-auto w-full">
      <div class="flex-1 text-center md:text-left">
        <h1 class="font-serif text-4xl md:text-6xl font-bold mb-4 leading-tight tracking-tight text-gold drop-shadow-lg animate-fade-in">
          Temukan Aroma Khas Anda
        </h1>
        <p class="text-lg md:text-2xl text-gray-300 mb-8 font-light animate-fade-in delay-100">
          Parfum premium modern untuk gaya hidup elegan dan percaya diri.
        </p>
        <a href="#katalog" class="inline-block px-8 py-3 rounded-full bg-gold text-black font-semibold shadow-lg hover:scale-105 hover:shadow-gold/40 transition-all duration-200 animate-fade-in delay-200">
          Belanja Sekarang
        </a>
      </div>
      <div class="flex-1 flex items-center justify-center animate-fade-in delay-150">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80"
               alt="Botol Parfum Premium"
               class="w-80 h-96 object-cover rounded-3xl shadow-2xl border-4 border-gold/30 bg-black/30"
          />
          <span class="absolute -top-4 -right-4 bg-gold text-black text-xs font-bold px-3 py-1 rounded-full shadow-lg uppercase tracking-widest animate-bounce">Baru</span>
        </div>
      </div>
    </section>

    <!-- Product Showcase -->
    <section id="katalog" class="max-w-7xl mx-auto w-full px-6 pb-20">
      <h2 class="font-serif text-2xl md:text-3xl font-semibold mb-8 text-gold">Koleksi Unggulan</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-gradient-to-br from-[#232946] to-[#16161a] rounded-3xl shadow-xl p-6 flex flex-col items-center group hover:scale-105 transition-transform duration-200 relative"
        >
          <div class="relative w-40 h-40 mb-4">
            <img :src="product.image" :alt="product.name" class="object-cover w-full h-full rounded-2xl shadow-lg border-4 border-gold/20 group-hover:border-gold/60 transition" />
            <span v-if="product.isNew" class="absolute top-2 right-2 bg-gold text-black text-xs px-2 py-1 rounded-full shadow font-bold uppercase tracking-widest">Baru</span>
          </div>
          <h3 class="font-serif text-xl font-semibold text-gold mb-1 text-center">{{ product.name }}</h3>
          <p class="text-gray-400 text-center mb-4 text-sm">{{ product.description }}</p>
          <div class="flex items-center justify-between w-full">
            <span class="text-lg font-bold text-gold">{{ product.price }}</span>
            <button class="ml-4 px-4 py-2 bg-gold text-black rounded-full shadow hover:bg-white hover:text-gold transition font-semibold">Beli</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Brand Story -->
    <section id="brand-story" class="bg-gradient-to-r from-black/80 via-[#232946]/90 to-black/80 py-16 px-6">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="font-serif text-2xl md:text-3xl font-semibold mb-4 text-gold">Cerita Kami</h2>
        <p class="text-gray-300 text-lg font-light mb-4">
          Parfum Elegance lahir dari dedikasi untuk menghadirkan aroma premium yang menonjolkan karakter dan kepercayaan diri. Setiap botol dirancang dengan detail, menggunakan bahan terbaik, dan sentuhan modern untuk pengalaman wewangian tak terlupakan.
        </p>
        <div class="flex justify-center gap-6 mt-6">
          <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="hover:text-gold transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="2" y="2" width="20" height="20" rx="5" />
              <circle cx="12" cy="12" r="5" />
              <circle cx="17" cy="7" r="1.5" />
            </svg>
          </a>
          <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="hover:text-gold transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H6v4h4v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-black/90 py-8 px-6 mt-auto">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-gray-400 text-sm">
        <div>
          &copy; {{ new Date().getFullYear() }} Parfum Elegance. All rights reserved.
        </div>
        <div class="flex gap-4">
          <a href="#" class="hover:text-gold transition">Kebijakan Privasi</a>
          <a href="#" class="hover:text-gold transition">Syarat &amp; Ketentuan</a>
          <a href="mailto:info@parfumelegance.com" class="hover:text-gold transition">info@parfumelegance.com</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: "HomePage",
  data() {
    return {
      cartCount: 0,
      products: [
        {
          id: 1,
          name: "Aroma Classic",
          description: "Keharuman elegan dengan sentuhan floral dan woody, cocok untuk segala suasana.",
          price: "Rp 299.000",
          image: "https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 2,
          name: "Fresh Citrus",
          description: "Parfum segar dengan aroma citrus yang membangkitkan semangat sepanjang hari.",
          price: "Rp 259.000",
          image: "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=400&q=80",
          isNew: true
        },
        {
          id: 3,
          name: "Night Elegance",
          description: "Aroma misterius dan mewah, sempurna untuk malam yang berkesan.",
          price: "Rp 349.000",
          image: "https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 4,
          name: "Summer Breeze",
          description: "Kombinasi aroma buah dan bunga, memberikan kesan ceria dan energik.",
          price: "Rp 279.000",
          image: "https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 5,
          name: "Oriental Oud",
          description: "Wewangian oriental dengan sentuhan oud yang eksotis dan tahan lama.",
          price: "Rp 399.000",
          image: "https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80",
          isNew: true
        },
        {
          id: 6,
          name: "Romantic Rose",
          description: "Aroma mawar yang lembut dan romantis, cocok untuk hadiah spesial.",
          price: "Rp 319.000",
          image: "https://images.unsplash.com/photo-1465101178521-c1a9136a3b99?auto=format&fit=crop&w=400&q=80",
          isNew: false
        }
      ]
    };
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap');

.font-serif { font-family: 'Playfair Display', serif; }
.font-sans { font-family: 'Inter', sans-serif; }

.bg-gold { background-color: #e5c07b; }
.text-gold { color: #e5c07b; }
.border-gold { border-color: #e5c07b; }

a, button { outline: none; }

.animate-fade-in {
  animation: fadeIn 1s cubic-bezier(.4,0,.2,1) both;
}
.animate-bounce {
  animation: bounce 1.2s infinite alternate;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(24px);}
  to { opacity: 1; transform: none;}
}
@keyframes bounce {
  from { transform: translateY(0);}
  to { transform: translateY(-8px);}
}

/* Custom scrollbar for dark mode */
::-webkit-scrollbar {
  width: 8px;
  background: #232946;
}
::-webkit-scrollbar-thumb {
  background: #e5c07b;
  border-radius: 8px;
}

/* Responsive tweaks */
@media (max-width: 768px) {
  nav ul { display: none; }
  section { padding-left: 1rem; padding-right: 1rem; }
}
</style>

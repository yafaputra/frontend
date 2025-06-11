<template>
  <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-black min-h-screen flex flex-col font-sans text-white">
    <section class="pt-32 pb-16 px-6 max-w-7xl mx-auto w-full">
      <h1 class="font-serif text-3xl md:text-4xl font-bold mb-10 text-gold text-center drop-shadow-lg">Koleksi Parfum Elegance</h1>
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
  </div>
</template>

<script>
export default {
  name: "Produk",
  data() {
    return {
      products: [
        {
          id: 1,
          name: "Aroma Classic",
          description: "Keharuman elegan dengan sentuhan floral dan woody, cocok untuk segala suasana.",
          price: "Rp 299.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 2,
          name: "Fresh Citrus",
          description: "Parfum segar dengan aroma citrus yang membangkitkan semangat sepanjang hari.",
          price: "Rp 259.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
          isNew: true
        },
        {
          id: 3,
          name: "Night Elegance",
          description: "Aroma misterius dan mewah, sempurna untuk malam yang berkesan.",
          price: "Rp 349.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 4,
          name: "Summer Breeze",
          description: "Kombinasi aroma buah dan bunga, memberikan kesan ceria dan energik.",
          price: "Rp 279.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
          isNew: false
        },
        {
          id: 5,
          name: "Oriental Oud",
          description: "Wewangian oriental dengan sentuhan oud yang eksotis dan tahan lama.",
          price: "Rp 399.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
          isNew: true
        },
        {
          id: 6,
          name: "Romantic Rose",
          description: "Aroma mawar yang lembut dan romantis, cocok untuk hadiah spesial.",
          price: "Rp 319.000",
          image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80",
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

::-webkit-scrollbar {
  width: 8px;
  background: #232946;
}
::-webkit-scrollbar-thumb {
  background: #e5c07b;
  border-radius: 8px;
}
</style>

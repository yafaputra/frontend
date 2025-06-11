<template>

  <footer class="w-full bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-black/90 backdrop-blur-lg shadow-inner py-8 text-white border-t border-white/10">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
      <div class="text-center md:text-left mb-4 md:mb-0">
        <h2 class="text-2xl font-serif font-bold text-gold mb-2">Parfum Elegance</h2>
        <p class="text-gray-300 max-w-md">
          Temukan koleksi parfum eksklusif kami yang dirancang untuk menambah kepercayaan diri dan meninggalkan kesan tak terlupakan di setiap momen spesial Anda. Wewangian berkualitas, tahan lama, dan cocok untuk segala suasana.
        </p>
      </div>
      <div class="flex flex-col items-center md:items-end space-y-2">
        <span class="text-gray-400">Hubungi kami:</span>
        <a href="mailto:info@parfumelegance.com" class="text-gold hover:underline">info@parfumelegance.com</a>
        <a href="https://instagram.com/parfumelegance" target="_blank" class="text-gold hover:underline">@parfumelegance</a>
      </div>
    </div>
    <div class="mt-6 text-center text-gray-400 text-sm">
      &copy; {{ new Date().getFullYear() }} Parfum Elegance. All rights reserved.
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterBar",
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap');

.font-serif { font-family: 'Playfair Display', serif; }
.bg-gold { background-color: #e5c07b; }
.text-gold { color: #e5c07b; }
.border-gold { border-color: #e5c07b; }

footer {
  border-top: 1px solid rgba(255,255,255,0.08);
  font-family: 'Inter', sans-serif;
  /* Pastikan tidak ada margin-top default */
  margin-top: 0 !important;
  /* Pastikan footer menempel di bawah jika konten sedikit */
}
</style>

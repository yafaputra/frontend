<template>
  <nav class="flex items-center justify-between px-6 py-4 bg-black/60 backdrop-blur-md fixed w-full z-30 border-b border-white/10">
    <div class="flex items-center gap-2 flex-shrink-0">
      <!-- Inline SVG logo for consistency with homepage -->
      <span class="h-8 w-8 flex items-center justify-center rounded-full bg-gold shadow-lg mr-1 logo-hover">
        <svg viewBox="0 0 32 32" fill="none" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" aria-label="Logo Parfum Elegance">
          <circle cx="16" cy="16" r="15" stroke="#232946" stroke-width="2" fill="#e5c07b"/>
          <path d="M16 8v8l6 6" stroke="#232946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="16" cy="16" r="3" fill="#232946"/>
        </svg>
      </span>
      <span class="font-serif text-2xl font-bold tracking-wide text-gold brand-hover">Parfum Elegance</span>
    </div>
    <!-- Navigation Links -->
    <div class="hidden md:flex gap-8 text-sm font-medium">
      <router-link
        to="/"
        class="nav-link"
        exact-active-class="nav-link-active"
      >Beranda</router-link>
      <router-link
        to="/produk"
        class="nav-link"
        exact-active-class="nav-link-active"
      >Produk</router-link>
      <router-link
        to="/tentang"
        class="nav-link"
        exact-active-class="nav-link-active"
      >Tentang</router-link>
      <router-link
        to="/kontak"
        class="nav-link"
        exact-active-class="nav-link-active"
      >Kontak</router-link>
    </div>
    <!-- Auth Buttons -->
    <div class="flex items-center gap-4">
      <button class="ml-2 px-4 py-1.5 rounded-full border border-gold text-gold font-semibold btn-hover">Login</button>
      <button class="ml-2 px-4 py-1.5 rounded-full bg-gold text-black font-semibold border border-gold btn-hover-gold">Register</button>
    </div>
    <!-- Mobile menu button -->
    <div class="md:hidden flex items-center">
      <button @click="toggleMenu" class="text-gold hover:text-white focus:outline-none transition-colors duration-200">
        <svg v-if="!menuOpen" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <!-- Mobile Menu -->
    <div v-if="menuOpen" class="absolute top-full left-0 w-full md:hidden bg-black/90 backdrop-blur-md shadow-lg z-40">
      <div class="px-4 pt-4 pb-4 space-y-2">
        <router-link to="/" class="block px-3 py-2 rounded nav-link-mobile" exact-active-class="nav-link-active">Beranda</router-link>
        <router-link to="/produk" class="block px-3 py-2 rounded nav-link-mobile" exact-active-class="nav-link-active">Produk</router-link>
        <router-link to="/tentang" class="block px-3 py-2 rounded nav-link-mobile" exact-active-class="nav-link-active">Tentang</router-link>
        <router-link to="/kontak" class="block px-3 py-2 rounded nav-link-mobile" exact-active-class="nav-link-active">Kontak</router-link>
        <div class="flex flex-col space-y-2 mt-2">
          <button class="px-4 py-2 rounded-full border border-gold text-gold font-semibold btn-hover">Login</button>
          <button class="px-4 py-2 rounded-full bg-gold text-black font-semibold border border-gold btn-hover-gold">Register</button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      menuOpen: false
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap');

.font-serif { font-family: 'Playfair Display', serif; }
.bg-gold { background-color: #e5c07b; }
.text-gold { color: #e5c07b; }
.border-gold { border-color: #e5c07b; }

nav {
  border-bottom: 1px solid rgba(255,255,255,0.08);
  transition: background 0.3s, box-shadow 0.3s;
  font-family: 'Inter', sans-serif;
}

/* Minimalis & professional hover effect for nav links */
.nav-link {
  color: #fff;
  position: relative;
  padding-bottom: 2px;
  transition: color 0.18s cubic-bezier(.4,0,.2,1);
}
.nav-link::after {
  content: "";
  display: block;
  position: absolute;
  left: 0; right: 0; bottom: 0;
  height: 2px;
  background: #e5c07b;
  border-radius: 1px;
  transform: scaleX(0);
  transition: transform 0.18s cubic-bezier(.4,0,.2,1);
}
.nav-link:hover,
.nav-link:focus {
  color: #e5c07b;
}
.nav-link:hover::after,
.nav-link:focus::after {
  transform: scaleX(1);
}
.nav-link-active {
  color: #e5c07b !important;
}
.nav-link-active::after {
  transform: scaleX(1);
}

.nav-link-mobile {
  color: #fff;
  transition: color 0.18s cubic-bezier(.4,0,.2,1), background 0.18s cubic-bezier(.4,0,.2,1);
}
.nav-link-mobile:hover,
.nav-link-mobile:focus {
  color: #e5c07b;
  background: rgba(229,192,123,0.07);
}

.btn-hover {
  background: transparent;
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  box-shadow: none;
}
.btn-hover:hover,
.btn-hover:focus {
  background: #e5c07b;
  color: #232946;
  box-shadow: 0 2px 8px 0 rgba(229,192,123,0.08);
}
.btn-hover-gold {
  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
  box-shadow: none;
}
.btn-hover-gold:hover,
.btn-hover-gold:focus {
  background: #fff;
  color: #e5c07b;
  box-shadow: 0 2px 8px 0 rgba(229,192,123,0.08);
  border-color: #e5c07b;
}

/* Logo and brand hover (subtle scale and shadow) */
.logo-hover {
  transition: box-shadow 0.18s, transform 0.18s;
}
.logo-hover:hover,
.logo-hover:focus {
  box-shadow: 0 4px 16px 0 rgba(229,192,123,0.18);
  transform: scale(1.06);
}
.brand-hover {
  transition: color 0.18s, letter-spacing 0.18s;
}
.brand-hover:hover,
.brand-hover:focus {
  color: #fff;
  letter-spacing: 0.06em;
}
</style>

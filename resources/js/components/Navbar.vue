<template>
  <header class="bg-[#564AB1] shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex items-center justify-between px-4 py-3">
      <router-link to="/" class="logo flex items-center">
        <img src="/public/image/logo.png" alt="Logo" class="h-10" />
      </router-link>

      <!-- Hamburger Icon -->
      <button @click="toggleMenu"
        class="md:hidden flex flex-col justify-center items-center w-10 h-10 focus:outline-none">
        <span class="block w-6 h-0.5 bg-white mb-1"></span>
        <span class="block w-6 h-0.5 bg-white mb-1"></span>
        <span class="block w-6 h-0.5 bg-white"></span>
      </button>

      <!-- Navigation Links -->
      <ul
        :class="['nav-links', isMenuOpen ? 'flex' : 'hidden', 'md:flex', 'flex-col', 'md:flex-row', 'md:items-center', 'md:space-x-6', 'absolute', 'md:static', 'top-16', 'left-0', 'w-full', 'md:w-auto', 'bg-[#564AB1]', 'md:bg-transparent', 'shadow', 'md:shadow-none', 'px-6', 'md:px-0', 'py-4', 'md:py-0', 'transition-all', 'duration-300']">
        <li v-for="link in navLinks" :key="link.href">
          <router-link :to="link.href" class="flex items-center gap-2 hover:text-yellow-300"
            :class="[$route.path === link.href ? 'text-yellow-300 font-semibold active' : 'text-white']">
            <span v-html="link.icon"></span>
            {{ link.text }}
          </router-link>
        </li>

        <!-- Auth Buttons (Mobile) -->
        <li class="md:hidden flex flex-col space-y-2 mt-2">
          <router-link to="/login">
            <button class="login w-full">Login</button>
          </router-link>
          <router-link to="/register">
            <button class="signup w-full">Sign Up</button>
          </router-link>
        </li>
      </ul>

      <!-- Auth Buttons (Desktop) -->
      <div class="auth-buttons hidden md:flex space-x-3">
        <router-link to="/login">
          <button class="login">Login</button>
        </router-link>
        <router-link to="/register">
          <button class="signup">Sign Up</button>
        </router-link>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Navbar',
  data() {
    return {
      isMenuOpen: false,
      navLinks: [
        {
          text: 'Home',
          href: '/',
          icon: `<svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m-4 0h4"></path></svg>`
        },
        {
          text: 'Mentoring',
          href: '/mentoring',
          icon: `<svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 01-8 0M12 14v7m-7-7a7 7 0 0114 0v7H5v-7z"></path></svg>`
        },
        {
          text: 'Course',
          href: '/course',
          icon: `<svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M12 4v16m0 0H3"></path></svg>`
        },
        {
          text: 'Event',
          href: '/event',
          icon: `<svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8"></path></svg>`
        }
      ]
    };
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    }
  }
};
</script>

<style scoped>
/* Tambahkan jika perlu custom styling */
</style>

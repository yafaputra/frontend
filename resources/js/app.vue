<!-- App.vue -->
<template>
  <div>
    <Navbar />
    <router-view />
    <FooterBar />
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';
import FooterBar from './components/Footer.vue';

export default {
  components: {
    Navbar,
    FooterBar,
  }
};
</script>

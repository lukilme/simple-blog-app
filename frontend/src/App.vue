<script setup lang="ts">
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';

</script>

<template>
  <Header/>
  <main>
    <RouterView />
  </main>
  <Footer/>
</template>

<style scoped>

</style>

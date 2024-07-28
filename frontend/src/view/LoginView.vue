<template>
    <h1>Login</h1>
    <p>{{ name }}</p>
  </template>
  
  <script setup lang="ts">
  import { ref, watch, onMounted } from 'vue';
  
  const name = ref<string>(localStorage.getItem('name') || '');
  
  watch(name, (newName) => {
    localStorage.setItem('name', newName);
  });
  
  onMounted(() => {
    name.value = localStorage.getItem('name') || '';
  });
  </script>
  
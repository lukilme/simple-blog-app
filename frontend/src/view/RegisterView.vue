<template>
    <h1>Registrar</h1>
    <input v-model="text" placeholder="Digite seu nome" />
    <button @click="call">Enviar</button>
</template>
  
<script setup lang="ts">
    import { ref } from 'vue';
  
    const text = ref<string>(localStorage.getItem('name') || '');
  
    function call() {
      localStorage.setItem('name', text.value);
  }
</script>
  
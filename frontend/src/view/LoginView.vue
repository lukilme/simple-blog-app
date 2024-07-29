<template>
<div class="w-full pt-4 mt-10">
   <div id="login-form" class="max-w-md bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4 mx-auto">
      <div class="flex items-center rounded bg-blue-500 text-white justify-center my-2 p-1">
         <p class="font-bold">Login</p>
      </div>
      <div class="mb-4">
         <label class="block text-gray-700 text-sm font-bold mb-2" for="username"> Username </label>
         <input class="shadow appearance-none border rounded bg-white w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" placeholder="Enter your username!">
      </div>
      <div class="mb-6">
         <label class="block text-gray-700 text-sm font-bold mb-2" for="password"> Password </label>
         <input class="shadow appearance-none border rounded bg-white w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="********">
         <p class=" text-xs">Please enter your password.</p>
      </div>
      <div class="flex items-center justify-between">
         <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit"> Sign In </button>
         <button class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"> Forgot Password? </button>
      </div>
   </div>
</div>
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
  
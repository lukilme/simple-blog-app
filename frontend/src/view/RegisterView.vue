<template>
<div class="w-full pt-4 mt-10 px-1">
   <div id="register-form" class="max-w-md bg-white shadow-lg border-2 rounded px-4 pt-6 pb-6 mb-4 mx-auto" >
      <div class="flex items-center rounded bg-blue-500 justify-center mt-2 p-1">
         <p class="font-bold text-white">Register</p>
      </div>
      <div class="mb-2">
         <label class="block text-gray-700 text-base font-bold mb-2" for="name"> Name </label>
         <input class="shadow appearance-none text-base border rounded w-full py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline" 
            id="name-register" 
            name="name-register" 
            type="text" 
            placeholder="Name's user"> 
         <p class=' text-sm' id="name-info">The name cannot contain numbers.</p>
         <div class="my-1">
            <label class="block text-gray-700 text-base font-bold mb-2" for="username"> Username </label>
            <input  class="shadow appearance-none border text-base rounded w-full py-2 px-3 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline" 
               id="username-register" 
               name="username-register" 
               type="text" 
               placeholder="Username"> 
            <p class=' text-sm' id="username-info">The name cannot contain numbers.</p>
         </div>
         <div class="mb-2">
            <label class="block text-gray-700 text-base  font-bold mb-2" for="email"> Email </label>
            <input  class="shadow appearance-none border text-base  rounded w-full py-2 px-2 text-gray-700 bg-white leading-tight focus:outline-none focus:shadow-outline" 
               id="email-register" 
               name="email-register" 
               type="text" 
               placeholder="exemplo@gmail.com"> 
            <p class=' text-sm' id="email-info">The name cannot contain numbers.</p>
         </div>
         <div class="flex flex-row justify-between">
            <div class="mb-3 mr-2">
               <label class="block text-gray-700 text-base font-bold mb-1" for="password"> Password </label>
               <input  class="shadow appearance-none  border text-base bg-white rounded max-w-half py-2 px-2 text-gray-700 mb-1 leading-tight focus:outline-none focus:shadow-outline" 
                  id="password-register" 
                  name="password-register" 
                  type="password" 
                  placeholder="********"> 
               <p class=' text-sm' id="password-info">The name cannot contain numbers.</p>
            </div>
            <div class="mb-3 ml-2">
               <label class="block text-gray-700 text-base  font-bold mb-1" for="repeat-password"> Repeat the password </label>
               <input class="shadow appearance-none border text-base bg-white rounded max-w-half py-2 px-2 text-gray-700 mb-1 leading-tight focus:outline-none focus:shadow-outline" id="repeat-password-register" name="repeat-password-register" type="password" placeholder="********"> 
               <p class=' text-sm' id="repeat-password-info">The name cannot contain numbers.</p>
            </div>
         </div>
         <div class="flex items-center justify-center mt-2">
            <button id="button-registred" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="register()"> Done! </button>
         </div>
      </div>
   </div>
</div>
</template>
  
<script setup lang="ts">
    import { ref } from 'vue';
  
    const text = ref<string>(localStorage.getItem('name') || '');
  
    function call() {
      localStorage.setItem('name', text.value);
  }
</script>
  
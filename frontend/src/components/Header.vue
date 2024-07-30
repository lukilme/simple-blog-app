<template>
    <nav class="bg-white fixed w-full z-20 top-0 start-0 border-b border-gray-400">
      <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-4">
        <a href="/home" class="flex items-center text-blue-700 text-xl space-x-1 ml-2 rtl:space-x-reverse">
          <i class="fa-solid fa-calendar-check"></i>
          <span class="self-center text-2xl font-semibold whitespace-nowrap">TaskFlow</span>
        </a>
        <div class="flex md:order-2">
          <template v-if="!isLoggedIn">
            <RouterLink to="/register">
              <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Sign up</button>
            </RouterLink>
            <RouterLink to="/login">
              <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center mx-2">Log in</button>
            </RouterLink>
          </template>
          <template v-else>
            <button id="buttonMenu" data-collapse-toggle="navbar-sticky" type="button" class="inline-flex text-white bg-blue-700 items-center p-2 w-9 h-9 mx-1 justify-center text-sm rounded-lg md:hidden hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-sticky" aria-expanded="false">
              <span class="sr-only">Open main menu</span>
              <i class="fa-solid fa-bars"></i>
            </button>
            <div class="items-center justify-between hidden w-full mx-auto md:flex md:w-auto md:order-1" id="navbar-sticky">
          <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium font-extrabold border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
            <li>
              <a href="/home" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent hover:underline md:hover:text-blue-700 md:p-0" aria-current="page">Home</a>
            </li>
            <li>
              <a href="/tasks" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent hover:underline md:hover:text-blue-700 md:p-0">Tasks</a>
            </li>
            <li>
              <a href="/analysis" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent hover:underline md:hover:text-blue-700 md:p-0">Charts</a>
            </li>
            <li>
              <a href="/friends" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent hover:underline md:hover:text-blue-700 md:p-0">Friends</a>
            </li>
          </ul>
        </div>
          </template>
        </div>
        
      </div>
    </nav>
  </template>
  
  <script lang="ts">
  export default {
      name: 'Header',
      data() {
          return {
              isLoggedIn: false
          };
      },
      mounted() {
          this.isLoggedIn = this.checkLoginStatus();
      },
      methods: {
          checkLoginStatus(): boolean {
              // Lógica para verificar o status de login
              return false;
          }
      }
  };
  </script>
  
  
  <style scoped>
  /* Adicione seu estilo personalizado aqui, se necessário */
  </style>
  
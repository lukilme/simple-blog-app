import { createRouter, createWebHistory } from 'vue-router'

import HomeView from '../view/HomeView.vue'
import LoginView from '../view/LoginView.vue'
import RegisterView from '../view/RegisterView.vue'
import User from '../components/User.vue'
import PageNotFound from '../components/PageNotFound.vue'

const routes = [
  { path: '/home', component: HomeView },
  { path: '/', component: LoginView },
  { path: '/login', component: LoginView },
  { path: '/register', component: RegisterView },
  { path: '/users/:id', component: User },
  { path: "/:pathMatch(.*)*", component: PageNotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;
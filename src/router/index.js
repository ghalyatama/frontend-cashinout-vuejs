import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import store from '@/store'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/cashes',
    name: 'cashes.index',
    component: () => import(/* webpackChunkName: "about" */ '../views/cashes/Index.vue'),
    meta: { auth:true }
  },
  {
    path: '/cashes/:slug',
    name: 'cashes.show',
    component: () => import(/* webpackChunkName: "about" */ '../views/cashes/Show.vue'),
    meta: { auth:true }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "login" */ '../views/auth/Login.vue'),
    meta: { guest:true }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import(/* webpackChunkName: "register" */ '../views/auth/Register.vue'),
    meta: { guest:true }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  linkExactActiveClass: 'bg-blue-500 text-white rounded-lg'
})

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters['auth/authenticated']) router.push('/login')
  if (to.meta.guest && store.getters['auth/authenticated']) router.push('/')

  next()
})

export default router

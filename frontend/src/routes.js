import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('./views/Home.vue'),
  },
  {
    path: '/cart',
    name: 'ShoopingCart',
    component: () => import('./views/ShoppingCart.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router

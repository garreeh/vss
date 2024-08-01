import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import { isAuthenticated } from '../auth';

const routes: Array<RouteRecordRaw> = [
  { path: '/', redirect: '/login' }, // Redirect root to login page
  { path: '/login', component: () => import('../views/LoginPage.vue') },
  { path: '/home', component: () => import('../views/HomePage.vue') }, // Adjust as necessary
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const authenticated = isAuthenticated(); // Check if user is authenticated

  // If user is authenticated and trying to access login page, redirect to home
  if (authenticated && to.path === '/login') {
    next('/home'); // Redirect to home page
  } else if (!authenticated && to.path !== '/login') {
    // If user is not authenticated and trying to access a protected page, redirect to login
    next('/login');
  } else {
    next(); // Allow navigation if authenticated or if route is login
  }
});


export default router;

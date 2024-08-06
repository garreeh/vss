import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import { isAuthenticated } from '../auth';

const routes: Array<RouteRecordRaw> = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: () => import('../views/LoginPage.vue') },
  { path: '/register', component: () => import('../views/RegisterPage.vue') },
  { path: '/forgot-password', component: () => import('../views/ForgotPassPage.vue') },
  { path: '/home', component: () => import('../views/HomePage.vue') },
  
  // This is specific Pet Page.
  {
    path: '/pet-details/:petName',
    name: 'PetDetails',
    component: () => import('../views/ViewPetPage.vue'),
  },

  { path: '/pet', component: () => import('../views/PetPage.vue') },
  { path: '/patient', component: () => import('../views/PatientPage.vue') },
  { path: '/case', component: () => import('../views/CasePage.vue') },
  { path: '/billing', component: () => import('../views/BillingPage.vue') },
  { path: '/setup/services', component: () => import('../views/setup/ServicesPage.vue') },
  { path: '/setup/vaccine', component: () => import('../views/setup/UsersPage.vue') },
  { path: '/setup/user', component: () => import('../views/setup/VaccinePage.vue') },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const authenticated = isAuthenticated();

  // This is for testing only.
  // console.log(`Navigating to: ${to.path}, Authenticated: ${authenticated}`);

  const publicPages = ['/login', '/register', '/forgot-password'];

  if (!authenticated && !publicPages.includes(to.path)) {
    next('/login');
  } else if (authenticated && publicPages.includes(to.path)) {
    next('/home');
  } else {
    next();
  }
});

export default router;

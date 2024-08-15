import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import { isAuthenticated } from '../auth';

// FOR DEBUGGING
const routes: Array<RouteRecordRaw> = [
  { path: '/', redirect: '/login'  },
  { path: '/login', component: () => import('../views/LoginPage.vue') },
  { path: '/home', component: () => import('../views/HomePage.vue') },
  
  // This is specific for Pet Page.
  {
    path: '/pet-details/:petName',
    name: 'PetDetails',
    component: () => import('../views/ViewPetPage.vue'),
  },

  // This is specific for Case Details Page.
  {
    path: '/case-details/:case_id',
    name: 'CaseDetails',
    component: () => import('../components/petinfo/PetCaseDetailsPage.vue'),
  },

  // { path: '/setup/services', component: () => import('../views/setup/ServicesPage.vue') },
  // { path: '/setup/vaccine', component: () => import('../views/setup/UsersPage.vue') },
  // { path: '/setup/user', component: () => import('../views/setup/VaccinePage.vue') },

  
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const authenticated = isAuthenticated();

  // This is for testing only.
  // console.log(`Navigating to: ${to.path}, Authenticated: ${authenticated}`);

  const publicPages = ['/login', /* You can add another here. */];

  if (!authenticated && !publicPages.includes(to.path)) {
    next('/login');
  } else if (authenticated && publicPages.includes(to.path)) {
    next('/home');
  } else {
    next();
  }
});

export default router;

<template>
  <ion-content>
    <ion-header>
      <ion-toolbar>
        <ion-title>Home</ion-title>
        <ion-buttons slot="end">
          <ion-button @click="logout">Logout</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-grid>
      <ion-row>
        <ion-col>
          <h1>Welcome Home!</h1>
        </ion-col>
      </ion-row>
    </ion-grid>
  </ion-content>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
  // Remove the token from localStorage
  localStorage.removeItem('authToken');
  
  // Redirect to login page
  router.push('/login');
};
</script>

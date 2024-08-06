<template>
  <ion-header :translucent="true">
    <ion-toolbar>
      <ion-buttons slot="start">
        <ion-menu-button color="primary"></ion-menu-button>
      </ion-buttons>
      <ion-title> VSS Application </ion-title>
      <ion-buttons slot="end">
        <ion-button @click="logout">Logout</ion-button>
      </ion-buttons>
    </ion-toolbar>
  </ion-header>
</template>

<script setup lang="ts">
import { IonButtons, IonHeader, IonMenuButton, IonTitle, IonToolbar, IonButton } from '@ionic/vue';

const logout = () => {
  // Remove the token from localStorage
  localStorage.removeItem('authToken');
  localStorage.removeItem('clientId');
  localStorage.removeItem('clientFirstname');
  localStorage.removeItem('clientLastname');
  
  // Redirect to login page
  window.location.href = '/login';
};
</script>

<style scoped>
/* Add any styles specific to the header component here */
</style>

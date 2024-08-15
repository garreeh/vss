<template>
  <ion-header :translucent="true">
    <ion-toolbar>
      <ion-buttons slot="start">
        <ion-menu-button color="primary" class="menu-button"></ion-menu-button>
      </ion-buttons>
      <ion-title class="title">
        {{ databaseDisplayName }}
      </ion-title>
      <ion-buttons slot="end">
        <ion-button @click="logout" class="logout-button">Logout</ion-button>
      </ion-buttons>
    </ion-toolbar>
  </ion-header>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { IonButtons, IonHeader, IonMenuButton, IonTitle, IonToolbar, IonButton } from '@ionic/vue';

const databaseDisplayName = ref('Unknown Database');

onMounted(() => {
  const displayName = localStorage.getItem('databaseDisplayName') || 'Unknown Database';
  databaseDisplayName.value = displayName;
});

const logout = () => {
  // Remove the token from localStorage
  localStorage.removeItem('authToken');
  localStorage.removeItem('clientId');
  localStorage.removeItem('clientFirstname');
  localStorage.removeItem('clientLastname');

  // For debugging
  window.location.href = '/login';

  // For Production
  // window.location.href = '/ionic/dist';
};
</script>

<style scoped>
/* Header styling */
.ion-header {
  position: relative;
  z-index: 1; /* Ensure header is on top */
}

/* Style for menu button */
.menu-button {
  z-index: 2; /* Ensure menu button is above other elements */
}

/* Style for title */
.title {
  z-index: 1; /* Title is on the same level as header */
}

/* Style for logout button */
.logout-button {
  z-index: 2; /* Ensure logout button is above other elements */
}

/* If you have an image in the header that needs to be moved to the front */
.header-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100px; /* Adjust size as needed */
  height: auto;
  z-index: 3; /* Ensure image is on top */
}
</style>

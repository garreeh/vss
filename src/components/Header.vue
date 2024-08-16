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

  <!-- Loader -->
  <ion-loading
    :is-open="loading"
    message="Logging out..."
    spinner="crescent"
  ></ion-loading>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { IonButtons, IonHeader, IonMenuButton, IonTitle, IonToolbar, IonButton, IonLoading } from '@ionic/vue';
import { useRoute, useRouter } from 'vue-router';

const databaseDisplayName = ref('Unknown Database');
const loading = ref(false); // State for loader
const router = useRouter(); // Use this for navigation


onMounted(() => {
  const displayName = localStorage.getItem('databaseDisplayName') || 'Unknown Database';
  databaseDisplayName.value = displayName;
});

const logout = () => {
  loading.value = true; // Show loader

  setTimeout(() => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('clientId');
    localStorage.removeItem('clientFirstname');
    localStorage.removeItem('clientLastname');
    localStorage.removeItem('databaseDisplayName');
    localStorage.removeItem('databaseName');

    const url = `/login`;

    // Navigate to the case details page
    router.push(url);

    loading.value = false; // Hide loader
  }, 1000); // Simulate a delay for logout process
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

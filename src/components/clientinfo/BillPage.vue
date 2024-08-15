<template>
  <div class="bill-page">
    <ion-grid class="no-pets">
      <ion-row class="ion-justify-content-center ion-align-items-center">
        <ion-col size="12" md="8">
          <div class="background-container">
            <img src="./../../images/svg/coming-soon.svg" alt="No Pets" class="no-pets-svg" />
            <h2>Coming Soon</h2>
          </div>
        </ion-col>
      </ion-row>
    </ion-grid>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { IonCol, IonRow, IonGrid, } from '@ionic/vue';

// Data references
const pets = ref([]);
const loading = ref(true); // Add a loading state

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    
    if (!clientId || !databaseName) {
      console.error('Client ID or database name is missing in local storage.');
      loading.value = false; // Stop loading if data cannot be fetched
      return;
    }
    
    const response = await apiService.fetchPatientData(clientId, databaseName);
    if (response.data.status === 'success') {
      pets.value = response.data.data; // Handle array of pets
    } else {
      console.error('Error:', response.data.message);
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false; // Stop loading regardless of success or failure
  }
};

const goToPetDetails = (pet) => {
  // Construct the URL using the pet's name
  const petName = encodeURIComponent(pet.patient_name);
  const url = `/pet-details/${petName}`;

  // This is for testing only
  // console.log(url)
  window.location.href = url;
};

// Fetch data when component is mounted
onMounted(() => {
  fetchDataAndDisplay();
});
</script>

<style scoped>
.bill-page {
  padding: 15px;
  text-align: center;
}

.no-pets-svg {
  margin-bottom: 10px;
  pointer-events: none; /* Prevents clicking */
}

@media (min-width: 768px) {
  .bill-page {
    padding: 30px;
  }

  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}

@media (min-width: 1024px) {
  .bill-page {
    padding: 50px;
  }

  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}
</style>


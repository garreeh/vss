<template>
  <div class="info-page">
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>
    <!-- Check if there are any pets -->
    <div v-else-if="pets.length > 0" class="pets-list">
      <ion-card
        v-for="(pet, index) in pets"
        :key="index"
        class="info-item"
      >
        <ion-item lines="none" class="pet-item">
          <ion-icon :icon="pawSharp" class="pet-icon"></ion-icon>
          <ion-label>
            <p class="pet-name">Brand: {{ pet.brand || 'No Details' }}</p>
            <p class="pet-name">Vaccine Date: {{ pet.date_vaccine || 'No Details' }}</p>
            <p class="pet-name">Vaccine Expiration: {{ pet.expiration || 'No Details' }}</p>
            <p class="pet-name">Deworm: {{ pet.deworming || 'No Details' }}</p>
          </ion-label>
        </ion-item>
      </ion-card>
    </div>

    <div v-else class="no-cases-message">
      <img src="./../../images/svg/no-data.svg" alt="No Pets" class="no-pets-svg" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRouter, useRoute } from 'vue-router';
import { pawSharp } from 'ionicons/icons';
import { IonLabel, IonIcon, IonCard, IonItem } from '@ionic/vue';

// Data references
const pets = ref([]);
const route = useRoute();
const loading = ref(true); // Add a loading state

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const petName = route.params.petName; // Get petName from route params

    if (!clientId || !databaseName || !petName) {
      console.error('Client ID, database name, or pet name is missing.');
      loading.value = false; // Stop loading if data cannot be fetched
      return;
    }

    const response = await apiService.fetchVaccineData(clientId, databaseName, petName);

    console.log(response);

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

// Fetch data when component is mounted
onMounted(() => {
  fetchDataAndDisplay();
});
</script>

<style scoped>
.info-page {
  padding: 15px;
  text-align: center;
}

.pets-list {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.info-item {
  width: 100%;
  max-width: 600px;
  margin-bottom: 10px;
  cursor: pointer; /* Makes the cursor clickable */
}

.pet-item {
  display: flex;
  align-items: center;
}

.pet-icon {
  font-size: 24px;
  color: #333;
  margin-right: 10px; /* Space between icon and text */
}

.pet-name {
  font-size: 16px;
  color: #666;
}

.no-pets {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.no-pets-svg {
  margin-bottom: 10px;
  pointer-events: none; /* Prevents clicking */
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .pet-name {
    font-size: 18px;
  }

  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .pet-name {
    font-size: 20px;
  }

  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}
</style>
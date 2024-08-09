<template>
  <div class="info-page">
    <!-- Check if there are any pets -->
    <div v-if="pets.length > 0" class="pets-list">
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

    <!-- Show a message if there are no pets -->
    <div v-else class="no-cases-message">
      <p>No cases found for this patient.</p>
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

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const petName = route.params.petName; // Get petName from route params

    if (!clientId || !databaseName || !petName) {
      console.error('Client ID, database name, or pet name is missing.');
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

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .pet-name {
    font-size: 18px;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .pet-name {
    font-size: 20px;
  }
}
</style>

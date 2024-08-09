<template>
  <div class="info-page">
    <!-- Check if there are any pets -->
    <div v-if="pets.length > 0" class="pets-list">
      <ion-card
        v-for="(pet, index) in pets"
        :key="index"
        class="info-item"
        @click="goToCaseDetails(pet)"
      >
        <ion-item lines="none" class="pet-item">
          <ion-icon :icon="pawSharp" class="pet-icon"></ion-icon>
          <ion-label>
            <p class="pet-name">Patient ID: {{ pet.patient_id }}</p>
            <p class="pet-name">Case ID: {{ pet.case_id }}</p>
            <p class="pet-name">Case Date: {{ pet.case_date }}</p>
            <p class="pet-name">Chief Complaint: {{ pet.case_chief_complaint }}</p>
            <p class="pet-name">Pet Name: {{ pet.patient_name }}</p>
          </ion-label>
        </ion-item>
      </ion-card>
    </div>

    <!-- Show a message if there are no pets -->
    <!-- <div v-else class="no-cases-message">
      <p>No cases found for this patient.</p>
    </div> -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRoute, useRouter } from 'vue-router';
import { pawSharp } from 'ionicons/icons';
import { IonLabel, IonIcon, IonCard, IonItem } from '@ionic/vue';

// Data references
const pets = ref([]);
const route = useRoute(); // Use this if you need access to route parameters
const router = useRouter(); // Use this for navigation

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');

    if (!clientId || !databaseName) {
      console.error('Client ID, database name, or Case ID is missing.');
      return;
    }

    // Fetch pet details
    const response = await apiService.fetchCaseData(clientId, databaseName);

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

const goToCaseDetails = (pet) => {
  // Construct the URL using the pet's case_id
  const petCase = encodeURIComponent(pet.case_id);
  const url = `/case-details/${petCase}`;

  // Navigate to the case details page
  router.push(url);
};
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

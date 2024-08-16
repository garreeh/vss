<template>
  <div class="info-page">
    <!-- Show loading indicator while data is being fetched -->
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>
    
    <!-- Show pets list if there are pets -->
    <div v-else-if="pets.length > 0" class="pets-list">
      <ion-card v-for="(pet, index) in pets" :key="index" class="info-item" @click="goToPetDetails(pet)">
        <ion-item lines="none" class="pet-item">
          <ion-icon :icon="pawSharp" class="pet-icon"></ion-icon>
          <ion-label>
            <p class="pet-name">{{ pet.patient_name }}</p>
          </ion-label>
        </ion-item>
        <!-- Click to View text -->
        <div class="view-container">
          <div class="view-details">
            <span>Click to View</span>
          </div>
        </div>
      </ion-card>
    </div>
    
    <!-- Show no pets message if there are no pets -->
    <div v-else class="no-pets">
      <img src="./../../images/svg/no-data.svg" alt="No Pets" class="no-pets-svg" />
    </div>

    <!-- IonLoading Component -->
    <ion-loading
      :is-open="showLoader"
      message="Loading pet details..."
      spinner="crescent"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRouter } from 'vue-router';
import { pawSharp } from 'ionicons/icons';
import { IonLabel, IonIcon, IonCard, IonItem, IonLoading } from '@ionic/vue';

// Data references
const pets = ref([]);
const loading = ref(true); // Add a loading state
const showLoader = ref(false); // Loading spinner state
const router = useRouter(); // Use this for navigation

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
  showLoader.value = true; // Show the loading spinner
  
  // Construct the URL using the pet's name
  const petName = encodeURIComponent(pet.patient_name);

  // Production
  const url = `/pet-details/${petName}`;

  // This is for testing only
  // console.log(url)
  // window.location.href = url;

  // Simulate a delay for loading (you can remove this in production)
  setTimeout(() => {
    showLoader.value = false; // Hide the loading spinner
    router.push(url); // Navigate to the pet details page
  }, 1000); // Adjust the timeout as needed, or remove if not needed
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
  position: relative; /* For positioning the "Click to View" text */
}

.pet-item {
  display: flex;
  align-items: center;
}

.loading {
  font-size: 20px;
  color: #666;
  margin: 20px;
}

.pet-icon {
  font-size: 24px;
  color: #007bff; /* Blue color for the icon */
  margin-right: 10px; /* Space between icon and text */
}

.pet-name {
  font-size: 16px;
  color: #666;
}

.view-container {
  position: relative;
}

.view-details {
  bottom: 0;
  left: 0;
  right: 0;
  text-align: center;
  background-color: #f0f8ff;
  padding: 10px;
  border-top: 1px solid #ddd;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  font-size: 14px;
  color: #007bff; /* Blue color for the text */
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s, transform 0.3s, box-shadow 0.3s;
}

.info-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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

<template>
  <div class="info-page">
    <div v-if="pets.length > 0" class="pets-list">
      <ion-card v-for="(pet, index) in pets" :key="index" class="info-item" @click="goToPetDetails(pet)">
        <ion-item lines="none" class="pet-item">
          <ion-icon :icon="pawSharp" class="pet-icon"></ion-icon>
          <ion-label>
            <p class="pet-name">{{ pet.patient_name }}</p>
          </ion-label>
        </ion-item>
      </ion-card>
    </div>
    <!-- <p v-else>No pets found for this client.</p> -->
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, defineProps  } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { pawSharp } from 'ionicons/icons';
import {  IonLabel,
  IonIcon,
  IonCard,
  IonItem,        
} from '@ionic/vue';

// Define the Pet type
interface Pet {
  patient_name: string;
}

// Data references
const pets = ref<Pet[]>([]);
const router = useRouter();

const fetchDataAndDisplay = async () => {
  try {
    // Retrieve client_id from local storage
    const clientId = localStorage.getItem('clientId');
    
    if (!clientId) {
      console.error('Client ID is missing in local storage.');
      return;
    }
    
    const response = await axios.post('http://192.168.100.102/IonicProject/vss/backend/pet_display.php', {
      client_id: parseInt(clientId, 10)
    });

    if (response.data.status === 'success') {
      pets.value = response.data.data; // Handle array of pets

    } else {
      console.error('Error:', response.data.message);
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const goToPetDetails = (pet: Pet) => {
  // Navigate to pet details page with the pet name as a parameter
  // router.push({ name: 'PetDetails', params: { petName: pet.patient_name } });

  // Construct the URL using the pet's name
  const petName = encodeURIComponent(pet.patient_name);
  const url = `/pet-details/${petName}`;

  console.log(url)
  
  // Navigate to the URL
  window.location.href = url;
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


<template>
  <div class="info-page">
    <!-- Loader when navigating -->
    <ion-loading :is-open="isNavigating" message="Loading case details..." spinner="crescent"></ion-loading>

    <!-- Loader while data is being fetched -->
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>

    <!-- Pets list -->
    <div v-else-if="pets.length > 0" class="pets-list">
      <ion-card
        v-for="(pet, index) in pets"
        :key="index"
        class="info-item"
        @click="goToCaseDetails(pet)"
      >
        <ion-item lines="none" class="pet-item">
          <ion-label class="pet-details">
            <div class="pet-detail">
              <ion-icon :icon="documentTextOutline" class="detail-icon"></ion-icon>
              <div class="pet-detail-info">
                <span class="detail-text">Case ID:</span>
                <span class="detail-data">{{ pet.case_id }}</span>
              </div>
            </div>
            <div class="pet-detail">
              <ion-icon :icon="calendarOutline" class="detail-icon"></ion-icon>
              <div class="pet-detail-info">
                <span class="detail-text">Case Date:</span>
                <span class="detail-data">{{ pet.case_date }}</span>
              </div>
            </div>
            <div class="pet-detail">
              <ion-icon :icon="medkitOutline" class="detail-icon"></ion-icon>
              <div class="pet-detail-info">
                <span class="detail-text">Chief Complaint:</span>
                <span class="detail-data">{{ pet.case_chief_complaint }}</span>
              </div>
            </div>
          </ion-label>
        </ion-item>
        <div class="view-details">
          <span>Click to View Details</span>
        </div>
      </ion-card>
    </div>


    <!-- No pets message -->
    <div v-else class="no-cases-message">
      <img src="./../../images/svg/no-data.svg" alt="No Pets" class="no-pets-svg" />
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRoute, useRouter } from 'vue-router';

import { IonLabel, IonIcon, IonCard, IonItem, IonLoading } from '@ionic/vue';

import { 
  fingerPrintOutline, 
  documentTextOutline,
  calendarOutline,
  medkitOutline,
  pawOutline 
} from 'ionicons/icons'; // Import icons from ionicons

const pets = ref([]);
const route = useRoute(); 
const router = useRouter(); 
const loading = ref(true); 
const isNavigating = ref(false); 

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');

    if (!clientId || !databaseName) {
      console.error('Client ID, database name, or Case ID is missing.');
      loading.value = false; 
      return;
    }

    const response = await apiService.fetchCaseData(clientId, databaseName);

    if (response.data.status === 'success') {
      pets.value = response.data.data;
    } else {
      console.error('Error:', response.data.message);
    }

  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDataAndDisplay();
});

const goToCaseDetails = (pet) => {
  isNavigating.value = true;
  const petCase = encodeURIComponent(pet.case_id);
  const url = `/case-details/${petCase}`;
  router.push(url).finally(() => {
    isNavigating.value = false;
  });
};
</script>

<style scoped>
.info-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
}

.pets-list {
  width: 100%;
  max-width: 700px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.loading {
  font-size: 20px;
  color: #666;
  margin: 20px;
}

.info-item {
  width: 100%;
  max-width: 600px;
  margin-bottom: 15px;
  cursor: pointer;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border-radius: 12px;
  background: #fff;
}

.info-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.pet-item {
  display: flex;
  align-items: center;
  padding: 16px;
}

.pet-detail {
  display: flex;
  align-items: flex-start; /* Align items to start vertically */
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #eee;
}

.detail-icon {
  font-size: 24px;
  color: #007bff;
  margin-right: 16px; /* Space between icon and text */
  margin-top: 8px; /* Adjusts vertical alignment of icon */
  flex-shrink: 0;
}


.pet-detail-info {
  display: flex;
  flex-direction: column; /* Stack the text and data vertically */
}

.detail-text {
  font-size: 16px;
  color: #555;
  font-weight: bolder;
  margin-bottom: 4px;
  word-wrap: break-word; /* Ensures long text wraps */
}

.detail-data {
  font-size: 16px;
  color: #333;
  font-weight: 400;
}

.view-details {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 12px;
  background-color: #f0f8ff;
  border-top: 1px solid #ddd;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  cursor: pointer;
}

.view-details-icon {
  font-size: 24px;
  color: #007bff;
  margin-right: 8px;
}

.view-details span {
  font-size: 16px;
  color: #007bff;
}

.no-cases-message {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.no-pets-svg {
  width: 100%;
  max-width: 320px;
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .view-details span {
    font-size: 18px;
  }

  .no-pets-svg {
    width: 360px;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .view-details span {
    font-size: 20px;
  }

  .no-pets-svg {
    width: 400px;
  }
}
</style>

<template>
  <div class="info-page">
    <!-- Loader while data is being fetched -->
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>

    <!-- Pets list -->
    <div v-else-if="pets.length > 0" class="details-card">
      <ion-card
        v-for="(pet, index) in pets"
        :key="index"
        class="info-item"
      >
        <ion-item lines="none" class="info-item-content">
          <ion-label class="info-details">
            <div class="info-detail">
              <ion-icon :icon="pawSharp" class="detail-icon"></ion-icon>
              <span class="detail-text">Brand:</span>
              <span class="detail-data">{{ pet.brand || 'No Details' }}</span>
            </div>
            <div class="info-detail">
              <ion-icon :icon="calendarOutline" class="detail-icon"></ion-icon>
              <span class="detail-text">Vaccine Date:</span>
              <span class="detail-data">{{ pet.date_vaccine || 'No Details' }}</span>
            </div>
            <div class="info-detail">
              <ion-icon :icon="calendarOutline" class="detail-icon"></ion-icon>
              <span class="detail-text">Vaccine Expiration:</span>
              <span class="detail-data">{{ pet.expiration || 'No Details' }}</span>
            </div>
            <div class="info-detail">
              <ion-icon :icon="checkmarkDoneOutline" class="detail-icon"></ion-icon>
              <span class="detail-text">Deworm:</span>
              <span class="detail-data">{{ pet.deworming || 'No Details' }}</span>
            </div>
          </ion-label>
        </ion-item>
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
import { useRoute } from 'vue-router';
import { pawSharp, calendarOutline, checkmarkDoneOutline } from 'ionicons/icons';
import { IonLabel, IonIcon, IonCard, IonItem } from '@ionic/vue';

const pets = ref([]);
const route = useRoute();
const loading = ref(true);

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const petName = route.params.petName;

    if (!clientId || !databaseName || !petName) {
      console.error('Client ID, database name, or pet name is missing.');
      loading.value = false;
      return;
    }

    const response = await apiService.fetchVaccineData(clientId, databaseName, petName);

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
</script>

<style scoped>
.info-page {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 15px;
}

.details-card {
  width: 100%;
  max-width: 600px;
}

.info-item {
  border-radius: 12px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  background: #fff;
}

.info-item-content {
  padding: 16px;
}

.info-details {
  display: flex;
  flex-direction: column;
}

.info-detail {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #eee;
}

.detail-icon {
  font-size: 24px;
  color: #007bff;
  margin-right: 12px; /* Space between icon and label */
}

.detail-text {
  font-size: 13px;
  color: #555;
  flex-grow: 1;
  font-weight: 500;

}

.detail-data {
  font-size: 12px;
  color: #333;
}

.loading {
  font-size: 20px;
  color: #666;
  margin: 20px;
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }
}
</style>


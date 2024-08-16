<template>
  <div class="info-page">
    <!-- Loader while data is being fetched -->
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>

    <!-- Content when data is available -->
    <div v-else class="details-card">
      <ion-card class="info-item">
        <ion-item lines="none" class="info-item-content">
          <ion-label class="info-details">
            <div v-for="(item, index) in details" :key="index" class="info-detail">
              <ion-icon :icon="item.icon" class="detail-icon"></ion-icon>
              <div class="detail-text-container">
                <span class="detail-text">{{ item.label }}:</span>
                <span class="detail-data">{{ item.value }}</span>
              </div>
            </div>
          </ion-label>
        </ion-item>
      </ion-card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRoute } from 'vue-router';
import { IonIcon, IonLabel, IonItem, IonCard } from '@ionic/vue';
import { 
  locationOutline, 
  mailOutline,
  callOutline
} from 'ionicons/icons'; // Import icons from ionicons

const loading = ref(true);
const details = ref([]);

const route = useRoute();

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');

    if (!clientId || !databaseName) {
      console.error('Client ID or database name is missing.');
      loading.value = false;
      return;
    }

    const response = await apiService.fetchClientData(clientId, databaseName);

    if (response.data.status === 'success') {
      const { client_address, client_email, client_mobile, client_home } = response.data.data;
      details.value = [
        { icon: locationOutline, label: 'Address', value: client_address || 'No Address Provided' },
        { icon: mailOutline, label: 'Email', value: client_email || 'No Email Provided' },
        { icon: callOutline, label: 'Mobile', value: client_mobile || 'No Mobile Phone Provided' },
        { icon: callOutline, label: 'Telephone', value: client_home || 'No Telephone Provided' },
      ];
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
  align-items: flex-start;
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

.detail-text-container {
  display: flex;
  flex-direction: column;
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

.loading {
  font-size: 20px;
  color: #666;
  margin: 20px;
}

@media (max-width: 767px) {
  .info-detail {
    align-items: flex-start;
  }

  .detail-icon {
    margin-right: 16px;
    margin-top: 0;
  }

  .detail-text-container {
    display: flex;
    flex-direction: column;
    width: 80%;
  }

  .detail-text {
    margin-bottom: 4px;
    font-size: 14px;
  }

  .detail-data {
    font-size: 14px;
  }
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


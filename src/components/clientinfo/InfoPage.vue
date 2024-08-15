<template>
  <div class="info-page">
    <ion-card class="info-card">
      <ion-card-content>
        <ul class="info-list">
          <li>
            <span class="label">Address:</span>
            <span class="data">{{ address }}</span>
          </li>
          <li>
            <span class="label">Email:</span>
            <span class="data">{{ email }}</span>
          </li>
          <li>
            <span class="label">Mobile:</span>
            <span class="data">{{ mobile }}</span>
          </li>
          <li>
            <span class="label">Telephone:</span>
            <span class="data">{{ telephone }}</span>
          </li>
        </ul>
      </ion-card-content>
    </ion-card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { IonCard, IonCardHeader, IonCardTitle, IonCardContent } from '@ionic/vue';
import apiService from './../../apiServices/apiService.js';

// Data references
const address = ref('');
const email = ref('');
const mobile = ref('');
const telephone = ref('');

// Fetch data and update state
const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');

    if (!clientId || !databaseName) {
      console.error('Client ID or database name is missing in local storage.');
      return;
    }

    const response = await apiService.fetchClientData(clientId, databaseName);

    if (response.data.status === 'success') {
      const { client_address, client_email, client_mobile, client_home } = response.data.data;
      address.value = client_address || 'No Address Provided';
      email.value = client_email || 'No Email Provided';
      mobile.value = client_mobile || 'No Mobile Phone Provided';
      telephone.value = client_home || 'No Telephone Provided';

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
  display: flex;
  justify-content: center;
  padding: 16px;
}

.info-card {
  width: 100%;
  max-width: 600px;
}

ion-card-header {
  background-color: var(--ion-color-primary);
  color: white;
}

ion-card-title {
  font-size: 1.5rem;
}

.info-list {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.info-list li {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #ccc;
}

.label {
  font-weight: bold;
}

.data {
  text-align: right;
  color: var(--ion-color-dark);
}

@media (min-width: 768px) {
  .info-card {
    padding: 24px;
  }

  .info-list li {
    padding: 12px 0;
  }

  ion-card-title {
    font-size: 2rem;
  }
}
</style>

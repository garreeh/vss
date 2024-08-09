<template>
  <div class="info-page">
    <div class="info-item">
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
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
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
  padding: 20px;
  text-align: left;
}

.info-page h2 {
  font-size: 24px;
  margin-bottom: 20px;
}

.info-list {
  list-style: none;
  padding: 0;
}

.info-list li {
  display: flex;
  margin-bottom: 15px;
  padding: 5px 0;
}

.info-list .label {
  flex: 1;
  font-weight: bold;
}

.info-list .data {
  flex: 2;
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .info-page h2 {
    font-size: 28px;
  }

  .info-list .label, .info-list .data {
    font-size: 18px;
  }

  .info-list li {
    margin-bottom: 20px;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .info-page h2 {
    font-size: 32px;
  }

  .info-list .label, .info-list .data {
    font-size: 20px;
  }
}
</style>

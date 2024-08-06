<template>
  <div class="info-page">
    <div class="Case">
      <p><strong>Address:</strong></p>
      <p>{{ address }}</p>
    </div>
    <div class="info-item">
      <p><strong>Email:</strong></p>
      <p>{{ email }}</p>
    </div>
    <div class="contact-info">
      <div class="info-item">
        <p><strong>Mobile:</strong></p>
        <p>{{ mobile }}</p>
      </div>
      <div class="info-item">
        <p><strong>Telephone:</strong></p>
        <p>{{ telephone }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Data references
const address = ref('');
const email = ref('');
const mobile = ref('');
const telephone = ref('');

const fetchDataAndDisplay = async () => {
  try {
    // Retrieve client_id from local storage
    const clientId = localStorage.getItem('clientId');
    
    if (!clientId) {
      console.error('Client ID is missing in local storage.');
      return;
    }
    
    const response = await axios.post('http://192.168.100.102/IonicProject/vss/backend/client_display.php', {
      client_id: parseInt(clientId, 10)
    });

    if (response.data.status === 'success') {
      const { client_address, client_email, client_mobile, client_home } = response.data.data;
      address.value = client_address || 'No Address Provided'; // Handle potential undefined values
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
  padding: 15px;
  text-align: center;
}

.info-page h2 {
  font-size: 24px;
  margin-bottom: 15px;
}

.info-item {
  margin-bottom: 10px;
}

.info-item p {
  margin: 0;
  font-size: 16px;
  color: #666;
}

.contact-info {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.contact-info .info-item {
  width: 100%;
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .info-page h2 {
    font-size: 28px;
  }

  .info-item p {
    font-size: 18px;
  }

  .contact-info {
    flex-direction: row;
    justify-content: space-around;
  }

  .contact-info .info-item {
    width: 45%;
    text-align: center;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .info-page h2 {
    font-size: 32px;
  }

  .info-item p {
    font-size: 20px;
  }

  .contact-info .info-item p {
    font-size: 20px;
  }
}
</style>

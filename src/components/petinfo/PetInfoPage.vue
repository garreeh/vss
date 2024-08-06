<template>
  <div class="info-page">
    <div class="info-item">
      <p><strong>Microchip No:</strong></p>
      <p>{{ microchip }}</p>
    </div>

    <div class="contact-info">
      <div class="info-item">
        <p><strong>Breed:</strong></p>
        <p>{{ breed }}</p>
      </div>
      <div class="info-item">
        <p><strong>Species:</strong></p>
        <p>{{ species }}</p>
      </div>
    </div>

    <div class="contact-info">
      <div class="info-item">
        <p><strong>Birthdate:</strong></p>
        <p>{{ birthdate }}</p>
      </div>
      <div class="info-item">
        <p><strong>Neutered:</strong></p>
        <p>{{ neutered }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, defineProps } from 'vue';

import axios from 'axios';

// Define props
const props = defineProps<{
  petName: string;
}>();

// Data references
const microchip = ref('');
const breed = ref('');
const species = ref('');
const birthdate = ref('');
const neutered = ref('');

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    
    if (!clientId) {
      console.error('Client ID is missing in local storage.');
      return;
    }

    console.log('Client ID:', clientId);
    console.log('Pet Name:', props.petName);
    
    const response = await axios.post('http://192.168.100.102/IonicProject/vss/backend/petinfo/patient_solo.php', {
      client_id: parseInt(clientId, 10),
      patient_name: props.petName
    });

    console.log('API Response:', response.data);

    if (response.data.status === 'success') {
      const { patient_microchip, patient_breed, patient_species, patient_birthdate, patient_neutered } = response.data.data;
      microchip.value = patient_microchip || 'No Microchip Provided';
      breed.value = patient_breed || 'No Breed Provided';
      species.value = patient_species || 'No Species Provided';
      birthdate.value = patient_birthdate || 'No Birthdate Provided';
      neutered.value = patient_neutered || 'Not Specified';

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

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

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import apiService from './../../apiServices/apiService.js';

// Data references
const microchip = ref('');
const breed = ref('');
const species = ref('');
const birthdate = ref('');
const neutered = ref('');

// Use the route to get the petName parameter
const route = useRoute();

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const petName = route.params.petName; // Get petName from route params

    if (!clientId || !databaseName || !petName) {
      console.error('Client ID, database name, or pet name is missing.');
      return;
    }

    const response = await apiService.fetchPatientSoloData(clientId, databaseName, petName);

    if (response.data.status === 'success') {
      const { patient_id, patient_microchip, patient_breed, patient_species, patient_birthdate, patient_neutered } = response.data.data;
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

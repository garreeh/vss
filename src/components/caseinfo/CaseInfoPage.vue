<template>
  <div class="info-page">
    <div v-if="pets.length > 0" class="pets-list">
      <ion-card
        v-for="(pet, index) in pets"
        :key="index"
        class="info-item"
      >
        <ion-item lines="none" class="pet-item">
          <ion-label>
            <div class="pet-details">
              <div class="pet-detail full-width centered">
                <p class="label">Chief Complaint:</p>
                <p class="value">{{ pet.case_chief_complaint || 'No Details' }}</p>
              </div>
              <div class="pet-detail full-width centered">
                <p class="label">Attending Vet:</p>
                <p class="value">{{ pet.attending_vet || 'No Details' }}</p>
              </div>
              <div class="pet-detail centered">
                <p class="label">Case Date:</p>
                <p class="value">{{ pet.case_date || 'No Details' }}</p>
              </div>
              <div class="pet-detail centered">
                <p class="label">Case Follow Up Date:</p>
                <p class="value">{{ pet.case_date_followup || 'No Details' }}</p>
              </div>
            </div>
            <h3 class="vital-signs-header centered">Vital Signs</h3>
            <div class="vital-signs">
              <div class="vital-sign centered">
                <p class="label">Temperature:</p>
                <p class="value">{{ pet.temp || 'No Details' }}</p>
              </div>
              <div class="vital-sign centered">
                <p class="label">Weight:</p>
                <p class="value">{{ pet.weight || 'No Details' }}</p>
              </div>
              <div class="vital-sign centered">
                <p class="label">RR:</p>
                <p class="value">{{ pet.heart_rate || 'No Details' }}</p>
              </div>
              <div class="vital-sign centered">
                <p class="label">HR:</p>
                <p class="value">{{ pet.respiratory_rate || 'No Details' }}</p>
              </div>
            </div>
            <div class="pet-details">
              <div class="pet-detail full-width centered">
                <p class="label">Clinical Symptoms:</p>
                <p class="value">{{ pet.case_clinical_symptoms || 'No Details' }}</p>
              </div>
              <div class="pet-detail full-width centered">
                <p class="label">Laboratory Requested:</p>
                <ul class="value three-columns">
                  <li v-for="(item, idx) in pet.case_laboratory_requested.split('\r\n')" :key="idx">
                    {{ item || 'No Details' }}
                  </li>
                </ul>
              </div>
              <div class="pet-detail full-width centered">
                <p class="label">Treatment:</p>
                <p class="value">{{ pet.treatment || 'No Details' }}</p>
              </div>
              <div class="pet-detail full-width centered">
                <p class="label">Prescription:</p>
                <ul class="value">
                  <li v-for="(item, idx) in pet.prescription.split('\r\n')" :key="idx">
                    {{ item || 'No Details' }}
                  </li>
                </ul>
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
import { useRoute } from 'vue-router';
import apiService from './../../apiServices/apiService.js';
import { IonLabel, IonCard, IonItem } from '@ionic/vue';

// Data references
const pets = ref([]);

// Use the `useRoute` hook to get route parameters
const route = useRoute();

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const case_id = route.params.case_id; // Get `case_id` from route params

    if (!clientId || !databaseName || !case_id) {
      console.error('Client ID, database name, or case_id is missing.');
      return;
    }

    const response = await apiService.fetchCaseSoloData(clientId, databaseName, case_id);

    if (response.data.status === 'success') {
      pets.value = response.data.data; // Handle array of pets
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

.pets-list {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.info-item {
  width: 100%;
  max-width: 600px;
  margin-bottom: 10px;
}

.pet-item {
  display: flex;
  align-items: center;
}

.pet-details, .vital-signs {
  display: grid;
  grid-template-columns: auto auto;
  gap: 10px;
}

.pet-detail, .vital-sign {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.full-width {
  grid-column: span 2;
}

.label {
  font-weight: bold;
  color: #333;
}

.value {
  color: #666;
}

.vital-signs-header {
  grid-column: span 2;
  margin-top: 15px;
  font-size: 18px;
  font-weight: bold;
  color: #333;
  text-align: center; /* Center header text */
}

.centered {
  text-align: center; /* Center single column and headers */
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Style for three-column list */
.three-columns {
  column-count: 1;
  column-gap: 10px;
  list-style-type: none; /* Optional: remove bullets if not needed */
  padding: 0; /* Optional: remove padding if not needed */
}

.three-columns li {
  break-inside: avoid-column;
  margin-bottom: 10px; /* Adjust space between items */
}

@media (min-width: 768px) {
  .info-page {
    padding: 30px;
  }

  .pet-name {
    font-size: 18px;
  }

  .pet-details, .vital-signs {
    grid-template-columns: 1fr 1fr;
  }

  .three-columns {
    column-count: 2;
  }
}

@media (min-width: 1024px) {
  .info-page {
    padding: 50px;
  }

  .pet-name {
    font-size: 20px;
  }

  .pet-details, .vital-signs {
    grid-template-columns: 1fr 1fr;
  }

  .three-columns {
    column-count: 3;
  }
}
</style>

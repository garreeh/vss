<template>
  <div class="info-page">
    <!-- Loading indicator -->
    <div v-if="loading" class="loading">
      <p>Loading...</p>
    </div>

    <!-- Display pet details -->
    <div v-if="pets.length > 0" class="details-card">
      <ion-card v-for="(pet, index) in pets" :key="index" class="info-item">
        <ion-item lines="none" class="info-item-content">
          <ion-label class="info-details">
            <div class="pet-details">
              <!-- Pet Details Section -->
              <div class="detail-group">
                <div class="pet-detail">
                  <ion-icon :icon="alertCircleOutline" class="detail-icon"></ion-icon>
                  <p class="label">Chief Complaint:</p>
                  <p class="value">{{ pet.case_chief_complaint || 'No Details' }}</p>
                </div>
                <div class="pet-detail">
                  <ion-icon :icon="personOutline" class="detail-icon"></ion-icon>
                  <p class="label">Attending Vet:</p>
                  <p class="value">{{ pet.attending_vet || 'No Details' }}</p>
                </div>
              </div>
              <div class="detail-group">
                <div class="pet-detail">
                  <ion-icon :icon="calendarOutline" class="detail-icon"></ion-icon>
                  <p class="label">Date:</p>
                  <p class="value">{{ pet.case_date || 'No Details' }}</p>
                </div>
                <div class="pet-detail">
                  <ion-icon :icon="calendarOutline" class="detail-icon"></ion-icon>
                  <p class="label">Follow Up:</p>
                  <p class="value">{{ pet.case_date_followup || 'No Details' }}</p>
                </div>
              </div>
              <h3 class="vital-signs-header">Vital Signs</h3>
              <div class="vital-signs">
                <div class="vital-sign">
                  <ion-icon :icon="thermometerOutline" class="detail-icon"></ion-icon>
                  <p class="label">Temperature:</p>
                  <p class="value">{{ pet.temp || 'No Details' }}</p>
                </div>
                <div class="vital-sign">
                  <ion-icon :icon="fitnessOutline" class="detail-icon"></ion-icon>
                  <p class="label">Weight:</p>
                  <p class="value">{{ pet.weight || 'No Details' }}</p>
                </div>
                <div class="vital-sign">
                  <ion-icon :icon="pulseOutline" class="detail-icon"></ion-icon>
                  <p class="label">RR:</p>
                  <p class="value">{{ pet.heart_rate || 'No Details' }}</p>
                </div>
                <div class="vital-sign">
                  <ion-icon :icon="heartOutline" class="detail-icon"></ion-icon>
                  <p class="label">HR:</p>
                  <p class="value">{{ pet.respiratory_rate || 'No Details' }}</p>
                </div>
              </div>

              <h3 class="case-header">Case Details</h3>
              <div class="detail-group">
                <div class="pet-detail">
                  <ion-icon :icon="alertCircleOutline" class="detail-icon"></ion-icon>
                  <p class="label">Symptoms:</p>
                  <p class="value">{{ pet.case_clinical_symptoms || 'No Details' }}</p>
                </div>
                <div class="pet-detail">
                  <ion-icon :icon="documentsOutline" class="detail-icon"></ion-icon>
                  <p class="label">Laboratory:</p>
                  <button @click="openModal('Laboratory', pet.case_laboratory_requested)" class="view-button">View</button>
                </div>
                <div class="pet-detail">
                  <ion-icon :icon="medkitOutline" class="detail-icon"></ion-icon>
                  <p class="label">Treatment:</p>
                  <p class="value">{{ pet.treatment || 'No Details' }}</p>
                </div>
                <div class="pet-detail">
                  <ion-icon :icon="clipboardOutline" class="detail-icon"></ion-icon>
                  <p class="label">Prescription:</p>
                  <button @click="openModal('Prescription', pet.prescription)" class="view-button">View</button>
                </div>
              </div>
            </div>
          </ion-label>
        </ion-item>
      </ion-card>
    </div>

    <div v-if="isModalOpen" class="custom-modal">
    <div class="modal-overlay" @click="closeModal"></div>
    <div class="modal-content">
      <button class="close-button" @click="closeModal">&times;</button>
      <h3 class="modal-title">{{ modalTitle }}</h3>
      <ul>
        <li v-for="(item, idx) in modalContent.split('\r\n')" :key="idx">
          {{ item || 'No Details' }}
        </li>
      </ul>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import apiService from './../../apiServices/apiService.js';
import { IonLabel, IonCard, IonItem, IonButton, IonContent, IonHeader, IonToolbar, IonButtons, IonTitle, IonModal, IonIcon } from '@ionic/vue';
import { 
  alertCircleOutline, 
  personOutline, 
  calendarOutline, 
  thermometerOutline, 
  fitnessOutline, 
  pulseOutline, 
  heartOutline, 
  documentsOutline, 
  medkitOutline, 
  clipboardOutline 
} from 'ionicons/icons';

const pets = ref([]);
const loading = ref(true);
const isModalOpen = ref(false);
const modalTitle = ref('');
const modalContent = ref('');

const route = useRoute();

const fetchDataAndDisplay = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const case_id = route.params.case_id;

    if (!clientId || !databaseName || !case_id) {
      console.error('Client ID, database name, or case_id is missing.');
      loading.value = false;
      return;
    }

    const response = await apiService.fetchCaseSoloData(clientId, databaseName, case_id);

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

const openModal = (title, content) => {
  modalTitle.value = title;
  modalContent.value = content;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

onMounted(() => {
  fetchDataAndDisplay();
});
</script>

<style scoped>
.info-page {
  padding: 20px;
}

.loading {
  font-size: 20px;
  color: #666;
  margin: 20px;
}

.details-card {
  margin-top: 20px;
  width: 100%;
}

.info-item {
  margin-bottom: 20px;
}

.info-item-content {
  padding: 10px;
}

.info-details {
  font-size: 16px;
  color: #333;
}

.pet-details {
  display: flex;
  flex-direction: column;
}

.detail-group {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

.pet-detail {
  flex: 1 1 50%;
  display: flex;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #ddd;
  justify-content: flex-end; /* Align content to the right */
}

.pet-detail:last-child {
  border-bottom: 1px solid #ddd; /* Ensure last child in group has a line */
}

.details-card .detail-group:last-child .pet-detail:last-child {
  border-bottom: none; /* Remove border only from the very last item in the entire group */
  
}

.detail-icon {
  margin-right: 10px;
  color: #007bff; /* Primary color for icons */
}

.label {
  font-weight: bold;
  flex-basis: 40%; /* Make label take 40% of the space */
  color: #555;
}

.value {
  flex-basis: 60%; /* Make value take 60% of the space */
  text-align: right; /* Align value to the right */
  color: #333;
}

.vital-signs-header {
  font-size: 18px;
  font-weight: bold;
  margin: 20px 0 10px;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
  color: #333;
}

.case-header {
  font-size: 18px;
  font-weight: bold;
  margin: 20px 0 10px;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
  color: #333;
}

.vital-signs {
  display: flex;
  flex-wrap: wrap;
  border-bottom: 1px solid #ddd; /* Ensure last child in group has a line */

}

.vital-sign {
  flex: 1 1 50%;
  display: flex;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #ddd; /* Ensure last child in group has a line */

}

.vital-sign:last-child {
  border-bottom: none; /* Remove border from the last item */
}

.view-button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  margin-left: 10px; /* Optional: Adds space between button and previous content */
}

.view-button:hover {
  background-color: #0056b3;
}

.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}

.modal-content {
  position: relative;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 600px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.close-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

.modal-title {
  font-size: 20px;
  font-weight: bold;
  border-bottom: 2px solid #007bff;
  padding-bottom: 10px;
  margin-bottom: 15px;
  color: #333;
}

.modal-content ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.modal-content li {
  padding: 10px 0;
  border-bottom: 1px solid #ddd; /* Line for each item in the modal */
}

.modal-content li:last-child {
  border-bottom: none; /* Remove border from the last item */
}

@media (max-width: 768px) {
  .modal-content {
    width: 90%;
  }
}
</style>


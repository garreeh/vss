<template>
  <div class="info-page">
    <ion-accordion-group>
      <ion-accordion value="first">
        <ion-item slot="header" color="light">
          <ion-label>Laboratory Attachments</ion-label>
        </ion-item>
        <div class="ion-padding" slot="content">
          <div v-if="attachments.length > 0" class="three-columns">
            <div v-for="(attachment, idx) in attachments" :key="idx" class="attachment-item">
              <template v-if="isImage(attachment.lab_file)">
                <img 
                  :src="getFullUrl(attachment.lab_file)" 
                  :alt="attachment.lab_file_name" 
                  class="attachment-thumbnail" 
                  @click="openModal(getFullUrl(attachment.lab_file))"
                />
              </template>
              <template v-else-if="isPDF(attachment.lab_file)">
                <div class="file-wrapper">
                  <ion-icon 
                    class="file-icon" 
                    :icon="documentTextOutline"
                    @click="handleFile(getFullUrl(attachment.lab_file))"
                  ></ion-icon>
                  <span 
                    @click="handleFile(getFullUrl(attachment.lab_file))"
                    class="file-label"
                    >{{ attachment.lab_file_name }}</span>
                </div>
              </template>
              <template v-else-if="isDOCX(attachment.lab_file)">
                <div class="file-wrapper">
                  <ion-icon 
                    :icon="documentTextOutline"
                    class="file-icon" 
                    @click="handleFile(getFullUrl(attachment.lab_file))"
                  ></ion-icon>
                  <span 
                    class="file-label"
                    @click="handleFile(getFullUrl(attachment.lab_file))"
                  >{{ attachment.lab_file_name }}</span>
                </div>
              </template>
              <template v-else>
                <a 
                  :href="getFullUrl(attachment.lab_file)" 
                  :target="isMobile() ? '_self' : '_blank'" 
                  rel="noopener noreferrer"
                >
                  {{ attachment.lab_file_name }}
                </a>
              </template>
            </div>
          </div>

          <div v-else class="no-pets">
            <img src="./../../images/svg/no-data.svg" alt="No Pets" class="no-pets-svg" />
          </div>

          <div class="no-pets">
            {{ errorMessage }}
          </div>
        </div>
      </ion-accordion>
    </ion-accordion-group>

    <div v-if="isModalOpen" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <img :src="currentImage" class="modal-image" />
        <ion-icon 
          :icon="close" 
          class="modal-close" 
          @click.stop="closeModal"
        ></ion-icon>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiService from './../../apiServices/apiService.js';
import { useRoute } from 'vue-router';
import { close } from 'ionicons/icons';
import { Browser } from '@capacitor/browser';
import { IonLabel, IonAccordion, IonAccordionGroup, IonIcon, IonItem, } from '@ionic/vue';

import { 
  documentTextOutline,
} from 'ionicons/icons'; // Import icons from ionicons

const attachments = ref([]);

// Get the database name from localStorage
const databaseName = localStorage.getItem('databaseName');

// Base URL will now be dynamic depending on the database name
const baseUrlMap = {
  vssphcom_animed: 'https://vssph.com/animed/',
  vssphcom_av_vet: 'https://vssph.com/avvet/',
  vssphcom_betlehem: 'https://vssph.com/bethlehem/',
  vssphcom_cebuvetcentral: 'https://vssph.com/cebuvetcentral/',
  vssphcom_circleoflife: 'https://vssph.com/circleoflife/',
  vssphcom_furpetsake: 'https://vssph.com/furpetsake/',
  vssphcom_glittersparkle: 'https://vssph.com/glittersparkle/',
  vssphcom_jollyvet: 'https://vssph.com/jollyvet/',
  vssphcom_lafurry: 'https://vssph.com/lafurrypaws/',
  vssphcom_northeastvetclinic: 'https://vssph.com/northeastvetclinic/',
  vssphcom_panacea: 'https://vssph.com/panacea/',
  vssphcom_pawprints: 'https://vssph.com/pawprints/',
  vssphcom_pet911: 'https://vssph.com/pet911',
  vssphcom_pet911camarin: 'https://vssph.com/pet911_camarin/',
  vssphcom_pethelp: 'https://vssph.com/pethelp/',
  vssphcom_petish: 'https://vssph.com/petish/',
  vssphcom_petlandia: 'https://vssph.com/petlandia/',
  vssphcom_petlife: 'https://vssph.com/petlife/',
  vssphcom_petstacular: 'https://vssph.com/petstacular/',
  vssphcom_petwell: 'https://vssph.com/petwell/',
  vssphcom_petworx: 'https://vssph.com/petworx/',
  vssphcom_primevet: 'https://vssph.com/primevet/',
  vssphcom_thecat: 'https://vssph.com/thecatclinic/',
  vssphcom_trial: 'https://vssph.com/trial/',
  vssphcom_vetcorner: 'https://vssph.com/vetcorner/',
  vssphcom_vetcornervc: 'https://vssph.com/vetcorner_imus/',
  vssphcom_veterinarius: 'https://vssph.com/veterinarius/',
  vssphcom_vetlink: 'https://vssph.com/vetlink/',
  vssphcom_vetsquare: 'https://vssph.com/vetsquare/',
  'vssphcom_vet_clinicdr.e': 'https://vssph.com/drevetclinic/',
  vssphcom_wt_animal: 'https://vssph.com/wtclinic/',
};

// Get the corresponding base URL
const baseUrl = baseUrlMap[databaseName] || 'https://vssph.com/default/'; // Fallback URL

const route = useRoute();

const isModalOpen = ref(false);
const currentImage = ref('');
const errorMessage = ref(null);

const fetchLaboratoryAttachments = async () => {
  try {
    const clientId = localStorage.getItem('clientId');
    const databaseName = localStorage.getItem('databaseName');
    const case_id = route.params.case_id;

    if (!clientId || !databaseName || !case_id) {
      console.error('Client ID, database name, or case_id is missing.');
      return;
    }

    const response = await apiService.fetchLaboratoryData(clientId, databaseName, case_id);

    if (response.data.status === 'success') {
      attachments.value = response.data.data;
    } else {
      console.error('Error:', response.data.message);
      errorMessage.value = `Error fetching attachments: ${response.data.message}`;
    }
  } catch (error) {
    console.error('Error fetching data:', error);
    errorMessage.value = 'Error fetching attachments. Please try again later.';
  }
};

const getFullUrl = (relativePath) => {
  const sanitizedPath = relativePath.replace(/(\.\.\/)+/, '');
  return baseUrl + sanitizedPath;
};

const openModal = (imageUrl) => {
  currentImage.value = imageUrl;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const isImage = (filePath) => /\.(jpg|jpeg|png)$/i.test(filePath);
const isPDF = (filePath) => /\.pdf$/i.test(filePath);
const isDOCX = (filePath) => /\.docx$/i.test(filePath);
const isMobile = () => /Mobi|Android/i.test(navigator.userAgent);

const getMimeType = (filePath) => {
  if (isPDF(filePath)) return 'application/pdf';
  if (isDOCX(filePath)) return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
  return '';
};

const handleFile = async (url) => {
  try {
    await Browser.open({ url });
  } catch (error) {
    console.error('Error opening URL in browser:', error);
    errorMessage.value = 'Unable to open the URL. Please try again later.';
  }
};

onMounted(() => {
  const caseId = parseInt(route.params.case_id, 10);
  if (isNaN(caseId)) {
    console.error('Invalid case_id');
    return;
  }
  fetchLaboratoryAttachments(caseId);
});
</script>

<style scoped>
.attachment-thumbnail {
  width: 100px;
  height: 75px;
  object-fit: cover;
  cursor: pointer;
  transition: transform 0.2s;
}

.attachment-thumbnail:hover {
  transform: scale(1.05);
}

.attachment-item {
  text-align: center;
}

.file-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.file-icon {
  font-size: 2.5rem;
  cursor: pointer;
  margin-bottom: 5px; /* Adjust this margin to control the space between the icon and the label */
}

.file-label {
  display: block;
  font-size: 0.9rem;
  color: #333;
}

.no-pets-svg {
  margin-bottom: 10px;
  pointer-events: none; /* Prevents clicking */
}

@media (min-width: 768px) {
  .three-columns {
    column-count: 3;
  }

  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}

@media (min-width: 1024px) {
  .three-columns {
    column-count: 3;
  }
  .no-pets-svg {
    width: 35rem; /* Adjust size for larger screens */
  }
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  position: relative;
}

.modal-image {
  margin-top: 4rem;
  max-width: 75vw;
  max-height: 75vh;
}

.modal-close {
  margin-top: 4rem;
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  color: #fff;
  cursor: pointer;
  z-index: 1002;
}

.three-columns {
  column-count: 3;
}

.pdf-overlay, .docx-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.pdf-viewer, .docx-viewer {
  width: 80%;
  height: 80%;
}

</style>

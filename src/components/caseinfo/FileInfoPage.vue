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
                <a 
                  href="#" 
                  @click.prevent="downloadFile(getFullUrl(attachment.lab_file), 'application/pdf')"
                >
                  {{ attachment.lab_file_name }}
                </a>
              </template>
              <template v-else-if="isDOCX(attachment.lab_file)">
                <a 
                  href="#" 
                  @click.prevent="downloadFile(getFullUrl(attachment.lab_file), 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')"
                >
                  {{ attachment.lab_file_name }}
                </a>
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
          <p v-else>No Attachments Available</p>
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
import { IonLabel, IonAccordion, IonAccordionGroup, IonIcon, IonItem } from '@ionic/vue';

const attachments = ref([]);

// Get the database name from localStorage
const databaseName = localStorage.getItem('databaseName');

// Base URL will now be dynamic depending on the database name
const baseUrlMap = {
  vssphcom_pet911: 'https://vssph.com/pet911/',
  vssphcom_petish: 'https://vssph.com/petish/',
  vssphcom_trial: 'https://vssph.com/trial/'
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

const downloadFile = async (url, mimeType) => {
  try {
    const fileName = url.split('/').pop() || 'tempFile';
    const link = document.createElement('a');
    link.href = url;
    link.download = fileName;
    link.target = '_blank';
    link.click();
  } catch (error) {
    console.error('Error downloading file:', error);
  }
};

const isImage = (filePath) => /\.(jpg|jpeg|png)$/i.test(filePath);
const isPDF = (filePath) => /\.pdf$/i.test(filePath);
const isDOCX = (filePath) => /\.docx$/i.test(filePath);
const isMobile = () => /Mobi|Android/i.test(navigator.userAgent);
 
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

.attachment-item a {
  display: block;
  color: #007bff;
  text-decoration: underline;
}

.attachment-item a:hover {
  text-decoration: none;
}

@media (min-width: 768px) {
  .three-columns {
    column-count: 3;
  }
}

@media (min-width: 1024px) {
  .three-columns {
    column-count: 3;
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

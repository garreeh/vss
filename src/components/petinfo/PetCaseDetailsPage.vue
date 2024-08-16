<template>
  <ion-page>
    <Header />

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">{{ patientName }}</ion-title>
        </ion-toolbar>
      </ion-header>
      
      <div id="container">
        <div>
          <ion-avatar>
            <img src="./../../images/svg/cat-and-dog.svg" alt="Pet Avatar"/>
            <ion-label class="verified-label">Case for Pet</ion-label>
          </ion-avatar>
        </div>

        <strong class="capitalize">{{ patientName }}</strong>

        <ion-segment v-model="selectedSegment">
          <ion-segment-button value="PetInfo">
            Case Details
          </ion-segment-button>
          <ion-segment-button value="Cases">
            Case Files
          </ion-segment-button>
        </ion-segment>
        
        <div v-if="selectedSegment === 'PetInfo'">
          <CaseInformation :petName="patientName" />
        </div>
        <div v-if="selectedSegment === 'Cases'">
          <FileCases :petName="patientName" />
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, } from 'vue';
import { useRoute } from 'vue-router';
import { IonLabel, IonContent, IonHeader, IonPage, IonTitle, IonToolbar, IonAvatar, IonSegmentButton, IonSegment } from '@ionic/vue';
import Header from './../../components/Header.vue';
import CaseInformation from './../../components/caseinfo/CaseInfoPage.vue';
import FileCases from './../../components/caseinfo/FileInfoPage.vue';

const selectedSegment = ref('PetInfo');

const patientName = ref('');

// Route parameters
const route = useRoute();

</script>

<style scoped>
#container {
  text-align: center;
  padding: 15px;
}

#container ion-avatar {
  display: inline-block;
  width: 10rem;
  height: 8rem;
}

#container ion-avatar img {
  border-radius: 50%;
}

#container ion-label.verified-label {
  display: block;
  background-color: #4caf50;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  text-align: center;
  margin-top: 10px;
}

#container strong {
  font-size: 20px;
  line-height: 26px;
  display: block;
  margin: 10px 0;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  color: #8c8c8c;
  margin: 0;
}

@media (min-width: 768px) {
  #container {
    padding: 30px;
  }

  #container ion-avatar {
    width: 10rem;
    height: 8rem;
  }

  #container ion-label.verified-label {
    font-size: 14px;
    padding: 6px 12px;
  }

  #container strong {
    font-size: 24px;
    line-height: 30px;
  }

  #container p {
    font-size: 18px;
    line-height: 24px;
  }
}

@media (min-width: 1024px) {
  #container {
    padding: 50px;
  }

  #container ion-avatar {
    width: 10rem;
    height: 8rem;
  }

  #container ion-label.verified-label {
    font-size: 12px
  }

  #container strong {
    font-size: 28px;
    line-height: 34px;
  }

  #container p {
    font-size: 20px;
    line-height: 26px;
  }
}
</style>

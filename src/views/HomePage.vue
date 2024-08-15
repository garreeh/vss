<template>
  <ion-page>
    <Header :title="$route.params.id" />

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">{{ $route.params.id }}</ion-title>
        </ion-toolbar>
      </ion-header>
      
      <div id="container">
        <div>
          <ion-avatar>
            <img src="./../images/svg/user.svg" alt="Client Avatar"/>
            <ion-label class="verified-label">Verified Client</ion-label>
          </ion-avatar>
        </div>

        <strong class="capitalize">Hi, {{ userName }}</strong>

        <ion-segment v-model="selectedSegment">
          <ion-segment-button value="info">
            My Details
          </ion-segment-button>
          <ion-segment-button value="pets">
            Pets
          </ion-segment-button>
          <ion-segment-button value="bill">
            Bill
          </ion-segment-button>
        </ion-segment>
        
        <div v-if="selectedSegment === 'info'">
          <Information :title="$route.params.id" />
        </div>
        <div v-if="selectedSegment === 'pets'">
          <PetDashboard :title="$route.params.id" />
        </div>
        <div v-if="selectedSegment === 'bill'">
          <BillingPage :title="$route.params.id" />
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import Header from './../components/Header.vue';
import Information from './../components/clientinfo/InfoPage.vue';
import PetDashboard from './../components/clientinfo/PetsDashboardPage.vue';
import BillingPage from './../components/clientinfo/BillPage.vue';


import { IonLabel, IonContent, IonHeader, IonPage, IonTitle, IonToolbar, IonAvatar, IonSegmentButton, IonSegment } from '@ionic/vue';

const selectedSegment = ref('info');

const userName = ref('Guest');
onMounted(() => {
  const firstname = localStorage.getItem('clientFirstname') || 'Guest';
  const lastname = localStorage.getItem('clientLastname') || '';
  userName.value = `${firstname} ${lastname}`;
});

</script>

<style scoped>
#container {
  text-align: center;
  padding: 15px;
}

#container ion-avatar {
  display: inline-block;
  width: 100px;
  height: 100px;
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
    width: 150px;
    height: 150px;
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
    width: 100px;
    height: 100px;
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

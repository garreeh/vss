<template>
  <ion-content>
    <!-- <ion-header>
      <ion-toolbar>
        <ion-title>VSS Application</ion-title>
      </ion-toolbar>
    </ion-header> -->
    <ion-grid class="card-container">
      <ion-row class="ion-justify-content-center">
        <ion-col size="12" md="8">
          <ion-card>
            <ion-card-header class="card-header">
              <ion-row class="ion-align-items-center ion-justify-content-center">
                <ion-col class="icon-col" size="auto">
                  <img src="./../images/logo-vss.png" alt="Logo" class="login-icon"> <!-- Use your image here -->
                </ion-col>
              </ion-row>
              <!-- Added Sign-in heading -->
              <ion-row class="ion-justify-content-center">
                <ion-col class="text-center">
                  <h4 class="sign-in-heading">Sign-in</h4>
                </ion-col>
              </ion-row>
            </ion-card-header>
            <ion-card-content>
              <form @submit.prevent="login">
                <!-- Input Email Address -->
                  <ion-input
                    :value="client_email"
                    @ionInput="e => client_email = e.target.value"
                    @keyup.enter="login"
                    label="Email Address" label-placement="floating" fill="outline" placeholder="Enter your email address"
                  ></ion-input>
                  <br>
                <!-- Input password -->
                  <ion-input
                    :type="showPassword ? 'text' : 'password'"
                    :value="client_password"
                    @ionInput="e => client_password = e.target.value"
                    @keyup.enter="login"
                    label="Password" label-placement="stacked" fill="outline" placeholder="Enter your password"
                  >
                    <ion-input-password-toggle slot="end"></ion-input-password-toggle>
                
                  </ion-input>


                <ion-button shape="round" expand="full" type="submit" class="ion-margin-top">
                  Login
                </ion-button>
                <p v-if="loginMessage" class="ion-text-center ion-margin-top">
                  {{ loginMessage }}
                </p>

              </form>
            </ion-card-content>
          </ion-card>
        </ion-col>
      </ion-row>
    </ion-grid>
  </ion-content>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import loginProcess from './../apiServices/loginProcess';
import { IonCard, IonInputPasswordToggle, IonCardHeader, IonInput, IonItem, IonCol, IonRow, IonGrid, IonCardContent, IonContent, IonTitle, IonToolbar, IonButton, IonIcon } from '@ionic/vue';

const client_email = ref('');
const client_password = ref('');
const loginMessage = ref('');
const router = useRouter();
const showPassword = ref(false); // State for showing password

const databaseNameMapping = {
  'vssphcom_animated': 'Animated',
  'vssphcom_av_vet': 'AV VET',
  'vssphcom_betlehem': 'Betlehem',
  'vssphcom_cebuvetcentral': 'Cebu Vet Central',
  'vssphcom_circleoflife': 'Circle of Life',
  'vssphcom_furpetsake': 'Furpetsake',
  'vssphcom_glittersparkle': 'Glitter Sparkle',
  'vssphcom_jollyvet': 'Jolly Vet',
  'vssphcom_lafurry': 'Lafurry',
  'vssphcom_northeastvetclinic': 'North East Vet Clinic',
  'vssphcom_panacea': 'Panacea',
  'vssphcom_pawprints': 'Paw Prints',
  'vssphcom_pet911': 'Pet 911',
  'vssphcom_pet911camarin': 'Pet 911 Camarin',
  'vssphcom_pethelp': 'Pet help',
  'vssphcom_petish': 'Petish',
  'vssphcom_petlandia': 'Petlandia',
  'vssphcom_petlife': 'Petlife',
  'vssphcom_petstacular': 'Petstacular',
  'vssphcom_petwell': 'Petwell',
  'vssphcom_petworx': 'Petworx',
  'vssphcom_primevet': 'Prime vet',
  'vssphcom_thecat': 'The Cat',
  'vssphcom_trial': 'Trial',
  'vssphcom_vetcorner': 'Vet Corner',
  'vssphcom_vetcornervc': 'Vet Corner Imus',
  'vssphcom_veterinarius': 'Veterinarius',
  'vssphcom_vetlink': 'Vetlink',
  'vssphcom_vetsquare': 'VetSquare',
  'vssphcom_vet_clinicdr.e': 'Dr. E Vet Clinic',
  'vssphcom_wt_animal': 'WT Animal',

  
};

const login = async () => {
  try {
    const response = await loginProcess.login({
      client_email: client_email.value,
      client_password: client_password.value
    });

    if (response.data.status === 'success') {
      const token = response.data.token;
      const clientId = response.data.client_id;
      const clientFirstname = response.data.client_firstname;
      const clientLastname = response.data.client_lastname;
      const databaseName = response.data.database_name; // Actual database name

      // Store actual database name
      localStorage.setItem('authToken', token);
      localStorage.setItem('clientId', clientId);
      localStorage.setItem('clientFirstname', clientFirstname);
      localStorage.setItem('clientLastname', clientLastname);
      localStorage.setItem('databaseName', databaseName); // Store actual database name

      // Convert to user-friendly name and store it
      const userFriendlyName = databaseNameMapping[databaseName] || 'Unknown Database';
      localStorage.setItem('databaseDisplayName', userFriendlyName);

      router.push('/home');
    } else {
      loginMessage.value = response.data.message;
    }
  } catch (error) {
    console.error('Login error:', error);
    loginMessage.value = 'Network Error. Please Try Again.';
  }
};

</script>

<style scoped>

/* The background is called in variables.scss */
ion-content {
  --background: var(--ion-content-background-image);
  background-size: cover; /* Adjust size as needed */
  background-position: center; /* Adjust position as needed */
}

.login-icon {
  width: 6rem; /* Adjust the size of the logo */
  height: auto;
}

.sign-in-heading {
  text-align: center;
  font-weight: bold;
  margin-top: 10px; /* Adjust spacing as needed */
}

ion-card {
  --background: #eaF4F4;
  /* --color: #9efff0; */
}

.card-container {

  height: 100%; /* Ensure the grid takes full height */
  display: flex;
  flex-direction: column;
  justify-content: center; /* Center content vertically */
}
.icon-col {
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-icon {
  font-size: 60px; /* Adjust size as needed */
  color: #333; /* Adjust color as needed */
}

.ion-card-title {
  text-align: center;
  margin-top: 20px; /* Adjust spacing as needed */
}
</style>

<template>
  <ion-content>
    <ion-header>
      <ion-toolbar>
        <ion-title>Register</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-grid class="card-container">
      <ion-row class="ion-justify-content-center">
        <ion-col size="12" md="8">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Register</ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <form @submit.prevent="login">
                <ion-item>
                  <ion-input
                    :value="client_email"
                    @ionInput="e => client_email = e.target.value"
                    @keyup.enter="login"
                    type="email"
                    placeholder="Email"
                  ></ion-input>
                </ion-item>
                <ion-item>
                  <ion-input
                    :value="client_password"
                    @ionInput="e => client_password = e.target.value"
                    @keyup.enter="login"
                    type="password"
                    placeholder="Password"
                  ></ion-input>
                </ion-item>

                <ion-button expand="full" type="submit" class="ion-margin-top">
                  Login
                </ion-button>
                <p v-if="errorMessage" class="ion-text-center ion-margin-top">
                  {{ errorMessage }}
                </p>
                <ion-grid>
                  <ion-row>
                    <ion-col class="ion-text-center">
                      <ion-button fill="clear" @click="loginPage">
                        Back to Login
                      </ion-button>
                    </ion-col>
                  </ion-row>
                </ion-grid>
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
import { IonCard, IonCardTitle, IonHeader, IonCardHeader,IonInput, IonItem, IonCol, IonRow, IonGrid, IonCardContent, IonContent, IonTitle, IonToolbar, IonButton } from '@ionic/vue';

// Static values for testing
const client_email = ref('');
const client_password = ref('');
const errorMessage = ref('');
const router = useRouter();

const login = async () => {
  console.log('Login function called');
  console.log('Client Email:', client_email.value);
  console.log('Client Password:', client_password.value);

  try {
    const response = await loginProcess.login({
      client_email: client_email.value,
      client_password: client_password.value
    });

    console.log('Login response:', response);

    if (response.data.status === 'success') {
      const token = response.data.token;
      const clientFirstname = response.data.client_firstname; // Ensure this is returned by backend
      const clientLastname = response.data.client_lastname; // Ensure this is returned by backend

      localStorage.setItem('authToken', token); // Store token
      localStorage.setItem('clientFirstname', clientFirstname); // Store user first name
      localStorage.setItem('clientLastname', clientLastname); // Store user last name
      
      console.log('Token stored:', localStorage.getItem('authToken')); // Verify storage
      console.log('Client First Name stored:', localStorage.getItem('clientFirstname')); // Verify storage
      console.log('Client Last Name stored:', localStorage.getItem('clientLastname')); // Verify storage

      window.location.href = '/home';
    } else {
      errorMessage.value = response.data.message;
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.value = 'An error occurred during login.';
  }
};

const loginPage = () => {
  window.location.href = '/login';
};


</script>

<style scoped>
.card-container {
  padding: 20px;
}
</style>

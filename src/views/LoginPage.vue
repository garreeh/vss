<template>
  <ion-content>
    <ion-header>
      <ion-toolbar>
        <ion-title>Login</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-grid class="card-container">
      <ion-row class="ion-justify-content-center">
        <ion-col size="12" md="8">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Login</ion-card-title>
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
                <p v-if="loginMessage" class="ion-text-center ion-margin-top">
                  {{ loginMessage }}
                </p>
                <ion-grid>
                  <ion-row>
                    <ion-col class="ion-text-start">
                      <ion-button fill="clear" @click="forgotPassword">
                        Forgot Password
                      </ion-button>
                    </ion-col>
                    <ion-col class="ion-text-end">
                      <ion-button fill="clear" @click="newClient">
                        New Client
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

const client_email = ref('');
const client_password = ref('');
const loginMessage = ref('');
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
      const clientId = response.data.client_id;
      const clientFirstname = response.data.client_firstname;
      const clientLastname = response.data.client_lastname;

      localStorage.setItem('authToken', token);
      localStorage.setItem('clientId', clientId);
      localStorage.setItem('clientFirstname', clientFirstname);
      localStorage.setItem('clientLastname', clientLastname);

      // This is for testing only
      // console.log(`Token stored: ${token}`); // Verify storage
      // console.log(`User ID: ${clientId}`);
      // console.log(`Client First Name stored: ${clientFirstname}`); // Verify storage
      // console.log(`Client Last Name stored: ${clientLastname}`); // Verify storage

      window.location.href = '/home';
    } else {
      loginMessage.value = response.data.message;
    }
  } catch (error) {
    console.error('Login error:', error);
    loginMessage.value = 'An error occurred during login.';
  }
};

const forgotPassword = () => {
  window.location.href = '/forgot-password';
};

const newClient = () => {
  window.location.href = '/register';
};


</script>

<style scoped>
.card-container {
  padding: 20px;
}
</style>

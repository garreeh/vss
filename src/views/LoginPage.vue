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
                    :value="user_name"
                    @ionInput="e => user_name = e.target.value"
                    @keyup.enter="login"
                    type="text"
                    placeholder="Username"
                  ></ion-input>
                </ion-item>
                <ion-item>
                  <ion-input
                    :value="user_password"
                    @ionInput="e => user_password = e.target.value"
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

// Static values for testing
const user_name = ref('');
const user_password = ref('');
const errorMessage = ref('');
const router = useRouter();

const login = async () => {
  console.log('Login function called');
  console.log('User Name:', user_name.value);
  console.log('User Password:', user_password.value);

  try {
    const response = await loginProcess.login({
      user_name: user_name.value,
      user_password: user_password.value
    });

    console.log('Login response:', response);

    if (response.data.status === 'success') {
      const token = response.data.token;
      const userFirstname = response.data.user_firstname; // Ensure this is returned by backend
      const userLastname = response.data.user_lastname; // Ensure this is returned by backend

      localStorage.setItem('authToken', token); // Store token
      localStorage.setItem('userFirstname', userFirstname); // Store user first name
      localStorage.setItem('userLastname', userLastname); // Store user last name
      
      console.log('Token stored:', localStorage.getItem('authToken')); // Verify storage
      console.log('User First Name stored:', localStorage.getItem('userFirstname')); // Verify storage
      console.log('User Last Name stored:', localStorage.getItem('userLastname')); // Verify storage

      window.location.href = '/home';
    } else {
      errorMessage.value = response.data.message;
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMessage.value = 'An error occurred during login.';
  }
};

</script>

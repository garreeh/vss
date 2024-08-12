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
        <strong class="capitalize">{{ $route.params.id }}</strong>
        <ion-button>Add </ion-button>
        <table id="petTable" class="display">
          <thead>
            <tr>
              <th>Patient</th>
              <th>Owner</th>
              <th>Gender</th>
              <th>Breed</th>
              <th>Species</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data will be inserted here by DataTables -->
          </tbody>
        </table>
      </div>
    </ion-content>
  </ion-page>
</template>
<script setup lang="ts">
import { onMounted } from 'vue';
import { IonButton, IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import Header from './../components/Header.vue';

import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-responsive';

import axios from 'axios';

// Function to fetch data and display it in DataTable
const fetchDataAndDisplay = async () => {
  try {
    // Retrieve client_id from local storage
    const clientId = localStorage.getItem('clientId');
    
    if (!clientId) {
      console.error('Client ID is missing in local storage.');
      return;
    }
    
    // Send POST request to fetch patient data
    const response = await axios.post('http://192.168.0.111/IonicProject/vss/backend/patient_display.php', {
      client_id: parseInt(clientId, 10) // Ensure it's an integer
    });

    // Handle the response
    if (response.data.status === 'success') {
      // Initialize DataTable with the fetched data
      $('#petTable').DataTable({
        responsive: true,
        data: response.data.data,
        columns: [
          { data: 'patient_name' },
          { 
            data: null, 
            title: 'Owner',
            render: function (data) {
              return `${data.client_firstname} ${data.client_lastname}`;
            }
          },
          { data: 'patient_gender' },
          { data: 'patient_breed' },
          { data: 'patient_species' },
        ]
      });
    } else {
      console.error('Error:', response.data.message);
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
  fetchDataAndDisplay();
});

</script>

<style scoped>
#container {
  text-align: center;
  position: relative;
  padding: 20px;
}

#container table {
  width: 100%;
  margin: 20px auto;
  overflow-x: auto; /* Ensure horizontal scroll on smaller screens */
}

#container th, #container td {
  padding: 10px;
  text-align: left;
}

#container ion-button {
  margin: 20px;
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  color: #8c8c8c;
  margin: 0;
}

#container a {
  text-decoration: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  #container table {
    font-size: 14px; /* Adjust font size for smaller screens */
  }

  #container th, #container td {
    padding: 8px; /* Adjust padding for smaller screens */
  }
}
</style>

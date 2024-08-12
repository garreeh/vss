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
        <table id="patientTable" class="display">
          <thead>
            <tr>
              <th>ID</th>
              <th>Patient</th>
              <th>Owner</th>
              <th>Breed</th>
              <th>Delete</th>
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
import { IonContent, IonHeader, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import Header from './../components/Header.vue';

import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-responsive';


const fetchData = async () => {
  try {
    const response = await fetch('http://192.168.100.111//IonicProject/vss/backend/patient_display.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    });
    
    if (!response.ok) throw new Error('Network response was not ok');
    const result = await response.json();

    if (result.status === 'success') {
      $('#patientTable').DataTable({
        responsive: true,
        data: result.data,
        columns: [
          { data: 'patient_id' },
          { data: 'patient_name' },
          { data: 'client_lastname' },
          { data: 'patient_breed' },
          { data: 'patient_breed' },

        ]
      });
    } else {
      console.error('Error:', result.message);
    }
  } catch (error) {
    console.error('Fetch error:', error);
  }
};

onMounted(() => {
  fetchData();
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

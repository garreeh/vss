import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://192.168.100.111/IonicProject/vss/backend/', // Adjust the base URL if necessary
  headers: {
    'Content-Type': 'application/json'
  }
});

export default {
  // This is the bridge for backend to frontend
  
  login(data) {
    return apiClient.post('loginProcess.php', data);
  },
  
  fetchClientData(clientId, databaseName) {
    return apiClient.post('client_display.php', {
      client_id: clientId,
      database_name: databaseName
    });
  },

  fetchPatientData(clientId, databaseName) {
    return apiClient.post('patient_display.php', {
      client_id: clientId,
      database_name: databaseName
    });
  },

  fetchPatientSoloData(clientId, databaseName, patientName) {
    return apiClient.post('petinfo/patient_solo.php', {
      client_id: clientId,
      database_name: databaseName,
      patient_name: patientName
    });
  },

  fetchCaseSoloData(clientId, databaseName, case_id) {
    return apiClient.post('petinfo/case_solo.php', {
      client_id: clientId,
      database_name: databaseName,
      case_id: case_id
    });
  },

  fetchVaccineData(clientId, databaseName, patientName) {
    return apiClient.post('petinfo/patient_vaccine.php', {
      client_id: clientId,
      database_name: databaseName,
      patient_name: patientName
    });
  },

  fetchCaseData(clientId, databaseName, case_id) {
    return apiClient.post('petinfo/case_display.php', {
      client_id: clientId,
      database_name: databaseName,
      case_id: case_id
    });
  },

  fetchLaboratoryData(clientId, databaseName, case_id) {
    return apiClient.post('attachments/laboratory_attachments.php', {
      client_id: clientId,
      database_name: databaseName,
      case_id: case_id
    });
  }
  
  // Add other API functions as needed
};

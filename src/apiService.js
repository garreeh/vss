import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://192.168.69.115/IonicProject/vss/connection/', // Adjust the base URL if necessary
  headers: {
    'Content-Type': 'application/json'
  }
});

export default {
  testConnection() {
    return apiClient.get('./connection.php'); // Path to your PHP script
  }
};



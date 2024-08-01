import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://192.168.100.125/IonicProject/vss/backend/', // Adjust the base URL if necessary
  headers: {
    'Content-Type': 'application/json'
  }
});

export default {
  // Method to handle user login
  login(credentials) {
    return apiClient.post('login_process.php', credentials); // Ensure this path is correct and handles POST requests
  }
};

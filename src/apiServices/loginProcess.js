import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://192.168.69.118/IonicProject/vss/backend/', // Adjust the base URL if necessary
  headers: {
    'Content-Type': 'application/json'
  }
});

export default {
  login(credentials) {
    return apiClient.post('login_process.php', credentials);
  },
};

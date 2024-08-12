// src/auth.ts
export const isAuthenticated = (): boolean => {
  const token = localStorage.getItem('authToken');

  // This is for testing only
  console.log('Retrieved Token:', token); // Debugging line to check token value
  return token !== null; // Return true if token is not null
};

export async function login(credentials: { user_name: string; user_password: string }): Promise<any> {
  try {
    const response = await fetch('http://192.168.100.111/IonicProject/vss/backend/login_process.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(credentials),
    });
    
    const data = await response.json();
    
    if (data.status === 'success') {
      localStorage.setItem('authToken', data.token); // Store token
    }
    
    return data;
  } catch (error) {
    console.error('Login error:', error);
    throw error; // Rethrow to handle in calling function
  }
}

export function logout(): void {
  localStorage.removeItem('authToken');
}

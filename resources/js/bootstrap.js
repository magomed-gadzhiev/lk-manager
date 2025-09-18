import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('auth_token');
if (token) {
  window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Глобальный перехватчик для неавторизованных запросов
window.axios.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error?.response?.status;
    if (status === 401 || status === 403) {
      try {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_roles');
      } catch {}
      if (typeof window !== 'undefined') {
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

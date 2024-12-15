import axios from 'axios';

const axiosClient = axios.create({
    baseURL: 'http://3.12.163.103:8080',
    headers: {
        'Content-Type': 'application/json',
        'accept': 'application/json',
    },
    timeout: 60000,
});

// Interceptor de solicitudes
axiosClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);


axiosClient.interceptors.response.use((response) => response, (error) => {
    if (error.response.status === 401) {
        localStorage.removeItem('token');
        window.location = '/login';
    }
    return Promise.reject(error);
});

export default axiosClient;
import axios from 'axios'
import endPointApis from "../endPointApis";
import {useAuthentication} from "../store";

const $endPoints = endPointApis;

const $api = axios.create({
    baseURL: `/api`,
    headers: {
        "Content-type": "application/json",
        "Access-Control-Allow-Origin": "*",
        'Accept': 'application/json',
    }
});

let isRefreshing = false;
let refreshSubscribers = [];

function subscribeTokenRefresh(callback) {
    refreshSubscribers.push(callback);
}

function onTokenRefreshed(token) {
    refreshSubscribers.forEach(callback => callback(token));
    refreshSubscribers = [];
}

function updateAuthorizationHeader(token) {
    $api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

$api.interceptors.request.use(
    config => {
        const store = useAuthentication();
        const token = store.getToken;

        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;
    },
    error => Promise.reject(error)
);

$api.interceptors.response.use(
    response => response,
    error => {
        const originalRequest = error.config;
        const { status } = error.response;
        const store = useAuthentication();
        let token = store.getToken;

        if (status === 401 && !originalRequest._retry) {

            if (!isRefreshing) {
                isRefreshing = true;

                return axios
                    .get('/api/auth/refresh', {
                        headers: {
                            Authorization: 'Bearer ' + token,
                        },
                    })
                    .then(response => {
                        const newToken = response.data.token;
                        store.setToken(newToken);
                        localStorage.setItem('token', newToken);
                        updateAuthorizationHeader(newToken);

                        setTimeout(() => {
                            onTokenRefreshed(newToken);
                        }, 1000);
                        return new Promise(resolve => {
                            originalRequest.headers.Authorization = `Bearer ${newToken}`;
                            resolve(axios(originalRequest));
                        });

                    })
                    .catch(error => {
                        isRefreshing = false;
                        handleTokenRefreshError(store);
                        return Promise.reject(error);
                    })
                    .finally(() => {
                        isRefreshing = false;
                    });
            } else {
                return new Promise(resolve => {
                    subscribeTokenRefresh(newToken => {
                        updateAuthorizationHeader(newToken);
                        originalRequest.headers.Authorization = `Bearer ${newToken}`;
                        resolve(axios(originalRequest));
                    });
                });
            }
        }

        return Promise.reject(error);
    }
);

function handleTokenRefreshError(store) {
    localStorage.clear();
    store.setToken(null);
    store.setUser([]);
    store.setRole(null);
    store.setRole(null);
    store.setPermissions(null);
    store.setLocale(null);
    window.location.reload();
}


export { $api, $endPoints };

export default {
    install: (app) => {
        app.config.globalProperties.$api = $api;
        app.config.globalProperties.$endPoints = $endPoints;
    },
};

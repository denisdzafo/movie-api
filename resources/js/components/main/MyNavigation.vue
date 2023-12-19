<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <div class="ml-auto d-flex align-items-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/register">Register</router-link>
                    </li>
                    <li class="nav-item" v-if="store.getToken">
                        <router-link class="nav-link" to="/my-movies">My Movies</router-link>
                    </li>
                    <li class="nav-item" v-if="!store.getToken">
                        <a href="#" class="nav-link" @click="showLoginForm = !showLoginForm">Login</a>
                    </li>
                    <li class="nav-item" v-if="store.getToken">
                        <a href="#" class="nav-link" @click="logOut">LogOut</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="login-form-container" :class="{ 'd-flex': showLoginForm }">
            <form class="row ml-auto pop-up" @submit.prevent>
                <div class=" col-md-4">
                    <input type="email" v-model="email" class="form-control mr-1" placeholder="Email" required>
                </div>
                <div class=" col-md-4">
                    <input type="password" v-model="password" class="form-control mr-1" placeholder="Password" required>
                </div>
                <div class=" col-md-4">
                    <button type="submit" class="btn btn-primary" @click = login()>Login</button>
                </div>
            </form>
            <button type="button" class="close ml-3" @click="showLoginForm = false">
                <span>&times;</span>
            </button>
        </div>
    </nav>
</template>

<script setup lang="ts">
    import {useAuthentication} from "../../services/store"
    import { ref } from 'vue';
    import {useGlobalPlugins} from "../../services/plugins/useGlobalPlugins";

    const { $api, $endPoints } = useGlobalPlugins();
    const showLoginForm = ref(false);
    const email = ref('');
    const password = ref('');
    const store = useAuthentication();

    function login() {
        $api.post($endPoints.login, {
            email: email.value,
            password: password.value

        })
            .then(response => {
                store.setToken(response.data.token)
                showLoginForm.value = false
            })
            .catch(e => {
                console.error(e);
            });

    }

    function logOut(){
        $api.get($endPoints.logout)
            .then(response => {
                store.setToken(null)
            })
            .catch(e => {
                console.error(e);
            });
    }

</script>

<style scoped>
    /* Navigation menu styles */
    .navbar {
        background-color: #333;
    }

    .nav-link {
        color: #fff;
        font-size: 18px;
        text-decoration: none;
    }

    .nav-link:hover {
        color: #fff;
        text-decoration: underline;
    }

    .login-form-container {
        background-color: transparent;
        position: absolute;
        top: 100%;
        right: 0;
        left: 0;
        height: 0;
        overflow: hidden;
        transition: height 0.3s ease-in-out;
        z-index: 100;
    }

    .login-form-container.d-flex {
        height: 50px;
    }

    .login-form {
        display: flex;
        align-items: center;
    }

    .login-form input,
    .login-form button {
        height: 38px;
    }

    .close {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        opacity: 0.5;
    }

    .pop-up{
        background: #f8f9fa;
    }
</style>

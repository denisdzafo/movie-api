<template>
    <div>
        <div
            class="image-section"
            :style="{ backgroundImage: 'url(' + imageUrl + ')' }"
        >
            <div class="overlay">
                <h1 class="text-center text-white">{{ imageText }}</h1>
            </div>
        </div>
        <div class="container mt-5">
            <form @submit.prevent="onSubmit">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        v-model="name"
                        :class="{ 'is-invalid': nameError }"
                    />
                    <div class="invalid-feedback">{{ nameError }}</div>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        v-model="email"
                        :class="{ 'is-invalid': emailError }"
                    />
                    <div class="invalid-feedback">{{ emailError }}</div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        v-model="password"
                        :class="{ 'is-invalid': passwordError }"
                    />
                    <div class="invalid-feedback">{{ passwordError }}</div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="confirmPassword"
                        v-model="confirmPassword"
                        :class="{ 'is-invalid': confirmPasswordError }"
                    />
                    <div class="invalid-feedback">{{ confirmPasswordError }}</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="mt-5"></div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue';
    import {useGlobalPlugins} from "../../services/plugins/useGlobalPlugins";
    const { $api, $endPoints } = useGlobalPlugins();
    const name = ref('');
    const email =  ref('');
    const password =  ref('');
    const confirmPassword =  ref('');
    const nameError =  ref('');
    const emailError =  ref('');
    const passwordError =  ref('');
    const confirmPasswordError =  ref('');
    const imageUrl = ref('https://picsum.photos/1920/600');
    const imageText = ref('Register')

    function onSubmit() {
        console.log($api);
        console.log($endPoints);
        if (
            validateName() &&
            validateEmail() &&
            validatePassword() &&
            validateConfirmPassword()
        ){
            $api.post($endPoints.register, {
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: confirmPassword.value
            })
                .then(response => {
                    //$router.push( { name: 'Login' });
                })
                .catch(e => {
                    console.log(e);
                });
        }

    };

    function validateName() {
        if (!name.value) {
            nameError.value = 'Name is required.';
            return false;
        } else {
            nameError.value = '';
            return true;
        }
    };
    function   validateEmail() {
        if (!email.value) {
            emailError.value = 'Email is required.';
            return false;
        } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
            emailError.value = 'Invalid email format.';
            return false;
        } else {
            emailError.value = '';
            return true;
        }
    };
    function  validatePassword() {
        if (!password.value) {
            passwordError.value = 'Password is required.';
            return false;
        } else if (password.value.length < 3) {
            passwordError.value = 'Password must be at least 3 characters long.';
            return false;
        } else {
            passwordError.value = '';

            return true;
        }
    };
    function validateConfirmPassword() {
        if (!confirmPassword.value) {
            confirmPasswordError.value = 'Please confirm your password.';
            return false;
        } else if (confirmPassword.value !== password.value) {
            confirmPasswordError.value = 'Passwords do not match.';
            return false;
        } else {
            confirmPasswordError.value = '';
            return true;
        }
    };
</script>

<style scoped>
    .register {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .image-section {
        height: 350px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .image-section .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-section .overlay h1 {
        font-size: 48px;
        font-weight: bold;
        text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);
    }

    .container {
        max-width: 500px;
    }

    .mt-5 {
        margin-top: 2.5rem;
    }
</style>

import { defineStore } from 'pinia'


export let useAuthentication = defineStore('auth', {
    state: () => {
        return {
            token: null,
        }
    },

    persist: true,

    getters: {
        isLoggedIn(state) {
            if (state.token) {
                return true;
            }
            return false;
        },

        getToken(state){
            return state.token;
        },

    },

    actions: {
        setToken(token){
            this.token = token;
        },

    },

});


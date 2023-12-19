import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/pages/Home.vue';
import SingleMovie from '../components/pages/SingleMovie.vue';
import Register from '../components/pages/Register.vue';
import MyMovies from '../components/pages/MyMovies.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/single/:id', component: SingleMovie,  name: 'single', },
    { path: '/register', component: Register },
    { path: '/my-movies', component: MyMovies },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

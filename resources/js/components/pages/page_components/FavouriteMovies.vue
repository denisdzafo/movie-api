<template>
    <section class="movie-section py-5">
        <div class="container">
            <h2 class="mb-4">Favourite Movies</h2>
            <div class="row">
                <div
                    class="col-md-3 mb-3"
                    v-for="(movie, index) in movies"
                    :key="index"
                >
                    <div class="card">
                        <img class="card-img-top" :src="getMoviePoster(movie)" />
                        <div class="card-body">
                            <h5 class="card-title">{{ movie.original_title }}</h5>
                            <p class="card-text">Release date:{{ movie.release_date }}</p>
                            <a v-if="store.getToken" @click="openSingleMovie(movie.id)" class="btn btn-primary mb-2">Read more</a>
                            <br>
                            <a v-if="store.getToken" @click="removeMovie(movie.id)" class="btn btn-secondary">Remove</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</template>

<script setup lang="ts">
    import { ref, onMounted, computed } from 'vue';
    import { useGlobalPlugins } from '../../../services/plugins/useGlobalPlugins';
    import {useRouter} from "vue-router";
    import {useAuthentication} from "../../../services/store"
    const { $api, $endPoints } = useGlobalPlugins();
    const store = useAuthentication();
    const movies = ref([]);
    const router = useRouter();

    onMounted(() => {
        loadData();
    });

    function loadData() {
        $api.get($endPoints.getMyFavouriteMovies)
            .then(response => {
                movies.value = response.data.data;
            })
            .catch(e => {
                console.error(e);
            });
    }

    function removeMovie(movieId){
        $api.post($endPoints.removeMovieFromFavourite, {
            id: movieId,
        })
            .then(response => {
                loadData();
            })
            .catch(e => {
                console.error(e);
            });
    }


    function getMoviePoster(movie) {
        const baseUrl = 'https://image.tmdb.org/t/p/original/';
        return baseUrl + movie.poster_path;
    }

    function openSingleMovie(movieId){
        router.push({ name: "single", params: {id:movieId} });
    }


</script>

<style scoped>

    .card {
        border: none;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }
</style>

<template>
    <section class="movie-section py-5">
        <div class="container">
            <div class="row">
                    <div class="card">
                        <img class="card-img-top" :src="getMoviePoster(movie)" />
                        <div class="card-body">
                            <h5 class="card-title">{{ movie.original_title }}</h5>
                            <p class="card-text">Release date:{{ movie.release_date }}</p>
                            <p class="card-text">{{ movie.overview }}</p>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { useGlobalPlugins } from '../../../services/plugins/useGlobalPlugins';
    import {useRouter} from "vue-router";

    const { $api, $endPoints } = useGlobalPlugins();

    const movie = ref([]);
    const router = useRouter();
    const selectedMovie = ref(null);


    onMounted(() => {
        loadData();
    });

    function loadData() {
        $api.post($endPoints.getSingleMovie, {
            id: router.currentRoute.value.params.id,

        })
            .then(response => {
                movie.value = response.data.data;
            })
            .catch(e => {
                console.error(e);
            });
    }

    function getMoviePoster(movie) {
        const baseUrl = 'https://image.tmdb.org/t/p/original/';
        return baseUrl + movie.poster_path;
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

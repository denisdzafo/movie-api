<template>
    <section class="movie-section py-5">
        <div class="container">
            <div class="filter-section mb-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search">Search:</label>
                        <input type="text" v-model="searchTerm" @input="applyFilters" class="form-control" id="search" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="categories">Categories(multiple):</label>
                        <!-- Replace 'categories' with your actual data -->
                        <select v-model="selectedCategories" @change="applyFilters" class="form-control" id="categories" multiple>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy">Sort By:</label>
                        <select v-model="sortBy" @change="applyFilters" class="form-control" id="sortBy">
                            <option value="id">Default</option>
                            <option value="release_date">Release Date</option>
                            <option value="original_title">Title</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="itemsPerPage">Items per Page:</label>
                        <input type="number" v-model="itemsPerPage" @input="applyFilters" class="form-control" id="itemsPerPage" />
                    </div>
                </div>
            </div>

            <h2 class="mb-4">Top Movies</h2>
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
                            <a v-if="store.getToken" @click="followMovie(movie.id)" class="btn btn-secondary">Add To Favourite</a>
                        </div>
                    </div>
                </div>
            </div>

            <nav v-if="pages.length > 1" class="pagination-container">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" href="#" @click="gotoPage(currentPage - 1)">Previous</a>
                    </li>
                    <template v-if="totalPages <= 10">
                        <li v-for="page in pages" :key="page" :class="{ active: currentPage === page }">
                            <a class="page-link" href="#" @click="gotoPage(page)">{{ page }}</a>
                        </li>
                    </template>
                    <template v-else>
                        <li v-for="page in visiblePages" :key="page">
                            <template v-if="page === 'ellipsis'">
                                <span class="page-link">...</span>
                            </template>
                            <template v-else>
                                <a class="page-link" href="#" @click="gotoPage(page)">{{ page }}</a>
                            </template>
                        </li>
                    </template>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <a class="page-link" href="#" @click="gotoPage(currentPage + 1)">Next</a>
                    </li>
                </ul>
            </nav>
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
    const itemsPerPage = ref(5);
    const currentPage = ref(1);
    const totalPages = ref(0);
    const searchTerm = ref('');
    const selectedCategories = ref ([]);
    const categories = ref([]);
    const sortBy = ref('id');
    const router = useRouter();

    onMounted(() => {
        loadData();
        loadCategories();
    });

    function loadData() {
        $api.post($endPoints.getMovies, {
            page: currentPage.value,
            perPage: itemsPerPage.value,
            orderProp: sortBy.value,
            searchTerm: searchTerm.value,
            categories: selectedCategories.value
        })
            .then(response => {
                movies.value = response.data.data;
                totalPages.value = response.data.last_page;
            })
            .catch(e => {
                console.error(e);
            });
    }

    function loadCategories() {
        $api.get($endPoints.getAllCategories, )
            .then(response => {
                categories.value = response.data;
            })
            .catch(e => {
                console.error(e);
            });
    }

    function followMovie(movieId){
        $api.post($endPoints.addMovieToFavourite, {
            id: movieId,
        })
            .then(response => {

            })
            .catch(e => {
                console.error(e);
            });
    }

    function openSingleMovie(movieId){
        router.push({ name: "single", params: {id:movieId} });
    }

    function getMoviePoster(movie) {
        const baseUrl = 'https://image.tmdb.org/t/p/original/';
        return baseUrl + movie.poster_path;
    }

    function prevPage() {
        if (currentPage.value > 1) {
            currentPage.value--;
            loadData();
        }
    }

    function nextPage() {
        if (currentPage.value < totalPages.value) {
            currentPage.value++;
            loadData();
        }
    }

    function gotoPage(page) {
        if (page >= 1 && page <= totalPages.value) {
            currentPage.value = page;
            loadData();
        }
    }

    function applyFilters() {
        currentPage.value = 1;
        loadData();
    }

    const visiblePages = computed(() => {
        if (totalPages.value <= 10) {
            return pages.value;
        }

        const result = [];
        const aroundCurrentPage = 2; // Number of pages around the current page

        let startPage = Math.max(1, currentPage.value - aroundCurrentPage);
        let endPage = Math.min(totalPages.value, currentPage.value + aroundCurrentPage);

        if (startPage > 1) {
            result.push(1);
            if (startPage > 2) {
                result.push('ellipsis');
            }
        }

        for (let page = startPage; page <= endPage; page++) {
            result.push(page);
        }

        if (endPage < totalPages.value) {
            if (endPage < totalPages.value - 1) {
                result.push('ellipsis');
            }
            result.push(totalPages.value);
        }

        return result;
    });

    const totalPage = computed(() => {
        return Math.ceil(movies.value.length / itemsPerPage.value);
    });

    const pages= computed(() => {
        const pagesArray = [];
        for (let i = 1; i <=  totalPages.value; i++) {
            pagesArray.push(i);
        }

        return pagesArray;
    });

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

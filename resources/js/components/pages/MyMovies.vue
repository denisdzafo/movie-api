<template>
    <div>
        <SliderSection />
        <FavouriteMovies />
    </div>
</template>

<script>
    import SliderSection from './page_components/SliderSection.vue';
    import FavouriteMovies from './page_components/FavouriteMovies.vue';


    export default {
        components: {
            SliderSection,
            FavouriteMovies,

        },
    };
</script>


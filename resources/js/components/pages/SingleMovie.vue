<template>
    <div>
        <SliderSection />
        <MovieDetails />
    </div>
</template>

<script>
    import SliderSection from './page_components/SliderSection.vue';
    import MovieDetails from './page_components/MovieDetails.vue';

    export default {
        components: {
            SliderSection,
            MovieDetails,
        },
    };
</script>

<template>
    <div class="hero-section">
        <div class="hero-background" :style="{ backgroundImage: 'url(' + imageUrl + ')' }">
            <div class="overlay"></div>
            <div class="hero-text">
                <h1 class="hero-title">{{ title }}</h1>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SliderSection',
        data() {
            return {
                imageUrl: 'https://cdn.pixabay.com/photo/2016/04/04/14/12/monitor-1307227_960_720.jpg',
                title: 'Movie API',
            }
        }
    }
</script>

<style scoped>
    .hero-section {
        height: 35vh;
    }

    .hero-background {
        height: 100%;
        background-size: cover;
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .hero-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        margin: 0;
    }
</style>


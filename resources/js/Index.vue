<template>
    <div class="index">
        <my-navigation></my-navigation>
        <div class="main">
            <router-view></router-view>
        </div>
        <my-footer></my-footer>
    </div>
</template>

<script>
    import MyNavigation from './components/main/MyNavigation.vue';
    import MyFooter from './components/main/MyFooter.vue';

    export default {
        name: 'Index',
        components: {
            MyNavigation,
            MyFooter,
        }
    };
</script>

<style>
    .index {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
</style>

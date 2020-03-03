<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Index page for client render with SSR </h2>
                <div v-if="strings">
                    <ul>
                        <li v-for="(text, key) in strings" :class="key" v-text="text"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Index',
        computed: {
            strings() {
                return this.$store.getters['strings'];
            }
        }
    };
</script>

<style scoped>

</style>

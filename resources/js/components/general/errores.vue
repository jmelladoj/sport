<template>
    <ul class="ml-3" v-show="errores.length > 0">
        <li v-for="(e, index) in errores" :key="index" v-text="e"></li>
    </ul>
</template>

<script>
    export default {
        props: {
            errores: null
        }
    }
</script>
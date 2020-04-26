<template>
    <ul class="ml-3 mt-n4 mb-4" v-show="errores.length > 0">
        <li class="h6" v-for="(e, index) in errores" :key="index"><b>{{ e }}</b></li>
    </ul>
</template>

<script>
    export default {
        props: {
            errores: null
        }
    }
</script>
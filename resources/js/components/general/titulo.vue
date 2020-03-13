<template>
    <div class="row page-header">
        <div class="col-lg-12 align-self-center ">
            <h2>{{ titulo }}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">{{ titulo }}</li>
            </ol>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'titulo'
        ]
    }
</script>

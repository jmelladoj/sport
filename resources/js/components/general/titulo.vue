<template>
    <div class="row page-header block">
        <div class="col-lg-6 align-self-center">
            <h2>{{ titulo }}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">{{ titulo }}</li>
            </ol>
        </div>

        <div class="col-lg-6 align-self-center text-right">		
            <slot name="btn_accion"></slot>
            <b-button :class="accion ? '' : 'd-none'" variant="danger" class="box-shadow btn-icon btn-rounded" @click="$router.go(-1)" v-show="accion"><i class="fa fa-long-arrow-left"></i> Volver atrás</b-button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            titulo: String,
            accion: Boolean
        }
    }
</script>

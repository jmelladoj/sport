<template>
    <div>
        <titulo-pagina titulo="Reservas del cliente" :accion="true"></titulo-pagina>

        <section class="main-content">
            <b-card>
                <reservas-cliente :id_cliente="$route.params.id"></reservas-cliente>
            </b-card>
        </section>
    </div>
</template>
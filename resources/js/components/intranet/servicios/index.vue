<template>
    <div>
        <titulo-pagina :titulo="'Servicios de ' + especialidad.nombre " :accion="false">
            <template slot="btn_accion">
                <router-link class="btn btn-success box-shadow btn-icon btn-rounded" :to="{ name: 'administrar_servicios', params: {id_especialidad: especialidad.id}}"><i class="fa fa-plus"></i> Agregar registro</router-link>
            </template>
        </titulo-pagina>

        <section class="main-content">
            <b-card>
                <b-row>
                    <b-col lg="6" class="my-1">
                        <b-form-group label="Búsqueda" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="filterInput" class="mb-0" >
                        <b-input-group size="sm">
                            <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Escribe para buscar"></b-form-input>
                            <b-input-group-append>
                                <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                            </b-input-group-append>
                        </b-input-group>
                        </b-form-group>
                    </b-col>

                    <b-col lg="6" class="my-1">
                        <b-form-group label="Ordenar" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="sortBySelect" class="mb-0">
                            <b-input-group size="sm">
                                <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                                    <template v-slot:first>
                                        <option value="">Sin ordenar</option>
                                    </template>
                                </b-form-select>
                                <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                                    <option :value="false">Asc</option>
                                    <option :value="true">Desc</option>
                                </b-form-select>
                            </b-input-group>
                        </b-form-group>
                    </b-col>

                    <b-col lg="6" class="my-1">
                        <b-form-group label="Por página" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="perPageSelect" class="mb-0">
                            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-col lg="6" class="my-1">
                        <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm" class="my-0" ></b-pagination>
                    </b-col>
                </b-row>

                <b-table class="my-3" show-empty small striped outlined stacked="sm" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered" >
                    <template v-slot:empty>
                        <center><h5>No hay registros</h5></center>
                    </template>

                    <template v-slot:cell(index)="data">
                        {{ data.index + 1 }}
                    </template>

                    <template v-slot:cell(valor)="data">
                        {{ data.item.valor | currency }}
                    </template>

                    <template v-slot:cell(estado)="data">
                        <b-badge v-if="!data.item.deleted_at" variant="success" class="text-white">Activo</b-badge>
                        <b-badge v-else variant="danger" class="text-white">Inactivo</b-badge>
                    </template>

                    <template v-slot:cell(acciones)="row">
                        <router-link  class="btn btn-warning btn-rounded btn-xs" :to="{ name: 'administrar_servicios', params: {id_especialidad: especialidad.id, id: row.item.id}}"  v-b-tooltip.hover title="Actualizar información del registro"><i class="fa fa-pencil"></i></router-link>

                        <b-button v-if="row.item.deleted_at" variant="warning" class="btn-rounded"  size="xs" v-b-tooltip.hover title="Restaurar registro" @click="borrar(row.item.id, 2)">
                            <i class="fa fa fa-undo"></i>
                        </b-button>

                        <b-button v-else size="xs" variant="danger" class="btn-rounded" v-b-tooltip.hover title="Eliminar registro" @click="borrar(row.item.id, 1)">
                            <i class="fa fa-trash"></i>
                        </b-button>
                    </template>
                </b-table>
            </b-card>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                especialidad: {
                    id: null,
                    nombre: ''
                },
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'valor', label: 'Valor', sortable: true, class: 'text-left' },
                    { key: 'cantidad_sesiones', label: 'Sesiones', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 250],
                sortBy: '',
                sortDesc: false,
                filter: null,
            }
        },
        computed: {
            sortOptions() {
                return this.fields.filter(f => f.sortable).map(f => {
                    return { text: f.label, value: f.key }
                })
            }
        },
        methods: {
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_servicios(){
                if(!this.$route.params.id){
                    this.$router.push({ name: 'especialidades' });
                }

                let me = this

                axios.get('/api/servicio/' + this.$route.params.id).then(function (response) {
                    me.especialidad.id = response.data.id
                    me.especialidad.nombre = response.data.nombre
                    me.items = response.data.servicios
                }).catch(function (error) {
                    me.$store.commit('msg_error')
                })
            },
            borrar(id, accion) {
                swal.fire({
                    title: accion == 1 ? '¿Deseas borrar el registro?' : '¿Deseas resturar el registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: accion == 1 ? 'Sí, ¡bórrar!' : 'Sí, restaurar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        let me = this
                        axios.delete('/api/servicios/' + id).then(function (response) {
                            me.listar_servicios();
                            me.$store.commit('msg_success', accion == 1 ? 3 : 4)
                        }).catch(function (error) {
                            me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_servicios()
        }
    }
</script>

<template>
    <div>
        <titulo-pagina titulo="Horarios clínica"></titulo-pagina>

        <section class="main-content">
            <b-row>
                <b-col>
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

                            <template v-slot:cell(acciones)="row">
                                <b-button v-if="!row.item.deleted_at" size="xs" variant="warning" v-b-tooltip.hover title="Actualizar infromación de registro" @click="abrir_modal_hora(row.item)">
                                    <i class="fa fa-pencil"></i>
                                </b-button>

                                <b-button v-if="row.item.deleted_at" size="xs" v-b-tooltip.hover title="Restaurar registro" @click="borrar(row.item.id, 2)" class="btn btn-success">
                                    <i class="fa fa fa-undo"></i>
                                </b-button>

                                <b-button v-else size="xs" variant="danger" v-b-tooltip.hover title="Eliminar registro" @click="borrar(row.item.id, 1)">
                                    <i class="fa fa-trash"></i>
                                </b-button>
                            </template>
                        </b-table>
                    </b-card>
                </b-col>
            </b-row>

            <b-modal ref="modal_hora" :title="modal_hora.titulo" no-close-on-backdrop scrollable static>
                <b-form-group label="Hora">
                    <b-form-input
                        type="time"
                        v-model="$v.hora.hora.$model"
                        :state="$v.hora.hora.$dirty ? !$v.hora.hora.$error : null"
                        aria-describedby="hora-hora"
                    ></b-form-input>

                    <b-form-invalid-feedback id="hora-hora">
                        Campo de requerido.
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Tipo de hora">
                    <b-form-select
                        v-model="$v.hora.tipo_hora.$model"
                        :state="$v.hora.tipo_hora.$dirty ? !$v.hora.tipo_hora.$error : null"
                        aria-describedby="hora-tipo-hora">
                        <option :value="1">Hora normal</option>
                        <option :value="2">Hora de sobrecupo</option>
                    </b-form-select>

                    <b-form-invalid-feedback id="hora-tipo-hora">
                        Debes de seleccionar una opción.
                    </b-form-invalid-feedback>
                </b-form-group>

                <template slot="modal-footer">
                    <b-button type="button" v-b-tooltip.hover title="Añadir registro a la base de datos" :disabled="$v.hora.$invalid" v-show="modal_hora.accion == 1" size="md" variant="success" @click="crear_actualizar_hora"> Guardar </b-button>
                    <b-button type="button" v-b-tooltip.hover title="Actualizar información de registro" :disabled="$v.hora.$invalid" v-show="modal_hora.accion == 2" size="md" variant="warning" @click="crear_actualizar_hora"> Actualizar </b-button>
                    <b-button type="button" size="md" variant="danger" @click="cerrar_modal_hora"> Cerrar </b-button>
                </template>
            </b-modal>

            <div class="fixed-bottom mb-2 mr-2">
                <b-button v-if="consulta == 2" v-b-tooltip.hover title="Ocultar registros eliminados" pill variant="warning" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_horas(1)"><i class="fa fa-eye"></i></b-button>
                <b-button v-else v-b-tooltip.hover title="Mostrar registros eliminados" pill variant="danger" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_horas(2)"><i class="fa fa-eye-slash"></i></b-button>
                <b-button v-b-tooltip.hover title="Agregar registro" pill variant="success" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="abrir_modal_hora"><i class="fa fa-plus"></i></b-button>
            </div>
        </section>
    </div>

</template>

<script>
    import { required, between, email, minLength, isUnique, maxLength, numeric, requiredIf } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                consulta: 1,
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'hora', label: 'Hora', sortable: true, class: 'text-left' },
                    { key: 'nombre_tipo_hora', label: 'Tipo de horario', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_hora: {
                    titulo: '',
                    accion: 0
                },
                hora: {
                    id: 0,
                    hora: '',
                    tipo_hora: 1
                }
            }
        },
        validations:{
            hora: {
                hora: {
                    required,
                    async isUnique (value) {
                        if(this.modal_hora.accion == 2) return true
                        if (value === '' || value.length < 3) return true

                        const response = await fetch(`/horas/unico/${value}`)
                        return await response.json()
                    }
                },
                tipo_hora: {
                    required
                }
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
            ...mapMutations(['msg_success', 'msg_error']),
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_horas(consulta = 1){
                let me = this

                me.consulta = consulta

                axios.get('/horas/usuario/'+ me.consulta).then(function (response) {
                    me.items = response.data.horas
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_hora(data = []) {
                let me = this

                me.limpiar_datos_hora()
                me.modal_hora.titulo = data.id == undefined ? "Agregar hora" : "Modificar hora"
                me.modal_hora.accion = data.id == undefined ? 1 : 2

                if(me.modal_hora.accion == 2){
                    me.hora.id = data.id
                    me.hora.hora = data.hora
                    me.hora.tipo_hora = data.tipo_hora

                    this.$v.hora.$touch(true)
                }

                this.$refs['modal_hora'].show()
            },
            cerrar_modal_hora() {
                this.modal_hora.titulo = ""
                this.modal_hora.accion = 0
                this.$refs['modal_hora'].hide()
            },
            limpiar_datos_hora() {
                this.hora.id = 0
                this.hora.hora = ''
                this.hora.tipo_hora = 1

                this.$v.$reset();
            },
            crear_actualizar_hora() {
                if(this.$v.hora.$invalid){
                    this.$v.hora.$touch()
                    return
                }

                let me = this

                axios.post('/hora/crear/actualizar',{
                        'id': me.hora.id,
                        'hora': me.hora.hora,
                        'tipo_hora': me.hora.tipo_hora
                    }).then(function (response) {
                        me.listar_horas()
                        me.$store.commit('msg_success', me.hora.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.hora.id == 0){
                            me.limpiar_datos_hora()
                        } else {
                            me.cerrar_modal_hora()
                        }

                    }).catch(function (error) {
                        me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                        console.log(error)
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
                        axios.post('/hora/borrar',{
                            'id': id,
                            'accion': accion
                        }).then(function (response) {
                            me.listar_horas(me.consulta);
                            me.$store.commit('msg_success', accion == 1 ? 'Registro eliminado exitosamente.' : 'Registro restaurado exitosamente.')
                        }).catch(function (error) {
                            me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_horas()
        }
    }
</script>

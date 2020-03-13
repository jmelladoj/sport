<template>
    <div>
        <titulo-pagina titulo="Profesionales"></titulo-pagina>

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

                            <template v-slot:cell(telefono)="data">
                                +596 {{ data.item.telefono }}
                            </template>

                            <template v-slot:cell(acciones)="row">
                                <b-button v-if="!row.item.deleted_at" size="xs" variant="warning" v-b-tooltip.hover title="Actualizar infromación de registro" @click="abrir_modal_profesional(row.item)">
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

            <b-modal ref="modal_profesional" :title="modal_profesional.titulo" no-close-on-backdrop scrollable static>
                <b-row>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Nombre completo">
                            <b-form-input
                                v-model="$v.profesional.nombre.$model"
                                :state="$v.profesional.nombre.$dirty ? !$v.profesional.nombre.$error : null"
                                aria-describedby="profesional-nombre"
                            ></b-form-input>

                            <b-form-invalid-feedback id="profesional-nombre">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Run">
                            <b-form-input
                                v-rut:live
                                v-model="$v.profesional.run.$model"
                                :state="$v.profesional.run.$dirty ? !$v.profesional.run.$error : null"
                                aria-describedby="profesional-run"
                            ></b-form-input>

                            <b-form-invalid-feedback id="profesional-run">
                                Debes ingresar un run válido y no existente en la base de datos.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Dirección">
                            <b-form-input
                                v-model="$v.profesional.direccion.$model"
                                :state="$v.profesional.direccion.$dirty ? !$v.profesional.direccion.$error : null"
                                aria-describedby="profesional-direccion"
                            ></b-form-input>

                            <b-form-invalid-feedback id="profesional-direccion">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Correo">
                            <b-form-input
                                v-model="$v.profesional.correo.$model"
                                :state="$v.profesional.correo.$dirty ? !$v.profesional.correo.$error : null"
                                aria-describedby="profesional-correo"
                            ></b-form-input>

                            <b-form-invalid-feedback id="profesional-correo">
                                Campo de alfanúmerico, mínimo de 3 caracteres y no debe de estar en la base de datos.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Teléfono">
                            <b-form-input
                                v-model="$v.profesional.telefono.$model"
                                :state="$v.profesional.telefono.$dirty ? !$v.profesional.telefono.$error : null"
                                aria-describedby="profesional-telefono"
                            ></b-form-input>

                            <b-form-invalid-feedback id="profesional-telefono">
                                Campo de númerico, debes ingresar un número de teléfono ej: 987654321.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Especialidad">
                            <b-input-group>
                                <b-form-select
                                    v-model="$v.profesional.especialidad_id.$model"
                                    :state="$v.profesional.especialidad_id.$dirty ? !$v.profesional.especialidad_id.$error : null"
                                    aria-describedby="profesional-especialidad-id">
                                    <option :value="null">Selecciona una opción</option>
                                    <option v-for="e in especialidades" :key="e.id" :value="e.id" v-text="e.nombre"></option>
                                </b-form-select>

                                <template v-slot:append>
                                    <b-button size="xs" variant="success" v-b-tooltip.hover title="Agregar especialidad" @click="abrir_modal_especialidad">
                                        <i class="fa fa-plus"></i>
                                    </b-button>
                                </template>

                                <b-form-invalid-feedback id="profesional-especialidad-id">
                                    Debes de seleccionar una opción.
                                </b-form-invalid-feedback>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="12">
                        <b-form-group label="Observaciones">
                            <b-form-textarea
                                v-model="$v.profesional.observacion.$model"
                                :state="$v.profesional.observacion.$dirty ? !$v.profesional.observacion.$error : null"
                                aria-describedby="profesional-observacion"
                                rows="3"
                                max-rows="6"
                            ></b-form-textarea>

                            <b-form-invalid-feedback id="profesional-observacion">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>

                <template slot="modal-footer">
                    <b-button type="button" v-b-tooltip.hover title="Añadir registro a la base de datos" :disabled="$v.profesional.$invalid" v-show="modal_profesional.accion == 1" size="md" variant="success" @click="crear_actualizar_profesional"> Guardar </b-button>
                    <b-button type="button" v-b-tooltip.hover title="Actualizar información de registro" :disabled="$v.profesional.$invalid" v-show="modal_profesional.accion == 2" size="md" variant="warning" @click="crear_actualizar_profesional"> Actualizar </b-button>
                    <b-button type="button" size="md" variant="danger" @click="cerrar_modal_profesional"> Cerrar </b-button>
                </template>
            </b-modal>

            <b-modal ref="modal_especialidad" title="Agregar especialidad" no-close-on-backdrop scrollable static>
                <b-form-group label="Nombre">
                    <b-form-input
                        v-model="$v.especialidad.nombre.$model"
                        :state="$v.especialidad.nombre.$dirty ? !$v.especialidad.nombre.$error : null"
                        aria-describedby="especialidad-nombre"
                    ></b-form-input>

                    <b-form-invalid-feedback id="especialidad-nombre">
                        Campo de alfanúmerico, mínimo de 3 caracteres y no debe estar en la base de datos.
                    </b-form-invalid-feedback>
                </b-form-group>

                <template slot="modal-footer">
                    <b-button type="button" v-b-tooltip.hover title="Añadir registro a la base de datos" :disabled="$v.especialidad.$invalid" size="md" variant="success" @click="crear_especialidad"> Guardar </b-button>
                    <b-button type="button" size="md" variant="danger" @click="cerrar_modal_especialidad"> Cerrar </b-button>
                </template>
            </b-modal>

            <div class="fixed-bottom mb-2 mr-2">
                <b-button v-if="consulta == 2" v-b-tooltip.hover title="Ocultar registros eliminados" pill variant="warning" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_profesionales(1)"><i class="fa fa-eye"></i></b-button>
                <b-button v-else v-b-tooltip.hover title="Mostrar registros eliminados" pill variant="danger" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="listar_profesionales(2)"><i class="fa fa-eye-slash"></i></b-button>
                <b-button v-b-tooltip.hover title="Agregar registro" pill variant="success" size="sm" class="pull-right heigth-25 px-2 ml-1 mr-1" @click="abrir_modal_profesional"><i class="fa fa-plus"></i></b-button>
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
                especialidades: [],
                consulta: 1,
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'nombre_especialidad', label: 'Especialidad', sortable: true, class: 'text-left' },
                    { key: 'correo', label: 'Correo', sortable: true, class: 'text-left' },
                    { key: 'telefono', label: 'Teléfono', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_profesional: {
                    titulo: '',
                    accion: 0
                },
                profesional: {
                    id: 0,
                    run: '',
                    nombre: '',
                    direccion: '',
                    correo: '',
                    telefono: null,
                    especialidad_id: null,
                    observacion: ''
                },
                especialidad: {
                    nombre: ''
                }
            }
        },
        validations:{
            profesional: {
                run: {
                    required,
                    minLength: minLength(7),
                    async isUnique (value) {
                        if(this.modal_profesional.accion == 2) return true
                        if (value === '' || value.length < 3) return true

                        const response = await fetch(`/profesionales/unico/${value}`)
                        return await response.json()
                    }
                },
                nombre: {
                    required,
                    minLength: minLength(3)
                },
                direccion: {
                    required,
                    minLength: minLength(3)
                },
                correo: {
                    required: requiredIf(function () {
                        return this.modal_profesional.accion == 1 ? false : true
                    }),
                    email,
                    minLength: minLength(3)
                },
                telefono: {
                    required,
                    numeric,
                    minLength: minLength(9),
                    maxLength: maxLength(9)
                },
                especialidad_id: {
                    required
                },
                observacion: {
                    minLength: minLength(3),
                }
            },
            especialidad: {
                nombre: {
                    required,
                    minLength: minLength(3),
                    async isUnique (value) {
                        if (value === '' || value.length < 3) return true

                        const response = await fetch(`/especialidades/unico/${value}`)
                        return await response.json()
                    }
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
            listar_especialidades(){
                let me = this

                axios.get('/especialidades/usuario/1').then(function (response) {
                    me.especialidades = response.data.especialidades
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_profesionales(consulta = 1){
                let me = this

                me.consulta = consulta

                axios.get('/profesionales/usuario/'+ me.consulta).then(function (response) {
                    me.items = response.data.profesionales
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_especialidad(data = []) {
                this.$refs['modal_especialidad'].show()
            },
            cerrar_modal_especialidad() {
                this.especialidad.nombre = ''
                this.$refs['modal_especialidad'].hide()
            },
            abrir_modal_profesional(data = []) {
                let me = this

                me.limpiar_datos_profesional()
                me.modal_profesional.titulo = data.id == undefined ? "Agregar profesional" : "Modificar profesional"
                me.modal_profesional.accion = data.id == undefined ? 1 : 2

                if(me.modal_profesional.accion == 2){
                    me.profesional.id = data.id
                    me.profesional.run = data.run
                    me.profesional.nombre = data.nombre
                    me.profesional.direccion = data.direccion
                    me.profesional.correo = data.correo
                    me.profesional.telefono = data.telefono
                    me.profesional.especialidad_id = data.especialidad_id
                    me.profesional.observacion = data.observacion

                    this.$v.profesional.$touch(true)
                }

                this.$refs['modal_profesional'].show()
            },
            cerrar_modal_profesional() {
                this.modal_profesional.titulo = ""
                this.modal_profesional.accion = 0
                this.$refs['modal_profesional'].hide()
            },
            limpiar_datos_profesional() {
                this.profesional.id = 0
                this.profesional.run = ''
                this.profesional.nombre = ''
                this.profesional.direccion = ''
                this.profesional.correo = ''
                this.profesional.telefono = null
                this.profesional.especialidad_id = null
                this.profesional.observacion = ''

                this.$v.$reset();
            },
            crear_actualizar_profesional() {
                if(this.$v.profesional.$invalid){
                    this.$v.profesional.$touch()
                    return
                }

                let me = this

                axios.post('/profesional/crear/actualizar',{
                        'id': me.profesional.id,
                        'run': me.profesional.run,
                        'nombre': me.profesional.nombre,
                        'direccion': me.profesional.direccion,
                        'correo': me.profesional.correo,
                        'telefono': me.profesional.telefono,
                        'especialidad_id': me.profesional.especialidad_id,
                        'observacion': me.profesional.observacion
                    }).then(function (response) {
                        me.listar_profesionales()
                        me.$store.commit('msg_success', me.profesional.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.profesional.id == 0){
                            me.limpiar_datos_profesional()
                        } else {
                            me.cerrar_modal_profesional()
                        }

                    }).catch(function (error) {
                        me.$store.commit('msg_error', 'Ha ocurrido un error, por favor intentalo nuevamente.')
                        console.log(error)
                })
            },
            crear_especialidad() {
                if(this.$v.especialidad.$invalid){
                    this.$v.especialidad.$touch()
                    return
                }

                let me = this

                axios.post('/especialidad/crear/actualizar',{
                        'id': 0,
                        'nombre': me.especialidad.nombre
                    }).then(function (response) {
                        me.listar_especialidades()
                        me.$store.commit('msg_success', 'Registro agregado exitosamente.')
                        me.profesional.especialidad_id = response.data.id
                        me.cerrar_modal_especialidad()
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
                        axios.post('/profesional/borrar',{
                            'id': id,
                            'accion': accion
                        }).then(function (response) {
                            me.listar_profesionales(me.consulta);
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
            this.listar_profesionales()
            this.listar_especialidades()
        }
    }
</script>

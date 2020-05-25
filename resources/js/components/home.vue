<template>
    <div>
        <div class="top-bar primary-top-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <a class="admin-logo" href="javascript::void(0)">
                            <h1>
                                <img alt="" src="/assets/img/src.png" class="logo-icon margin-r-10 img-fluid" width="120">
                            </h1>
                        </a>
                        <div class="left-nav-toggle" >
                            <a  href="#" class="nav-collapse"><i class="fa fa-bars"></i></a>
                        </div>
                        <div class="left-nav-collapsed" >
                            <a  href="#" class="nav-collapsed"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="list-inline top-right-nav">
                            <!--<li class="dropdown icons-dropdown d-none-m">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bell"></i><div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div></a>
                                <ul class="dropdown-menu top-dropdown lg-dropdown notification-dropdown">
                                    <li>
                                        <div class="dropdown-header">
                                            <a class="float-right" href="#"><small>View All</small></a> Notifications
                                        </div>
                                        <div class="scrollDiv">
                                            <div class="notification-list">

                                                <a class="clearfix" href="javascript:%20void(0);">
                                                    <span class="notification-icon">
                                                        <i class="icon-cloud-upload text-primary"></i>
                                                    </span>
                                                    <span class="notification-title">Upload Complete</span>
                                                    <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                                                    <span class="notification-time">15 minutes ago</span>
                                                </a>

                                                <a class="clearfix" href="javascript:%20void(0);">
                                                    <span class="notification-icon">
                                                        <i class="icon-info text-warning"></i>
                                                    </span>
                                                    <span class="notification-title">Storage Space low</span>
                                                    <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                                                    <span class="notification-time">15 minutes ago</span>
                                                </a>

                                                <a class="clearfix" href="javascript:%20void(0);">
                                                    <span class="notification-icon">
                                                        <i class="icon-check text-success"></i>
                                                    </span>
                                                    <span class="notification-title">Project Task Complete</span>
                                                    <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                                                    <span class="notification-time">15 minutes ago</span>
                                                </a>

                                                <a class="clearfix" href="javascript:%20void(0);">
                                                    <span class="notification-icon">
                                                        <i class=" icon-graph text-danger"></i>
                                                    </span>
                                                    <span class="notification-title">CPU Usage</span>
                                                    <span class="notification-description">Lorem Ipsum is simply dummy text of the printing.</span>
                                                    <span class="notification-time">15 minutes ago</span>
                                                </a>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>-->
                            <li class="dropdown avtar-dropdown">
                                <a href="#">
                                    <img alt="" class="rounded-circle" src="assets/img/avtar-1.png" width="30">
                                    <span v-if="usuario" v-text="usuario.nombre" class="text-capitalize"></span>
                                </a>
                                <ul class="dropdown-menu top-dropdown">
                                    <li>
                                        <a class="dropdown-item" href="javascript:%20void(0);"><i class="icon-bell"></i> Activities</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:%20void(0);"><i class="icon-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:%20void(0);"><i class="icon-settings"></i> Settings</a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:%20void(0);"><i class="icon-logout"></i> Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-sidebar-nav default-navigation">
            <div class="nano">
                <div class="nano-content sidebar-nav">

					<div class="card-body border-bottom text-center nav-profile">
						<div class="notify setpos"> <span class="heartbit"></span> <span class="point hidden"></span> </div>
                        <img alt="profile" class="margin-b-10  " src="assets/img/avtar-1.png" width="80">
                        <p class="lead margin-b-0 toggle-none text-capitalize" v-if="usuario" v-text="usuario.nombre"></p>
                        <p class="text-muted mv-0 toggle-none">Bienvenido</p>
                    </div>

                    <ul class="metisMenu nav flex-column" id="menu">
                        <li class="nav-heading"><span>Personas</span></li>
                        <li class="nav-item"><router-link class="nav-link" to="/home"><i class="fa fa-home"></i><span class="toggle-none">Escritorio</span></router-link></li>
                        <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/clientes"><i class="fa fa-users"></i><span class="toggle-none">Clientes</span></router-link></li>

                        <li class="nav-heading" v-if="usuario && usuario.tipo_usuario == 1"><span>Clínica</span></li> 
                        <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/profesionales"><i class="fa fa-user-md"></i><span class="toggle-none">Profesionales</span></router-link></li>
                         <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/ventas"><i class="fa fa-usd"></i><span class="toggle-none">Ventas</span></router-link></li>

                        <li class="nav-heading" v-if="usuario && usuario.tipo_usuario == 1"><span>Configuración</span></li>
                        <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/usuarios"><i class="fa fa-users"></i><span class="toggle-none">Usuarios</span></router-link></li>
                        <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/especialidades"><i class="fa fa-list"></i><span class="toggle-none">Especialidades</span></router-link></li>
                        <li class="nav-item" v-if="usuario && usuario.tipo_usuario == 1"><router-link class="nav-link" to="/horarios/clinica"><i class="fa fa-cog"></i><span class="toggle-none">Horas clínica</span></router-link></li>
                        <li class="nav-item"><b-button size="sm" variant="danger" class="btn-rounded mt-4" block @click="logout"><i class="fa fa-sign-out"></i><span class="toggle-none">Salir</span></b-button></li>
                    </ul>
                </div>
            </div> 
        </div>

        <router-view></router-view>

        <footer class="footer text-center ml-0">
            <span>Copyright &copy; 2020 SRCenter APP</span>
        </footer>
    </div>
</template>

<script>
    import { mapState, mapActions, mapMutations } from 'vuex'

    export default {
        computed: {
            ...mapState('usuario', ['usuario'])
        },
        methods: {
            ...mapActions('usuario', ['salir']),
            ...mapMutations(['msg_error', 'cambiar_token', 'eliminar_token']),
            logout(){
                let me = this

                axios.get('/api/auth/logout').then(function (response) {
                    me.eliminar_token()
                    me.salir()
                    window.location = '/'
                })
            }   
        },
        created() {
            this.cambiar_token()

            if(!this.$store.state.access_token){
                window.location = '/'
            }
        }
    }
</script>

<style>
    @import '../../../public/css/app.css';

    html,body{
        height: 100%;
    }
</style>

<template>
    <header class="topnavbar-wrapper">
        <!-- START Top Navbar-->
        <nav class="navbar topnavbar">
            <!-- START navbar header-->
            <div class="navbar-header">
                <a class="navbar-brand" href="#/">
                    <div class="brand-logo">
                        <img class="img-fluid" src="img/logos/jumpsix-logo_white.png" alt="App Logo">
                    </div>
                    <div class="brand-logo-collapsed">
                        <img class="img-fluid" src="img/logos/triangle_logo_white_sm.png" alt="App Logo">
                    </div>
                </a>
            </div>
            <!-- END navbar header-->
            <!-- START Left navbar-->
            <ul class="navbar-nav mr-auto flex-row">


            </ul>
            <!-- END Left navbar-->
            <!-- START Right Navbar-->
            <ul class="navbar-nav flex-row">
                <!-- Search icon-->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-search-open="">
                        <em class="icon-magnifier"></em>
                    </a>
                </li>

                <b-nav-item-dropdown class="dropdown-list" no-caret menuClass="animated flipInX" right>
                    <template slot="button-content">
                        <em class="icon-user"></em>
                    </template>
                    <b-dropdown-item>
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status">
                                    <img class="img-thumbnail rounded-circle" :src="user_avatar" alt="Avatar" width="60"
                                         height="60">
                                    <div class="circle bg-success circle-lg"></div>
                                </div>
                            </div>
                            <!-- Name and Job-->
                            <div class="user-block-info">
                                <span class="user-block-name" v-text="user_full_name"></span>
                                <span class="user-block-role">{{user_role | capitalize}}</span>
                            </div>
                        </div>

                    </b-dropdown-item>
                    <b-dropdown-item @click="logout()">
                        <div class="item bg-danger text-center">Log Out</div>
                    </b-dropdown-item>
                </b-nav-item-dropdown>
                <!-- START Alert menu-->

                <b-nav-item-dropdown class="dropdown-list" no-caret menuClass="animated flipInX" right>
                    <template slot="button-content">
                        <em class="icon-bell"></em>
                        <span class="badge badge-danger" v-if="notifs.length > 0">{{notifs.length}}</span>

                    </template>
                    <b-dropdown-item>
                        <div class="list-group">
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action" v-for="notif in notifs">
                                <div class="media">
                                    <div class="media-body">
                                        <p class="m-0">New User - {{notif.data.name}}</p>
                                    </div>
                                </div>
                            </div>
                            <div @click="markAsRead" v-if="notifs.length > 0"
                                 class="list-group-item list-group-item-action bg-primary align-items-center text-center">
                                <span class="text-center">
                                    Mark All As Read
                                </span>
                            </div>
                        </div>
                    </b-dropdown-item>
                </b-nav-item-dropdown>

                <!-- END Alert menu-->

            </ul>
            <!-- END Right Navbar-->
            <HeaderSearch/>
        </nav>
        <!-- END Top Navbar-->
    </header>
</template>

<script>
    import HeaderSearch from './HeaderSearch'
    import ToggleFullscreen from '../Common/ToggleFullscreen'

    export default {
        name: 'Header',
        data() {
            return {
                user_avatar: this.$store.getters.getUser.avatar,
                user_full_name: this.$store.getters.getUser.name,
                user_role: this.$store.getters.getUser.role,
                notifs: this.$store.getters.getUser.notifs
            }
        },
        components: {
            HeaderSearch,
            ToggleFullscreen
        },
        methods: {
            markAsRead() {
                let id = this.$store.getters.getUser.id
                let self = this
                axios.patch('/api/users/' + id + '/markNotifs')
                    .then(function () {
                        self.notifs = []
                    })
            },
            logout(){
                axios.get('/logout');
                this.$store.dispatch('logoutUser')
            }
        },
        created() {
        }
    }
</script>

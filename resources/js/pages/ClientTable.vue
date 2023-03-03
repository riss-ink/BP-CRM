<template>
    <ContentWrapper>
        <div class="content-heading">
            <div>Clients
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <select class="form-control" v-model="teamId" v-if="can.includes('view all clients')">
                            <option selected disabled value="">Select Team</option>
                            <option value="0">All</option>
                            <option v-for="(team, i) in teams" :value="i">{{team}}</option>
                        </select>
                    </div>
                    <div class="justify-content-center spinner align-items-center row  ">
                        <div class="ball-grid-beat">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <table class="table my-4" style="display: none;">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Url</th>
                            <th>Status</th>
                            <th>Sales Person</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="td in tableData">
                            <td>{{td.name}}</td>
                            <td>{{td.email}}</td>
                            <td>{{td.phone}}</td>
                            <td>Website: <a :href="td.current_url" target="_blank"><em class="fas fa-external-link-square-alt"></em> </a> </td>
                            <td>{{td.status}}</td>
                            <td>{{td.user.name}}</td>
                            <td>
                                <router-link tag="button" :to="{path: 'clients/' + td.id}" :class="'btn mr-1 mb-1 btn-success'">View</router-link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </ContentWrapper>
</template>

<script>
    require('datatables.net-bs')
    require('datatables.net-bs4/js/dataTables.bootstrap4.js')
    require('datatables.net-bs4/css/dataTables.bootstrap4.css')
    require('datatables.net-buttons')
    require('datatables.net-buttons-bs')
    require('datatables.net-responsive')
    require('datatables.net-responsive-bs')
    require('datatables.net-responsive-bs/css/responsive.bootstrap.css')
    require('datatables.net-buttons/js/buttons.colVis.js') // Column visibility
    require('datatables.net-buttons/js/buttons.html5.js') // HTML 5 file export
    require('datatables.net-buttons/js/buttons.flash.js') // Flash file export
    require('datatables.net-buttons/js/buttons.print.js') // Print view button
    require('datatables.net-keytable');
    require('datatables.net-keytable-bs/css/keyTable.bootstrap.css')
    require('jszip/dist/jszip.js');
    require('pdfmake/build/pdfmake.js');
    require('pdfmake/build/vfs_fonts.js');
    import 'loaders.css/loaders.css';
    import 'spinkit/css/spinkit.css';

    export default {
        name: "ClientTable",
        components: {},
        data() {
            return {
                dtOptions: {'paging': true, // Table pagination
                    'info': true, // Bottom left status text
                    responsive: true,
                    "columns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        { "orderable": false },
                    ],

                    // Text translation options
                    // Note the required keywords between underscores (e.g _MENU_)
                    oLanguage: {
                        sSearch: '<em class="fa fa-search"></em>',
                        sLengthMenu: '_MENU_ records per page',
                        info: 'Showing page _PAGE_ of _PAGES_',
                        zeroRecords: 'Nothing found - sorry',
                        infoEmpty: 'No records available',
                        infoFiltered: '(filtered from _MAX_ total records)',
                        oPaginate: {
                            sNext: '<em class="fa fa-caret-right"></em>',
                            sPrevious: '<em class="fa fa-caret-left"></em>'
                        }
                    },
                    "initComplete": function (settings, json) {
                        $('.spinner').fadeOut(function () {
                            $('.table').fadeIn()

                        })
                    }},
                teams: {},
                teamId: '',
                tableData: {},
                can: this.$store.getters.getUserCan
            }
        },
        watch:{
            teamId:function () {
                this.getClientsWithTeam()
            }
        },
        mounted() {
            let self = this;
            axios.get('api/clients')
                .then(function (res) {
                    self.tableData = res.data.clients;
                    self.teams = res.data.teams
                }).then(function () {
                $('.table').DataTable(self.dtOptions)

            })
        },
        methods:{
            getClientsWithTeam(){
                let self = this;
                $('.table').DataTable().destroy();
                axios.get('api/clients', {params: {teamId : self.teamId}})
                    .then(function (res) {
                        self.tableData = res.data.clients;
                        self.teams = res.data.teams
                    }).then(function () {
                    $('.table').DataTable(self.dtOptions);
                })
            }
        },
        updated: function () {

        }
    }
</script>

<style scoped>

</style>

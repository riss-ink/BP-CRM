<template>
    <ContentWrapper>
        <div class="card card-default">
            <div class="card-header">
                Users
            </div>
            <div class="card-body">
                <div class="justify-content-center spinner align-items-center row  ">
                    <div class="pacman">
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
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Team</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="td in tableData">
                        <td><img :src="td.avatar" class="align-self-center mr-3 rounded-circle thumb48"/></td>
                        <td>{{td.name}}</td>
                        <td>{{td.email}}</td>
                        <td ><span v-if="td.team">{{td.team.name}}</span></td>
                        <td>
                            <router-link tag="button" :to="{path: 'users/' + td.id}" :class="'btn mr-1 mb-1 btn-success'">View</router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </ContentWrapper>
</template>

<script>
    import 'loaders.css/loaders.css';
    import 'spinkit/css/spinkit.css';
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
    export default {
        name: "UsersIndex",
        data(){
            return{
                tableData: {}
            }
        },
        mounted() {
            let self = this;
            axios.get('api/users')
                .then(function (res) {
                    self.tableData = res.data;
                })
        },
        updated: function () {
            $('.table').DataTable({
                'paging': true, // Table pagination
                'info': true, // Bottom left status text
                'order': [[1, 'asc']],
                responsive: true,
                "columns": [
                    { "orderable": false },
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
                }
            })

        }
    }
</script>

<style scoped>

</style>
<template>
    <ContentWrapper>
        <div class="content-heading">
            <h3>Definitions</h3>
        </div>
        <div class="card card-default">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Definition</th>
                        <th>Services</th>
                        <th>Field Group</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(definition, definitionIndex) in definitions">
                        <td>{{definition.name | capitalize}}</td>
                        <td><ul class="list-unstyled"><li v-for="category in definition.product_categories">{{category.name}}</li> </ul></td>
                        <td v-if="definition.field_group">{{definition.field_group.name | capitalize}}</td>
                        <td v-else>Default</td>
                        <td>
                            <router-link tag="button" :to="{path: 'definitions/' + definition.id}" :class="'btn mr-1 mb-1 btn-success'">Edit</router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </ContentWrapper>

</template>

<script>
    export default {
        name: "DefinitionIndex",
        data(){
            return{
                definitions : []
            }
        },
        mounted() {
            this.getDefinitions()
        },
        methods:{
            getDefinitions(){
                axios.get('/api/definitions')
                    .then(res => this.definitions = res.data)
            }
        },
        updated: function () {
            $('.table').DataTable({
                'paging': true, // Table pagination
                'info': true, // Bottom left status text
                responsive: true,
                "columns": [
                    null,
                    null,
                    null,
                    {"orderable": false},
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
                }
            })
        }
    }
</script>

<style scoped>

</style>
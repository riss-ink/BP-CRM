<template>
    <ContentWrapper>
        <div class="content-heading">
            <h3>Roles</h3>
        </div>
        <div class="card card-default">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Role</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(role, roleIndex) in roles">
                        <td>{{role.name | capitalize}}</td>
                        <td>
                            <button @click="showModal(roleIndex)" class="btn btn-success">Edit</button>
                            <button @click="deleteRole(role.id)" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-success" @click="showModal('')">Add Role</button>
            </div>
        </div>
        <b-modal size="lg" ref="roleModal" hide-footer :title="modalTitle">
            <form @submit.prevent="updateModal">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" v-model="roleForm.name"/>
                </div>
                <div class="form-group">
                    <label>Permissions</label>
                    <div v-for="(permission, permissionIndex) in permissions" class="checkbox c-checkbox">
                        <label>
                            <input type="checkbox" :value="permissionIndex" v-model="roleForm.permissions"/>
                            <span class="fa fa-check"></span>{{permission|capitalize}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </b-modal>
    </ContentWrapper>
</template>

<script>
    import swal from 'sweetalert2'
    import 'loaders.css/loaders.css';
    import 'spinkit/css/spinkit.css';


    export default {
        name: "RolesIndex",
        data() {
            return {
                roles: '',
                permissions: '',
                roleForm: new Form({
                    name: '',
                    permissions: [],
                    id: ''
                }),
                modalTitle: '',
            }
        },

        mounted() {
            this.loadRoles()
        },
        methods: {
            showModal(roleIndex) {
                this.roleForm.name = ''
                this.roleForm.permissions = []
                this.roleForm.id = ''
                if (roleIndex === '') {
                    this.modalTitle = 'Add Role'
                } else {
                    this.modalTitle = 'Edit Role'
                    this.roleForm.name = this.roles[roleIndex].name
                    this.roleForm.id = this.roles[roleIndex].id
                    for (let i in this.roles[roleIndex].permissions) {
                        this.roleForm.permissions.push(this.roles[roleIndex].permissions[i].id)
                    }
                }
                this.$refs.roleModal.show();
            },
            updateModal() {
                let self = this
                if (self.roleForm.id !== '') {
                    self.roleForm.patch('api/roles/' + self.roleForm.id)
                        .then(function () {
                            self.$refs.roleModal.hide()
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                            self.loadRoles()
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                tpye: 'error'
                            });
                        })
                } else {
                    self.roleForm.post('api/roles')
                        .then(function () {
                            self.$refs.roleModal.hide()
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                            self.loadRoles()
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                tpye: 'error'
                            });
                        })
                }
            },
            deleteRole(roleId) {
                let self = this
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('api/roles/' + roleId)
                            .then(function () {
                                self.loadRoles()
                                swal(
                                    'Deleted!',
                                    'Role has been deleted.',
                                    'success'
                                )
                            })
                            .catch(function () {
                                swal({
                                    title: 'Error!',
                                    tpye: 'error'
                                });
                            })

                    }
                })
            },
            loadRoles() {
                let self = this;
                axios.get('api/roles')
                    .then(function (res) {
                        self.roles = res.data.roles;
                        self.permissions = res.data.permissions
                    })
            }

        }
    }
</script>

<style scoped>

</style>
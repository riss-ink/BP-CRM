<template>
    <ContentWrapper>
        <div class="content-heading">
            <h3>Permissions</h3>
        </div>
        <div class="card card-default">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Permission</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(permission, permissionIndex) in permissions">
                        <td>{{permission.name | capitalize}}</td>
                        <td>
                            <button @click="showModal(permissionIndex)" class="btn btn-success">Edit</button>
                            <button @click="deletePermission(permission.id)" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-success" @click="showModal('')">Add permission</button>
            </div>
        </div>
        <b-modal size="lg" ref="permissionModal" hide-footer :title="modalTitle">
            <form @submit.prevent="updateModal">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" v-model="permissionForm.name"/>
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
        name: "PermissionsIndex",
        data() {
            return {
                permissions: '',
                permissionForm: new Form({
                    name: '',
                    id: ''
                }),
                modalTitle: '',
            }
        },
        mounted() {
            this.loadPermissions()
        },
        methods: {
            showModal(permissionIndex) {
                this.permissionForm.name = ''
                this.permissionForm.id = ''
                if (permissionIndex === '') {
                    this.modalTitle = 'Add Permission'
                } else {
                    this.modalTitle = 'Edit Permission'
                    this.permissionForm.name = this.permissions[permissionIndex].name
                    this.permissionForm.id = this.permissions[permissionIndex].id
                }
                this.$refs.permissionModal.show();
            },
            updateModal() {
                let self = this
                if (self.permissionForm.id !== '') {
                    self.permissionForm.patch('api/permissions/' + self.permissionForm.id)
                        .then(function () {
                            self.$refs.permissionModal.hide()
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                            self.loadPermissions()
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                tpye: 'error'
                            });
                        })
                } else {
                    self.permissionForm.post('api/permissions')
                        .then(function () {
                            self.$refs.permissionModal.hide()
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                            self.loadPermissions()
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                tpye: 'error'
                            });
                        })
                }
            },
            deletePermission(permissionId) {
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
                        axios.delete('api/permissions/' + permissionId)
                            .then(function () {
                                self.loadPermissions()
                                swal(
                                    'Deleted!',
                                    'permission has been deleted.',
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
            loadPermissions() {
                let self = this;
                axios.get('api/permissions')
                    .then(function (res) {
                        self.permissions = res.data;
                    })
            }

        }
    }
</script>

<style scoped>

</style>
<template>
    <ContentWrapper>
        <div class="content-heading">
            <h2 v-if="this.$route.params.slug">Edit Team</h2>
            <h2 v-else>Add Team</h2>
        </div>
        <div class="card card-default">
            <div class="card-body">
                <form @submit.prevent="onSubmit">
                    <b-form-group label="Name">
                        <b-form-input v-model="form.name"></b-form-input>
                    </b-form-group>
                    <b-form-group label="Users">
                        <b-form-checkbox-group :options="users"
                                               v-model="form.users"></b-form-checkbox-group>
                    </b-form-group>
                    <b-button type="submit" variant="primary">Submit</b-button>

                </form>
            </div>
        </div>
    </ContentWrapper>
</template>

<script>
    import swal from 'sweetalert2'

    export default {
        name: "TeamForm",
        data() {
            return {
                form: new Form({
                    name: '',
                    users: []
                }),
                users: []
            }
        },
        mounted() {
            this.getAllUsers()
            if (this.$route.params.slug) {
                this.getTeam()
            }
        },
        methods: {
            getAllUsers() {
                let self = this
                axios.get('api/users')
                    .then(function (res) {
                        let users = []
                        for (let i in res.data) {
                            users.push({text: res.data[i].name, value: res.data[i].id})
                        }
                        self.users = users
                    })
            },
            getTeam() {
                let self = this
                axios.get('api/teams/' + this.$route.params.slug)
                    .then(function (res) {
                        self.form = new Form(res.data)
                    })
            },
            onSubmit() {
                let self = this
                if (this.$route.params.slug) {
                    self.form.patch('api/teams/' + self.$route.params.slug)
                        .then(function () {
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                type: 'error'
                            });
                        })
                } else {
                    self.form.post('api/teams')
                        .then(function () {
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                        })
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                type: 'error'
                            });
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
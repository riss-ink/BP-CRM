<template>
    <ContentWrapper>
        <div class="content-heading">
            <img :src="form.avatar" class="img-thumbnail rounded-circle thumb64"/>
            <h2>{{form.name}}</h2>
        </div>
        <div class="card card-default">
            <div class="card-header">
                Edit User
            </div>
            <div class="card-body">
                <form @submit.prevent="onSubmit">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" v-model="form.name" required/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" v-model="form.email" disabled>
                    </div>
                    <div class="form-group">
                        <label>Team</label>
                        <select v-model="form.team_id" class="form-control">
                            <option v-for="(team, teamIndex) in teams" :value="teamIndex">{{team}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Roles</label>
                        <div v-for="(role, roleIndex) in roles" class="checkbox c-checkbox">
                            <label>
                                <input type="checkbox" :value="roleIndex" v-model="form.roles"/>
                                <span class="fa fa-check"></span>{{role|capitalize}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </ContentWrapper>
</template>

<script>
    import swal from 'sweetalert2'

    export default {
        name: "EditUser",
        data() {
            return {
                form: new Form({}),
                roles: '',
                teams: ''
            }
        },
        mounted() {
            let self = this
            axios.get('api/users/' + this.$route.params.slug)
                .then(function (res) {
                        let protectedColumns = ['created_at', 'updated_at', 'password', 'remember_token', 'email_verified_at']
                        for (let column in res.data.user) {
                            if (protectedColumns.includes(column)) {
                                delete res.data.user[column]
                            }
                        }
                        self.form = new Form(res.data.user)
                        self.roles = res.data.roles
                        self.teams = res.data.teams
                    }
                )
                .catch()
        },
        methods: {
            onSubmit() {
                let self = this
                self.form.patch('api/users/' + self.$route.params.slug)
                    .then(function (res) {
                        swal({
                            title: 'Success!',
                            type: 'success'
                        })
                    })
                    .catch(function (res) {
                        swal({
                            title: 'Error!',
                            tpye: 'error'
                        });
                    })
            }
        }
    }
</script>

<style scoped>

</style>
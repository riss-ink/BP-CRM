<template>
    <ContentWrapper>
        <div class="content-heading">
            <div style="width: 90%">
            <h2>Teams</h2>
            </div>
            <router-link tag="button" :to="{path: 'teams/create'}" :class="'btn mr-1 mb-1 btn-success'" style="color: #fff">
                New Team
            </router-link>
        </div>
    <div v-for="teams in groupedTeams" class="row">
        <div class="col-lg-4 col-sm-12" v-for="team in teams">
            <div class="card card-default">
                <div class="card-header">
                    <div class="float-right">
                    </div>
                    <div class="card-title">{{team.name}}</div>
                </div>
                <div class="card-body bt">
                    <a v-for="user in team.users" class="inline" href="#" v-b-tooltip.hover :title="user.name">
                        <img class="rounded-circle thumb48" :src="user.avatar" alt="project member" />
                    </a>
                </div>
                <div class="card-body bb">
                    <div class="row text-center">
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <p class="text-bold">CLIENTS</p>
                            <div class="h3 m-0">{{team.clients}}</div>
                        </div>
                        <div class="col-4">
                        </div>
                    </div>
                </div>
                    <div class="card-footer text-center">
                        <router-link tag="button" :to="{path: 'teams/' +team.id + '/edit'}" :class="'btn btn-outline-info'" >
                            Manage Team
                        </router-link>
                    </div>

            </div>
        </div>
    </div>
    </ContentWrapper>
</template>

<script>
    export default {
        name: "TeamIndex",
        data(){
            return{
                teams : []
            }
        },
        mounted() {
            let self = this
            axios.get('/api/teams')
                .then(function (res) {
                    self.teams = res.data
                })
        },
        computed:{
            groupedTeams(){
                return _.chunk(this.teams, 3)
            }
        }
    }
</script>

<style scoped>

</style>
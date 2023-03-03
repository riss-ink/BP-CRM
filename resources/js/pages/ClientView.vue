<template>
    <ContentWrapper>
        <div class="content-heading">
            <span style="width: 90%">
                {{client.name}}
            </span>
            <router-link tag="button" :to="{path: edit_url}" :class="'btn mr-1 mb-1 btn-success'" style="color: #fff">
                Edit
            </router-link>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <h5>GTM Access</h5>
                                <p v-if="client.google_tag_manager_access !== 0">Yes</p>
                                <p v-else>No</p>
                            </div>
                            <div class="col-4">
                                <h5>GA UA Code</h5>
                                <p v-if="client.ga_ua_code">{{client.ga_ua_code}}</p>
                                <p v-else>Not Found</p>
                            </div>
                            <div class="col-4">
                                <h5>GMB</h5>
                                <p v-if="client.gmb">{{client.gmb}}</p>
                                <p v-else>Not Found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card card-default">
                    <div class="card-header">
                        Contacts
                    </div>
                    <div class="card-body">
                        <b-tabs>
                            <b-tab v-for="(contact, contactIndex) in client.contacts"
                                   :title="'Contact ' + (contactIndex + 1)" :key="contactIndex">
                                <b-list-group>
                                    <b-list-group-item> Type: {{contact.contact_type | capitalize}}</b-list-group-item>
                                    <b-list-group-item>Preferred Method: {{contact.contact_method}}</b-list-group-item>
                                    <b-list-group-item> Name: {{contact.contact_name}}</b-list-group-item>
                                    <b-list-group-item> Email: {{contact.contact_email}}</b-list-group-item>
                                    <b-list-group-item> Phone 1: {{contact.contact_number}}</b-list-group-item>
                                    <b-list-group-item>Phone 2: {{contact.contact_number_2}}</b-list-group-item>
                                </b-list-group>
                            </b-tab>
                        </b-tabs>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card card-default border-primary">
                    <div class="card-header text-white bg-primary">
                        Products
                        <div class="card-tool float-right">
                            <router-link tag="em" :class="'fas fa-edit text-white'"
                                         :to="{path: '/clients/' + this.$route.params.slug + '/products'}"></router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                v-for="client_products in client.client_products">
                                {{client_products.product.product_category.name}} -
                                {{client_products.product.name}}
                                <router-link tag="em" class="fas fa-edit"
                                             :to="{path: '/client-products/' + client_products.id}"></router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-default border-success">
                    <div class="card-header text-white bg-success">
                        Logins
                    </div>
                    <div class="card-body text-center">
                        <div v-if="logins">
                            <b-tabs>
                                <b-tab v-for="(login, login_index) in client.logins" :key="login.id" :title="login.name"
                                       :active="login_index === 0">
                                    <div class="row bb  p-2">
                                        <div class="col-6">
                                            <h3 class="m-0">User Name</h3>
                                            <p class="m-0">{{login.username}}</p>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="m-0">Password</h3>
                                            <p class="m-0">{{login.password}}</p>
                                        </div>
                                    </div>
                                    <div class="row p-2">
                                        <div class="col-6">
                                            <h3 class="m-0">URL</h3>
                                            <p class="m-0">{{login.url}}</p>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="m-0">Notes</h3>
                                            <p class="m-0">{{login.notes}}</p>
                                        </div>
                                    </div>
                                </b-tab>
                            </b-tabs>
                        </div>
                        <div class="text-center" v-else>
                            <h2>No Logins Added For Client</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ContentWrapper>
</template>

<script>
    export default {
        name: "ClientView",
        data() {
            return {
                client: '',
                edit_url: '/clients/' + this.$route.params.slug + '/edit',
                logins: false
            }
        },
        created() {
            this.loadClient()
        },
        methods: {
            loadClient() {
                let self = this
                axios.get('api/clients/' + this.$route.params.slug)
                    .then(function (res) {
                        self.client = res.data
                        if (self.client.logins.length > 0) {
                            self.logins = true
                        }
                    })
                    .catch()
            }
        },
        watch: {
            '$route.params.slug': function () {
                this.loadClient();
            }
        }
    }
</script>

<style scoped>

</style>

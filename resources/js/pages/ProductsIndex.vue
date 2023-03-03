<template>
    <ContentWrapper>
        <v-client-table :data="products" :columns="columns" :options="options">
            <template slot="action" slot-scope="props">
                <div>
                    <router-link :class="'btn btn-xs btn-outline-info mr-2'" :to="{path: 'products/' + props.row.id + '/edit'}">edit</router-link>
                </div>
            </template>
            <template slot="details" slot-scope="props">
                <div v-html="props.row.details"></div>
            </template>
            <template slot="spend" slot-scope="props">
                <span v-if="props.row.spend">Yes</span>
                <span v-else>No</span>
            </template>
            <template slot="child_row" slot-scope="props">
                <div class="row" style="width:60%" v-for="price in props.row.prices">
                    <div class="col-3">
                        <h6><span v-if="price.term.length !== '0'"> Term Length:</span></h6>
                        <p>{{price.term.length}}</p>
                    </div>
                    <div class="col-3">
                        <h6>Frequency:</h6>
                        <p>{{price.term.type_name}}</p>
                    </div>
                    <div class="col-3">
                        <h6>Set Up Cost</h6>
                        <p>${{price.set_up_amount}}</p>
                    </div>
                    <div class="col-3">
                        <h6>Recurring Cost</h6>
                        <p v-if="price.quote">Quote</p>
                        <p v-else>${{price.recurring_amount}}</p>
                    </div>
                </div>
            </template>
        </v-client-table>
    </ContentWrapper>
</template>

<script>
    import Vue from 'vue';
    import {ClientTable} from 'vue-tables-2';

    Vue.use(ClientTable);

    export default {
        name: "ProductsIndex",
        components: {
            ClientTable
        },
        data() {
            return {
                products: [],
                options: {
                    sortIcon: {
                        is: 'fa-sort',
                        base: 'fas',
                        up: 'fa-sort-up',
                        down: 'fa-sort-down'
                    },
                    sortable: ['category', 'name', 'details', 'spend']
                },
                columns: ['category', 'name', 'details', 'spend', 'action']
            }
        },
        mounted() {
            this.getProducts()
        },
        methods: {
            getProducts() {
                let self = this
                axios.get('/api/products')
                    .then(function (res) {
                        self.products = res.data
                    })

            },

        }
    }
</script>

<style scoped>

</style>
<template>
    <ContentWrapper>
        <div class="content-heading">
            <div>
                {{client.name}}

                <span class="text-sm d-none d-sm-block" >Products</span>
            </div>
        </div>
        <div class="row">
            <div class="card card-default col-12">
                <div class="card-header">
                    Current Products
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li v-for="client_product in currentProducts" class="list-group-item">
                            {{client_product.product_category.name}} - {{client_product.name}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="card card-default col-12">
                    <div class="card-header">
                        Add Products
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-4">
                                <label>Category</label>
                                <select class="form-control" v-model="categoryIndex">
                                    <option v-for="(category, index) in allProducts" :value="index">{{category.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-4">
                                <div v-if="categoryIndex !== ''">
                                    <div class="form-group">
                                        <label>Prodcut</label>
                                        <select class="form-control" v-model="selectedProduct">
                                            <option v-for="product in allProducts[categoryIndex].products"
                                                    :value="product.id">{{product.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button v-if="selectedProduct !== ''" @click="attachProduct"
                                                class="btn btn-success">Add Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </ContentWrapper>
</template>

<script>
    import swal from 'sweetalert2'

    export default {
        name: "EditClientProducts",
        data() {
            return {
                currentProducts: [],
                allProducts: [],
                categoryIndex: '',
                selectedProduct: '',
                client: ''
            }
        },
        methods: {
            getClientProducts() {
                let self = this
                axios.get('api/clients/' + this.$route.params.slug + '/products')
                    .then(function (res) {
                        self.currentProducts = res.data.client_products
                        self.allProducts = res.data.products
                        self.client = res.data.client
                    })
                    .catch(function (res) {
                        swal({
                            title: 'Error!',
                            tpye: 'error'
                        });
                    })
            },
            attachProduct() {
                let self = this
                axios.put('api/clients/' + this.$route.params.slug + '/products', {id: this.selectedProduct})
                    .then(function (res) {
                        self.categoryIndex = ''
                        self.selectedProduct = ''
                        swal({
                            title: 'Success!',
                            text: 'Product Added',
                            type: 'success'
                        })
                        self.getClientProducts()
                    })
                    .catch(function (res) {
                        swal({
                            title: 'Error!',
                            tpye: 'error'
                        });
                    })
            }
        },
        mounted() {
            this.getClientProducts()
        }
    }
</script>

<style scoped>

</style>
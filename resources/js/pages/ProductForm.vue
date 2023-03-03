<template>
    <ContentWrapper>
        <div class="content-heading">
            <h2 v-if="this.$route.params.slug">Edit Product</h2>
            <h2 v-else>New Product</h2>

        </div>
        <div class="card card-default">
            <form @submit.prevent="onSubmit">

                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" v-model="form.name">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" v-model="form.category">
                            <option v-for="(category, catI) in categories" :value="catI">{{category | capitalize}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Spend</label>
                        <select class="form-control" v-model="form.spend">
                            <option value="false">No</option>
                            <option value="true">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Details</label>
                        <quill-editor v-model="form.details" :options="quillEditorOptions"/>

                    </div>
                    <hr/>

                </div>
                <div class="card-body">
                    <div class="row" v-for="(price, i) in form.prices">
                        <div class="col-2 form-group">
                            <label>Term Length</label>
                            <masked-input :mask="lengthMask" v-model="form.prices[i].term_length"
                                          class="form-control"></masked-input>                        </div>
                        <div class="col-2 form-group">
                            <label>Term Frequency</label>
                            <select v-model="form.prices[i].term_type" class="form-control">
                                <option value="0">One Time</option>
                                <option value="1">Monthly</option>
                                <option value="2">Hourly</option>
                                <option value="3">Indefinite</option>
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <label>Quote</label>
                            <select class="form-control" v-model="form.prices[i].quote">
                                <option :value="false">No</option>
                                <option :value="true">Yes</option>
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <label>Set Up Cost</label>
                            <b-input-group>
                                <b-input-group-append>
                                    <div class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </b-input-group-append>
                                <b-form-input type="number" v-model="form.prices[i].set_up_amount"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="col-3 form-group">
                            <label>Recurring Cost</label>
                            <b-input-group>
                                <b-input-group-append>
                                    <div class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </b-input-group-append>
                                <b-form-input type="number" v-model="form.prices[i].recurring_amount"></b-form-input>
                                <b-input-group-prepend>
                                    <b-button @click="addPrice" variant="outline-success"><i class="fas fa-plus"></i>
                                    </b-button>
                                    <b-button v-if="i > 0" @click="removePrice(i)" variant="outline-danger"><i
                                            class="fas fa-trash"></i></b-button>

                                </b-input-group-prepend>
                            </b-input-group>
                        </div>
                    </div>
                    <b-button variant="success" type="submit">Submit</b-button>
                </div>
            </form>

        </div>
    </ContentWrapper>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import {quillEditor} from 'vue-quill-editor'
    import swal from 'sweetalert2'
    import MaskedInput from 'vue-text-mask'

    export default {
        name: "EditProduct",
        components: {quillEditor, MaskedInput},
        data() {
            return {
                lengthMask : [/\d/, /\d/],
                categories: {},
                form: new Form({
                    name: '',
                    details: '',
                    category: '',
                    spend: false,
                    prices: [
                        {
                            term_length: "0",
                            term_type: 1,
                            set_up_amount: 0,
                            quote: false,
                            recurring_amount: 0
                        }
                    ]
                }),
                quillEditorOptions: {
                    modules: {
                        toolbar: [
                            [{'header': [1, 2, 3, 4, 5, 6, false]}, {'font': []}, {'size': []}],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{'color': []}, {'background': []}],
                            [{'script': 'sub'}, {'script': 'super'}],
                            ['blockquote', 'code-block'],
                            [{'list': 'ordered'}, {'list': 'bullet'}, {'indent': '-1'}, {'indent': '+1'}],
                            [{'direction': 'rtl'}, {'align': []}],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                },
            }
        },
        mounted() {
            this.getCategories()
            if(this.$route.params.slug){
                this.getProduct()
            }
        },
        methods: {
            getProduct(){
              let self = this
              axios.get('api/products/' + this.$route.params.slug)
                  .then(function (res) {
                      self.form = new Form(res.data)
                  })
            },
            getCategories() {
                let self = this
                axios.get('/api/product-categories')
                    .then(function (res) {
                        let obj = {}
                        for (let k in res.data) {
                            obj[res.data[k].id] = res.data[k].name
                        }
                        self.categories = obj
                    })
            },
            addPrice() {
                this.form.prices.push({
                    term_length: 0,
                    term_type: 1,
                    set_up_amount: 0,
                    recurring_amount: 0
                })
            },
            removePrice(i) {
                this.form.prices.splice(i, 1)
            },
            onSubmit() {
                let self = this
                if (this.$route.params.slug) {
                    this.form.patch('api/products/' + this.$route.params.slug)
                        .then(function (res) {
                            self.onSuccess()
                        })
                        .catch(function (error) {
                            self.onFailure()
                        })
                } else {
                    this.form.post('api/products')
                        .then(function (res) {
                            self.onSuccess()
                        })
                        .catch(function (error) {
                            self.onFailure()
                        })
                }
            },
            onSuccess() {
                let self = this
                swal({
                    title: 'Success!',
                    text: "Client has been added",
                    type: 'success',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        self.$router.push('/products')
                    }
                })
            },
            onFailure() {
                swal({
                    title: 'Error!',
                    type: 'error'
                });
            }
        }

    }
</script>

<style scoped>

</style>
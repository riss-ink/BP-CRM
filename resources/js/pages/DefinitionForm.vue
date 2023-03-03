<template>
    <ContentWrapper>
        <div class="card card-default">
            <div class="card-header">
                New Custom Field Definition
            </div>
            <div class="card-body">
                <form @submit.prevent="onSubmit">
                    <b-form-group label="Name">
                        <b-form-input v-model="form.name"></b-form-input>
                    </b-form-group>
                    <b-form-group label="Type">
                        <b-select v-model="form.field_type" :options="type_select"></b-select>
                    </b-form-group>
                    <b-form-group label="Field Group">
                        <multiselect v-model="form.field_group_id" :options="field_groups" label="name"
                                     track-by="name"/>
                        <b-button variant="success" v-b-modal.modal1>Add New Field Group</b-button>

                    </b-form-group>
                    <b-form-group label="Service">
                        <b-form-checkbox-group :options="product_categories"
                                               v-model="form.product_categories"></b-form-checkbox-group>
                    </b-form-group>
                    <b-button type="submit" variant="primary">Submit</b-button>
                </form>
            </div>
        </div>
        <b-modal hide-footer id="modal1" ref="modal" title="Add Field Group">
            <form @submit.prevent="addFieldGroup()">
                <div class="form-group">
                    <input v-model="field_group_form.name" class="form-control" placeholder="Name"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </b-modal>
    </ContentWrapper>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import swal from 'sweetalert2'

    export default {
        name: "DefinitionForm",
        components: {Multiselect},
        data() {
            return {
                form: new Form({
                    name: '',
                    field_group_id: '',
                    field_type: '',
                    product_categories: []
                }),
                type_select: [
                    {value: 'string', text: 'Short Text'},
                    {value: 'text', text: 'Long Text'},
                    {value: 'currency', text: 'Money'},
                    {value: 'int', text: 'Number'},
                    {value: 'json', text: 'Repeater'}
                ],
                field_group_form: new Form({
                    name: ''
                }),
                field_groups: [],
                product_categories: []
            }
        },
        created() {
            let self = this
            if (self.$route.params.slug) {
                axios.get('api/definitions/' + self.$route.params.slug + "/edit")
                    .then(function (res) {
                        self.form = new Form(res.data)
                    })
                    .catch()
            }
            this.getFieldGroups();
            axios.get('/api/product-categories')
                .then(function (res) {
                    let cats = []
                    for (let i in res.data) {
                        cats.push({text: res.data[i].name, value: res.data[i].id})
                    }
                    self.product_categories = cats
                })
                .catch()
        },
        methods: {
            onSubmit() {
                let self = this
                if (this.$route.params.slug) {
                    self.form.patch('api/definitions/' + self.$route.params.slug)
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
                    self.form.post('api/definitions')
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
            },
            addFieldGroup() {
                let self = this
                this.field_group_form.post('/api/field-groups')
                    .then(function () {
                        self.getFieldGroups()
                        self.$refs.modal.hide()
                    })
            },
            getFieldGroups() {
                let self = this
                axios.get('/api/field-groups')
                    .then(function (res) {
                        let groups = []
                        for (let i in res.data) {
                            groups.push({name: res.data[i].name, id: res.data[i].id})
                        }
                        self.field_groups = groups
                    })
                    .catch()
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

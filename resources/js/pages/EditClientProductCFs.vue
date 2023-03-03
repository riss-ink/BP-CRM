<template>
    <ContentWrapper>
        <div class="content-heading">
            <div>
            {{category}}

            <span class="text-sm d-none d-sm-block" >{{client}}</span>
            </div>
        </div>
        <form @submit.prevent="onSubmit">
            <div v-for="(fields, field_group) in field_groups">
                <div class="card card-default">
                    <div v-if="field_group !== 'default'" class="card-header">{{field_group}}</div>
                    <div class="card-body">
                        <div class="align-items-center">
                            <div v-for="(field, fieldIndex) in fields" class="form-group">
                                <label class="">{{field.label | capitalize}}</label>
                                <input v-model="field.value" class="form-control" type="text" v-if="field.type === 'string'"/>
                                <input v-model="field.value" class="form-control" type="number" v-if="field.type === 'int'"/>
                                <textarea v-model="field.value" class="form-control" v-if="field.type === 'text'"></textarea>
                                <datepicker
                                        v-if="field.type === 'date'"
                                        v-model="field.value"
                                        :bootstrapStyling="true"
                                        :monday-first="true"
                                        :full-month-name="true"
                                        placeholder="Select date"
                                        :calendar-button="true"
                                        calendar-button-icon="far fa-calendar"
                                        :clear-button="true"
                                />
                                <select v-model="field.value" class="form-control" v-if="field.type === 'bool'">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <div v-if="field.type === 'json'" class="" v-for="(repeater_field, repeater_index) in field.value">
                                    <b-input-group>
                                        <b-form-input v-model="field.value[repeater_index]"></b-form-input>
                                        <b-input-group-append>
                                            <b-btn variant="success" @click="field.value.push('')"><i class="fas fa-plus" style="color:white"></i>
                                            </b-btn>
                                            <b-btn v-if="repeater_index > 0" variant="danger" @click="field.value.splice(repeater_index, 1)">
                                                <i class="fas fa-trash" style="color:white"></i></b-btn>
                                        </b-input-group-append>
                                    </b-input-group>
                                </div>
                                <b-input-group v-if="field.type === 'currency'" prepend="$">
                                    <b-form-input :type="'number'" v-model="field.value"></b-form-input>
                                </b-input-group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mb-2" type="submit">Submit</button>

        </form>
    </ContentWrapper>
</template>

<script>
    import swal from 'sweetalert2'
    import moment from 'moment'
    import Datepicker from 'vuejs-datepicker'
    export default {
        name: "EditClientProduct",
        components: {Datepicker},
        data() {
            return {
                field_groups: '',
                client: '',
                category: ''
            }
        },
        created() {
            let self = this
            axios.get('api/client-products/' + this.$route.params.slug)
                .then(function (res) {
                    for (let i in res.data.fields) {
                        for (let key in res.data.fields[i]) {
                            if (res.data.fields[i][key].type === 'json' && !res.data.fields[i][key].value) {
                                res.data.fields[i][key].value = [''];
                            }
                            if (res.data.fields[i][key].type === 'date' && !res.data.fields[i][key].value) {
                                res.data.fields[i][key].value = moment().format('MMMM D YYYY')
                            }
                            }
                    }
                    self.field_groups = res.data.fields
                    self.client = res.data.client
                    self.category = res.data.category
                })
        },
        methods: {
            onSubmit: function () {
                let self = this
                axios.patch('api/client-products/' + self.$route.params.slug, self.field_groups)
                    .then(function () {
                        swal({
                            title: 'Success!',
                            type: 'success'
                        })
                    })
                    .catch()
            },

        }
    }
</script>

<style scoped>

</style>

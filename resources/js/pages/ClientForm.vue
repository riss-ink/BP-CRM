<template>
    <ContentWrapper>
        <div class="content-heading">
            <div>Client Form
            </div>
        </div>
        <div class="card card-default">
            <div class="card-body">
                <form-wizard @on-complete="onComplete" ref="form">
                    <template slot="step" slot-scope="props">
                        <!-- By using a custom header markup we can pass html in title attribute -->
                        <wizard-step
                                :tab="props.tab"
                                @click.native="props.navigateToTab(props.index)"
                                @keyup.enter.native="props.navigateToTab(props.index)"
                                :transition="props.transition"
                                :index="props.index">
                            <span slot="title" :class="{'text-danger':props.tab.validationError}"
                                  v-html="props.tab.title"></span>
                        </wizard-step>
                    </template>
                    <!-- Headers -->
                    <tab-content title="<div>Client Information</div>">
                        <b-card class="mb-3 border">

                            <div class="form-group  row">
                                <label for="name" class="">Business Name</label>
                                <input type="text" name="name" id="name" v-model="form.name"
                                       class="form-control m-input m-input--air">
                                <span v-show="form.errors.has('name')"
                                      class="form-control-feedback m--font-danger">Business Name is required.</span>
                            </div>
                            <div class="form-group  row">
                                <label for="url" class="col-form-label">Client Website Url</label>
                                <input type="text" name="url" id="url" v-model="form.current_url"
                                       class="form-control m-input m-input--air">
                            </div>

                            <div class="form-group  row">
                                <label for="email">Client Email
                                    Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>
                                    </div>
                                    <input type="email" name="email" id="email" v-model="form.email"
                                           class="form-control m-input m-input--air">
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address"
                                       v-model="form.address"
                                       class="form-control m-input m-input--air">
                            </div>
                            <div class="form-group  row">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" v-model="form.city"
                                       class="form-control m-input m-input--air">
                            </div>
                            <div class="form-group  row">
                                <label>State</label>
                                <multiselect
                                        v-model="form.state"
                                        :options="state_select"
                                        :searchable="true"
                                        :show-labels="false"
                                        placeholder="Pick a State"/>
                            </div>
                            <div class="form-group  row">
                                <label>Zip</label>
                                <masked-input v-model="form.zip" :mask="zipMask"
                                              class="form-control"></masked-input>
                            </div>
                            <div class="form-group  row">
                                <label>Phone</label>
                                <masked-input type="text" class="form-control" placeholder="Phone.."
                                              v-model="form.phone"
                                              :mask="phoneMask"/>
                            </div>
                            <div class="card">
                                <div class="card-header">Business Hours</div>
                                <div class="card-body">

                                    <div class="form-group row"
                                         v-for="(business_hours, bh_index) in form.business_hours">
                                        <div class="col-4">
                                            <b-form-group label="Days">
                                                <b-form-checkbox-group
                                                        name="days"
                                                        v-model="form.business_hours[bh_index].days"
                                                        :options="days"
                                                />
                                            </b-form-group>
                                        </div>
                                        <div class="col-4">
                                            <label>
                                                Open
                                            </label>
                                            <masked-input v-model="form.business_hours[bh_index].open"
                                                          placeholder="HH:MM am/pm" class="form-control"
                                                          :mask="timeMask"></masked-input>
                                        </div>
                                        <div class="col-4">
                                            <label>
                                                Close
                                            </label>
                                            <b-input-group>
                                                <masked-input v-model="form.business_hours[bh_index].close"
                                                              placeholder="HH:MM am/pm"
                                                              class="form-control" :mask="timeMask"></masked-input>
                                                <b-input-group-append>
                                                    <b-btn variant="success"
                                                           @click="repeater_add('business_hours', {days: [], open:'', close:''})">
                                                        <i
                                                                class="fas fa-plus" style="color:white"></i>
                                                    </b-btn>
                                                    <b-btn v-if="bh_index > 0" variant="danger"
                                                           @click="repeater_remove('business_hours', bh_index)">
                                                        <i class="fas fa-trash" style="color:white"></i></b-btn>
                                                </b-input-group-append>
                                            </b-input-group>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card>
                        <div v-if="this.custom_fields.length > 0" class="card card-default">
                            <div class="card-header">
                                Custom Fields
                            </div>
                            <div class="card-body">
                                <div v-for="(fields, field_group) in custom_fields">
                                    <div class="card card-default">
                                        <div v-if="field_group !== 'default'" class="card-header">{{field_group}}</div>
                                        <div class="card-body">
                                            <div class="form-row align-items-center">
                                                <div v-for="(field, fieldIndex) in fields" class="col">
                                                    <label class="">{{field.label}}</label>
                                                    <input v-model="field.value" class="form-control" type="text"
                                                           v-if="field.type === 'string'"/>
                                                    <input v-model="field.value" class="form-control" type="number"
                                                           v-if="field.type === 'int'"/>
                                                    <textarea v-model="field.value" class="form-control"
                                                              v-if="field.type === 'text'"></textarea>
                                                    <div v-if="field.type === 'json'" class=""
                                                         v-for="(repeater_field, repeater_index) in field.value">
                                                        <b-input-group>
                                                            <b-form-input
                                                                    v-model="field.value[repeater_index]"></b-form-input>
                                                            <b-input-group-append>
                                                                <b-btn variant="success" @click="field.value.push('')">
                                                                    <i class="fas fa-plus"
                                                                       style="color:white"></i>
                                                                </b-btn>
                                                                <b-btn v-if="repeater_index > 0" variant="danger"
                                                                       @click="field.value.splice(repeater_index, 1)">
                                                                    <i class="fas fa-trash" style="color:white"></i>
                                                                </b-btn>
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </div>
                                                    <b-input-group v-if="field.type === 'currency'" prepend="$">
                                                        <b-form-input :type="'number'"
                                                                      v-model="field.value"></b-form-input>
                                                    </b-input-group>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </tab-content>
                    <tab-content title="<div>Contact Info</div>">
                        <div class="card card-default" v-for="(contact, contactIndex) in form.contacts">
                            <div class="card-header bg-dark text-white">
                                <span> Contact</span>>
                                <div class="card-tool float-right">
                                    <em class="fas fa-plus text-success" @click="repeater_add('contacts',{
                        contact_number: '',
                        contact_number_2: '',
                        contact_name: '',
                        contact_email: '',
                        custom_fields: '',
                        contact_method: '',
                        contact_type: ''
                    })"></em>
                                    <em v-if="contactIndex > 0" class="fas fa-trash text-danger"
                                        @click="repeater_remove('contacts', contactIndex)"></em>
                                </div>
                            </div>
                            <div class="card-body">
                                <b-form-group label="Type">
                                    <select class="form-control" v-model="contact.contact_type">
                                        <option value="primary">Primary</option>
                                        <option value="secondary">Secondary</option>

                                    </select>
                                </b-form-group>
                                <div class="form-group">
                                    <label for="contact_name">Contact Name</label>
                                    <input type="text" name="contact_name" id="contact_name"
                                           v-model="contact.contact_name"
                                           class="form-control">

                                </div>
                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" name="contact_email" id="contact_email"
                                           v-model="contact.contact_email"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <masked-input :mask="phoneMask" v-model="contact.contact_number"
                                                  class="form-control"></masked-input>


                                </div>
                                <div class="form-group">
                                    <label>Contact Number 2</label>
                                    <masked-input :mask="phoneMask" v-model="contact.contact_number_2"
                                                  class="form-control"></masked-input>
                                </div>
                                <div class="form-group">
                                    <label>Preferred Contact Method</label>
                                    <input type="text" name="contact_method" v-model="contact.contact_method"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </tab-content>
                    <tab-content title="<div>Basic Marketing</div>">
                        <b-card class="mb-3 border">
                            <div class="form-group">
                                <label>GA UA Code</label>
                                <input type="text" v-model="form.ga_ua_code"
                                       class="form-control m-input m-input--air">
                            </div>
                            <div class="form-group">
                                <label>Objectives</label>
                                <input type="text" v-model="form.objectives"
                                       class="form-control m-input m-input--air">
                            </div>
                            <div class="form-group">
                                <label>What Sets The Client Apart?</label>
                                <input type="text" v-model="form.difference"
                                       class="form-control m-input m-input--air">
                            </div>

                            <div class="form-group">
                                <label>Preliminary Keywords</label>
                                <div class="" v-for="(keyword, keywordIndex) in form.keywords" :key="keywordIndex">
                                    <b-input-group>
                                        <b-form-input v-model="form.keywords[keywordIndex]"></b-form-input>
                                        <b-input-group-append>
                                            <b-btn @click="repeater_add('keywords', '')" variant="success"><i
                                                    class="fas fa-plus" style="color:white"></i>
                                            </b-btn>
                                            <b-btn v-if="keywordIndex > 0" variant="danger"
                                                   @click="repeater_remove('keywords', keywordIndex)"><i
                                                    class="fas fa-trash" style="color:white"></i></b-btn>
                                        </b-input-group-append>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Competitors</label>
                                <div class="row" v-for="(competitor, competitorIndex) in form.competitors">
                                    <div class="col-6">
                                        <input class="form-control" placeholder="Name"
                                               v-model="form.competitors[competitorIndex].name">
                                    </div>
                                    <div class="col-6">
                                        <b-input-group>
                                            <b-form-input placeholder="URL"
                                                          v-model="form.competitors[competitorIndex].url"></b-form-input>
                                            <b-input-group-append>
                                                <b-btn variant="success"
                                                       @click="repeater_add('competitors', {name: '', url: ''})"><i
                                                        class="fas fa-plus"
                                                        style="color:white"></i>
                                                </b-btn>
                                                <b-btn v-if="competitorIndex > 0" variant="danger"
                                                       @click="repeater_remove('competitors', competitorIndex)"><i
                                                        class="fas fa-trash" style="color:white"></i></b-btn>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Geo Targeting</label>
                                <div class="" v-for="(geo_target, geo_targetingIndex) in form.geo_targeting">
                                    <b-input-group>
                                        <b-form-input v-model="form.geo_targeting[geo_targetingIndex]"></b-form-input>
                                        <b-input-group-append>
                                            <b-btn variant="success" @click="repeater_add('geo_targeting', '')"><i
                                                    class="fas fa-plus" style="color:white"></i>
                                            </b-btn>
                                            <b-btn v-if="geo_targetingIndex > 0" variant="danger"
                                                   @click="repeater_remove('geo_targeting', geo_targetingIndex)">
                                                <i class="fas fa-trash" style="color:white"></i></b-btn>
                                        </b-input-group-append>
                                    </b-input-group>
                                </div>
                            </div>
                        </b-card>
                    </tab-content>
                    <tab-content title="<div>Additional Data</div>">
                        <div class="card card-default">
                            <div class="card-body">
                            <div class="form-group">
                                <label>Team</label>
                                <select class="form-control" v-model="form.team_id">
                                    <option v-for="(team, i) in teams" :value="team.value">{{team.text}}</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        <b-card class="mb-3 border primary">
                            <div class="card">
                                <div v-for="(login, login_index) in form.logins">
                                    <div class="card-header bg-dark text-white">Login {{login_index + 1}}
                                        <div class="card-tool float-right">
                                            <em class="fas fa-plus text-success" @click="repeater_add('logins',{type: '',
                            name: '',
                            url: '',
                            username: '',
                            password: '',
                            notes: ''})"></em>
                                            <em v-if="login_index > 0" class="fas fa-trash text-danger"
                                                @click="repeater_remove('logins', login_index)"></em>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-4">
                                                <label>Type</label>
                                                <select class="form-control" v-model="form.logins[login_index].type">
                                                    <option value="marketing">Marketing</option>
                                                    <option value="development">Development</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label>Name</label>
                                                <b-form-input v-model="form.logins[login_index].name"/>
                                            </div>
                                            <div class="form-group col-4">
                                                <label>Url</label>
                                                <b-form-input v-model="form.logins[login_index].url"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label>Username</label>
                                                <b-form-input v-model="form.logins[login_index].username"/>
                                            </div>
                                            <div class="form-group col-6">
                                                <label>Password</label>
                                                <b-form-input v-model="form.logins[login_index].password"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                <label>Login Notes</label>
                                                <textarea v-model="form.logins[login_index].notes"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>General Client Notes</label>
                                <textarea v-model="form.notes" class="form-control"></textarea>
                            </div>
                        </b-card>
                    </tab-content>
                    <!-- Directions -->
                    <b-btn variant="secondary" slot="prev">Back</b-btn>
                    <b-btn variant="secondary" slot="next">Next</b-btn>
                    <b-btn variant="info" slot="finish">Finish</b-btn>
                </form-wizard>
            </div>
        </div>

    </ContentWrapper>
</template>

<script>
    import {FormWizard, TabContent, WizardStep} from 'vue-form-wizard'
    import MaskedInput from 'vue-text-mask'
    import Multiselect from 'vue-multiselect'
    import swal from 'sweetalert2'

    export default {
        name: "ClientForm",
        data() {
            return {
                days: [
                    {text: "Monday", value: 1},
                    {text: "Tuesday", value: 2},
                    {text: "Wednesday", value: 3},
                    {text: "Thursday", value: 4},
                    {text: "Friday", value: 5},
                    {text: "Saturday", value: 6},
                    {text: "Sunday", value: 7}

                ],
                form: new Form({
                    name: '',
                    team_id: '',
                    current_url: '',
                    objectives: '',
                    email: '',
                    difference: '',
                    team_id: 0,
                    keywords: [
                        ''
                    ],
                    address: '',
                    city: '',
                    state: '',
                    zip: '',
                    phone: '',
                    business_hours: [
                        {
                            days: [],
                            open: '',
                            close: ''
                        }
                    ],
                    geo_targeting: [
                        ''
                    ],
                    competitors: [{
                        name: '',
                        url: '',
                    }],
                    logins: [
                        {
                            type: '',
                            name: '',
                            url: '',
                            username: '',
                            password: '',
                            notes: ''
                        }
                    ],
                    ga_ua_code: '',
                    notes: '',
                    contacts: [{
                        contact_number: '',
                        contact_number_2: '',
                        contact_name: '',
                        contact_email: '',
                        custom_fields: '',
                        contact_method: '',
                        contact_type: '',
                    }]
                }),
                config: {
                    format: 'LT',
                    icons: {
                        up: 'fa fa-angle-up',
                        down: 'fa fa-angle-down'
                    }
                },
                phoneMask: ['(', /[1-9]/, /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/],
                zipMask: [/\d/, /\d/, /\d/, /\d/, /\d/],
                custom_fields: {},
                timeMask: [/\d/, /\d/, ':', /\d/, /\d/, ' ', /[a-zA-Z]/, /[a-zA-Z]/],
                teams: [],
                state_select: ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY']
            }


        },
        components: {
            FormWizard,
            TabContent,
            WizardStep,
            MaskedInput,
            Multiselect
        },
        mounted() {
            let self = this
            if (this.$route.params.slug) {
                this.getClientData()
            } else {
                this.getCustomFields()
            }
            this.$refs.form.activateAll();
            axios.get('api/teams')
                .then(function (res) {
                    axios.get('api/teams')
                        .then(function (res) {
                            let teams = []
                            for (let i in res.data) {
                                teams.push({text: res.data[i].name, value: res.data[i].id})
                            }
                            self.teams = teams
                        })
                })
        },
        methods: {
            getCustomFields: function () {
                let self = this
                axios.get('api/clients/create')
                    .then(function (res) {
                        self.custom_fields = res.data
                    })
                    .catch()
            },
            onComplete: function () {
                let cfs = []
                let self = this
                for (let field_group in this.custom_fields) {
                    for (let index in this.custom_fields[field_group]) {
                        console.log(this.custom_fields[field_group][index])
                        cfs.push(this.custom_fields[field_group][index])
                    }
                }
                this.form.custom_fields = cfs
                if (this.$route.params.slug) {
                    this.form.patch('api/clients/' + this.$route.params.slug)
                        .then(function (res) {
                            swal({
                                title: 'Success!',
                                type: 'success'
                            })
                        })
                        .catch(function (res) {
                            let errors = ""
                            for (let i in res.errors.name){
                                errors = errors + "<p>" + res.errors.name[i] + "</p>"
                            }
                            swal({
                                title: 'Error!',
                                type: 'error',
                                html: errors
                            });
                        })
                } else {
                    this.form.post('api/clients')
                        .then(function (res) {
                                swal({
                                    title: 'Success!',
                                    text: "Client has been added",
                                    type: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Add Products'
                                }).then((result) => {
                                    if (result.value) {
                                        self.$router.push('/clients/' + res + '/products')
                                    }
                                })
                            }
                        )
                        .catch(function (res) {
                            swal({
                                title: 'Error!',
                                type: 'error'
                            });
                        })
                }
            },
            repeater_add(field, data) {
                this.form[field].push(data);
            },
            repeater_remove(field, index) {
                this.form[field].splice(index, 1);
            },
            getClientData() {
                let self = this
                axios.get('/api/clients/' + this.$route.params.slug + '/edit')
                    .then(function (res) {
                        for (let field in res.data) {
                            if (field in self.form && field != 'status') {
                                if (field == 'custom_fields') {
                                    for (let field_group in res.data[field]) {
                                        self.custom_fields = res.data[field]
                                    }
                                } else {
                                    self.form[field] = res.data[field]
                                }
                            }
                        }
                        if (!self.form.logins[0]) {
                            self.form.logins[0] = {
                                type: '',
                                name: '',
                                url: '',
                                username: '',
                                password: '',
                                notes: ''
                            }
                        }
                        if (!self.form.business_hours[0]) {
                            self.form.business_hours[0] = {
                                days: [],
                                open: '',
                                close: ''
                            }
                        }
                    })
                    .catch(function (res) {
                        swal({
                            title: 'Error!',
                            type: 'error'
                        })
                    })
            }
        }
    }
</script>

<style src="vue-form-wizard/dist/vue-form-wizard.min.css"></style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


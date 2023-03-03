<template>
    <!-- START Search form-->
    <div>
        <div class="navbar-form" role="search">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Search..." v-model.lazy="search" v-debounce="300">
                <div class="fas fa-times navbar-form-close" data-search-dismiss=""></div>
            </div>
            <button class="d-none" type="submit">Submit</button>
        </div>
        <div>
            <ul class="results" v-if="results !== ''">
                <li v-for="result in results">
                    <router-link v-if="result.id" :to="{path: '/clients/' + result.id}">{{result.name}}</router-link>
                    <span v-else>{{result}}</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- END Search form-->
</template>

<script>
    import $ from '../Common/wrapper.js';
    import debounce from 'v-debounce';

    export default {
        name: 'HeaderSearch',
        data() {
            return {
                search: '',
                results: ''
            }
        },
        mounted() {
            // NAVBAR SEARCH
            // -----------------------------------
            var navSearch = new navbarSearchInput();
            let self = this
            // Open search input
            var $searchOpen = $('[data-search-open]');

            $searchOpen
                .on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                })
                .on('click', navSearch.toggle);

            // Close search input
            var $searchDismiss = $('[data-search-dismiss]');
            var inputSelector = '.navbar-form input[type="text"]';

            $(inputSelector)
                .on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                })

            $(document).on('click',navSearch.dismiss).on('click', function () {
                self.results = ''
            });

            // dismissable optionsas x223x1 a
            $searchDismiss
                .on('click', function (e) {
                    e.stopPropagation();
                    self.results = ''

                })
                .on('click', navSearch.dismiss);

            function navbarSearchInput() {
                var navbarFormSelector = 'div.navbar-form';
                return {
                    toggle: function () {

                        var navbarForm = $(navbarFormSelector);

                        navbarForm.toggleClass('open');

                        var isOpen = navbarForm.hasClass('open');

                        navbarForm.find('input')[isOpen ? 'focus' : 'blur']();

                    },

                    dismiss: function () {
                        $(navbarFormSelector)
                            .removeClass('open') // Close control
                            .find('input[type="text"]').blur() // remove focus
                        // .val('') // Empty input
                        ;
                    }
                };

            }
        },
        watch: {
            search(after, before) {
                this.fetch();
            }
        },
        methods: {
            fetch() {
                axios.get('/api/search', {params: {q: this.search}})
                    .then(response => this.results = response.data)
                    .catch(error => {
                    })
            }
        },
        directives: {
            debounce
        }
    }

</script>
<style>
     .results {
        position: absolute;
        top: 82px;
        left: 220px;
        right: 0;
        z-index: 10;
        padding: 0;
        margin: 0;
        border-width: 1px;
        border-style: solid;
        border-color: #cbcfe2 #c8cee7 #c4c7d7;
        border-radius: 3px;
        background-color: #fdfdfd;
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fdfdfd), color-stop(100%, #eceef4));
        background-image: -webkit-linear-gradient(top, #fdfdfd, #eceef4);
        background-image: -moz-linear-gradient(top, #fdfdfd, #eceef4);
        background-image: -ms-linear-gradient(top, #fdfdfd, #eceef4);
        background-image: -o-linear-gradient(top, #fdfdfd, #eceef4);
        background-image: linear-gradient(top, #fdfdfd, #eceef4);
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        -ms-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        -o-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .results li { display: block }

    .results li:first-child { margin-top: -1px }

   .results li:first-child:before, .search .results li:first-child:after {
        display: block;
        content: '';
        width: 0;
        height: 0;
        position: absolute;
        left: 50%;
        margin-left: -5px;
        border: 5px outset transparent;
    }

     .results li:first-child:before {
        border-bottom: 5px solid #c4c7d7;
        top: -11px;
    }

     .results li:first-child:after {
        border-bottom: 5px solid #fdfdfd;
        top: -10px;
    }

   .results li:first-child:hover:before, .search .results li:first-child:hover:after { display: none }

    .results li:last-child { margin-bottom: -1px }

    .results a {
        display: block;
        position: relative;
        margin: 0 -1px;
        padding: 6px 40px 6px 10px;
        color: #808394;
        font-weight: 500;
        text-shadow: 0 1px #fff;
        border: 1px solid transparent;
        border-radius: 3px;
    }

     .results a span { font-weight: 200 }

     .results a:before {
        content: '';
        width: 18px;
        height: 18px;
        position: absolute;
        top: 50%;
        right: 10px;
        margin-top: -9px;
    }

    .results a:hover {
        text-decoration: none;
        color: #fff;
        text-shadow: 0 -1px rgba(0, 0, 0, 0.3);
        border-color: #2380dd #2179d5 #1a60aa;
        background-color: #338cdf;
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #59aaf4), color-stop(100%, #338cdf));
        background-image: -webkit-linear-gradient(top, #59aaf4, #338cdf);
        background-image: -moz-linear-gradient(top, #59aaf4, #338cdf);
        background-image: -ms-linear-gradient(top, #59aaf4, #338cdf);
        background-image: -o-linear-gradient(top, #59aaf4, #338cdf);
        background-image: linear-gradient(top, #59aaf4, #338cdf);
        -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        -moz-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        -ms-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        -o-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
        box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px rgba(0, 0, 0, 0.08);
    }

    :-moz-placeholder {
        color: #a7aabc;
        font-weight: 200;
    }

    ::-webkit-input-placeholder {
        color: #a7aabc;
        font-weight: 200;
    }

</style>
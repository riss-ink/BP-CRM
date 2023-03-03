<template>
    <div class="wrapper">

        <!-- top navbar-->
        <Header />

        <!-- sidebar-->
        <SideNav />


        <!-- Main section-->
        <section class="section-container">
            <!-- Page content-->
            <router-view/>
        </section>

        <!-- Page footer-->
        <Footer />

    </div>
</template>

<script>
    import Vue from 'vue';
    import Header from './Header'
    import Sidebar from './Sidebar'
    import Footer from './Footer'
    import SideNav from './SideNav'
    import ContentWrapper from './ContentWrapper'

    Vue.component('ContentWrapper', ContentWrapper)

    export default {
        name: 'Layout',
        components: {
            Header,
            SideNav,
            Sidebar,
            Footer
        }
    }
</script>
<style lang="scss">
</style>

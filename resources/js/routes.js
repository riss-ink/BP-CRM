/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue'
import VueRouter from 'vue-router'
import Layout from './components/Layout/Layout'
import store from './store'

import Home from './pages/Home'
import ClientForm from './pages/ClientForm'
import ClientTable from "./pages/ClientTable";
import EditClientProductCFs from "./pages/EditClientProductCFs";
import DefinitionForm from "./pages/DefinitionForm";
import ClientView from "./pages/ClientView";
import EditClientProducts from "./pages/EditClientProducts";
import UsersIndex from "./pages/UsersIndex";
import EditUser from "./pages/EditUser";
import RolesIndex from "./pages/RolesIndex";
import PermissionsIndex from "./pages/PermissionsIndex";
import DefinitionIndex from "./pages/DefinitionIndex";
import ProductsIndex from "./pages/ProductsIndex";
import ProductForm from "./pages/ProductForm";
import TeamIndex from "./pages/TeamIndex";
import TeamForm from "./pages/TeamForm";
/*
    Extends Vue to use Vue Router
*/
Vue.use(VueRouter)

function requireAuth(to, from, next) {
    /*
        Determines where we should send the user.
    */
    function proceed() {
        /*
            If the user has been loaded determine where we should
            send the user.
        */
        if (store.getters.getUserLoadStatus() == 2) {
            /*
                If the user is not empty, that means there's a user
                authenticated we allow them to continue. Otherwise, we
                send the user back to the home page.
            */
            let can = store.getters.getUserCan
            if (store.getters.getUser != '') {
                if (typeof to.meta.can !== 'undefined') {
                    if (can.includes(to.meta.can)) {
                        next();
                    } else {
                        next('/')
                    }
                } else {
                    next();
                }
            } else {
                next('/');
            }
        }
    }

    /*
        Confirms the user has been loaded
    */
    if (store.getters.getUserLoadStatus() != 2) {
        /*
            If not, load the user
        */
        store.dispatch('loadUser');

        /*
            Watch for the user to be loaded. When it's finished, then
            we proceed.
        */
        store.watch(store.getters.getUserLoadStatus, function () {
            if (store.getters.getUserLoadStatus() == 2) {
                proceed();
            }
        });
    } else {
        /*
            User call completed, so we proceed
        */
        proceed()
    }
}

/*
	Makes a new VueRouter that we will use to run all of the routes
	for the app.
*/

export default new VueRouter({
    routes: [
        {
            path: '/',
            redirect: '/clients',
            beforeEnter: requireAuth,

        },
        {
            component: Layout,
            path: '/',
            beforeEnter: requireAuth,

            children: [{
                path: '/home',
                component: Home,

            }, {
                path: '/clients/create',
                component: ClientForm,
                beforeEnter: requireAuth,

            }, {
                path: '/clients',
                component: ClientTable,
                beforeEnter: requireAuth,
            },
                {
                    path: "clients/:slug",
                    component: ClientView
                },
                {
                    path: 'clients/:slug/products',
                    component: EditClientProducts
                },
                {
                    path: '/client-products/:slug',
                    component: EditClientProductCFs
                },
                {
                    path: 'clients/:slug/edit',
                    component: ClientForm
                },
                {
                    path: 'definitions/create',
                    component: DefinitionForm
                },
                {
                    path: 'definitions',
                    component: DefinitionIndex
                },
                {
                    path: 'definitions/:slug',
                    component: DefinitionForm
                },
                {
                    path: 'users',
                    component: UsersIndex
                },
                {
                    path: 'users/:slug',
                    component: EditUser
                },
                {
                    path: 'roles',
                    component: RolesIndex
                },
                {
                    path: 'permissions',
                    component: PermissionsIndex
                },
                {
                    path: 'products',
                    component: ProductsIndex
                },
                {
                    path: 'products/:slug/edit',
                    component: ProductForm
                },
                {
                    path: 'products/create',
                    component: ProductForm
                }, {
                    path: 'teams',
                    component: TeamIndex
                },
                {
                    path: "teams/create",
                    component: TeamForm
                },
                {
                    path: "teams/:slug/edit",
                    component: TeamForm
                }]
        }
    ]
})

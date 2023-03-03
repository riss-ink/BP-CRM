  export const user = {
    state:{
        user:{},
        can:{},
        userLoadStatus: 0,
        userUpdateStatus: 0
    },
    actions: {
        loadUser( { commit } ){
            commit( 'setUserLoadStatus', 1 );

            axios.get('/api/user')
                .then( function( response ){
                    let user = response.data;
                    let can = user.can;
                    delete user.can
                    commit( 'setUser', user );
                    commit( 'setUserLoadStatus', 2 );
                    commit('setUserCan', can)
                })
                .catch( function(){
                    commit( 'setUser', {} );
                    commit( 'setUserLoadStatus', 3 );
                });
        },

        /*
          Edits a user
        */
        editUser( { commit, state, dispatch }, data ){
            commit( 'setUserUpdateStatus', 1 );

            axios.patch('api/user', data )
                .then( function( response ){
                    commit( 'setUserUpdateStatus', 2 );
                    dispatch( 'loadUser' );
                })
                .catch( function(){
                    commit( 'setUserUpdateStatus', 3 );
                });
        },

        /*
          Logs out a user and clears the status and user pieces of
          state.
        */
        logoutUser( { commit } ){
            commit( 'setUserLoadStatus', 0 );
            commit( 'setUser', {} );
        }
    },

    /*
      Defines the mutations used
    */
    mutations: {
        /*
          Sets the user load status
        */
        setUserLoadStatus( state, status ){
            state.userLoadStatus = status;
        },

        /*
          Sets the user
        */
        setUser( state, user ){
            state.user = user;
        },

        /*
          Sets the user update status
        */
        setUserUpdateStatus( state, status ){
            state.userUpdateStatus = status;
        },
        setUserCan(state, can){
            state.can = can;
        }
    },

    /*
      Defines the getters used by the module.
    */
    getters: {
        /*
          Returns the user load status.
        */
        getUserLoadStatus( state ){
            return function(){
                return state.userLoadStatus;
            }
        },

        /*
          Returns the user.
        */
        getUser( state ){
            return state.user;
        },

        /*
          Gets the user update status
        */
        getUserUpdateStatus( state, status ){
            return state.userUpdateStatus;
        },
        getUserCan(state){
            return state.can
        }
    }
}

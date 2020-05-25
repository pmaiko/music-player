import Vue from 'vue'
import Vuex from 'vuex'
import api from '../api'
Vue.use(Vuex);
import axios from 'axios'

const store = new Vuex.Store({
    state: {
        logged: false,
        token: null,
        userInfo: '' || JSON.parse(window.localStorage.getItem('userInfo')),
        playlist: [],
        total_tracks: '',
        list_playlist: [],
    },

    mutations: {
        list_playlist(state) {
            api.getPlaylist((json) => {
               state.list_playlist = json.data;
            });
        },
        set_list_playlist(state, params) {
            api.setPlaylist(params);
        },
        delTrackPlaylist(state, params) {
            api.delTrackPlaylist(params);
        },



        set(state, {type, value}) {
            state[type] = value;
        },

        playlist(state, {params, counter, callback, subject}) {
            if (params !== undefined && counter !== undefined) {
                api.selectTrack(params, (json) => {
                    let playlist = json.data.playlist;
                    playlist[counter].active = 'active';
                    state.playlist = playlist;
                    state.total_tracks = json.data.total_tracks  - 1;
                    callback(json);
                });

            } else if(params !== undefined && subject !== undefined) {
                api.selectTrack(params, function (json) {
                    state.playlist = json.data.playlist;
                    let counter = undefined;
                    state.playlist.map((item, index) => {
                        if (item.src === subject) {
                            counter = index;
                        }
                    });

                    if (counter !== undefined) {
                        state.playlist[counter].active = 'active';
                    }

                    state.total_tracks = json.data.total_tracks  - 1;
                });
            }
            else if(params !== undefined) {
                api.selectTrack(params, function (json) {
                    state.playlist = json.data.playlist;
                    state.total_tracks = json.data.total_tracks  - 1;
                });

            } else if(counter !== undefined) {
                let playlist = state.playlist;
                playlist.map((item) => {
                    delete item.active;
                });
                playlist[counter].active = 'active';
                state.playlist = playlist;
            }
        },

    },
    actions: {
        login({commit}, data) {
           api.login(data.mail, data.password, function (json) {
               if (json.status === 200) {
                   console.log(json);
                   commit('set',{
                       type: 'token',
                       value: json.data.token
                   });
                   commit('set',{
                       type: 'userInfo',
                       value: json.data.userInfo
                   });
                   window.localStorage.setItem("token", json.data.token);
                   window.localStorage.setItem("userInfo", JSON.stringify(json.data.userInfo));
                   window.axios.defaults.headers.common['Authorization'] = `Bearer ${json.data.token}`;
                   commit('set',{
                       type: 'logged',
                       value: true
                   });
                   data.success();
               }
           })
        },
        checkLogged({state}) {
            let token = window.localStorage.getItem('token');
            if (token !== null && token !== '' && token !== undefined) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                state.token = token;
                state.logged = true;
            }
            else {
                axios.defaults.headers.common['Authorization'] = '';
                state.token = null;
                state.logged = false;
            }
        },
        logout({state}) {
            window.localStorage.removeItem('token');
            window.axios.defaults.headers.common['Authorization'] = '';

            state.token = undefined;
            state.logged = false;
        },

        // selectPlaylist({commit}, data) {
        //     return axios({
        //         method: 'post',
        //         url: '/api/selectPlayList.php',
        //         data: {
        //             user_id: data.cookieID,
        //             limit: data.limitValue,
        //         }
        //     }).then(function (dataOutINServer) {
        //         console.log('nava');
        //         const result = dataOutINServer.data;
        //         commit('set', {type: 'playListUser', items: result});
        //     })
        // },
        //
        // selectPic({commit}, cookieID) {
        //     axios({
        //         method: 'post',
        //         url: '/api/selectPic.php',
        //         data: {
        //             user_id: cookieID,
        //         }
        //     }).then(function (dataOutINServer) {
        //         const result = dataOutINServer.data;
        //         commit('set', {type: 'randomCover', items: result});
        //     })
        // },
    }
});

export default store

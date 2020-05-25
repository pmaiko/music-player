import {API_URL} from "../constants";
import axios from "axios/index";
export default {
    login(email, password, callback) {
        axios.post(API_URL + '/login', {
            mail: email,
            password: password,
        }).then(function (data) {
            callback(data);
        });
    },
    getPlaylist(callback) {
        axios.get(API_URL + '/getPlaylist')
            .then(function (data) {
                callback(data);
            });
    },

    setPlaylist(data) {
        axios.post(API_URL + '/setPlaylist', data)
            .then(function (response) {
                console.log(response);
            });
    },

    delTrackPlaylist(data) {
        axios.post(API_URL + '/delTrackPlaylist', data)
            .then(function (response) {
                console.log(response);
            });
    },

    register(data, callback) {
        axios.post(`${API_URL}/register`, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            callback(response);

        }).catch(error => {
            callback(error.response);
        });
    },

    selectTrack(params, callback) {
        axios.get(params.playlist_name ?
            `${API_URL}/selectTrack?size=${params.size}&playlist_name=${params.playlist_name}`
            : `${API_URL}/selectTrack?size=${params.size}`)
            .then(function (data) {
            callback(data);
        });
    },
    getTrack(src, callback) {
        axios.get(src,{
            responseType: 'arraybuffer'
        }).then(function (data) {
            callback(data);
        });
    },
    uploadTracks(data, callback) {
        axios.post(API_URL + '/uploadTracks', data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },

            onUploadProgress: (progressEvent) => {
                let progress;
                const {loaded, total} = progressEvent;
                progress = (loaded * 100) / total;
                console.log(progress);
            }
        }).then(function (response) {
            callback(response);
        });
    }
}

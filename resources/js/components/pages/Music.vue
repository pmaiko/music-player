<template>
<section class="content">
    <div class="background-gradient">
        <div class="background-image"
             :style="'background-image: url('+ musicPicture +')'">
        </div>
    </div>
    <section class="music">
        <header class="header">
            <div class="site-logo">
                E.P.T.A
            </div>
            <div class="header-link">
                <a href="#">Home</a>
            </div>
            <div class="header-search">
                <input class="header-search__input" placeholder="Search" type="search">
            </div>
            <div class="header-link">
                <a href="#" data-toggle="modal" data-target="#exampleModalCenter">Upload</a>
            </div>
            <div class="user-block dropdown show">
                <div class="user-block__photo">
                    <template v-if="$store.state.userInfo.image">
                        <img :src="$store.state.userInfo.image" alt="User photo">
                    </template>
                    <template v-else>
                        <img src="../../assets/icons/boy.svg" alt="User photo">
                    </template>
                </div>
                <a class="user-block__email dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $store.state.userInfo.mail }}
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#" @click="logout">Выйти</a>
                </div>
            </div>
        </header>

        <div class="left-panel">
            <transition name="move_out">
                <div class="playlist" v-if="playlistToggle">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#"
                               @click.prevent="filterTrack('')"
                               :class="{'active': params.playlist_name === ''}"
                            >
                                Все
                            </a>
                        </li>
                        <li class="nav-item"
                            v-for="(data, index) in list_playlist"
                        >
                            <a class="nav-link"
                               href="#"
                               :class="{'active': params.playlist_name === data}"
                               @click.prevent="filterTrack(data)"
                            >
                                {{ data }}
                            </a>
                        </li>
                    </ul>
                    <div v-for="(item, index) in playlist"
                         :src="item.src"
                         :id="index"
                         :class="item.active"
                         class="playlist__item"
                    >
                        <span :base_id="item.id"
                              @click="playTrack(index, item.src)"
                        >
                            <template v-if="item.active">
                            <div v-if="paused" class="icons icons--play-track"></div>
                            <div v-else class="icons icons--pause-track"></div>
                        </template>
                        <template v-else>
                            <div class="icons icons--play-track"></div>
                        </template>
                        {{ item.name_track }}
                        </span>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                add
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="#"
                                   v-for="(data, index) in list_playlist"
                                   class="dropdown-item"
                                   @click.prevent="addTrackToPlaylist(item.id, data)">
                                    {{ data }}
                                </a>
                                <span data-toggle="modal" data-target="#name-playlist" @click="idSongAdd = item.id">
                                    add new play list
                                </span>
                            </div>
                        </div>

                        <div class="btn btn-primary" v-if="params.playlist_name !== ''"
                             @click="deleteTrack(item.id, params.playlist_name)"
                        >
                            delete
                        </div>

                    </div>
                </div>
            </transition>
            <div class="left-panel__button_show_hide"
                 @click="playlistToggle = !playlistToggle"
                 :style="playlistToggle === true ? 'transform : translateY(-50%) rotate(-180deg)': ''"
            >
                <div class="left-panel__button_arrow">

                </div>
            </div>
        </div>

        <section class="player">
            <div class="player__track-info">
                <div class="track-info"
                     :style="artistMaxLength === true ? 'font-size: .6em' : ''">
                    <div class="player__track-current-time"
                         :style="artistMaxLength === true ? 'font-size: 1.4em' : ''">
                        <span>{{ currentTime }}</span>
                    </div>
                    <div class="player__track-performer">
                        <span>{{ musicTags.artist ? musicTags.artist: ''}}</span>
                    </div>

                    <div class="player__track-full-name">
                        <template v-if="musicTags.artist && musicTags.title">
                            <span>{{ musicTags.artist }}</span> - {{ musicTags.title }}
                        </template>
                        <template v-else>
                            <span>{{ counter !== null ? playlist[counter].name_track : '' }}</span>
                        </template>
                    </div>
                </div>
                <div class="track-picture">
                    <img :src="musicPicture" id="picture" alt="">
                </div>
            </div>

            <div class="player__progress-bar"
                 :class="{'disabledJs': srcPlaying === ''}"
                 ref="progress_bar"
                 @mousemove="mouseMoveOnProgressBar($event)"
                 @click="goTime"
                 @mouseenter="mouseOnProgressBar = true"
                 @mouseleave="[mouseOnProgressBar = false, progressWaveform(clickMouseMoveLeftPx)]"
            >
                <canvas class="progress-bar__canvas" ref="progress_bar__canvas"></canvas>

                <div class="moveTime" :style="'left: ' + mouseMoveLeftPercent + '%'" v-show="mouseOnProgressBar">
                    <span>{{ mouseMoveTime60sec }}</span>
                </div>
            </div>
            <div class="player__control-panel">
                <div class="player__control-panel_left-block">
                    <div class="play-pause-track"
                         @click="play"
                    >
                        <div v-if="paused" class="icons icons--play-track"></div>
                        <div v-else class="icons icons--pause-track"></div>
                    </div>

                    <div class="player__control-panel_track-info">
                        <div class="player__control-panel_track-performer">
                            <span>{{ musicTags.artist ? musicTags.artist: ''}}</span>
                        </div>

                        <div class="player__control-panel_track-full-name">
                            <template v-if="musicTags.artist && musicTags.title">
                                <span>{{ musicTags.artist }}</span> - {{ musicTags.title }}
                            </template>
                            <template v-else>
                                <span>{{ counter !== null ? playlist[counter].name_track : '' }}</span>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="player__control-panel_right-block">
                    <div class="btn-circle prev-track"
                         @click="prev"
                    >
                        <div class="icons icons--prev-track"></div>
                    </div>
                    <div class="btn-circle next-track"
                         @click="next"
                    >
                        <div class="icons icons--next-track"></div>
                    </div>
                    <div class="volume">
                        <div v-if="volume === 0" class="btn-circle"
                             @click="isOpenInputRange = !isOpenInputRange"
                             :class="{'active-button': isOpenInputRange}"
                        >
                            <div class="icons icons--speaker-mute"></div>
                        </div>
                        <div v-if="volume <= 50 && volume !== 0" class="btn-circle"
                             @click="isOpenInputRange = !isOpenInputRange"
                             :class="{'active-button': isOpenInputRange}"
                        >
                            <div class="icons icons--speaker-50"></div>
                        </div>
                        <div v-if="volume > 50" class="btn-circle"
                             @click="isOpenInputRange = !isOpenInputRange"
                             :class="{'active-button': isOpenInputRange}"
                        >
                            <div class="icons icons--speaker-max"></div>
                        </div>
                        <div class="input-range" v-if="isOpenInputRange">
                            <vue-slider
                                    v-model="volume"
                                    direction="btt"
                                    :min="0"
                                    :max="100"
                            >

                            </vue-slider>
                        </div>
                    </div>
                    <div class="btn-circle"
                         @click="isShuffle = !isShuffle"
                         :class="{'active-button': isShuffle}"
                    >
                        <div class="icons icons--shuffle"></div>
                    </div>
                    <div class="btn-circle"
                         @click="isReplay = !isReplay"
                         :class="{'active-button': isReplay}"
                    >
                        <div class="icons icons--replay-arrow"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadForm" name="uploadForm" enctype="multipart/form-data">

                        <input type="file" id="files" name="file" multiple="multiple"><br>
                        <!--<input type="email" id="email" name="email">-->


                        <input type="button" value="Upload" @click="uploadTracks">
                        <!--<input type="submit">-->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!--play list-->

        <div class="modal fade" id="name-playlist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" v-model="namePlaylist">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addTrackToPlaylist(idSongAdd, namePlaylist)">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</section>
</template>

<script>
import api from '../../api'
import {mapState} from 'vuex'

export default {
    data() {
        return {
            audio: new Audio(),
            params: {
                size: 20,
                playlist_name: '',
            },
            srcPlaying: '',
            counter: null,

            playlistToggle: false,
            waveformData: '',
            currentTime: '',
            smoothing: 4,
            maxValue: '',
            progressWidth: '',
            mouseMoveLeftPx: '',
            clickMouseMoveLeftPx: '',
            mouseMoveLeftPercent: '',
            mouseMoveTime: '',
            mouseOnProgressBar: false,
            songRepeat: false,
            mouseMoveTime60sec: '',
            buffer: '',
            paused: true,
            musicTags: '',
            musicPicture: '',
            volume: 0,
            isOpenInputRange: false,
            isReplay: false,
            isShuffle: false,
            namePlaylist: '',
            idSongAdd: null,
        }
    },
    computed: {
        ...mapState(
            {
                playlist: state => state.playlist,
                list_playlist: state => state.list_playlist,
            },
        ),

        logged() {
            return this.$store.state.logged;
        },
        artistMaxLength() {
            if(this.musicTags.artist) {
                if (this.musicTags.artist.length > 18) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        },
    },

    methods: {
        deleteTrack(id, playlist_name) {
            this.$store.commit('delTrackPlaylist', {id, playlist_name});
        },

        filterTrack(playlist_name) {
            this.counter = null;
            this.params.playlist_name = playlist_name;
            this.$store.commit('playlist', {params: this.params, subject: this.srcPlaying});
        },

        addTrackToPlaylist(id, playlist_name) {
            this.$store.commit('set_list_playlist', {id, playlist_name});
        },

        uploadTracks() {
            const data = new FormData(document.querySelector('#uploadForm'));
            const Files = document.querySelector('#files');
            for (let i = 0; i < Files.files.length; i++) {
                data.append('file', Files.files[i]);
                 api.uploadTracks(data, (response) => {
                     if(this.audio.src) {
                         let count = this.counter;
                         count++;
                         this.$store.commit('playlist', {params: this.params, counter: count, callback: () => {
                                 this.counter++;
                             }});
                     } else {
                         this.$store.commit('playlist', {params: this.params});
                     }
                 });

            }
        },
        play() {
            if(this.counter === null) {
                this.counter = 0
            }
            this.playTrack(this.counter, this.playlist[this.counter].src)
        },
        next() {
            if(this.counter === null) {
                this.counter = 0
            }
            this.playTrack(this.counter < this.$store.state.total_tracks ? this.counter+=1: this.counter = 0, this.playlist[this.counter].src)
        },

        prev() {
            if(this.counter === null) {
                this.counter = 0
            }
            this.playTrack(this.counter > 0 ? this.counter-=1: this.counter = this.$store.state.total_tracks, this.playlist[this.counter].src)
        },

        playTrack(index, src) {
            this.counter = index;
            if (this.audio.paused) {
                if (src !== this.srcPlaying) {
                    this.audio.src = src;
                    this.audio.play();
                    this.srcPlaying = src;

                    this.createWaveform();
                    this.getTags();
                }
                else {
                    this.audio.play();
                }
            }
            else {
                if (src !== this.srcPlaying) {
                    this.audio.src = src;
                    this.audio.play();
                    this.srcPlaying = src;

                    this.createWaveform();
                    this.getTags();
                }
                else {
                    this.audio.pause();
                }
            }

            this.paused = this.audio.paused;


            this.audio.ontimeupdate =  () => {
                let currentTime = this.audio.currentTime;
                let duration = this.audio.duration;
                let current =((duration+currentTime)*100)/duration;
                let timesec = parseInt(currentTime%60);
                let tmp;

                if (timesec < 10){
                    tmp = "0";
                }
                else{
                    tmp = "";
                }

                this.currentTime = parseInt(currentTime/60)+':'+ tmp + parseInt(currentTime%60);

                if(this.mouseOnProgressBar === false) {
                    current = current - 100;
                    current = (this.progressWidth * current) / 100;
                    this.progressWaveform(current);
                }

                if (this.isReplay === true) {
                    if(duration === currentTime){
                        this.audio.currentTime = 0;
                        this.audio.play();
                    }
                }
                else {
                    if(duration === currentTime){
                        this.next();
                    }
                }

                this.clickMouseMoveLeftPx = current;

            }
        },

        getTags() {
            window.jsmediatags.read(this.audio.src, {
                onSuccess: (tag) =>{
                    tag.tags.artist ? tag.tags.artist = tag.tags.artist.replace( /amp;/g, "" ): tag.tags.artist;
                    this.musicTags = tag.tags;
                    let image = tag.tags.picture;
                    let base64String = "";
                    for (let i = 0; i < image.data.length; i++) {
                        base64String += String.fromCharCode(image.data[i]);
                    }
                    let base64 = "data:" + image.format + ";base64," +
                        window.btoa(base64String);
                    this.musicPicture = base64;
                    //document.getElementById('picture').setAttribute('src',base64);
                },
                onError: function(error) {
                    console.log(':(', error.type, error.info);
                }
            });
        },

        goTime() {
            this.audio.currentTime = this.mouseMoveTime;
            this.clickMouseMoveLeftPx = this.mouseMoveLeftPx;
        },

        mouseMoveOnProgressBar(e) {
            let leftMargin = (window.innerWidth - e.currentTarget.offsetWidth) / 2;
            this.mouseMoveLeftPx = e.pageX - (leftMargin); //px
            this.mouseMoveLeftPercent = ((this.mouseMoveLeftPx * 100) / e.currentTarget.offsetWidth); //%

            this.mouseMoveLeftPx  = parseInt(this.mouseMoveLeftPx);
            this.progressWaveform(this.mouseMoveLeftPx);
            this.mouseMoveTime = ((this.mouseMoveLeftPercent)* this.audio.duration) / 100;

            let timesec = parseInt(this.mouseMoveTime%60);
            let tmp;
            if (timesec < 10){
                tmp = "0";
            }
            else{
                tmp = "";
            }
            this.mouseMoveTime60sec = (parseInt(this.mouseMoveTime/60) + ':' + tmp + parseInt(this.mouseMoveTime%60));
        },

        progressWaveform(length) {
            const progress_bar = this.$refs.progress_bar;
            const canvas = this.$refs.progress_bar__canvas;
            const ctx = canvas.getContext('2d');
            const WIDTH = progress_bar.clientWidth;
            const HEIGHT = progress_bar.clientHeight;

            ctx.clearRect(0, 0, WIDTH, HEIGHT);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';

            let x = 0;
            for (let i = 0; i < this.waveformData.length; i++) {
                ctx.fillRect(x, (1 - this.waveformData[i] / this.maxValue) * HEIGHT, this.smoothing - 1, HEIGHT);
                x += this.smoothing;
            }

            let gradient = ctx.createLinearGradient(0, 0, WIDTH, HEIGHT);
            gradient.addColorStop(1, 'rgb(251, 45, 145)');
            gradient.addColorStop(0.2, 'rgb(250, 153, 82)');
            ctx.fillStyle  = gradient;

            x = 0;
            for (let i = 0; i < length / this.smoothing; i++) {
                ctx.fillRect(x, (1 - this.waveformData[i] / this.maxValue) * HEIGHT, this.smoothing - 1, HEIGHT);
                x += this.smoothing;
            }


        },

        addActive(newVal) {
            this.$store.commit('playlist', {counter: newVal.newVal});
        },

        createWaveform() {
            const audioCtx = new AudioContext();

            const progress_bar = this.$refs.progress_bar;
            const canvas = this.$refs.progress_bar__canvas;
            const ctx = canvas.getContext('2d');
            const WIDTH = progress_bar.clientWidth;
            const HEIGHT = progress_bar.clientHeight;
            this.progressWidth = WIDTH;

            api.getTrack(this.srcPlaying, (data) => {
                audioCtx.decodeAudioData(data.data).then(buffer => {

                    this.buffer = buffer;
                    this.waveformData = this.getWaveformData(buffer, WIDTH / this.smoothing);

                    function getMaxOfArray(numArray) {
                        return Math.max.apply(null, numArray);
                    }
                    this.maxValue = getMaxOfArray(this.waveformData);

                    canvas.width = WIDTH;
                    canvas.height = HEIGHT;
                    ctx.clearRect(0, 0, WIDTH, HEIGHT);
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';

                    let x = 0;
                    for (let i = 0; i < this.waveformData.length; i++) {
                        ctx.fillRect(x, (1 - this.waveformData[i] / this.maxValue) * HEIGHT, this.smoothing - 1, HEIGHT);
                        x += this.smoothing;
                    }
                });
            });

        },

        getWaveformData(audioBuffer, dataPoints) {
            const avg = values => values.reduce((sum, value) => sum + value, 0) / values.length;

            const leftChannel = audioBuffer.getChannelData(0);
            const rightChannel = audioBuffer.getChannelData(1);
            const values = new Float32Array(dataPoints);
            const dataWindow = Math.round(leftChannel.length / dataPoints);

            for (let i = 0, y = 0, buffer = []; i < leftChannel.length; i++) {
                const summedValue = (Math.abs(leftChannel[i]) + Math.abs(rightChannel[i])) / 2;
                buffer.push(summedValue);
                if (buffer.length === dataWindow) {
                    values[y++] = avg(buffer);
                    buffer = [];
                }
            }
            return values;
        },

        resize() {
            const progress_bar = this.$refs.progress_bar;
            const canvas = this.$refs.progress_bar__canvas;
            const ctx = canvas.getContext('2d');

            window.onresize = () => {
                const WIDTH = progress_bar.clientWidth;
                const HEIGHT = progress_bar.clientHeight;
                this.progressWidth = WIDTH;

                this.waveformData = this.getWaveformData(this.buffer, WIDTH / this.smoothing);
                canvas.width = WIDTH;
                canvas.height = HEIGHT;
                ctx.clearRect(0, 0, WIDTH, HEIGHT);
                ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';

                let x = 0;
                for (let i = 0; i < this.waveformData.length; i++) {
                    ctx.fillRect(x, (1 - this.waveformData[i] / this.maxValue) * HEIGHT, this.smoothing - 1, HEIGHT);
                    x += this.smoothing;
                }
            }
        },

        logout() {
            this.$store.dispatch('logout');
            this.$router.push({
                path: '/sign-in',
            });
        },

    },

    mounted() {
        this.resize();
        if(!this.logged) {
            this.$router.push({
                path: '/sign-in',
            });
        }
        else {
            this.$store.commit('playlist', {params: this.params});
            this.$store.commit('list_playlist');
            this.volume = 50;
        }
    },

    watch: {
        counter(newVal) {
            this.addActive({newVal});
        },
        volume(newVal) {
            this.audio.volume = newVal / 100;
        }

    },
}
</script>

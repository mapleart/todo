/**
 * ToDo app frontend
 * Malyshkin Aleksey
 */
import Vue from 'vue'
import VueMeta from 'vue-meta'
import Buefy from 'buefy'


// Переменные для быстрого доступа из приложения
Vue.prototype.$user = USER;
Vue.prototype.$token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


import App from './App.vue'
import router from  './router'

// Подключаем вспомогательные библиотеки
require('./bootstrap');

Vue.use(Buefy, {
    defaultIconPack: 'fas',
})
Vue.use(VueMeta, {
    refreshOnceOnNavigation: true,
})

new Vue({
    el: '#app',
    router,
    render(h){
        return h(
            App
        )
    },
    created() {
        this.checkSize();
        window.addEventListener('resize', ()=>{
            this.checkSize();
        });
    },

    methods: {
        checkSize(){
            let w = document.documentElement.clientWidth,
                isMobile = w < 992;

            if(this.$store.getters.getIsMobile != isMobile) {
                this.$store.commit('setIsMobile', isMobile)
            }
        }
    }
})

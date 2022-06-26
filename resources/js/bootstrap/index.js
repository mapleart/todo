import Vue from 'vue';

Date.prototype.yyyymmdd = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear(),
        (mm>9 ? '' : '0') + mm,
        (dd>9 ? '' : '0') + dd
    ].join('-');
};


import './validate'

import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.options.emulateJSON = true;

import { VueAjax } from './ajax'
Vue.use(VueAjax);

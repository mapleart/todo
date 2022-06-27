import Vue from 'vue';
window.moment = require('moment');
moment.locale('ru');


Date.prototype.yyyymmdd = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear(),
        (mm>9 ? '' : '0') + mm,
        (dd>9 ? '' : '0') + dd
    ].join('-');
};

Vue.filter('moment', function (date) {
    date = moment.unix(date);

    var now = moment();

    if (moment.duration(now.diff(date)).asHours() > 24) {
        return date.format("DD.MM.YYYY HH:mm");
    } else if (moment.duration(now.diff(date)).asSeconds() > 20) {
        return date.fromNow();

    } else {
        return 'Только что';
    }
});

import './validate'

import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.options.emulateJSON = true;

import { VueAjax } from './ajax'
Vue.use(VueAjax);

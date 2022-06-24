import Vue from 'vue';

import './vee-validate'

import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.options.emulateJSON = true;

import { VueAjax } from './ajax'
Vue.use(VueAjax);

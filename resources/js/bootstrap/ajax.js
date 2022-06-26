import Vue from 'vue';

import NProgress from 'nprogress/nprogress';
NProgress.configure({ showSpinner: false });
import { Notifications } from './notifications'

export const AjaxPlugin = {

    action(func='get', url, params = {}, config = {}, vue = null) {
        return new Promise(function(resolve, reject) {

            config.showNotices = typeof config.showNotices === 'undefined' ? true : config.showNotices;
            config.showProgress = typeof config.showProgress === 'undefined' ? true : config.showProgress;

            if(config.showProgress) NProgress.start();


            if ( Vue.prototype.$token ) params._token = Vue.prototype.$token;


            let httpParams = func === 'post' ? params : { params: params };

            Vue.http[func](url, httpParams).then( (response) => {

                let json = response.body;
                if(config.showProgress) NProgress.done();

                if ( !!!json.success || json.errors ) {
                    if ( config.showNotices && ( json.message_title || json.message ) ) Notifications.error( json.message_title, json.message );


                    if(json.routerRedirect && config.context ) {
                        config.context.$router.push(json.routerRedirect);
                    } else if(json.routerRedirect || json.redirect) {
                        window.location = json.routerRedirect || json.redirect
                    }
                    reject(json);

                } else {
                    if ( config.showNotices && ( json.message_title || json.message ) ) Notifications.notice( json.message_title, json.message );
                    resolve(json)

                    if(json.routerRedirect && config.context ) {
                        config.context.$router.push(json.routerRedirect);
                    } else if(json.routerRedirect || json.redirect) {
                        window.location = json.routerRedirect || json.redirect
                    }
                }

            },  (response) => {
                let json = response.body;

                if ( json.errors ) {
                    if ( config.showNotices && ( json.message_title || json.message ) ) Notifications.error( json.message_title, json.message );
                }
                if(config.showProgress) NProgress.done();
                reject();
            })
        });
    },
    delete(url, params = {}, config = {}, vue = null){
        return this.action('delete', url, params, config, vue, )
    },
    get(url, params = {}, config = {}, vue = null){
        return this.action('get', url, params, config, vue )
    },


    post(url, params = {}, config = {}, vue = null){
        return  this.action('post', url, params, config, vue)
    }
};

export const VueAjax = {
    install(Vue, options) {
        window.$ajax = AjaxPlugin;

        Vue.prototype.$ajax = {
            get(url, params = {}, config = {}) {
                return AjaxPlugin.get(url, params, config, Vue);
            },

            delete(url, params = {}, config = {}) {
                return AjaxPlugin.delete(url, params, config, Vue);
            },

            post(url, params = {}, config = {}){
                return AjaxPlugin.post(url, params, config, Vue)
            }
        }

    }
}

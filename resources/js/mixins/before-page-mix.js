import NProgress from 'nprogress/nprogress';
NProgress.configure({ showSpinner: false });

function get($url, routerParams, params) {

    NProgress.start();
    return new Promise((resolve, reject) => {

        if(typeof params == 'function') {
            params = params(routerParams);
        }

        window.$ajax.get($url, {
            ...params
        }).then((response)=>{
            NProgress.done();
            response.next = true;
            resolve(response);
        }, (response)=>{
            NProgress.done();
            reject(response)
        });
    });
}


export default function(options) {
    let pageParams = {
        endPoint: options.endPoint || '/ajax/vue-start/',
        endPointUpdate: options.endPointUpdate || null
    }

    return {
        data: () => {
            return {
                ready: false,
                errors: [],
            }
        },

        beforeRouteEnter(to, from, next) {
            let endPoint = pageParams.endPoint;
            if(typeof endPoint == 'function') {
                endPoint = endPoint(to.params);
            }

            get(endPoint, to.params, options.params).then((response)=>{

                if(response.next || response.redirect) {
                    if (response.redirect) {
                        window.location = '/' + response.redirect;
                        return next(response.redirect)
                    } else {
                        next(vm => vm.setData(response))
                    }
                }
            }, (response)=>{
                next('404')
            });
        },

        beforeRouteUpdate(to, from, next) {
            let endPoint = pageParams.endPointUpdate;
            if(typeof endPoint == 'function') {
                endPoint = endPoint(to.params);
            }

            if(!endPoint) return next();
            this.beforeUpdate();

            get(endPoint, to.params, options.params).then((response)=>{
                if(response.next || response.redirect) {
                    if (response.redirect) {
                        window.location = '/' + response.redirect;
                        return next(response.redirect)
                    } else {
                        this.setData(response);
                        next()
                    }
                }
            }, (response)=>{
                //next('404')
            });
        },

        methods: {

            pageParams: function () {
                return {}
            },

            setData(response) {
                this.ready = true;
                if (response.bStateError === true) {
                    this.errors.push({
                        msg: response.sMsg,
                        title: response.sMsgTitle
                    })
                }

                if(response.pageTitle) {
                    document.title = response.pageTitle;
                }
                this.pageSuccess(response);
                if(response.widgets){
                    this.$store.commit('setWidgets', response.widgets)
                }
            },
            beforeUpdate() {
            },
            pageSuccess(response) {
            },
            pageError(response) {
            },
            validateErrorBase(){
            },

            submitValidate(successCallback, errorCallback){
                let _this = this;
                this.$refs.observer.validate().then((success, success2, success3) => {
                    if (!success) {
                        return typeof errorCallback == 'function' ? errorCallback(success, success2, success3) : _this.validateErrorBase();
                    }

                    successCallback()
                }, (error) => {

                });
            }
        }
    }
}

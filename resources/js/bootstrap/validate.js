import Vue from 'vue'
import { required, min, email, regex } from 'vee-validate/dist/rules'
import { extend, validate, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'


extend('required', {
    ...required,
    message: 'Обязательно для заполнения'
});

extend('url', {
    validate(value, { min, textError }) {

        let expression = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
        let regex = new RegExp(expression);

        if (value.match(regex)) {
            return true
        } else {
            return textError || 'Должно соответствовать формату URL';
        }
    },
    params: ['textError'],
});


extend('regex', {
    validate(value, { test, textError }) {
        if(test.test(value)){
            return true;
        }
        return  textError || 'Поле заполнено неверно';
    },
    params: ['test', 'textError'],
})


extend('async', {
    validate(value, { endPoint, paramName }) {
        if (value === '') return true;

        // simulate async call, fail for all logins with even length
        return new Promise((resolve, reject) => {
            paramName = paramName || 'login';

            let remoteParams = {};
            remoteParams[paramName] = value;
            window.$ajax.get(endPoint, remoteParams).then((response)=> {
                let errors = response.errors || {};
                if(Object.keys(errors).length > 0) {
                    //this.asyncErrors = errors[this.name];
                    resolve(errors[paramName][0]);
                } else {
                    //this.asyncErrors = [];
                    resolve(true);
                }

            }, ()=>{
                // this.asyncErrors = [];
                resolve(false);
            });
        })
    },
    params: ['endPoint', 'paramName'],
});

extend('min', {
    validate(value, { min, textError }) {
        if(value.length < min) return textError || 'Минимум '+min;
        return true;
    },
    params: ['min', 'textError'],
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);

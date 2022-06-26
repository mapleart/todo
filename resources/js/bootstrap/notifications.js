import Vue from "vue";

/**
 * Обертка для уведомлений
 * @type {{init(), success(*=, *=, *=): void, show(*=, *=, *=, *=): (undefined|{duration: number, icon: boolean, title: *|string, message: *|string, type: *}), error(*=, *=, *=): void, notice(*=, *=, *=): void, info(*=, *=, *=): void}}
 */


export const Notifications = {

    /**
     * Отображение информационного сообщения
     */
    show( title, message, type, params = {}) {
        if ( ! title && ! message ) {
            console.warn('Необходимо указать заголовок или текст уведомления');
            return;
        }

        Vue.prototype.$buefy.notification.open({
            message: message,
            type: type,
            pauseOnHover: true,
        })
    },

    /**
     * Отображение информационного сообщения
     */
    notice( title, message, params ) {
        this.show(title, message, 'is-success', params)
    },

    /**
     * Отображение сообщения об успехе
     */
    success( title, message, params ) {
        this.show(title, message, 'is-success', params)
    },

    /**
     * Отображение сообщения об ошибке
     */
    error( title, message, params ) {
        this.show(title, message, 'is-danger', params)
    },

    /**
     * Отображение информационного сообщения
     */
    info( title, message, params ) {
        this.show(title, message, 'is-primary', params)
    }
};

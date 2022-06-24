import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)


import Index from '../pages/Index'


export default new Router({
    mode: 'history',
    scrollBehavior: (to, from, savedPosition) => {
        if (to.hash) return { selector: to.hash }
        if (savedPosition) return savedPosition

        return { x: 0, y: 0 }
    },
    routes: [
        {
            path: '/',
            name: 'home',
            component: Index,
        },
        {
            path: '/profile/:address',
            name: 'restaurants',
            component: Profile,
        }
    ]
})

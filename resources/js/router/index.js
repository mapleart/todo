import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)


import Index from '../pages/Index'
import Profile from '../pages/Profile'
import CreateTask from '../pages/CreateTask'
import Admin from '../admin/pages/Index'
import NotFound from '../pages/Error'

let adminRoutes = [
    {
        path: '/admin',
        name: 'admin',
        component: Admin,
    }
]

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
            path: '/profile',
            name: 'profile',
            component: Profile,
        },
        {
            path: '/create-task',
            name: 'create-task',
            component: CreateTask,
        },
        // Роуты только для админа
        ...adminRoutes,

        {
            path: '/404',
            name: '404',
            component: NotFound,
        }, {
            path: '*',
            redirect: '/404'
        }
    ]
})

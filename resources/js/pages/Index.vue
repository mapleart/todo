<template>
    <div>
        <div class="tt tt--layout">
            <h2 class="tt__text">Задания</h2>
            <router-link to="/create-task" class="button is-success is-rounded">+ Задачу</router-link>
        </div>

        <div class="nv">
            <a href="#" class="nv__link" :class="{'active': tab == 'today'}">На сегодня</a>
            <a href="#" class="nv__link" :class="{'active': tab == 'week'}">На неделю</a>
            <a href="#" class="nv__link" :class="{'active': tab == 'all'}">на будущее</a>
        </div>
    </div>
</template>

<script>
import BeforePageMixin from '../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/home'
});


export default {
    name: 'Home',
    mixins: [mix],
    data() {
        return {
            tab: 'today',
        }
    },
    methods: {
        changeAddress(){
            this.$router.push('/profile/'+this.searchAddress)
        }
    }
}
</script>

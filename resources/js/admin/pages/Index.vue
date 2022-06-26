<template>
    <div class="page-admin" v-if="ready">
        <div class="tt tt--layout">
            <h2 class="tt__text">Админпанель</h2>
        </div>

        <div class="a-panel">
            <div class="a-panel__title">Управление пользователями</div>
            <div class="a-panel__subtitle">В данном разделе можно настроить роли пользователей и распределить среди сотрудников</div>
            <div class="a-panel__body">
                <UserItem v-for="user in users" :user="user" :key="user.id" :heads="heads" @update-list="setUsers" @update-heads="setHeads"></UserItem>
            </div>
        </div>
    </div>
</template>

<script>
 import BeforePageMixin from '../../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/admin'
});

import UserItem from '../components/userItem';

export default {
    name: 'Admin',
    mixins: [mix],
    data() {
        return {
            ready: false,
            isSaving: false,
            users: [],
            heads: [],
        }
    },
    mounted() {
    },
    methods: {
        pageSuccess(response) {
            this.ready = true;
            this.users = response.users;
            this.heads = response.heads;
        },

        setUsers(users){
            this.users = users;
        },
        setHeads(heads){
            this.heads = heads;
        },
    },
    components: {
        UserItem
    }
}
</script>

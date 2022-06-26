<template>
    <div class="u-item" :class="{'u-item--child': user.parent_id}">

        <div class="u-item__level" v-if="user.parent_id"><i class="fas fa-chevron-up"></i></div>

        <img :src="user.avatar"> {{ user.display_name }}
        <div class="u-item__toolbar">

            <b-select v-if="user.role == 'staff'"  placeholder="Руководитель" @change.native="changeUserHead" v-model="userHeadId" :loading="loading" :disabled="loading">
                <option value="0" :selected="user.parent_id == 0">Руководитель</option>
                <option v-for="head in heads" :value="head.id" :selected="user.parent_id == head.id">{{ head.display_name }}</option>
            </b-select>

            <b-select placeholder="Роль" @change.native="changeUseRole" v-model="user.role" :loading="loading" :disabled="loading">
                <option value="staff" :selected="user.role == 'staff'">Сотрудник</option>
                <option value="head" :selected="user.role == 'head'">Руководитель</option>
            </b-select>
        </div>

    </div>
</template>

<script>
export default {
    name: 'UserItem',
    props: {
        user: {
            type: Object
        },
        heads: {
            type: Array
        }
    },
    data(){
        return {
            userHeadId: 0,
            loading: false,
        }
    },
    created() {
        this.userHeadId = this.user.parent_id;
    },
    methods: {
        changeUserHead(e){
            if(this.loading || !confirm('Сменить или установить руководителя?')) {
                this.userHeadId = this.user.parent_id;
                return false;
            }
            this.loading = true;

            this.$ajax.get('/vue/admin/save-user-head', {userId: this.user.id, headId: this.userHeadId}).then((response)=>{
                this.$emit('update-list', response.users);
                this.$emit('update-heads', response.heads);
                this.loading = false;
            }, ()=>{
                this.loading = false;
                this.userHeadId = this.user.parent_id;
            })
        },
        changeUseRole(e){
            let newValue = this.user.role,
                oldValue = 'head',
                confirmMessage = {
                    'staff': "Вы действительно хотите перевести в сотрудники ? Все его подчиненные будут расформированны!",
                    'head': "Вы действительно хотите сделать пользователя руководителем? если он у кого то в подченных, то будет удален!"
                };
            if(newValue == 'head') {
                oldValue = 'staff';
            }


            if(this.loading || !confirm(confirmMessage[newValue])) {
                this.user.role = oldValue;
                return false;
            }

            this.loading = true;

            this.$ajax.get('/vue/admin/save-user-role', {userId: this.user.id, role: newValue}).then((response)=>{
                this.$emit('update-list', response.users);
                this.$emit('update-heads', response.heads);
                this.loading = false;
            }, ()=>{
                this.loading = false;
                this.user.role = oldValue;
            })
        },
    }
}
</script>

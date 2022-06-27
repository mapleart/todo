<template>
    <div class="task-item" :class="['task-item--'+task.priority_code, 'task-item--status-'+task.status_code]">
        <a href="#" class="task-item__clicker" @click.self="edit"></a>

        <div class="task-item__body">
            <div class="task-item__header">
                <div class="task-item__header__title">
                    <router-link :to="'/task/'+task.id">{{ task.title}}</router-link>
                </div>

                <b-dropdown aria-role="list">
                    <template #trigger="{ active }">
                        <div class="task-item__header__status" :class="['task-item__header__status--'+task.status_code]">
                            {{task.status_text}}
                        </div>
                    </template>


                    <b-dropdown-item aria-role="listitem" @click="changeStatus(0)">К выполнению</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" @click="changeStatus(1)" active >Выполняется</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" @click="changeStatus(2)">Выполнена</b-dropdown-item>
                    <b-dropdown-item aria-role="listitem" @click="changeStatus(5)">Отменена</b-dropdown-item>
                </b-dropdown>


            </div>

            <div class="task-item__text" v-html="task.description">

            </div>

            <div class="task-item__params">

                <div class="task-item__param task-item__param--priority " :class="['task-item__param--priority_'+task.priority_code]">
                    {{task.priority_text }}
                </div>

                <div class="task-item__param task-item__param--date" v-if="task.assigned.id == task.user.id">
                    Личое
                </div>
                <div class="task-item__param task-item__param--date">
                    <span>Создан:</span> {{task.time_add | moment }}
                </div>
                <div class="task-item__param task-item__param--date">
                    <span>Обновлен:</span> {{task.time_update | moment }}
                </div>
                <div class="task-item__param task-item__param--date text-color--red">
                    <span>Завершить:</span> {{ task.date_end }}
                </div>
            </div>

            <div class="task-item__users">
                <div class="task-item__user" v-if="task.user">
                    <img :src="task.user.avatar">{{task.user.display_name}}
                </div>
                <svg width="37" height="16" viewBox="0 0 37 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="task-item__users__arrow">
                    <path d="M36.7071 8.70711C37.0976 8.31658 37.0976 7.68342 36.7071 7.29289L30.3431 0.928932C29.9526 0.538408 29.3195 0.538408 28.9289 0.928932C28.5384 1.31946 28.5384 1.95262 28.9289 2.34315L34.5858 8L28.9289 13.6569C28.5384 14.0474 28.5384 14.6805 28.9289 15.0711C29.3195 15.4616 29.9526 15.4616 30.3431 15.0711L36.7071 8.70711ZM0 9H36V7H0V9Z" fill="#E1E7ED"/>
                </svg>
                <div class="task-item__user task-item__user--assigned" v-if="task.assigned">
                    <img :src="task.assigned.avatar">{{task.assigned.display_name}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TaskItem',
    props: ['task'],
    methods: {
        edit(){
            this.$emit('edit-task', this.task);
        },
        changeStatus(status){
            if(!confirm('Изменить статус?')) return false;

            this.$ajax.get('/vue/set-task-status', {taskId: this.task.id, status: status}).then((response)=>{
                this.$emit('update-task', response.task);
            })
        }
    }
}
</script>

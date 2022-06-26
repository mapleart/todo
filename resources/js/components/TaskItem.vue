<template>
    <div class="task-item">
        <div class="task-item__body">
            <div class="task-item__header">
                <div class="task-item__header__title">
                    {{task.title}}
                </div>

                <b-dropdown aria-role="list">
                    <template #trigger="{ active }">
                        <div class="task-item__header__status">
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
        </div>
    </div>
</template>

<script>
export default {
    name: 'TaskItem',
    props: ['task'],
    methods: {
        changeStatus(status){
            if(!confirm('Изменить статус?')) return false;

            this.$ajax.get('/vue/set-task-status', {taskId: this.task.id, status: status}).then((response)=>{
                this.$emit('update-task', response.task);
            })
        }
    }
}
</script>

<template>
    <div>
        <div class="tt tt--layout">
            <h2 class="tt__text">Просмотр задачи</h2>
        </div>

        <Task :task="task"></Task>
    </div>
</template>

<script>
import Task from '../components/TaskItem'
import BeforePageMixin from '../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/view-task',
    params: (router) => {
        return {
            taskId: router.taskId
        }
    },
});


export default {
    name: 'TaskView',
    mixins: [mix],
    data() {
        return {
            task: null,
        }
    },
    methods: {
        pageSuccess(response){
            this.task = response.task;
        },
    },
    components: {
        Task
    }
}
</script>

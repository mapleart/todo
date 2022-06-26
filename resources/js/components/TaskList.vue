<template>
    <div class="task-list">
        <TaskItem v-for="task in tasks" :key="task.id" :task="task" @update-task="updateTask"></TaskItem>
    </div>
</template>

<script>
import Vue from 'vue';
import TaskItem from './TaskItem';
export default {
    name: 'TaskList',
    props: {
        'pageType': {},
        'filterUser': {},
        'filterDate': {}
    },
    data(){
        return {
            loading: false,
            page: 1,
            tasks: [],
        }
    },
    mounted() {
        this.fetchList()
    },
    watch:{
        filterUser(value){
            this.page = 1;
            this.items = [];
            this.fetchList()
        },
        filterDate(value){
            this.page = 1;
            this.items = [];
            this.fetchList()
        }
    },
    methods: {
        fetchList(){
            if(this.loading) return;
            this.loading = true;
            console.log('change filter');

            this.$ajax.get('/vue/fetch-task', { filterUser: this.filterUser, filterDate: this.filterDate, pageType: this.pageType }).then((response)=>{
                this.loading = false;
                this.tasks = response.tasks;
            }, ()=>{
                this.loading = false;
            });

        },
        updateTask(props){
            let taskId = props.id,
                ids = this.tasks.map(item => item.id),
                key = ids.indexOf(taskId);

            if (key !== -1) {
                for (let prop in props) {
                    Vue.set(this.tasks[key], prop, props[prop])
                }
            }
        }
    },
    components: {
        TaskItem
    }
}
</script>

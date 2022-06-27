<template>
    <div>

        <b-modal
            v-model="editActive"
            has-modal-card
            trap-focus
            :destroy-on-hide="false"
            aria-role="dialog"
            aria-label="Edit task"
            close-button-aria-label="Close"
            @close="taskEdited = null"
            aria-modal>
            <div class="modal-card">

                <header class="modal-card-head">
                    <p class="modal-card-title">Редактирование задачи</p>
                    <button
                        type="button"
                        class="delete"
                        @click="editActive=false; taskEdited=null;"/>
                </header>
                <div class="modal-card-body">
                    <EditTaskForm v-if="taskEdited" :task-id="taskEdited.id" @close="editActive=false; taskEdited=null;"></EditTaskForm>
                </div>
            </div>
        </b-modal>


        <div class="task-list">
            <TaskItem v-for="task in tasks" :key="task.id" :task="task" @update-task="updateTask" @edit-task="editTask"></TaskItem>
        </div>
        <b-notification type="is-info is-light" aria-close-label="Закрыть" v-if="!tasks.length">
            Задач нет
        </b-notification>
    </div>

</template>

<script>
import Vue from 'vue';
import TaskItem from './TaskItem';
import EditTaskForm from './EditTaskForm';


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
            editActive: false,
            taskEdited: null,
        }
    },
    mounted() {
        this.fetchList()
    },
    watch:{
        filterUser(value){
            this.page = 1;
            this.tasks = [];
            this.fetchList()
        },
        filterDate(value){
            this.page = 1;
            this.tasks = [];
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

        editTask(task){
            this.taskEdited = task;
            this.editActive = true;

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
        TaskItem, EditTaskForm
    }
}
</script>

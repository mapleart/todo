<template>
    <div>
        <AddTaskModal ref="addModal" @created="updateList"></AddTaskModal>
        <div class="tt tt--layout">
            <h2 class="tt__text">Задачи подчиненных</h2>
            <a href="/create-task" @click.prevent="openModal" class="button is-success is-rounded">+ Задачу</a>
        </div>

        <div class="nv">
            <a href="#" class="nv__link" :class="{'active': tab == 'all'}" @click="switchTab('all')">Все</a>

            <a href="#" class="nv__link" v-for="staff in staffs" :class="{'active': tab == staff.id }" @click="switchTab(staff.id)">{{staff.display_name}}</a>
        </div>

        <TaskList :filter-user="tab" filter-date="all" page-type="staff" ref="list"></TaskList>
    </div>
</template>

<script>
import TaskList from '../components/TaskList'
import AddTaskModal from '../components/AddTaskModal'
import BeforePageMixin from '../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/home-staff'
});


export default {
    name: 'Home',
    mixins: [mix],
    data() {
        return {
            tab: 'all',
            staffs: [],
        }
    },
    methods: {
        pageSuccess(response){
            this.staffs = response.staffs;
        },
        switchTab(tab){
            if(tab == this.tab) return;
            this.tab = tab;
        },
        openModal(){
            this.$refs.addModal.openModal();
        },
        updateList(){
            this.$refs.list.updateList()
        }
    },
    components: {
        TaskList, AddTaskModal
    }
}
</script>

<template>
    <div>
        <AddTaskModal ref="addModal" @created="updateList"></AddTaskModal>
        <div class="tt tt--layout">
            <h2 class="tt__text">Задания</h2>
            <a href="/create-task" @click.prevent="openModal" class="button is-success is-rounded">+ Задачу</a>
        </div>

        <div class="nv">
            <a href="#" class="nv__link" :class="{'active': tab == 'today'}" @click="switchTab('today')">На сегодня</a>
            <a href="#" class="nv__link" :class="{'active': tab == 'week'}" @click="switchTab('week')">На неделю</a>
            <a href="#" class="nv__link" :class="{'active': tab == 'all'}" @click="switchTab('all')">на будущее</a>
        </div>

        <TaskList filter-user="all" :filter-date="tab" page-type="my" ref="list"></TaskList>
    </div>
</template>

<script>
import TaskList from '../components/TaskList'
import BeforePageMixin from '../mixins/before-page-mix'
import AddTaskModal from '../components/AddTaskModal'
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

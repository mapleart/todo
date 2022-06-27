<template>
    <form class="fm" @submit.prevent="onSubmitEdit"  >
        <b-field label="Название задачи">
            <b-input v-model="form.title" placeholder="Заголовок" type="text" required :loading="updatingTask" :disabled="updatingTask || !allowEdit"></b-input>
        </b-field>



        <b-field label="Описание задачи">
            <b-input v-model="form.description" placeholder="Описание задачи" type="textarea" required minlength="10" :loading="updatingTask" :disabled="updatingTask  || !allowEdit"></b-input>
        </b-field>


        <b-field label="Выберите дату завершения задания">
            <b-datepicker
                :loading="updatingTask"
                :disabled="updatingTask || !allowEdit"
                placeholder="Дата завершения"
                :min-date="minDate"
                v-model="form.date_end"
                required
                editable>
            </b-datepicker>
        </b-field>


        <b-field label="Выберите ответственного" v-if="$user.role == 'head'">
            <b-select placeholder="Ответственный" expanded v-model="form.assigned_id" :loading="updatingTask" :disabled="updatingTask || !allowEdit">
                <option value="0">Выберите ответственного</option>
                <option v-for="staff in staffs" :value="staff.id">{{ staff.display_name }}</option>
            </b-select>
        </b-field>



        <b-field label="Приоретет">
            <b-slider :custom-formatter="hardFormatter" :step="1" :min="0" :max="2" :type="sliderColor" v-model="form.priority" :loading="updatingTask" :disabled="updatingTask || !allowEdit">
                <b-slider-tick :value="0">Низкий</b-slider-tick>
                <b-slider-tick :value="1">Средний</b-slider-tick>
                <b-slider-tick :value="2">Высокий</b-slider-tick>
            </b-slider>
        </b-field>

        <br>
        <br>
        <b-button native-type="submit" :loading="updatingTask" :datafld="updatingTask" type="is-primary">Сохранить</b-button>


    </form>
</template>

<script>
const defaultForm = {
    title: '',
    description: '',
    date_end: null,
    assigned_id: 0,
    priority: 0,
}

export default {
    name: 'EditTaskForm',
    props: ['taskId'],
    data(){
        const today = new Date()

        return {
            allowEdit: false,
            allowEditStatus: false,
            staffs: [],
            updatingTask: false,
            editTask: null,
            form: defaultForm,
            minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
        }
    },
    mounted() {

        const loadingComponent = this.$buefy.loading.open({
            container: this.$el
        })

        this.$ajax.get('/vue/get-edit-data', {taskId: this.taskId}).then((response)=>{
            this.staffs = response.staffs;
            let task = response.task;
            this.allowEdit = task.allow_edit;
            this.allowEditStatus = task.allow_edit_status;
            this.form.title = task.title;
            this.form.description = task.description;
            this.form.assigned_id = task.assigned_id;
            this.form.priority = task.priority;
            this.form.date_end = new Date(task.time_end * 1000);
            this.minDate = new Date(task.time_end * 1000);
             loadingComponent.close()
        }, ()=>{

            this.$emit('close')
            loadingComponent.close()
        })

    },

    computed: {
        sliderColor(){
            let colors = ['is-success', 'is-primary', 'is-danger']
            return colors[this.form.priority];
        }
    },
    methods: {
        hardFormatter(value){
            let labs = ['Низкий', 'Средний', 'Высокий' ]
            return labs[value];
        },
        onSubmitEdit(props){

        },
    }
}
</script>

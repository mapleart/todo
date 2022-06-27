<template>

    <b-modal
        v-model="modalActive"
        has-modal-card
        trap-focus
        :destroy-on-hide="false"
        aria-role="dialog"
        aria-label="Edit task"
        close-button-aria-label="Close"
        @close="closeModal"
        aria-modal>
        <div class="modal-card">

            <header class="modal-card-head">
                <p class="modal-card-title">Создание задачи</p>
                <button
                    type="button"
                    class="delete"
                    @click="modalActive=false; taskEdited=null;"/>
            </header>
            <div class="modal-card-body">
                <div>
                    <form class="fm" @submit.prevent="onSubmit">
                        <b-field label="Название задачи">
                            <b-input v-model="form.title" placeholder="Заголовок" type="text" required :loading="loading" :disabled="loading"></b-input>
                        </b-field>

                        <b-field label="Описание задачи">
                            <b-input v-model="form.description" placeholder="Описание задачи" type="textarea" required minlength="10" :loading="loading" :disabled="loading"></b-input>
                        </b-field>


                        <b-field label="Выберите дату завершения задания">
                            <b-datepicker
                                :loading="loading"
                                :disabled="loading"
                                placeholder="Дата завершения"
                                :min-date="minDate"
                                v-model="form.date_end"
                                required
                                editable>
                            </b-datepicker>
                        </b-field>


                        <b-field label="Выберите ответственного" v-if="$user.role == 'head'">
                            <b-select placeholder="Ответственный" expanded v-model="form.assigned_id" :loading="loading" :disabled="loading">
                                <option value="0">Выберите ответственного</option>
                                <option v-for="staff in staffs" :value="staff.id">{{ staff.display_name }}</option>
                            </b-select>
                        </b-field>



                        <b-field label="Приоретет">
                            <b-slider :custom-formatter="hardFormatter" :step="1" :min="0" :max="2" :type="sliderColor" v-model="form.priority" :loading="loading" :disabled="loading">
                                <b-slider-tick :value="0">Низкий</b-slider-tick>
                                <b-slider-tick :value="1">Средний</b-slider-tick>
                                <b-slider-tick :value="2">Высокий</b-slider-tick>
                            </b-slider>
                        </b-field>

                        <b-button native-type="submit" :loading="loading" :datafld="loading" type="is-primary">Сохранить</b-button>
                    </form>
                </div>
            </div>
        </div>
    </b-modal>
</template>

<script>


const defaultForm = {
    title: '',
    description: '',
    date_end: null,
    assigned_id: 0,
    priority: 0,
}

import BeforePageMixin from '../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/create-task'
});

export default {
    name: 'CreateTaskForm',
    mixins: [mix],
    data() {
        const today = new Date()

        return {
            modalActive: false,
            loading: false,
            minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            form: {...defaultForm},
            staffs: [],
        }
    },
    computed: {
        sliderColor(){
            let colors = ['is-success', 'is-primary', 'is-danger']
            return colors[this.form.priority];
        }
    },
    methods: {
        openModal(){
            this.form = {...defaultForm};
            this.$ajax.get('/vue/create-task', {taskId: this.taskId}).then((response)=>{
                this.staffs = response.staffs;
                this.modalActive = true;
            }, ()=>{
                this.$emit('close')
            })
        },

        hardFormatter(value){
            let labs = ['Низкий', 'Средний', 'Высокий' ]
            return labs[value];
        },
        onSubmit() {
            let data = { ...this.form };
            data.date_end = data.date_end ? data.date_end.yyyymmdd() : '';
            // this.loading = true;
            this.$ajax.post('/vue/create-task-submit', data).then(()=>{
                this.form = {...defaultForm};
                this.$emit('created')
                this.modalActive = false
            }, ()=>{

            });
        },
        closeModal(){
            this.form = {...defaultForm};
        }
    }
}
</script>

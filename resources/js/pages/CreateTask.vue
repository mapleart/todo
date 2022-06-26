<template>
    <div>
        <div class="tt tt--layout">
            <h2 class="tt__text">Создание нового задания</h2>
            <router-link to="/" class="button is-light is-rounded">Назад</router-link></div>


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

            <br>
            <br>
            <b-button native-type="submit" :loading="loading" :datafld="loading" type="is-primary">Сохранить</b-button>


        </form>


    </div>
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
    name: 'CreateTask',
    mixins: [mix],
    data() {
        const today = new Date()

        return {
            loading: false,
            minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            form: defaultForm,
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
        pageSuccess(response) {
            this.staffs = response.staffs;
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
                this.$router.push('/')
            }, ()=>{

            });
        }
    }
}
</script>

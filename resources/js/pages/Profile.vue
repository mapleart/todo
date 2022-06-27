<template>
    <div class="page-profile" v-if="user">

        <div class="p-panel">
            <img :src="user.avatar" class="p-panel__avatar">
            <div class="p-panel__body">
                <h1 class="p-panel__title">
                    Привет, {{ user.name }}!
                </h1>
                <div class="p-panel__subtitle">
                    Надеюсь, у вас отличное настроение поработать над задачами!
                </div>
            </div>
        </div>

        <section class="info-tiles mb-5">
            <div class="tile is-ancestor has-text-centered">

                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title" :class="{'text-color--red': counters.hot > 0}">{{counters.hot}}</p>
                        <p class="subtitle" :class="{'text-color--red': counters.hot > 0}">Горят сроки</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{counters.active}}</p>
                        <p class="subtitle">Активных задач</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{counters.end}}</p>
                        <p class="subtitle">Завершенных задач</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{counters.staffs}}</p>
                        <p class="subtitle">Подчиненных</p>
                    </article>
                </div>
            </div>
        </section>

        <div class="columns">
            <div class="column is-6">
                <div class="card events-card">
                    <header class="card-header">
                        <p class="card-header-title">
                             Последние завершенные задачи
                        </p>
                    </header>
                    <div class="card-table">
                        <div class="content">
                            <table class="table is-fullwidth is-striped" v-if="endTasks && endTasks.length > 0">
                                <tbody>
                                <tr v-for="task in endTasks">
                                    <td><router-link :to="'/task/'+task.id">{{ task.title}}</router-link></td>
                                </tr>
                                </tbody>
                            </table>

                            <b-notification type="is-info is-light" :closable="false" aria-close-label="Закрыть" v-else>
                                Пока завершеных задач нет
                            </b-notification>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Профиль
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <form @submit.prevent="saveForm">
                                <b-field label="Имя" :type="getTypeInput('name')" :message="getTypeMessage('name')">
                                    <b-input v-model="form.name" >></b-input>
                                </b-field>
                                <b-field label="Фамилия" :type="getTypeInput('surname')" :message="getTypeMessage('surname')">
                                    <b-input v-model="form.surname"></b-input>
                                </b-field>
                                <b-field label="Отчество" :type="getTypeInput('middle_name')" :message="getTypeMessage('middle_name')">
                                    <b-input v-model="form.middle_name"></b-input>
                                </b-field>

                                <b-button native-type="submit" :loading="isSaving" :datafld="isSaving" type="is-primary">Сохранить</b-button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import BeforePageMixin from '../mixins/before-page-mix'
let mix = BeforePageMixin({
    endPoint: '/vue/profile'
});


export default {
    name: 'Profile',
    mixins: [mix],
    data() {
        return {
            user: null,
            endTasks: [],
            isSaving: false,
            counters: {},
            form: {
                name: '',
                surname: '',
                middle_name: ''
            },
            formErrors: {},
        }
    },
    methods: {
        pageSuccess(response) {
            this.user = response.user;
            this.endTasks = response.endTasks || [];
            this.counters = response.counters;
            this.form.name = this.user.name;
            this.form.surname = this.user.surname;
            this.form.middle_name = this.user.middle_name;
        },

        saveForm(){
            if(this.isSaving) return false;
            this.isSaving = true;

            this.$ajax.post('/vue/save-profile', this.form).then((response)=>{
                if(response.formErrors && response.formErrors.length > 0) {
                    this.formErrors = response.formErrors;
                } else {
                    this.user = response.user;
                }
                this.isSaving = false;
            });

        },
        getTypeInput(input){
            if(this.formErrors[input]) {
                return 'is-danger';
            }
         },
        getTypeMessage(input){
            if(this.formErrors[input] && this.formErrors[input].length ) {
                return this.formErrors[input][0];
            }
        }
    }
}
</script>

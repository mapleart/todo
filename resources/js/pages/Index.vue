<template>
    <div>
        <div class="profile__search">
            <input type="text" class="profile__search__input" v-model="searchAddress" @change="changeAddress" placeholder="Wallet"></input>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DashboardPage',
    data() {
        return {
            searchAddress: '',
        }
    },
    methods: {
        changeAddress(){
            this.$router.push('/profile/'+this.searchAddress)
        }
    }
}
</script>

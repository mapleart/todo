<template>
    <div class="app">
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-brand">
                    <router-link to="/" class="navbar-item" href="/">
                        <svg width="202" height="202" viewBox="0 0 202 202" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: auto; height: 65px;">
                            <rect x="34.6627" y="57.4558" width="116" height="40" rx="20" transform="rotate(45 34.6627 57.4558)" fill="#B3DCFF"/>
                            <rect x="128.259" y="72.5561" width="40" height="40" rx="20" transform="rotate(45 128.259 72.5561)" fill="#0672CC"/>
                            <rect x="167.38" y="35" width="40" height="40" rx="20" transform="rotate(45 167.38 35)" fill="#0672CC"/>
                        </svg>
                    </router-link>

                    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarSite">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>


                <div id="navbarSite" class="navbar-menu">
                    <div class="navbar-start">
                        <router-link to="/" class="navbar-item">
                            Мои задачи
                        </router-link>

                        <router-link to="/staff" class="navbar-item" v-if="$user.role =='head'">
                            Задачи подчиненных
                        </router-link>
                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item">


                            <form id="logout-form" action="/logout" method="POST" class="d-none">
                                <input type="hidden" name="_token" :value="$token">
                            </form>


                            <div class="navbar-item has-dropdown is-hoverable">
                                <router-link to="/profile" class="navbar-link">
                                    <span>{{ $user.name }}</span>
                                </router-link>

                                <div class="navbar-dropdown">

                                    <router-link to="/profile" class="navbar-item">
                                        Профиль
                                    </router-link>
                                    <router-link class="navbar-item" to="/admin" v-if="$user.is_admin">
                                        Админпанель
                                    </router-link>

                                    <hr class="navbar-divider">
                                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="navbar-item">
                                        Выход
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container pt-5" >
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App',
    data: () => ({

    }),
}
</script>

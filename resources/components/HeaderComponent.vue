<template>
    <header>
        <div class="navbar navbar-custom">
            <div class="container-fluid col-11 mx-auto">
                <a class="brand" href="" @click.prevent="changePage('RecipesPage')">
                    <h4>Кулинарная книга</h4>
                </a>

                <button class="menu-btn" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    Меню
                </button>
                
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                    aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Меню</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div v-if="user == true">
                            <div class="actions vertical">
                                <a href="" class="menu-link" @click.prevent="changePage('RecipesPage')">Главная</a>
                                <div v-if="isAdmin">
                                    <a href="" class="menu-link" @click.prevent="changePage('RecipesControlPage')">Рецепты и категории</a>
                                    </div>
                                    <div v-if="isAdmin">
                                        <a href="" class="menu-link" @click.prevent="changePage('UsersControlPage')">Пользователи</a>
                                    </div>
                                <div v-else>
                                    <a href="" class="menu-link" @click.prevent="changePage('ProfilePage')">Личный кабинет</a>
                                </div>
                                <a href="" class="menu-link logout-btn" @click.prevent="logout">Выход</a>
                            </div>
                        </div>
                        <div v-else>
                            <LoginComponent :datasend="datasend" :changeToken="changeToken" />
                            <RegisterComponent :datasend="datasend" :changeToken="changeToken" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import LoginComponent from './LoginComponent.vue';
import RegisterComponent from './RegisterComponent.vue';
export default {
    name: 'HeaderComponent',
    data() {
        return {};
    },
    mounted() { },
    props: ['datasend', 'changeToken', 'user', 'isAdmin', 'logout', 'changePage'],
    methods: {},
    components: { LoginComponent, RegisterComponent },
};
</script>
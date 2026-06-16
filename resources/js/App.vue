<template>
    <HeaderComponent :datasend="datasend" :changeToken="changeToken" :logout="logout" :user="user" :isAdmin="isAdmin"
        :changePage="changePage" />
    <NotificationComponent :changeToken="changeToken" :logout="logout" />
    <template v-if="isLoad">
        <RecipesPage v-if="page == 'RecipesPage'" :changePage="changePage" :datasend="datasend" :user="user"
            :isAdmin="isAdmin" :storage="storage" :difficulty="difficulty" :userInfo="userInfo" />
        <SingleRecipePage v-if="page == 'SingleRecipePage'" :changePage="changePage" :datasend="datasend" :user="user"
            :pageId="pageId" :isAdmin="isAdmin" :storage="storage" :difficulty="difficulty" :userInfo="userInfo" />
        <EditSingleRecipe v-if="page == 'EditSingleRecipe'" :changePage="changePage" :datasend="datasend" :user="user"
            :pageId="pageId" :isAdmin="isAdmin" :storage="storage" :difficulty="difficulty" />
        <UsersControlPage v-if="page == 'UsersControlPage' && isAdmin" :changePage="changePage" :datasend="datasend" />
        <ProfilePage v-if="page == 'ProfilePage' && user && !isAdmin" :changePage="changePage" :datasend="datasend"
            :storage="storage" :difficulty="difficulty" />

        <RecipesControlPage v-if="page == 'RecipesControlPage' && isAdmin" :changePage="changePage" :datasend="datasend"
            :difficulty="difficulty" />
        <IngredientPage v-if="page == 'IngredientPage' && isAdmin" :changePage="changePage" :datasend="datasend"
            :difficulty="difficulty" />
        <AddCategoryPage v-if="page == 'AddCategoryPage'" :datasend="datasend" :changePage="changePage"
            :pageId="pageId" />
        <AddRecipePage v-if="page == 'AddRecipePage'" :datasend="datasend" :changePage="changePage" :pageId="pageId"
            :getUser="getUser" />
    </template>
</template>

<script>
import HeaderComponent from '../components/HeaderComponent.vue';
import NotificationComponent from '../components/NotificationComponent.vue';
import AddCategoryPage from './pages/AddCategoryPage.vue';
import AddRecipePage from './pages/AddRecipePage.vue';
import EditSingleRecipe from './pages/EditSingleRecipe.vue';
import ProfilePage from './pages/ProfilePage.vue';
import RecipesControlPage from './pages/RecipesControlPage.vue';
import RecipesPage from './pages/RecipesPage.vue';
import IngredientPage from './pages/IngredientPage.vue';
import SingleRecipePage from './pages/SingleRecipePage.vue';
import UsersControlPage from './pages/UsersControlPage.vue';
export default {
    name: 'App',
    data() {
        return {
            API: 'http://127.0.0.1:8000/api/',
            storage: 'http://127.0.0.1:8000/storage/',
            user: false,
            isAdmin: false,
            isLoad: false,
            userInfo: {},
            page: localStorage.getItem('page') || 'RecipesPage',
            pageId: localStorage.getItem('pageId') || null,
            difficulty: {
                easy: 'Легко',
                medium: 'Средне',
                hard: 'Сложно',
            },
        };
    },
    mounted() {
        if (localStorage.getItem('token')) {
            this.getUser();
        } else {
            this.isLoad = true;
        }
    },
    methods: {
        changeToken(token) {
            localStorage.setItem('token', token);
            this.user = true;
            this.getUser();
        },
        getUser() {
            this.datasend('user').then((response) => {
                this.user = true;
                this.userInfo = response;
                if (response.role == 'admin') {
                    this.isAdmin = true;
                }
                this.isLoad = true;
            });
        },
        changePage(page, pageId = null) {
            this.page = page;
            this.pageId = pageId;
            localStorage.setItem('page', page);
            localStorage.setItem('pageId', pageId);
        },
        logout() {
            localStorage.removeItem('token');
            this.user = false;
            this.isAdmin = false;
            this.changePage('RecipesPage');
            console.log('Вышел');
        },

        async datasend(route, method = 'GET', formdata = null, userid = null) {
            let myHeaders = new Headers();
            myHeaders.append('Accept', 'application/json');

            if (userid) {
                myHeaders.append('userid', userid);
            }
            if (localStorage.getItem('token')) {
                myHeaders.append('Authorization', 'Bearer ' + localStorage.getItem('token'));
            }
            let requestOptions = {
                method: method,
                headers: myHeaders,
                redirect: 'follow',
            };
            if (method != 'GET') {
                requestOptions.body = formdata;
            }
            return await fetch(this.API + route, requestOptions).then((response) => {
                if (response.status == 401) {
                    this.logout();
                    return;
                }
                return response.json();
            });
        },
    },
    components: {
        HeaderComponent,
        RecipesPage,
        SingleRecipePage,
        NotificationComponent,
        ProfilePage,
        UsersControlPage,
        RecipesControlPage,
        AddRecipePage,
        AddCategoryPage,
        EditSingleRecipe,
        IngredientPage,
    },
};
</script>

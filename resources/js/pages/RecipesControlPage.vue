<template>
    <div class="admin-panel">
        <h1 class="page-title">Управление рецептами и категориями</h1>
        
        <div class="admin-actions">
            <button class="action-btn primary" @click.prevent="changePage('AddRecipePage')">+ Добавление рецепта</button>
            <button class="action-btn primary" @click.prevent="changePage('AddCategoryPage')">+ Добавление категории</button>
        </div>

        <RecipesComponent :changePage="changePage" :datasend="datasend" :difficulty="difficulty" />
        <CategoriesComponent :changePage="changePage" :datasend="datasend" />
    </div>
</template>

<script>
import CategoriesComponent from '../../components/CategoriesComponent.vue';
import RecipesComponent from '../../components/RecipesComponent.vue';
export default {
    name: 'RecipesControlPage',
    props: ['changePage', 'datasend', 'difficulty'],
    data() {
        return {};
    },
    mounted() {
        localStorage.removeItem('categoryId');
        localStorage.removeItem('recipeId');
    },
    components: { CategoriesComponent, RecipesComponent },
};
</script>
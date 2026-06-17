<template>
    <div class="admin-section">
        <h2 class="section-title">Рецепты</h2>
        <div class="notification-message" v-if="notif">{{ notif }}</div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Время приготовления</th>
                        <th>Сложность</th>
                        <th colspan="2">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="recipe in recipes" :key="recipe.id">
                        <td>{{ recipe.id }}</td>
                        <td>
                            <a href="" @click.prevent="changePage('SingleRecipePage', recipe.id)">
                                <img :src="'storage/' + recipe.image_url" class="table-img" alt="" />
                            </a>
                        </td>
                        <td>{{ recipe.title }}</td>
                        <td>{{ recipe.category?.name }}</td>
                        <td>{{ recipe.cook_time + ' минут' }}</td>
                        <td>{{ difficulty[recipe.difficulty] }}</td>
                        <td class="actions-cell">
                            <button class="edit-btn" @click.prevent="changePage('AddRecipePage', recipe.id)">Редактировать</button>
                        </td>
                        <td class="actions-cell">
                            <button class="delete-btn" @click.prevent="deleteRec(recipe)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RecipesComponent',
    props: ['changePage', 'datasend', 'difficulty'],
    data() {
        return {
            recipes: {},
            notif: null,
        };
    },
    mounted() {
        this.getRecipes();
    },
    methods: {
        getRecipes() {
            this.datasend('recipes', 'POST').then((result) => {
                this.recipes = result.recipes;
            });
        },
        deleteRec(rec) {
            if (confirm('Удалить?')) {
                this.datasend('DeleteRecipe/' + rec.id, 'DELETE').then((result) => {
                    this.notif = result.Recipe;
                    setTimeout(() => { this.notif = null; }, 3000);
                    this.getRecipes();
                });
            }
        },
    },
};
</script>
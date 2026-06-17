<template>
    <h1 class="page-title">Рецепты</h1>

    <div class="filters-section">
        <div class="search-block">
            <h5>Найти рецепт</h5>
            <input type="search" class="search-input" v-model="search" />
            <button class="search-btn" type="submit" @click="getRecipes">Поиск</button>
        </div>

        <div class="categories-block">
            <h6>Категории</h6>
            <div class="categories-list">
                <div v-for="cat in categories" :key="cat.id" class="category-check">
                    <input type="checkbox" :id="cat.id" :value="cat.id" v-model="cat_filter" @change="getRecipes" />
                    <label :for="cat.id">{{ cat.name }}</label>
                </div>
            </div>
        </div>

        <div class="sort-block">
            <h5>Сортировать</h5>
            <select class="sort-select" v-model="sort" @change="getRecipes">
                <option value="title|asc">Название (А-Я)</option>
                <option value="title|desc">Название (Я-А)</option>
                <option value="cook_time|asc">Время (по возрастанию)</option>
                <option value="cook_time|desc">Время (по убыванию)</option>
                <option value="difficulty|asc">Сложность (легче → сложнее)</option>
                <option value="difficulty|desc">Сложность (сложнее → легче)</option>
            </select>
        </div>

        <div class="time-filter">
            <h5>Время приготовления</h5>
            <div class="time-options">
                <label class="time-option">
                    <input type="radio" v-model="cook_time" value="0" @change="getRecipes" />
                    <span>Любое</span>
                </label>
                <label class="time-option">
                    <input type="radio" v-model="cook_time" value="1" @change="getRecipes" />
                    <span>До 30 минут</span>
                </label>
                <label class="time-option">
                    <input type="radio" v-model="cook_time" value="2" @change="getRecipes" />
                    <span>30-60 минут</span>
                </label>
                <label class="time-option">
                    <input type="radio" v-model="cook_time" value="3" @change="getRecipes" />
                    <span>Более 60 минут</span>
                </label>
            </div>
        </div>
    </div>

    <div class="recipes-grid">
        <div class="recipe-card" v-for="recipe in recipes" :key="recipe.id">
            <a href="" @click.prevent="changePage('SingleRecipePage', recipe.id)">
                <img :src="storage + recipe.image_url" class="recipe-img" alt="" />
            </a>
            <div class="recipe-info">
                <h4 class="recipe-title">{{ recipe.title }}</h4>
                <p class="recipe-category">Категория: {{ recipe.category.name }}</p>
                <p class="recipe-difficulty">Сложность: {{ difficulty[recipe.difficulty] }}</p>
                <p class="recipe-time">Время приготовления: {{ recipe.cook_time }} минут</p>
            </div>
            <div v-if="user && !isAdmin" class="recipe-footer">
                <button class="fave-btn" @click.prevent="toggleFave(recipe.id)">
                    {{ faves?.indexOf && faves.indexOf(recipe.id) !== -1 ? 'Удалить из избранного' : 'В избранное' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'RecipesPage',
    data() {
        return {
            recipes: {},
            categories: [],
            faves: [],
            sort: 'title|asc',
            cook_time: null,
            search: null,
            cat_filter: [],
        };
    },
    props: ['datasend', 'changePage', 'user', 'isAdmin', 'storage', 'userInfo', 'difficulty'],
    mounted() {
        this.getRecipes();
    },
    methods: {
        toggleFave(recipe) {
            this.datasend('FaveRec/' + recipe, 'POST').then((result) => {
                this.faves = result;
            });
        },
        getRecipes() {
            let formdata = new FormData();
            formdata.append('sort', this.sort);
            if (this.cook_time) {
                formdata.append('cook_time', this.cook_time);
            }
            if (this.search) {
                formdata.append('search', this.search);
            }
            if (this.cat_filter) {
                formdata.append('cat_filter', JSON.stringify(this.cat_filter));
            }
            this.datasend('recipes', 'POST', formdata, this.userInfo.id).then((result) => {
                this.recipes = result.recipes;
                this.faves = result.faves;
                this.categories = result.categories;
            });
        },
    },
    components: {},
};
</script>
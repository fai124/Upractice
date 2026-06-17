<template>
    <div class="recipe-detail" v-if="recipe && recipe.id">
        <div class="recipe-header">
            <img :src="storage + recipe.image_url" v-if="recipe.image_url" class="detail-img" alt="" />
            <div class="detail-info">
                <h2>{{ recipe.title }}</h2>
                <p>Категория: {{ recipe.category.name }}</p>
                <p>Время приготовления: {{ recipe.cook_time + ' минут' }}</p>
                <p>Сложность: {{ difficulty[recipe.difficulty] }}</p>
                <p class="description">{{ recipe.description }}</p>
                <div v-if="user && !isAdmin">
                    <button class="fave-detail-btn" @click="toggleFave(recipe.id)">
                        {{ faves.indexOf(recipe.id) != -1 ? 'Удалить из избранного' : 'В избранное' }}
                    </button>
                </div>
                <div v-if="isAdmin">
                    <button class="edit-steps-btn" @click="changePage('EditSingleRecipe', recipe.id)">
                        Редактировать шаги и ингредиенты
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="recipe && recipe.ingredients && recipe.ingredients.length > 0" class="ingredients-section">
        <h3>Ингредиенты:</h3>
        <table class="ingredients-table">
            <thead>
                <tr><th>№</th><th>Ингредиент</th><th>Количество</th></tr>
            </thead>
            <tbody>
                <tr v-for="(ing, key) in recipe.ingredients" :key="key">
                    <td>{{ key + 1 }}</td>
                    <td>{{ ingredients[ing.ingredient_id]?.name }}</td>
                    <td>{{ ing.quantity }} {{ ingredients[ing.ingredient_id]?.unit }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="recipe && recipe.steps && recipe.steps.length > 0" class="steps-section">
        <h3>Шаги приготовления:</h3>
        <div class="step-card">
            <div class="step-header">
                <h5>Шаг {{ step + 1 }}</h5>
            </div>
            <div class="step-body">
                <p>{{ recipe.steps[step]?.description }}</p>
                <img :src="storage + recipe.steps[step]?.image_url" v-if="recipe.steps[step]?.image_url" class="step-img" alt="" />
            </div>
        </div>
        <div class="step-pagination">
            <button v-for="(st, num) in recipe.steps" :key="num" 
                :class="['step-page', { active: num == step }]"
                @click.prevent="changeStep(num)">
                {{ num + 1 }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SingleRecipePage',
    data() {
        return {
            recipe: null,
            step: 0,
            ingredients: {},
            faves: [],
        };
    },
    props: ['datasend', 'changePage', 'user', 'pageId', 'isAdmin', 'storage', 'difficulty', 'userInfo'],
    mounted() {
        this.getRec();
    },
    methods: {
        toggleFave(recipe) {
            this.datasend('FaveRec/' + recipe).then((result) => {
                this.faves = result;
            });
        },
        getRec() {
            this.datasend((this.user ? 'SingleRecipeAuth/' : 'SingleRecipe/') + this.pageId, 'GET', null, this.userInfo.id).then((result) => {
                this.recipe = result.recipe;
                this.step = result.step;
                this.ingredients = result.ingredients;
                this.faves = result.faves;
                if (!this.user) {
                    let stepSave = localStorage.getItem('step') ? JSON.parse(localStorage.getItem('step')) : 0;
                    if (stepSave && stepSave.page == this.pageId) {
                        this.step = stepSave.step;
                    }
                }
            });
        },
        changeStep(step) {
            if (this.user) {
                let formdata = new FormData();
                formdata.append('step_number', step);
                this.datasend('changeStep/' + this.pageId, 'POST', formdata).then(() => {
                    this.step = step;
                });
            }
            this.step = step;
            localStorage.setItem('step', JSON.stringify({ page: this.pageId, step: step }));
        },
    },
    components: {},
};
</script>
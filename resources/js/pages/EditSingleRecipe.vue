<template>
    <div class="edit-recipe-page">
        <div class="recipe-preview">
            <div class="preview-card">
                <img :src="storage + recipe.image_url" v-if="recipe.image_url" class="preview-main-img" alt="" />
                <div class="preview-info">
                    <h2>{{ recipe.title }}</h2>
                    <p v-if="recipe.category">Категория: {{ recipe.category.name }}</p>
                    <p>Время приготовления: {{ recipe.cook_time }} минут</p>
                    <p>Сложность: {{ difficulty[recipe.difficulty] }}</p>
                    <p>{{ recipe.description }}</p>
                    <button class="back-btn" @click="changePage('SingleRecipePage', recipe.id)">← Закончить редактирование</button>
                </div>
            </div>
        </div>

        <div class="ingredients-editor">
            <h3>Ингредиенты</h3>
            <button class="add-btn" @click="addIng">+ Добавить ингредиент</button>
            
            <div v-if="recipe.ingredients && recipe.ingredients.length > 0" class="ingredients-table-wrapper">
                <table class="edit-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Ингредиент</th>
                            <th>Количество</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(ing, key) in recipe.ingredients" :key="key">
                            <td>{{ key + 1 }}</td>
                            <td>
                                <select class="form-select-sm" v-model="ing.ingredient_id">
                                    <option v-for="inga in ingRecipe" :key="inga.id" :value="inga.id">{{ inga.name }}</option>
                                </select>
                                <div v-if="errors[key]?.ingredient_id" class="error-small">{{ errors[key].ingredient_id }}</div>
                            </td>
                            <td>
                                <div class="quantity-input">
                                    <input type="number" class="small-input" v-model="ing.quantity" />
                                    <span class="unit-label" v-if="ingRecipe[ing.ingredient_id]?.unit">
                                        {{ ingRecipe[ing.ingredient_id].unit }}
                                    </span>
                                </div>
                                <div v-if="errors[key]?.quantity" class="error-small">{{ errors[key].quantity }}</div>
                            </td>
                            <td>
                                <button class="remove-row-btn" @click="ingRem(key, ing.id)">−</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="message === 'Ингредиент добавлен/изменён'" class="success-message">Ингредиент добавлен/изменён</div>
            <button class="save-btn" @click="saveIngredients">Сохранить ингредиенты</button>
        </div>

        <div class="steps-editor">
            <h3>Шаги приготовления</h3>
            <button class="add-btn" @click="addStep">+ Добавить шаг</button>
            
            <div v-if="recipe.steps && recipe.steps.length > 0">
                <div v-for="(step, key) in recipe.steps" :key="key" class="step-editor-card">
                    <div class="step-header">
                        <h5>Шаг {{ key + 1 }}</h5>
                        <button class="remove-step-btn" @click="remove(step.id, key)">Удалить шаг</button>
                    </div>
                    <div class="step-content">
                        <div class="form-group">
                            <label :for="'desc' + key">Описание</label>
                            <textarea :id="'desc' + key" class="step-textarea" v-model="step.description"></textarea>
                            <div v-if="errors[key]?.description" class="error-small">{{ errors[key].description }}</div>
                        </div>
                        
                        <div class="form-group">
                            <label :for="'file' + key">Изображение для шага</label>
                            <input type="file" :id="'file' + key" class="file-input-small" />
                            <div v-if="errors[key]?.image_url" class="error-small">{{ errors[key].image_url }}</div>
                        </div>
                        
                        <div v-if="step.image_url" class="step-image">
                            <img :src="'storage/' + step.image_url" class="step-preview-img" alt="" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="message === 'Добавился/Изменился шаг'" class="success-message">Добавился/Изменился шаг</div>
            <button class="save-btn" @click="saveSteps">Сохранить шаги</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EditSingleRecipe',
    data() {
        return {
            recipe: {},
            ingRecipe: [],
            errors: [],
            message: null,
        };
    },
    props: ['datasend', 'changePage', 'user', 'pageId', 'isAdmin', 'storage', 'difficulty'],
    mounted() {
        this.getRec();
    },
    methods: {
        getRec() {
            this.datasend('SingleRecipe/' + this.pageId).then((result) => {
                this.ingRecipe = result.ingredients;
                this.recipe = result.recipe;
                this.errors = [];
            });
        },
        addIng() {
            if (!this.recipe.ingredients) this.recipe.ingredients = [];
            this.recipe.ingredients.push({ quantity: null, ingredient_id: null });
        },
        ingRem(key, id = null) {
            if (confirm('Хотите удалить?')) {
                if (id) {
                    this.datasend('ingRem/' + id, 'DELETE').then(() => {});
                }
                this.recipe.ingredients.splice(key, 1);
                this.message = null;
            }
        },
        addStep() {
            if (!this.recipe.steps) this.recipe.steps = [];
            this.recipe.steps.push({ description: null, image_url: null });
        },
        saveIngredients() {
            this.errors = [];
            let hasError = false;
            
            this.recipe.ingredients.forEach((el, num) => {
                if (!el.ingredient_id) {
                    if (!this.errors[num]) this.errors[num] = {};
                    this.errors[num].ingredient_id = 'Выберите ингредиент';
                    hasError = true;
                }
                if (!el.quantity) {
                    if (!this.errors[num]) this.errors[num] = {};
                    this.errors[num].quantity = 'Укажите количество';
                    hasError = true;
                }
            });
            
            if (!hasError) {
                let formdata = new FormData();
                formdata.append('ingredients', JSON.stringify(this.recipe.ingredients));
                this.datasend('ingSave/' + this.pageId, 'POST', formdata).then((result) => {
                    this.getRec();
                    if (result.saved?.ingredient) {
                        this.message = result.saved.ingredient;
                        setTimeout(() => { this.message = null; }, 3000);
                    }
                });
            }
        },
        saveSteps() {
            this.errors = [];
            let hasError = false;
            
            this.recipe.steps.forEach((el, key) => {
                if (!el.description) {
                    if (!this.errors[key]) this.errors[key] = {};
                    this.errors[key].description = 'Нет описания';
                    hasError = true;
                }
            });
            
            if (!hasError) {
                this.recipe.steps.forEach((el, key) => {
                    let formdata = new FormData();
                    formdata.append('description', el.description);
                    let file = document.querySelector('#file' + key);
                    if (file?.files[0]) {
                        formdata.append('file', file.files[0]);
                    }
                    if (this.recipe.steps[key].id) {
                        formdata.append('id', this.recipe.steps[key].id);
                    }
                    formdata.append('step_number', key + 1);
                    
                    this.datasend('saveStep/' + this.pageId, 'POST', formdata)
                        .then((result) => {
                            if (result.errors) {
                                this.errors[key] = result.errors;
                                return;
                            }
                            if (result.saved?.step) {
                                this.message = result.saved.step;
                                setTimeout(() => { this.message = null; }, 3000);
                                this.getRec();
                            }
                        });
                });
            }
        },
        remove(id, key) {
            if (id > 0) {
                if (confirm('Хотите удалить?')) {
                    this.datasend('delStep/' + id, 'DELETE').then(() => {
                        this.recipe.steps.splice(key, 1);
                    });
                }
            } else {
                this.recipe.steps.splice(key, 1);
            }
            this.message = null;
        },
    },
};
</script>
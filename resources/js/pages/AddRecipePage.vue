<template>
    <div class="form-page">
        <h1 class="page-title text-center">{{ pageId ? 'Редактирование' : 'Добавление' }} рецепта</h1>
        
        <div class="form-container large">
            <div class="form-group">
                <label for="title">Название</label>
                <input type="text" class="form-input" id="title" placeholder="Название" v-model="title" />
                <div class="error-message" v-if="errors.title">{{ errors.title.join('. ') }}</div>
            </div>
            
            <div class="form-group">
                <label for="category_id">Категория</label>
                <select id="category_id" class="form-select" v-model="category_id">
                    <option :value="category.id" v-for="category in categories" :key="category.id">{{ category.name }}</option>
                </select>
                <div class="error-message" v-if="errors.category_id">{{ errors.category_id.join('. ') }}</div>
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea class="form-textarea" id="description" placeholder="Описание" v-model="description"></textarea>
                <div class="error-message" v-if="errors.description">{{ errors.description.join('. ') }}</div>
            </div>
            
            <div class="form-row">
                <div class="form-group half">
                    <label for="cook_time">Время готовки (минуты)</label>
                    <input type="number" class="form-input" id="cook_time" placeholder="Время готовки" v-model="cook_time" />
                    <div class="error-message" v-if="errors.cook_time">{{ errors.cook_time.join('. ') }}</div>
                </div>
                
                <div class="form-group half">
                    <label for="difficulty">Сложность</label>
                    <select id="difficulty" class="form-select" v-model="difficulty">
                        <option value="easy">Легко</option>
                        <option value="medium">Средне</option>
                        <option value="hard">Сложно</option>
                    </select>
                    <div class="error-message" v-if="errors.difficulty">{{ errors.difficulty.join('. ') }}</div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Изображение</label>
                <input type="file" class="file-input" ref="fileInput" />
                <div v-if="pageId && image_url" class="current-image">
                    <img :src="'storage/' + image_url" class="preview-img" alt="" />
                </div>
                <div class="error-message" v-if="errors.image_url">{{ errors.image_url.join('. ') }}</div>
            </div>
            
            <button class="submit-btn" @click.prevent="addRecipe">{{ pageId ? 'Редактировать' : 'Добавить' }} рецепт</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AddRecipePage',
    props: ['changePage', 'datasend', 'pageId', 'getUser'],
    data() {
        return {
            categories: {},
            title: null,
            category_id: null,
            description: null,
            cook_time: null,
            difficulty: null,
            image_url: null,
            errors: {},
        };
    },
    mounted() {
        if (this.pageId) {
            this.getRecipe();
        }
        this.getCategories();
    },
    methods: {
        getRecipe() {
            this.datasend('recipe/' + this.pageId).then((result) => {
                this.title = result.title;
                this.description = result.description;
                this.cook_time = result.cook_time;
                this.difficulty = result.difficulty;
                this.image_url = result.image_url;
                this.category_id = result.category_id;
            });
        },
        getCategories() {
            this.datasend('categories').then((result) => {
                this.categories = result;
            });
        },
        addRecipe() {
            let formdata = new FormData();
            if (this.title) formdata.append('title', this.title);
            if (this.category_id) formdata.append('category_id', this.category_id);
            if (this.description) formdata.append('description', this.description);
            if (this.cook_time) formdata.append('cook_time', this.cook_time);
            if (this.difficulty) formdata.append('difficulty', this.difficulty);
            
            if (this.$refs.fileInput && this.$refs.fileInput.files[0]) {
                formdata.append('image_url', this.$refs.fileInput.files[0]);
            }
            
            this.datasend(this.pageId ? 'EditRecipe/' + this.pageId : 'addRecipe', 'POST', formdata).then((result) => {
                if (result.errors) {
                    this.errors = result.errors;
                }
                if (result.Recipe) {
                    this.changePage('RecipesControlPage');
                }
            });
        },
    },
};
</script>
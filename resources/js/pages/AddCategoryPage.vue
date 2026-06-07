<template>
    <div class="form-page">
        <h1 class="page-title text-center">{{ pageId ? 'Редактирование' : 'Добавление' }} категории</h1>
        
        <div class="form-container">
            <div class="form-group">
                <input type="text" class="form-input" placeholder="Название" v-model="name" />
                <div class="error-message" v-if="errors.name">{{ errors.name.join('. ') }}</div>
            </div>
            
            <div class="form-group">
                <input type="text" class="form-input" placeholder="Описание (slug)" v-model="slug" />
                <div class="error-message" v-if="errors.slug">{{ errors.slug.join('. ') }}</div>
            </div>
            
            <button class="submit-btn" @click.prevent="addCategory">{{ pageId ? 'Редактировать' : 'Добавить' }} категорию</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AddCategoryPage',
    props: ['changePage', 'datasend', 'pageId'],
    data() {
        return {
            name: null,
            slug: null,
            errors: {},
        };
    },
    mounted() {
        if (this.pageId) {
            this.getCat();
        }
    },
    methods: {
        getCat() {
            this.datasend('categories/' + this.pageId).then((result) => {
                this.name = result.name;
                this.slug = result.slug;
            });
        },
        addCategory() {
            let formdata = new FormData();
            if (this.name) formdata.append('name', this.name);
            if (this.slug) formdata.append('slug', this.slug);
            
            this.datasend(this.pageId ? 'EditCategory/' + this.pageId : 'categories', 'POST', formdata).then((result) => {
                if (result.errors) {
                    this.errors = result.errors;
                }
                if (result.Category) {
                    this.changePage('RecipesControlPage');
                }
            });
        },
    },
};
</script>
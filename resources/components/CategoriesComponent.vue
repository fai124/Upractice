<template>
    <div class="admin-section">
        <h2 class="section-title">Категории</h2>
        <div class="notification-message" v-if="notif">{{ notif }}</div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories" :key="category.id">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td>{{ category.slug }}</td>
                        <td class="actions-cell">
                            <button class="edit-btn" @click="editCat(category.id)">Редактировать</button>
                            <button class="delete-btn" @click="deleteCat(category)">Удалить</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CategoriesComponent',
    props: ['changePage', 'datasend'],
    data() {
        return {
            categories: [],
            notif: null,
        };
    },
    mounted() {
        this.getCats();
    },
    methods: {
        getCats() {
            this.datasend('categories').then((result) => {
                this.categories = result;
            });
        },
        editCat(id) {
            localStorage.setItem('categoryId', id);
            this.changePage('AddCategoryPage', localStorage.getItem('categoryId'));
        },
        deleteCat(cat) {
            if (cat.recipes != 0) {
                alert('У этой категории есть рецепты, отвяжи их');
            } else {
                if (confirm('Хотите удалить?')) {
                    this.datasend('categories/' + cat.id, 'DELETE').then((result) => {
                        if (result.Category) {
                            this.notif = result.Category;
                            setTimeout(() => { this.notif = null; }, 3000);
                        }
                        this.getCats();
                    });
                }
            }
        },
    },
};
</script>
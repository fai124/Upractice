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
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories" :key="category.id">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td class="actions-cell">
                            <button class="edit-btn" @click="edit(category.id)">Редактировать</button>
                            <button class="delete-btn" @click="remove(category)">Удалить</button>
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
        this.getCategories();
    },
    methods: {
        getCategories() {
            this.datasend('categories').then((result) => {
                this.categories = result;
            });
        },
        edit(id) {
            localStorage.setItem('categoryId', id);
            this.changePage('AddCategoryPage', localStorage.getItem('categoryId'));
        },
        remove(cat) {
            if (cat.recipes != 0) {
                alert('У этой категории есть рецепты!');
            } else {
                if (confirm('Удалить?')) {
                    this.datasend('categories/' + cat.id, 'DELETE').then((result) => {
                        if (result.Category) {
                            this.notif = result.Category;
                            setTimeout(() => { this.notif = null; }, 3000);
                        }
                        this.getCategories();
                    });
                }
            }
        },
    },
};
</script>
<template>
    <div class="form-page">
        <h1 class="page-title text-center">Управление ингредиентами</h1>

        <div v-if="!editing" class="form-container">
            <div class="admin-actions">
                <button class="action-btn primary" @click="startAdd">+ Добавить ингредиент</button>
            </div>

            <div v-if="ingredients.length > 0" class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Ед. изм</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in ingredients" :key="item.id">
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.unit || '-' }}</td>
                            <td>
                                <button class="btn" @click="edit(item)">Редактировать</button>
                                <button class="btn danger" @click="remove(item.id)">Удалить</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p v-else class="empty-message">Ингредиенты не найдены</p>
        </div>

        <div v-else class="form-container">
            <h2 class="page-title text-center">
                {{ form.id ? 'Редактирование ингредиента' : 'Добавление ингредиента' }}
            </h2>

            <div class="form-group">
                <label>Название</label>
                <input type="text" class="form-input" v-model="form.name" />
                <div class="error-message" v-if="errors.name">{{ errors.name.join('. ') }}</div>
            </div>

            <div class="form-group">
                <label>Ед. изм</label>
                <input type="text" class="form-input" v-model="form.unit" />
            </div>

            <div class="admin-actions">
                <button class="action-btn primary" @click="save">
                    {{ form.id ? 'Сохранить' : 'Добавить' }}
                </button>
                <button class="action-btn" @click="cancel">Отмена</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'IngredientPage',
    props: ['changePage', 'datasend'],
    data() {
        return {
            ingredients: [],
            editing: false,
            form: { id: null, name: '', unit: '' },
            errors: {},
        };
    },
    mounted() {
        this.getIngredients();
    },
    methods: {
        getIngredients() {
            this.datasend('Ingredients').then((result) => {
                this.ingredients = Object.values(result);
            });
        },
        startAdd() {
            this.editing = true;
            this.form = { id: null, name: '', unit: '' };
            this.errors = {};
        },
        edit(item) {
            this.editing = true;
            this.form = { ...item };
            this.errors = {};
        },
        cancel() {
            this.editing = false;
            this.form = { id: null, name: '', unit: '' };
            this.errors = {};
        },
        save() {
            const formData = new FormData();
            if (this.form.name && this.form.name.trim()) formData.append('name', this.form.name.trim());
            if (this.form.unit && this.form.unit.trim()) formData.append('unit', this.form.unit.trim());
            if (this.form.id) formData.append('id', this.form.id);

            this.datasend('Ingredients', 'POST', formData).then((result) => {
                if (result.errors) {
                    this.errors = result.errors;
                } else {
                    this.cancel();
                    this.getIngredients();
                }
            });
        },
        remove(id) {
            if (confirm('Удалить ингредиент?')) {
                this.datasend(`Ingredients/${id}`, 'DELETE').then(() => {
                    this.getIngredients();
                });
            }
        },
    },
};
</script>

<template>
    <h1 class="page-title">Личный кабинет</h1>
    
    <div v-if="favorites.length > 0" class="favorites-section">
        <h3>Избранные рецепты</h3>
        <table class="favorites-table">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Время приготовления</th>
                    <th>Сложность</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fave in favorites" :key="fave.id">
                    <td>{{ fave.id }}</td>
                    <td>
                        <a href="" @click.prevent="changePage('SingleRecipePage', fave.recipe.id)">
                            <img :src="storage + fave.recipe.image_url" class="fave-img" alt="" />
                        </a>
                    </td>
                    <td>{{ fave.recipe.title }}</td>
                    <td>{{ fave.recipe.cook_time }} минут</td>
                    <td>{{ difficulty[fave.recipe.difficulty] }}</td>
                    <td><button class="remove-fave-btn" @click="remove(fave.id)">Удалить</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p v-else class="empty-message">У вас пока нет избранных рецептов</p>
</template>

<script>
export default {
    name: 'ProfilePage',
    data() {
        return {
            favorites: [],
        };
    },
    props: ['changePage', 'datasend', 'storage', 'difficulty'],
    mounted() {
        this.getFave();
    },
    methods: {
        getFave() {
            this.datasend('Fave').then((result) => {
                this.favorites = result;
            });
        },
        remove(fave) {
            if (confirm('Удалить этот рецепт из избранного?')) {
                this.datasend('DelFave/' + fave).then(() => {
                    this.getFave();
                });
            }
        },
    },
    components: {},
};
</script>
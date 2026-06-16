<template>
    <div class="profile-page">
        <h1 class="page-title">Личный кабинет</h1>
        
        <div v-if="favorites.length > 0" class="favorites-section">
            <div class="section-header">
                <h3>Избранные рецепты</h3>
                <span class="favorites-count">Всего: {{ favorites.length }}</span>
            </div>
            
            <div class="favorites-grid">
                <div v-for="fave in favorites" :key="fave.id" class="favorite-card">
                    <div class="favorite-image">
                        <a href="" @click.prevent="changePage('SingleRecipePage', fave.recipe.id)">
                            <img :src="storage + fave.recipe.image_url" alt="" />
                        </a>
                    </div>
                    <div class="favorite-info">
                        <h4 class="favorite-title">
                            <a href="" @click.prevent="changePage('SingleRecipePage', fave.recipe.id)">
                                {{ fave.recipe.title }}
                            </a>
                        </h4>
                        <div class="favorite-meta">
                            <span class="meta-item">
                                <span class="meta-label">Время:</span>
                                {{ fave.recipe.cook_time }} мин
                            </span>
                            <span class="meta-item">
                                <span class="meta-label">Сложность:</span>
                                {{ difficulty[fave.recipe.difficulty] }}
                            </span>
                        </div>
                        <button class="remove-fave-btn" @click="remove(fave.id)">
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-else class="empty-state">
            <h3>У вас пока нет избранных рецептов</h3>
            <p>Добавляйте понравившиеся рецепты в избранное, чтобы они сохранялись здесь</p>
            <button class="browse-btn" @click="changePage('RecipesPage')">Посмотреть рецепты</button>
        </div>
    </div>
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
<template>
    <div class="toast-container position-static">
        <article v-if="changeToken">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">Вы авторизовались</div>
                <!-- <div class="toast-body">Добро пожаловать, админ</div> -->
            </div>
        </article>
        <article v-if="logout">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">Вы вышли из системы</div>
            </div>
        </article>
    </div>
</template>

<script>
export default {
    name: 'NotificationComponent',
    props: ['changeToken', 'logout'],
    data() {
        return {};
    },
    mounted() {},
    methods: {},
    components: {},
};
</script>

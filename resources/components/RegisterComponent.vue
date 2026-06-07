<template>
    <div class="auth-form register-form">
        <h5>Регистрация</h5>
        <div class="form-group">
            <input type="text" class="form-input" placeholder="ФИО" v-model="name" />
            <div class="error-message" v-if="errors.name">{{ errors.name.join('. ') }}</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-input" placeholder="Email" v-model="email" />
            <div class="error-message" v-if="errors.email">{{ errors.email.join('. ') }}</div>
        </div>

        <div class="form-group">
            <input type="password" class="form-input" placeholder="Пароль" v-model="password" />
            <div class="error-message" v-if="errors.password">{{ errors.password.join('. ') }}</div>
        </div>

        <button class="auth-btn" @click="register">Зарегистрироваться</button>
    </div>
</template>

<script>
export default {
    name: 'RegisterComponent',
    data() {
        return {
            name: null,
            email: null,
            password: null,
            errors: {},
        };
    },
    mounted() {},
    props: ['datasend', 'changeToken'],
    methods: {
        register() {
            let formdata = new FormData();
            if (this.name) formdata.append('name', this.name);
            if (this.email) formdata.append('email', this.email);
            if (this.password) formdata.append('password', this.password);
            this.datasend('register', 'POST', formdata).then((result) => {
                if (result.errors) {
                    this.errors = result.errors;
                }
                if (result.token) {
                    this.changeToken(result.token);
                }
            });
        },
    },
};
</script>
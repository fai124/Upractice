<template>
    <div class="auth-form">
        <h5>Логин</h5>
 
        <div class="form-group">
            <input type="text" class="form-input" placeholder="Email" v-model="email" />
            <div class="error-message" v-if="errors.email">{{ errors.email.join('. ') }}</div>
        </div>

        <div class="form-group">
            <input type="password" class="form-input" placeholder="Пароль" v-model="password" />
            <div class="error-message" v-if="errors.password">{{ errors.password.join('. ') }}</div>
        </div>

        <button class="auth-btn" @click="auth">Авторизоваться</button>
    </div>
</template>

<script>
export default {
    name: 'LoginComponent',
    data() {
        return {
            email: null,
            password: null,
            errors: {},
        };
    },
    props: ['datasend', 'changeToken'],
    methods: {
        auth() {
            let formdata = new FormData();
            if (this.email) formdata.append('email', this.email);
            if (this.password) formdata.append('password', this.password);
            this.datasend('login', 'POST', formdata).then((result) => {
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
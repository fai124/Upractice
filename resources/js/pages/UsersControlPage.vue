<template>
    <div class="admin-panel">
        <h1 class="page-title">Пользователи</h1>
        
        <div v-if="users.length > 0" class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <span :class="['role-badge', user.role === 'admin' ? 'role-admin' : 'role-user']">
                                {{ role[user.role] }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p v-else class="empty-message">Пользователи не найдены</p>
    </div>
</template>

<script>
export default {
    name: 'UsersControlPage',
    data() {
        return {
            users: [],
            role: {
                admin: 'Админ',
                user: 'Пользователь',
            },
        };
    },
    props: ['changePage', 'datasend'],
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            this.datasend('UsersControl').then((result) => {
                this.users = result;
            });
        },
    },
};
</script>
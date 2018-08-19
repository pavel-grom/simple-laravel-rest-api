<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-bind:class="{ 'is-invalid': error && errors.name }" v-model="name" required>
                <div class="invalid-feedback" v-if="error && errors.name" v-for="error in errors.name">{{ error }}</div>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" v-bind:class="{ 'is-invalid': error && errors.email }" placeholder="user@example.com" v-model="email" required>
                <div class="invalid-feedback" v-if="error && errors.email" v-for="error in errors.email">{{ error }}</div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-bind:class="{ 'is-invalid': error && errors.password }" v-model="password" required>
                <div class="invalid-feedback" v-if="error && errors.password" v-for="error in errors.password">{{ error }}</div>
            </div>
            <div class="form-group">
                <label for="password">Confirm password</label>
                <input type="password" id="password_confirmation" class="form-control" v-model="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this;
                this.$auth.register({
                    url: '/api/register',
                    data: {
                        name: app.name,
                        email: app.email,
                        password: app.password,
                        password_confirmation: app.password_confirmation
                    },
                    success: function () {
                        app.success = true;
                    },
                    error: function (resp) {
                        app.error = true;
                        app.errors = resp.response.data.errors;
                    },
                    redirect: null
                });
            }
        }
    }
</script>
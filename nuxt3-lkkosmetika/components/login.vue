<template>
    <div class="col-md-6" style="margin: auto; padding: 50px 0; max-width: 600px;">
        <div style="text-align: center;"><h1>Admin sekce</h1></div>
        <form v-if="!error">
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example1">User</label>
                <input type="text" id="form2Example1" class="form-control" v-model="user"/>
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="form2Example2">Password</label>
                <input type="password" id="form2Example2" class="form-control" v-model="pass"/>
            </div>
            <!-- 2 column grid layout for inline styling -->
            <div v-if="false" class="row mb-4">
                <div class="col d-flex justify-content-center">
                <!-- Checkbox -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                    <label class="form-check-label" for="form2Example31"> Remember me </label>
                </div>
                </div>
                <div class="col">
                <!-- Simple link -->
                <a href="#!">Forgot password?</a>
                </div>
            </div>
             <!-- Submit button -->
            <button type="button" class="btn btn-success btn-block mb-4" @click="tryLogin()">Sign in</button>
        </form>
        <!-- Submit button -->
        <button type="button" v-else class="btn btn-success btn-block mb-4" @click="reset()">Zkusit znovu</button>
        <div>{{error}}</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: '',
                pass: '',
                error: '',
                auth: null,
            }
        },
        methods: {
            reset() {
                this.user = '';
                this.pass = '';
                this.error = '';
            },
            async tryLogin() {
                try {
                    const success = await this.auth.tryLogin(this.user, this.pass);
                    this.reset();
                    if (!success) {
                        this.error = 'Přihlášní se nezdařilo!';
                    }
                } catch(ex) {
                    alert('Oopsss něco se pokazilo!')
                    console.error(ex);
                }
            }
        },
        async mounted() {
            this.auth = await useAuth();
        },
    }
</script>
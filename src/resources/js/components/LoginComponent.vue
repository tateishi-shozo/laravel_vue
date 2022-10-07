<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ログイン</div>
 
                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
 
                            <div class="col-md-6">
                                <input id="email" type="email" v-model="email" class="form-control @error('email') is-invalid @enderror" name="email">
                            </div>
                        </div>
 
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
 
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" v-model="password" name="password">
                            </div>
                        </div>
 
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
 
                                    <label class="form-check-label" for="remember">ログイン情報を保存する</label>
                                </div>
                            </div>
                        </div>
 
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" @click="login">ログイン</button>
 
                                <a class="btn btn-link" href="https:google.com">パスワードを忘れましたか？</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return{
                email: '',
                password: ''
            }

        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            login(){
                const response = axios.post('api/login',{
                    email: this.email,
                    password: this.password
                });
                console.log(response);
            }
        }
    }
</script>


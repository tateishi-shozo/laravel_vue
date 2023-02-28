<template>
<div class="register-form">
    <div class="navbar navbar-light navbar-dark bg-dark">
        <h2 class="navbar-brand">MYライブラリー</h2>
        <a class="btn btn-link" @click="indexLink()" id="indexlink">ログインせずに利用する</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">新規登録</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">ユーザー名</label>

                        <div class="col-md-6">
                            <input id="name" v-model="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                        <div class="col-md-6">
                            <input id="email" v-model="email" type="email" class="form-control" name="email" autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                        <div class="col-md-6">
                            <input id="password" v-model="password" type="password" class="form-control" name="password" required autocomplete="password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="message" v-for="(errorName,index) in errorNames" :key="index">
                            {{errorName}}
                        </div>
                        <div class="message" v-for="(errorEmail,index) in errorEmails" :key="index">
                            {{errorEmail}}
                        </div>
                        <div class="message" v-for="(errorPassword,index) in errorPasswords" :key="index">
                            {{errorPassword}}
                        </div>
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" @click="register()" id="register">登録</button>

                            <a class="btn btn-link" @click="loginLink()" id="loginlink">ログインへ戻る</a>
                        </div>
                    </div>
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
                name:'',
                email: '',
                password: '',
                errorNames: '',
                errorEmails: '',
                errorPasswords: ''
            }

        },
        methods: {
            async register(){
                try{
                    const response = await axios.post('api/register',{
                    name: this.name,
                    email: this.email,
                    password: this.password,
                });

                alert('登録が完了しました!');
                location.href = '/login';
                
                }catch(error){
                    console.log(error.response);
                    this.errorNames ='';
                    this.errorEmails ='';
                    this.errorPasswords ='';

                    this.errorNames = error.response.data.name;
                    this.errorEmails = error.response.data.email;
                    this.errorPasswords = error.response.data.password;
                };
            },
            loginLink(){
                location.href = '/login';
            },
            indexLink(){
                localStorage.removeItem('Authorization');
                localStorage.removeItem('user_id');
                localStorage.removeItem('user_name');
                location.href = '/index';
            }
        }
    }
</script>
<style scoped lang="scss">
.form-group{
    margin-top:10px;
}
.card{
    margin: 30px;
}
</style>

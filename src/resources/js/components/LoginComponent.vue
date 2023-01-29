<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ログイン</div>
 
                <div class="card-body">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>
 
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control"  v-model="email" name="email">
                            </div>
                        </div>
 
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>
 
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" v-model="password" name="password">
                            </div>
                        </div>
 
                        <div class="form-group row mb-0">
                            <div class="message" v-for="(errorEmail,index) in errorEmails" :key="index">
                                {{errorEmail}}
                            </div>
                            <div class="message" v-for="(errorPassword,index) in errorPasswords" :key="index">
                                {{errorPassword}}
                            </div>
                            <div class="col-md-8 offset-md-4 login">
                                <button class="btn btn-primary" @click="login()">ログイン</button>
 
                                <a class="btn btn-link" @click="registerLink()">新規登録の方はこちら</a>
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
                errorEmails: '',
                errorPasswords: ''
            }

        },
        methods: {
            async login(){
                try{
                    const response = await axios.post('api/login',{
                    name: this.email,
                    email: this.email,
                    password: this.password,
                });
                    console.log(response.data.token_type);
                    if(response.status = 200){
                        const token = response.data.token_type + ' ' + response.data.access_token;
                        console.log(token);
                        localStorage.setItem('Authorization', token);
                        location.href = '/index';
                    }
                }catch(error){
                    console.log(error.response);
                    this.errorEmails ='';
                    this.errorPasswords ='';

                    this.errorEmails = error.response.data.email;
                    this.errorPasswords = error.response.data.password;
                };
            },
            registerLink(){
                location.href = '/register';
            }
        }
    }
</script>
<style scoped lang="scss">
.login{
    margin-top:10px;
}
.form-group{
    margin-top:10px;
}
</style>

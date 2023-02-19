<template>
    <div class="bg-light">
        <div class="navbar navbar-light navbar-dark bg-dark">
            <h2 class="navbar-brand">MYライブラリー</h2>
            <div class="logout" v-if="token !== null">
                <h4 class="user_name navbar-brand">{{ user_name }}さん</h4>
                <button @click="addForm()" class="btn btn-primary" :disabled="isActive" id="addform">コメント</button>
            </div>
            <div class="login" v-else>
                <h4 class="user_name navbar-brand">ゲストさん</h4>
            </div>
            <div class="return">
                <button @click="returnIndex()" class="btn btn-primary" id="return">戻る</button>
            </div>
        </div>
        <div class="input-form" v-if="addFlg">
            <h3>コメント</h3>
            <input type="textarea" v-model="comment" id="comment">
            <button @click="addComment()" class="btn btn-primary" id="add">投稿</button>
            <button @click="addCancel()" class="btn btn-secondary" id="cancel">キャンセル</button>
        </div>
        <div class="message">
            {{ message }}
        </div>
        <div class="bg-light">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">タイトル</th>
                    <th scope="col">カテゴリー</th>
                    <th scope="col">投稿者</th>
                    <th scope="col">投稿時間</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ book.title }}</td>
                        <td>{{ book.category }}</td>
                        <td>{{ book.user_name }}</td>
                        <td>{{ book.updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-light">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">コメント</th>
                        <th scope="col">投稿者</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="comment in comments" :key="comment.id">
                        <td>{{ comment.pivot.comment }}</td>
                        <td>{{ comment.name }}</td>
                        <td>
                            <div class="btn-toolbar" v-if="comment.pivot.user_id == user_id">
                                <button @click="deleteComment(comment.pivot.id)" class="btn btn-danger" id="delete" :disabled="isActive">削除</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return{
            message: '',
            book: '',
            isActive: false,
            token: '',
            user_id: '',
            user_name: '',
            book_id: '',
            addFlg: false,
            comment: '',
            comments: [],
            commentFlg: false,
            comment_id: ''
        };
    },

    created: function() {
        this.token = localStorage.getItem('Authorization');
        this.user_id = localStorage.getItem('user_id');
        this.user_name = localStorage.getItem('user_name');

        const searchParams = new URLSearchParams(window.location.search);
        this.book_id = searchParams.get('book_id');

        this.getBook(this.book_id);
        this.getComments(this.book_id);

    },

    methods: {
        //１つの本の表示
        async getBook(book_id){
            try{
                const response = await axios.get('api/book/' + book_id);
                this.book = response.data;

                console.log(response.data);

                }catch(error){
                    console.log(error);
                    this.message = error;
                };
            },
        
        //コメントの表示
        async getComments(book_id){
            try{
                const response = await axios.get('api/book/comment/' + book_id);
                this.comments = response.data;

                }catch(error){
                    console.log(error);
                    this.message = error;
                };
            },

        //コメントの新規登録
        async addComment() {
            try{
                await axios.post('api/book/comment/',{
                    book_id: this.book_id,
                    user_id: this.user_id,
                    comment: this.comment
                    },{
                    headers: {
                        Authorization: this.token
                    }
                    });

                this.getComments(this.book_id);
                this.addCancel();

            }catch(error){

                console.log(error);
                this.message = error;
            };
        },
        
        //登録した本の削除
        async deleteComment(comment_id) {
            try{
                this.comment_id = comment_id;
                console.log(comment_id);

                await axios.delete('api/book/comment/' + this.comment_id,{
                    headers: {
                        Authorization: this.token
                    }
                    });

                this.getComments(this.book_id);
                
            }catch(error){

                console.log(error);
                this.message = error;
            };
        },

        //一覧に戻る
        async returnIndex(){
            try{
                location.href = '/index';
            }catch(error){
                this.message = error;
            }
        },

        //ログインへ
        loginLink(){
            try{
                location.href = '/login';
            }catch(error){
                this.message = error;
            }
        },

        //新規投稿
        addForm(){
            this.addFlg = true;
            this.isActive= true;
            this.message = '';
        },

        //新規投稿キャンセル
        addCancel(){
            this.addFlg = false;
            this.isActive= false;
        },
    }
}
</script>
<style scoped lang="scss">

.input-form {
    margin-left : 30px;
}

.edit-form {
    margin-left : 30px;
}

.btn{
    margin:0 10px;
}

.logout,.login{
    display:flex;
}

</style>
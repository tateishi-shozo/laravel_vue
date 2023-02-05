<template>
    <div class="bg-light">
        <div class="navbar navbar-light navbar-dark bg-dark">
            <h2 class="navbar-brand">MYライブラリー</h2>
            <div class="logout" v-if="token !== null">
                <h4 class="user_name navbar-brand">{{ user_name }}さん</h4>
                <button @click="addForm()" class="btn btn-primary" :disabled="isActive">新規投稿</button>
                <button @click="logout()" class="btn btn-primary">ログアウト</button>
            </div>
            <div class="login" v-else>
                <h4 class="user_name navbar-brand">ゲストさん</h4>
                <button @click="loginLink()" class="btn btn-primary">ログイン</button>
            </div>
        </div>
        <div class="input-form" v-if="addFlg">
            <h3>新規登録</h3>
            <p>タイトル</p>
            <input type="text" v-model="title" id="newtitle">
            <p>カテゴリー</p>
                <div v-for=" item in items" :key="item.id">
                    <input type="radio" name="category" :value="item" v-model="category">{{item}}
                </div>
            <button  @click="addBook()" class="btn btn-primary" id="add">追加</button>
            <button @click="addCancel()" class="btn btn-secondary" id="cancel">キャンセル</button>
        </div>
        <div class="edit-form" v-if="editFlg">
            <h3>編集フォーム</h3>
            <p>タイトル</p><input type="text" v-model="updateTitle">
            <p>カテゴリー</p>
                <div v-for=" item in items" :key="item.id">
                    <input type="radio" name="category" :value="item" v-model="updateCategory">{{item}}
                </div>
            <div class="btn-toolbar">
                <button @click="updateBook(updateId)" class="btn btn-primary" id="update">変更</button>
                <button @click="updateCancel()" class="btn btn-secondary" id="cancel">キャンセル</button>
            </div>
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
                    <tr v-for="book in books" :key="book.id">
                        <td>{{ book.title }}</td>
                        <td>{{ book.category }}</td>
                        <td>{{ book.user_name }}</td>
                        <td>{{ book.updated_at }}</td>
                        <td><button @click="getComment(book.id)">コメント({{ book.comments_count }})</button></td>
                        <td>
                            <div class="btn-toolbar" v-if="book.user_id == user_id">
                                <button @click="updateForm(book.id,book.title,book.category)" class="btn btn-primary" id="edit" :disabled="isActive">編集</button>
                                <button @click="deleteBook(book.id)" class="btn btn-danger" id="delete" :disabled="isActive">削除</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="btn-toolbar">
            <div class="prev-button">
                <button @click="prevPage()" v-show="isDisplayPrev" class="btn">&lt;&lt;前へ</button>
            </div>
            <div class="next-button">
                <button @click="nextPage()" v-show="isDisplayNext" class="btn">次へ&gt;&gt;</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return{
            id: '',
            title: '',
            category: '',
            message: '',
            editFlg: false,
            updateId: '',
            updateTitle: '',
            updateCategory: '',
            books: [],
            isActive: false,
            current_page: 1,
            last_page: '',
            isDisplayPrev: false,
            isDisplayNext: true,
            items: ["文芸","実用書","ビジネス書","絵本/児童書","学習参考書/専門書","コミック/雑誌"],
            token: '',
            user_id: '',
            user_name: '',
            addFlg: false,
            comments: [],
            commentFlg: false
        };
    },

    created: function() {
        this.token = localStorage.getItem('Authorization');
        this.user_id = localStorage.getItem('user_id');
        this.user_name = localStorage.getItem('user_name');
        this.getBooks();
    },

    methods: {
        //登録した本の一覧表示
        async getBooks(){
            try{
                const response = await axios.get(`api/books?page=${this.current_page}`);
                const books = response.data;
                console.log(response);
                this.books = books.data;
                this.current_page = books.current_page;
                this.last_page = books.last_page;

                if(this.current_page >= this.last_page){
                    this.isDisplayNext = false;

                    if(this.current_page <= 1){
                        this.isDisplayPrev = false;
                    }else{
                        this.isDisplayPrev = true;
                    }

                }else if(this.current_page <= 1){
                    this.isDisplayPrev = false;
                    
                    if(this.current_page >= this.last_page){
                        this.isDisplayNext = false;
                    }else{
                        this.isDisplayNext = true;
                    }

                }else{
                    this.isDisplayNext = true;
                    this.isDisplayPrev = true;
                };

                }catch(error){
                    console.log(error);
                    this.message = error;
                };
            },

        //本の新規登録
        async addBook() {
            try{
                if( this.title == "" || this.category == ""){
                    this.message = "全て入力してください!!";
                    return
                };

                await axios.post('api/books',{
                    title: this.title,
                    category: this.category,
                    user_id: this.user_id
                },{
                    headers: {
                        Authorization: this.token
                    }
                });
                this.getBooks();

                this.title = '';
                this.category = '';
                this.addFlg = false;
                this.isActive= false;

                }catch(error){
                    if(error.response.data.errors.title !== null){
                        this.message = error.response.data.errors.title[0];
                    }else if(error.response.data.errors.category !== null){
                        this.message = error.response.data.errors.category[0];
                    }else{
                        this.message = error;
                    }
                };
        },
        
        //登録した本の削除
        async deleteBook(id) {
            try{
                await axios.delete('api/books/' + id,{
                    headers: {
                        Authorization: this.token,
                    }
                });
                this.getBooks();

                }catch(error){
                    this.message = error;
                };
        },

        //本の編集フォームを開く
        updateForm(id,title,category){
            this.editFlg = true;
            this.updateTitle = title;
            this.updateCategory = category;
            this.updateId = id;
            this.isActive= true;
            this.message = '';
        },

        //本の編集
        async updateBook(updateId) {
            try{
                await axios.put('api/books/' + updateId ,{
                    title: this.updateTitle,
                    category: this.updateCategory
                },{
                    headers: {
                        Authorization: this.token
                    }
                });

                this.getBooks();

                }catch(error){
                    this.message = error;
                };

                this.editFlg = false;
                this.updateTitle = '';
                this.updateCategory = '';
                this.updateId = '';
                this.isActive= false;

        },

        //本の編集のキャンセル
        updateCancel() {
            this.editFlg = false;
            this.updateTitle = '';
            this.updateCategory = '';
            this.updateId = '';
            this.isActive= false;  
        },

        //ページネーション
        nextPage(){
            const next_page = this.current_page + 1;
            this.current_page = next_page;
            this.getBooks();
        },

        prevPage(){
            const prev_page = this.current_page - 1;
            this.current_page = prev_page;
            this.getBooks();
        },

        //ログアウト
        async logout(){
            try{
                const response = await axios.get(`api/logout`,{
                    headers: {
                        Authorization: this.token,
                    }
                });
                localStorage.removeItem('Authorization');
                localStorage.removeItem('user_id');
                localStorage.removeItem('user_name');
                location.href = '/login';
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

        //コメント閲覧
        async getComment(id){
            try{
                location.href = '/comment?book_id=' + id;

            }catch(error){
                this.message = error;
            }
        }
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
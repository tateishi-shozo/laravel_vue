<template>
    <div class="bg-light">
        <div class="navbar navbar-light navbar-dark bg-dark">
            <h2 class="navbar-brand">MYライブラリー</h2>
            <button @click="logout" class="btn btn-primary">ログアウト</button>
        </div>
        <div class="input-form">
            <div class="message">
                <h3>新規登録</h3>
                {{ message }}
            </div>
            <p>タイトル</p>
            <input type="text" v-model="title" id="newtitle">
            <p>カテゴリー</p>
                <div v-for=" item in items" :key="item.id">
                    <input type="radio" name="category" :value="item" v-model="category">{{item}}
                </div>
            <button class="btn btn-primary" @click="addBook()" id="add" :disabled="isActive">追加</button>
        </div>
        <div class="edit-form" v-if="editFlg">
            <h3>編集フォーム</h3>
            <p>タイトル</p><input type="text" v-model="updateTitle">
            <p>カテゴリー</p>
                <div v-for=" item in items" :key="item.id">
                    <input type="radio" name="category" :value="item" v-model="updateCategory">{{item}}
                </div>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button @click="updateBook(updateId)" class="btn btn-primary" id="update">変更</button>
                    <button @click="updateCancel()" class="btn btn-secondary" id="cancel">キャンセル</button>
                </div>
            </div>
        </div>        
        <div class="bg-light">
            <table class="table">
            <thead>
                <tr>
                <th scope="col">タイトル</th>
                <th scope="col">カテゴリー</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="book in books" :key="book.id">
                <td>{{ book.title }}</td>
                <td>{{ book.category }}</td>
                <td>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button @click="updateForm(book.id,book.title,book.category)" class="btn btn-primary" id="edit" :disabled="isActive">編集</button>
                            <button @click="deleteBook(book.id)" class="btn btn-danger" id="delete" :disabled="isActive">削除</button>
                        </div>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="btn-toolbar">
            <div class="btn-group">
                <div class="prev-button">
                    <button @click="prevPage()" v-show="isDisplayPrev" class="btn">&lt;&lt;前へ</button>
                </div>
                <div class="next-button">
                    <button @click="nextPage()" v-show="isDisplayNext" class="btn">次へ&gt;&gt;</button>
                </div>
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
            items: ["文芸","実用書","ビジネス書","絵本/児童書","学習参考書/専門書","コミック/雑誌"]
        };
    },

    created: function() {
        this.getBook();
    },

    methods: {
        //登録した本の一覧表示
        async getBook(){
            try{
                const token = localStorage.getItem('Authorization');
                const response = await axios.get(`api/books?page=${this.current_page}`,{
                    headers: {
                        Authorization: token,
                    }
                });
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
                const token = localStorage.getItem('Authorization');
                if( this.title == "" || this.category == ""){
                    this.message = "全て入力してください!!";
                    return
                };

                await axios.post('api/books',{
                    title: this.title,
                    category: this.category,
                    read_flg: 0
                },{
                    headers: {
                        Authorization: token
                    }
                });
                this.getBook();

                this.title = '';
                this.category = '';
                this.message = '新規追加しました!!';

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
                const token = localStorage.getItem('Authorization');
                const index = this.books.findIndex((book) => book.id === id );
                await axios.delete('api/books/' + id,{
                    headers: {
                        Authorization: token,
                    }
                });
                this.message = "削除しました!!";
                this.getBook();

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
        },

        //本の編集
        async updateBook(updateId) {
            try{
                const token = localStorage.getItem('Authorization');
                const index = this.books.findIndex((book) => book.id === updateId );
                await axios.put('api/books/' + updateId ,{
                    title: this.updateTitle,
                    category: this.updateCategory,
                    read_flg: 0
                },{
                    headers: {
                        Authorization: token
                    }
                });

                this.getBook();

                this.message = "変更しました!!";

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
            this.message = "キャンセルしました!!";         
        },

        //ページネーション
        nextPage(){
            const next_page = this.current_page + 1;
            this.current_page = next_page;
            this.getBook();
        },

        prevPage(){
            const prev_page = this.current_page - 1;
            this.current_page = prev_page;
            this.getBook();
        },

        //ログアウト
        async logout(){
            try{
                const token = localStorage.getItem('Authorization');
                const response = await axios.get(`api/logout`,{
                    headers: {
                        Authorization: token,
                    }
                });
                localStorage.removeItem('Authorization');
                location.href = '/login';
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

</style>
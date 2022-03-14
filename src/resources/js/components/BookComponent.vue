<template>
    <div class="bg-light">
        <div class="navbar navbar-light navbar-dark bg-dark">
            <h2 class="navbar-brand">MYライブラリー</h2>
        </div>
        <div class="input-form">
            <div class="message">
                <h3>新規登録</h3>
                {{ message }}
            </div>
            <div>
                <p>タイトル：<input type="text" v-model="title" id="newtitle"></p>
                <p>カテゴリー：
                    <input type="radio" name="category" value="文芸" v-model="category">文芸
                    <input type="radio" name="category" value="実用書" v-model="category">実用書
                    <input type="radio" name="category" value="ビジネス書" v-model="category">ビジネス書
                    <input type="radio" name="category" value="絵本/児童書" v-model="category">絵本/児童書
                    <input type="radio" name="category" value="学習参考書/専門書" v-model="category">学習参考書/専門書
                    <input type="radio" name="category" value="コミック/雑誌" v-model="category">コミック/雑誌
                </p>
                <button class="btn btn-primary" @click="addBook" id="add">追加</button>
            </div>
        </div>
        <div class="edit-form" v-if="editFlg">
            <h3>編集フォーム</h3>
            <p>タイトル：<input type="text" v-model="updateTitle"></p>
            <p>カテゴリー：
                <input type="radio" name="category" value="文芸" v-model="updateCategory">文芸
                <input type="radio" name="category" value="実用書" v-model="updateCategory">実用書
                <input type="radio" name="category" value="ビジネス書" v-model="updateCategory">ビジネス書
                <input type="radio" name="category" value="絵本/児童書" v-model="updateCategory">絵本/児童書
                <input type="radio" name="category" value="学習参考書/専門書" v-model="updateCategory">学習参考書/専門書
                <input type="radio" name="category" value="コミック/雑誌" v-model="updateCategory">コミック/雑誌
            </p>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button @click="updateBook(updateId)" class="btn btn-primary">変更</button>
                    <button @click="updateCancel" class="btn btn-secondary" id="cancel">キャンセル</button>
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
                <tr v-for="book in books">
                <td>{{ book.title }}</td>
                <td>{{ book.category }}</td>
                <td>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button @click="updateForm(book.id,book.title,book.category)" class="btn btn-primary" id="edit">編集</button>
                            <button @click="deleteBook(book.id)" class="btn btn-danger" id="delete">削除</button>
                        </div>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import axios from"axios";

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
            books: []
        }
    },

    mounted: function() {
        this.getBook()
    },

    methods: {
        //登録した本の一覧表示
        getBook(){
            console.log("マウント")
            axios.get('api/books')
            .then((response) => {
                this.books = response.data
            })
        },

        //本の新規登録
        addBook(event) {
            if( this.title == "" || this.category == ""){
                this.message = "全て入力してください!!"
                return
            }
            console.log("クリック")
            axios.post('api/books',{
                title: this.title,
                category: this.category,
                read_flg: 0
            })
            .then(()=>{
                console.log("更新")
                let newbook = {
                    title: this.title,
                    category: this.category
                }
                this.books.push(newbook)

                this.title = '',
                this.category = '',
                this.message = ''
            })
        },
        
        //登録した本の削除
        deleteBook(id) {
            const index = this.books.findIndex((book) => book.id === id )
            axios.delete('api/books/' + id)
            .then(
                this.books.splice(index,1),
                this.message = "削除しました!!"
            )
        },

        //本の編集フォームを開く
        updateForm(id,title,category){
            this.editFlg = true,
            this.updateTitle = title,
            this.updateCategory = category,
            this.updateId = id
        },

        //本の編集
        updateBook(updateId) {
            const index = this.books.findIndex((book) => book.id === updateId )
            axios.put('api/books/' + updateId ,{
                title: this.updateTitle,
                category: this.updateCategory,
                read_flg: 0
            })
            .then(
                this.books.splice(index,1,{
                    title: this.updateTitle,
                    category: this.updateCategory
                })
            )
            this.editFlg = false,
            this.updateTitle = '',
            this.updateCategory = '',
            this.updateId = '',
            this.message = "変更しました!!"
            console.log(index)
        },

        //本の編集のキャンセル
        updateCancel() {
            this.editFlg = false,
            this.updateTitle = '',
            this.updateCategory = '',
            this.updateId = '',
            this.message = "キャンセルしました!!"            
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
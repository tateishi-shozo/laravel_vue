<template>
    <div>
        <h2>MYライブラリー</h2>
        {{ message }}
        <div class="form">
            <p>タイトル：<input type="text" v-model="title"></p>
            <p>カテゴリー：
                <input type="radio" name="category" value="文芸" v-model="category">文芸
                <input type="radio" name="category" value="実用書" v-model="category">実用書
                <input type="radio" name="category" value="ビジネス書" v-model="category">ビジネス書
                <input type="radio" name="category" value="絵本/児童書" v-model="category">絵本/児童書
                <input type="radio" name="category" value="学習参考書/専門書" v-model="category">学習参考書/専門書
                <input type="radio" name="category" value="コミック/雑誌" v-model="category">コミック/雑誌
            </p>
            <button @click="addBook">追加</button>
        </div>
        <div class="booklist">
            <ul>
                <li v-for="book in books">
                    {{ book.id }}:{{ book.title }}/{{ book.category }}{{ book.evaluation }}{{ book.conclued }}
                    <button @click="deleteBook(book.id)">削除</button>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return{
        title: '',
        category: '',
        message: '',
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
            const vm = this
            axios.get('api/books')
            .then(function(response){
                vm.books = response.data
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
            console.log(index)

            axios.delete('api/books/' + id)
            .then(
                this.books.splice(index,1),
                this.message = "削除しました!!"
            )
        }
    },
}
</script>
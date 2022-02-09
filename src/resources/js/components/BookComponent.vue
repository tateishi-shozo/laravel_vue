<template>
    <div>
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
        <p v-if="message">{{ message }}</p>
        <div class="booklist">
            <ul>
                <li v-for="book in books">
                    {{ book.title }}/{{ book.category }}{{ book.evaluation }}{{ book.conclued }}
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
        books: null
        }
    },
    mounted: function() {
        this.getBook()
    },
    methods: {
        getBook: function(){
            console.log("マウント")
            let self = this
            axios.get('api/books')
            .then(function(response){
                self.books = response.data
            })
        },
        addBook: function(event) {
            console.log("クリック")
            axios.post('api/books',{
                title: this.title,
                category: this.category,
                read_flg: 0
            })
            .then(function(){
                console.log("更新")
                this.getBook();
                this.title = '',
                this.category = ''
            })
        }
    },
}
</script>
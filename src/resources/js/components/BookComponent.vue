<template>
    <div>
        <!-- 新規追加フォーム -->
        <div>
            <p>タイトル：<input type="text" v-model="title" /></p>
            <p>著者：<input type="text" v-model="author" /></p>
            <button @click="addBook">追加</button>
        </div>
 
        <!-- book一覧 -->
        <div>
            <ul>
                <li v-for="book in books" :key="book.id">
                    {{ book.id }}/{{ book.title }}/{{ book.author }}
                    <button
                        :disabled="isPush"
                        @click="displayUpdate(book.id, book.title, book.author)"
                    >
                        編集
                    </button>
                    <button :disabled="isPush" @click="deleteBook(book.id)">
                        削除
                    </button>
                </li>
            </ul>
        </div>
 
        <!-- 編集フォーム -->
        <div v-if="updateForm">
            <p>idが{{ updateId }}の編集フォーム</p>
            <p>タイトル：<input type="text" v-model="updateTitle" /></p>
            <p>著者：<input type="text" v-model="updateAuthor" /></p>
            <button @click="updateBook(updateId, updateTitle, updateAuthor)">
                編集する
            </button>
            <button @click="updateCancel">キャンセル</button>
        </div>
 
        <!-- エラーメッセージ -->
        <p v-if="message">{{ message }}</p>
    </div>
</template>
 
<script>
export default {
    name:'test',
    data() {
        return {
            message: "",
            isPush: false,
            updateForm: false,
            books: {},
            title: "",
            author: "",
            updateId: "",
            updateTitle: "",
            updateAuthor: ""
        };
    },
    created: function() {
        this.getBook();
    },
    methods: {
        getBook() {
            axios
                .get("/api/books/")
                .then(response => {
                    this.books = response.data;
                })
                .catch(err => {
                    this.message = err;
                });
        },
        addBook() {
            axios
                .post("/api/books/", {
                    title: this.title,
                    author: this.author
                })
                .then(response => {
                    this.getBook();
                    this.title = "";
                    this.author = "";
                    this.message = "";
                })
                .catch(err => {
                    this.message = err;
                });
        },
        displayUpdate(id, title, author) {
            this.isPush = true;
            this.updateForm = true;
            this.message = "";
            this.updateId = id;
            this.updateTitle = title;
            this.updateAuthor = author;
        },
        updateCancel() {
            this.isPush = false;
            this.updateForm = false;
            this.message = "";
        },
        updateBook(updateId, updateTitle, updateAuthor) {
            axios
                .put("/api/books/" + updateId, {
                    title: this.updateTitle,
                    author: this.updateAuthor
                })
                .then(response => {
                    this.getBook();
                    this.isPush = false;
                    this.updateForm = false;
                    this.message = "";
                })
                .catch(err => {
                    this.message = err;
                });
        },
        deleteBook(id) {
            axios
                .delete("/api/books/" + id)
                .then(response => {
                    this.getBook();
                    this.message = "";
                })
                .catch(err => {
                    this.message = err;
                });
        }
    }
};
</script>
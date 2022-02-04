<template>
    <div class="booklist">
        <ul>
            <li v-for="book in books">
                {{ book.title }}/{{ book.category }}
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    data:() =>({
        books: null
    }),
    mounted: function() {
        this.getBook()
    },
    methods: {
        getBook: function(){
            let self = this
            axios.get('api/books')
            .then(function(response){
                console.log(response)
                self.books = response.data
            })
        }
    },
}
</script>
<template>
    <div class="form">
        <p>タイトル<input type="text" class="title"></p>
        <div class="category"><p>カテゴリー</p>
            <p><input type="radio" value="文芸"></p>
            <p><input type="radio" value="実用書"></p>
            <p><input type="radio" value="ビジネス書"></p>
            <p><input type="radio" value="絵本・児童書"></p>
            <p><input type="radio" value="学習参考書"></p>
            <p><input type="radio" value="専門書"></p>
            <p><input type="radio" value="コミック・雑誌"></p>
        </div>
        <p>タイトルß<input type="text" class="title"></p>
    </div>
    
</template>
<script>
const app = Vue.createApp({
    data: () => ({

    }),
})
</script>
<template>
  <div>
    <p>test!!!!!!</p>
  </div>
</template>
<script>
import { onMounted } from 'vue'
export default ({
  setup() {
    data : () => ({
      test: false
    }),

    onMounted(() => {
      console.log('start mounted !')
    })
  },
})
</script>
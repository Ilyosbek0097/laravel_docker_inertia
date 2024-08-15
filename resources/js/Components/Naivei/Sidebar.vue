<script>
import { defineComponent, ref } from 'vue'
import { darkTheme } from 'naive-ui'

export default defineComponent({
    setup() {
        return {
            darkTheme,
            theme: ref(null)
        }
    }
})
</script>
<template>

</template>

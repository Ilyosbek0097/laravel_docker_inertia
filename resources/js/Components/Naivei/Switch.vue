<template>
    <n-switch
        checked-value="Foo"
        unchecked-value="Bar"
        @update:value="handleUpdateValue"
    />
</template>

<script>
import { defineComponent } from "vue";
import { useMessage } from "naive-ui";

export default defineComponent({
    setup() {
        const message = useMessage();
        return {
            handleUpdateValue(value) {
                message.info(value);
            }
        };
    }
});
</script>

<script>
import { router } from "@inertiajs/vue3";
import { ArrowUndoCircleSharp as BackIcon, Send } from '@vicons/ionicons5';
import MyLayout from "@/Layouts/MyLayout.vue";
import { defineComponent, ref } from "vue";
import axios from 'axios';

export default defineComponent({
    props: {
        permission: Object,
    },
    components: {
        MyLayout,
        BackIcon,
        Send,
    },
    setup() {
        const message1 = ref("");
        const chatResponse = ref("");

        const sendMessage = async () => {
            console.log("Yuborilayotgan xabar:", message1.value);
            try {
                const response = await axios.post(route('chat.action'), {
                    message: message1.value
                });
                console.log("Server javobi:", response.data); // Konsolga chiqarish
                chatResponse.value = response.data.response || response.data; // response.data.response bo'lmasa, to'g'ridan-to'g'ri response.data ni olish
            } catch (error) {
                console.error("Xatolik:", error.response ? error.response.data : error.message);
            }
        };

        const GoBack = () => {
            router.get(route('user.index'));
        };

        return {
            router,
            GoBack,
            message1,
            chatResponse,
            sendMessage,
        };
    }
});
</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height:700px;">
            <n-space justify="end" class="mb-4">
                <n-button type="primary" size="large" @click="GoBack">
                    <n-icon size="30">
                        <BackIcon/>
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-space vertical :size="12">
                <div v-if="chatResponse">
                    <p>Javob: {{ chatResponse }}</p>
                </div>
                <n-input
                    v-model:value="message1"
                    placeholder="Textarea"
                    type="textarea"
                    :autosize="{
                        minRows: 3,
                        maxRows: 5
                    }"
                />
                <n-button tertiary type="primary" @click="sendMessage">
                    <template #icon>
                        <n-icon><Send /></n-icon>
                    </template>
                </n-button>
            </n-space>
        </n-layout-content>
    </MyLayout>
</template>

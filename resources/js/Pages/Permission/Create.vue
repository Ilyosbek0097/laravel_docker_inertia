<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { ArrowUndoCircleSharp as BackIcon } from '@vicons/ionicons5';
import { router, useForm } from "@inertiajs/vue3";
import { defineComponent, ref, watch } from 'vue';
import { useMessage } from "naive-ui";

export default defineComponent({
    components: {
        MyLayout,
        BackIcon
    },
    setup() {
        const message = useMessage();
        const form = useForm({
            name: ''
        });
        const formRef = ref(null);
        const isDisable = ref(true);

        // Validatsiya funksiyasi
        const validateForm = async () => {
            try {
                const errors = await formRef.value?.validate();
                isDisable.value = !!errors;
            } catch (error) {
                console.error('Validation error:', error);
            }
        };

        // Validatsiya qoidalari
        const rules = {
            name: {
                required: true,
                message: "Please input your name",
                trigger: ["blur", "input"]
            }
        };

        // Go Back funksiyasi
        const GoBack = () => {
            router.get(route('permission.index'));
        };

        // Formani yuborish funksiyasi
        const submit = async (e) => {
            e.preventDefault();
            try {
                const errors = await formRef.value?.validate();
                if (!errors) {
                    message.success("Form is valid. Submitting...");
                    await form.post(route('permission.store'));
                } else {
                    console.log(errors);
                    message.error("There are validation errors. Please check your input.");
                }
            } catch (error) {
                console.error("An unexpected error occurred:", error);
                message.error("An unexpected error occurred. Please try again later.");
            }
        };


        // Form ma'lumotlarini kuzatish
        watch(() => form.name, validateForm);

        return {
            GoBack,
            form,
            submit,
            rules,
            isDisable,
            message,
            validateForm,
        };
    }
});
</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height: 700px;">
            <n-space justify="end" class="mb-4">
                <n-button type="primary" size="large" @click="GoBack">
                    <n-icon size="30">
                        <BackIcon/>
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-form
                ref="formRef"
                :rules="rules"
                :model="form"
            >
                <n-form-item label="Permission Name" path="name" class="mb-4">
                    <n-input
                        v-model:value="form.name"
                        placeholder="Input Name"
                    />
                </n-form-item>
                <n-form-item>
                    <n-button type="primary" @click="submit" :disabled="isDisable">
                        Submit
                    </n-button>
                </n-form-item>
            </n-form>
        </n-layout-content>
    </MyLayout>
</template>

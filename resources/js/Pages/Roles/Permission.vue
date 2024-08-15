<script>
import { defineComponent, ref} from "vue";
import MyLayout from "@/Layouts/MyLayout.vue";
import { ArrowUndoCircleSharp as BackIcon } from '@vicons/ionicons5';
import {router} from "@inertiajs/vue3";
import { useMessage} from "naive-ui";

export default defineComponent({
    props: {
      permissions: Object,
      role: Object,
      rolePermissions: Object,
      flash: Object
    },
    components: {
        MyLayout,
        BackIcon,
    },

    setup (props) {
        const model = ref({
            permissionValue: Object.values(props.rolePermissions) // Eski roli permisyonlarni to'ldirish
        });
        const message = useMessage();
        const formRef = ref(null);
        const GoBack = () => {
            router.get(route('role.index'));
        };
        return {
            formRef,
            GoBack,
            message,
            model,
            permission: props.permissions,
            role: props.role,
            size: ref("medium"),
            generalOptions: props.permissions.map(
                (v) => ({
                    label: v.name,
                    value: v.id
                })
            ),
            rules: {
                permissionValue: {
                    type: "array",
                    required: true,
                    trigger: "change",
                    message: "Please input permissionValue"
                }
            },
            handleValidateButtonClick(e) {
                e.preventDefault();
                formRef.value?.validate((errors) => {
                    if (!errors) {
                        router.put(route('role.permission.add', {role: props.role.id}), formRef.value.model);
                        message.success("Valid");
                    } else {
                        console.log(errors);
                        message.error("Invalid");
                    }
                });
            }
        };

    }
})
</script>
<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height:700px;">
            <n-space justify="end" class="mb-4">
                <n-button type="primary" size="large" @click="GoBack">
                    <n-icon size="30">
                        <BackIcon />
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-space vertical :size="12">
                <!-- Xabarni ko'rsatish -->
                <n-alert
                    v-if="flash.success"
                    type="success"
                    show-icon
                    closable
                    style="margin-bottom: 16px">
                    {{ flash.success }}
                </n-alert>
            </n-space>
            <n-space align="center" size="large" item-style="width:100%">
                <n-form
                    ref="formRef"
                    :model="model"
                    :rules="rules"
                    :size="size"
                    label-placement="top"
                >
                    <n-grid :span="24" :x-gap="24" >
                        <n-form-item-gi :span="14"  path="permissionValue">
                            <template #label>
                                <span style="color: red;">{{ role.name }}</span> for Permission
                            </template>
                            <n-transfer
                                v-model:value="model.permissionValue"
                                style="width: 100%; height: 500px"
                                :options="generalOptions"
                            />
                        </n-form-item-gi>
                        <n-gi :span="24">
                            <div style="display: flex; justify-content: flex-start">
                                <n-button round type="primary" @click="handleValidateButtonClick">
                                    Submit
                                </n-button>
                            </div>
                        </n-gi>
                    </n-grid>
                </n-form>
            </n-space>

        </n-layout-content>
    </MyLayout>
</template>

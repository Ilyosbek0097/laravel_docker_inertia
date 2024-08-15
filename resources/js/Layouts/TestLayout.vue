<template>
    <n-layout>
        <!-- Header -->
        <n-layout-header bordered>
            <n-space justify="space-between" align="center">
                <!-- Chap tomonda logotip -->
                <div>Logo</div>
                <!-- O'ng tomonda menyu -->
                <n-space>
                    <n-button>Menu 1</n-button>
                    <n-button>Menu 2</n-button>
                    <n-button>Menu 3</n-button>
                </n-space>
            </n-space>
        </n-layout-header>

        <n-layout has-sider position="absolute">
            <!-- Sidebar -->
            <n-layout-sider collapse-mode="width" width="200" bordered>
                <n-menu
                    :options="[
            { label: 'Menu 1', key: '1' },
            { label: 'Menu 2', key: '2' },
            { label: 'Menu 3', key: '3' }
          ]"
                />
            </n-layout-sider>

            <!-- Content -->
            <n-layout-content style="padding: 24px;">
                <slot />
            </n-layout-content>
        </n-layout>

        <!-- Footer -->
        <n-layout-footer bordered>
            <div style="text-align: center;">© 2024 My Company</div>
        </n-layout-footer>
    </n-layout>
</template>

<script>
import { defineComponent } from 'vue';
import { NLayout, NLayoutHeader, NLayoutSider, NLayoutContent, NLayoutFooter, NSpace, NButton, NMenu } from 'naive-ui';

export default defineComponent({
    components: {
        NLayout,
        NLayoutHeader,
        NLayoutSider,
        NLayoutContent,
        NLayoutFooter,
        NSpace,
        NButton,
        NMenu,
    }
});
</script>

<style scoped>
/* O'zingizga mos qo'shimcha stillar */
</style>

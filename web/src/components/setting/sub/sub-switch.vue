<template>
    <el-switch
        v-model="item.value"
        :disabled="item.attr.disabled || false"
        :active-text="item.attr.active_text || ''"
        :inactive-text="item.attr.inactive_text || ''"
        :active-color="item.attr.active_color || '#409EFF'"
        :inactive-color="item.attr.inactive_color || '#C0CCDA'"
    >
    </el-switch>
</template>

<script>
    export default {
        name: 'sub-switch',
        props: {
            item: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

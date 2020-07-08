<template>
    <el-input-number
        v-model="item.value"
        controls-position="right"
        :placeholder="$i18n.t('PLEASE_INPUT', { value: item.label })"
        :min="item.attr.min || undefined"
        :max="item.attr.max || undefined"
        :step="item.attr.step || undefined"
        :step-strictly="item.attr.step_strictly || false"
        :precision="item.attr.precision || undefined"
        :disabled="item.attr.disabled || false"
        :controls="item.attr.controls || false"
    >
    </el-input-number>
</template>

<script>
    export default {
        name: 'sub-number',
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

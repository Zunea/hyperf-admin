<template>
    <el-input
        v-model="item.value"
        :placeholder="$i18n.t('PLEASE_INPUT', { value: item.title })"
        type="textarea"
        :clearable="item.attr.clearable || false"
        :disabled="item.attr.disabled || false"
        :rows="item.attr.rows && item.attr.rows > 0 ? item.attr.rows : 3"
    >
    </el-input>
</template>

<script>
    export default {
        name: 'sub-json',
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

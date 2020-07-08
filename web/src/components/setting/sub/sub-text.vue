<template>
    <div>
        <el-input
            v-model="item.value"
            :placeholder="$i18n.t('PLEASE_INPUT', { value: item.title })"
            :type="item.attr.type || 'text'"
            :maxlength="(item.attr.maxlength && item.attr.maxlength > 0) ? item.attr.maxlength : undefined"
            :show-word-limit="item.attr.show_word_limit || false"
            :clearable="item.attr.clearable || false"
            :show-password="item.attr.show_password || false"
            :disabled="item.attr.disabled || false"
            :prefix-icon="item.attr.prefix_icon || ''"
            :suffix-icon="item.attr.suffix_icon || ''"
            :rows="item.attr.rows && item.attr.rows > 0 ? item.attr.rows : 3"
        >
        </el-input>
    </div>
</template>

<script>
    export default {
        name: 'sub-text',
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

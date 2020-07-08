<template>
    <el-select
        v-model="item.value"
        :value="item.value"
        :placeholder="$i18n.t('PLEASE_SELECT', { value: item.label })"
        :disabled="item.attr.disabled || false"
        :clearable="item.attr.clearable || false"
        :collapse-tags="item.attr.collapse_tags || false"
        :multiple="item.attr.multiple || false"
        :multiple-limit="item.attr.multiple_limit && item.attr.multiple_limit > 0 ? item.attr.multiple_limit : undefined"
        :filterable="item.attr.filterable || false"
        :allow-create="item.attr.allow_create || false"
    >
        <el-option v-for="(option, optIndex) in item.options_parse" :label="option.label" :value="option.value" :key="optIndex"></el-option>
    </el-select>
</template>

<script>
    export default {
        name: 'sub-select',
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

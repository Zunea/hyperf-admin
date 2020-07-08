<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('CLEARABLE')">
                <el-switch v-model="attr.clearable"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('COLLAPSE_TAGS')">
                <el-switch v-model="attr.collapse_tags"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('MULTIPLE')">
                <el-switch v-model="attr.multiple"></el-switch>
            </el-form-item>
            <el-form-item v-if="attr.multiple" :label="$i18n.t('MULTIPLE_LIMIT')">
                <el-input-number v-model="attr.multiple_limit" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('FILTERABLE')">
                <el-switch v-model="attr.filterable"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('ALLOW_CREATE')">
                <el-switch v-model="attr.allow_create"></el-switch>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-select',
        props: {
            attr: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('MULTIPLE')">
                <el-switch v-model="attr.multiple"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_FILE_LIST')">
                <el-switch v-model="attr.show_file_list"></el-switch>
            </el-form-item>
            <el-form-item v-if="attr.show_file_list" :label="$i18n.t('FILE_LIST_TYPE')">
                <el-select v-model="attr.list_type" :value="attr.list_type">
                    <el-option :label="$i18n.t('TEXT')" value="text"></el-option>
                    <el-option :label="$i18n.t('PICTURE')" value="picture"></el-option>
                    <el-option :label="$i18n.t('PICTURE_CARD')" value="picture-card"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label="$i18n.t('UPLOAD_ACCEPT')">
                <el-input v-model="attr.accept"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('DRAG_UPLOAD')">
                <el-switch v-model="attr.drag"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('UPLOAD_LIMIT')">
                <el-input-number v-model="attr.limit" :min="0"></el-input-number>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-upload',
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

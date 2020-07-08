<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('CLEARABLE')">
                <el-switch v-model="attr.clearable"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SUFFIX_ICON')">
                <el-input :prefix-icon="attr.suffix_icon" v-model="attr.suffix_icon"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('PREFIX_ICON')">
                <el-input :suffix-icon="attr.prefix_icon" v-model="attr.prefix_icon"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('MAXLENGTH')">
                <el-input-number v-model="attr.maxlength" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_WORD_LIMIT')">
                <el-switch v-model="attr.show_word_limit"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_PASSWORD')">
                <el-switch v-model="attr.show_password"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('TEXT_INPUT_TYPE')">
                <el-select v-model="attr.type" :value="attr.type">
                    <el-option :label="$i18n.t('INPUT')" value="text"></el-option>
                    <el-option :label="$i18n.t('TEXTAREA')" value="textarea"></el-option>
                    <el-option :label="$i18n.t('PASSWORD')" value="password"></el-option>
                </el-select>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-text',
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

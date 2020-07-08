<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('START_DATE_PLACEHOLDER')">
                <el-input v-model="attr.start_placeholder"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('END_DATE_PLACEHOLDER')">
                <el-input v-model="attr.end_placeholder"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('ALIGN')">
                <el-select v-model="attr.align" :value="attr.align">
                    <el-option :label="$i18n.t('LEFT')" value="left"></el-option>
                    <el-option :label="$i18n.t('CENTER')" value="center"></el-option>
                    <el-option :label="$i18n.t('RIGHT')" value="right"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label="$i18n.t('IS_READONLY')">
                <el-switch v-model="attr.readonly"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('EDITABLE')">
                <el-switch v-model="attr.editable"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('CLEARABLE')">
                <el-switch v-model="attr.clearable"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('IS_RANGE')">
                <el-switch v-model="attr.range"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('ARROW_CONTROL')">
                <el-switch v-model="attr.arrow_control"></el-switch>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-time',
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

<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('START_DATE_PLACEHOLDER')">
                <el-input v-model="attr.start_placeholder"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('END_DATE_PLACEHOLDER')">
                <el-input v-model="attr.end_placeholder"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('DATE_TYPE')">
                <el-select v-model="attr.type" :value="attr.type">
                    <el-option :label="$i18n.t('YEAR')" value="year"></el-option>
                    <el-option :label="$i18n.t('MONTH')" value="month"></el-option>
                    <el-option :label="$i18n.t('DATE')" value="date"></el-option>
                    <el-option :label="$i18n.t('DATES')" value="dates"></el-option>
                    <el-option :label="$i18n.t('DATETIME')" value="datetime"></el-option>
                    <el-option :label="$i18n.t('DATETIME_RANGE')" value="datetimerange"></el-option>
                    <el-option :label="$i18n.t('DATE_RANGE')" value="daterange"></el-option>
                    <el-option :label="$i18n.t('MONTH_RANGE')" value="monthrange"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label="$i18n.t('INPUT_FORMAT')">
                <el-input v-model="attr.format" placeholder="yyyy-MM-dd"></el-input>
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
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-date',
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

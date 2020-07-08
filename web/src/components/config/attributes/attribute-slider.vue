<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('STEP_VALUE')">
                <el-input-number v-model="attr.step"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('MIN_VALUE')">
                <el-input-number v-model="attr.min"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('MAX_VALUE')">
                <el-input-number v-model="attr.max"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_INPUT')">
                <el-switch v-model="attr.show_input"></el-switch>
            </el-form-item>
            <el-form-item v-if="attr.show_input === true" :label="$i18n.t('SHOW_INPUT_CONTROLS')">
                <el-switch v-model="attr.show_input_controls"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_STOPS')">
                <el-switch v-model="attr.show_stops"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('SHOW_TOOLTIP')">
                <el-switch v-model="attr.show_tooltip"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('IS_RANGE')">
                <el-switch v-model="attr.range"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('IS_VERTICAL')">
                <el-switch v-model="attr.vertical"></el-switch>
            </el-form-item>
            <el-form-item v-if="attr.vertical === true" :label="$i18n.t('VERTICAL_HEIGHT')">
                <el-input v-model="attr.height"></el-input>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-slider',
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

<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('MIN_VALUE')">
                <el-input-number v-model="attr.min"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('MAX_VALUE')">
                <el-input-number v-model="attr.max"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('STEP_VALUE')">
                <el-input-number v-model="attr.step" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('STEP_STRICTLY')">
                <el-switch v-model="attr.step_strictly"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('PRECISION')">
                <el-input-number v-model="attr.precision" :max="3" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('USE_CONTROLS')">
                <el-switch v-model="attr.controls"></el-switch>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-number',
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

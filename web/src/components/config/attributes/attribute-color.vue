<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('SHOW_ALPHA')">
                <el-switch v-model="attr.border"></el-switch>
            </el-form-item>
            <el-form-item :label="$i18n.t('COLOR_FORMAT')">
                <el-select v-model="attr.color_format" :value="attr.color_format">
                    <el-option label="HSL" value="hsl"></el-option>
                    <el-option label="HSV" value="hsv"></el-option>
                    <el-option label="HEX" value="hex"></el-option>
                    <el-option label="RGB" value="rgb"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-color',
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

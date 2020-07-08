<template>
    <div>
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <el-form-item :label="$i18n.t('SWITCH_ACTIVE_TEXT')">
                <el-input v-model="attr.active_text"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('SWITCH_INACTIVE_TEXT')">
                <el-input v-model="attr.inactive_text"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('SWITCH_ACTIVE_COLOR')">
                <el-color-picker v-model="attr.active_color" show-alpha :predefine="predefineColors"></el-color-picker>
            </el-form-item>
            <el-form-item :label="$i18n.t('SWITCH_INACTIVE_COLOR')">
                <el-color-picker v-model="attr.inactive_color" show-alpha :predefine="predefineColors"></el-color-picker>
            </el-form-item>
            <el-form-item :label="$i18n.t('DISABLE')">
                <el-switch v-model="attr.disabled"></el-switch>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'attribute-switch',
        props: {
            attr: {
                type: Object,
                required: true
            }
        },
        data () {
            return {
                predefineColors: [
                    '#ff4500',
                    '#ff8c00',
                    '#ffd700',
                    '#90ee90',
                    '#00ced1',
                    '#1e90ff',
                    '#c71585',
                    'rgba(255, 69, 0, 0.68)',
                    'rgb(255, 120, 0)',
                    'hsv(51, 100, 98)',
                    'hsva(120, 40, 94, 0.5)',
                    'hsl(181, 100%, 37%)',
                    'hsla(209, 100%, 56%, 0.73)',
                    '#c7158577'
                ]
            }
        }
    }
</script>

<style scoped>

</style>

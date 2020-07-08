<template>
    <el-radio-group
        v-model="value"
        :disabled="item.attr.disabled || false"
        :border="item.attr.border || false"
    >
        <el-radio
            v-for="(checkbox, index) in item.options_parse"
            :label="checkbox.label"
            :value="checkbox.value"
            :border="item.attr.border || false"
            :key="index">{{ checkbox.label }}</el-radio>
    </el-radio-group>
</template>

<script>
    export default {
        name: 'sub-radio',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        computed: {
            value: {
                get () {
                    const result = this.item.options_parse.filter(item => this.item.value === item.value)
                    return result.length > 0 ? result[0].label : ''
                },
                set (value) {
                    const result = this.item.options_parse.filter(item => value === item.label)
                    this.item.value = result.length > 0 ? result[0].value : ''
                }
            }
        }
    }
</script>

<style scoped>

</style>

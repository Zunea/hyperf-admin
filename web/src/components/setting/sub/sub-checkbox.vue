<template>
    <el-checkbox-group
        v-if="item.options_parse"
        v-model="value"
        :disabled="item.attr.disabled || false"
        :max="item.attr.max || undefined"
    >
        <el-checkbox v-for="(checkbox, index) in item.options_parse" :label="checkbox.label" :value="checkbox.value" :key="index"></el-checkbox>
    </el-checkbox-group>
</template>

<script>
    export default {
        name: 'sub-checkbox',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        computed: {
            value: {
                get () {
                    return this.item.options_parse.filter(item => this.item.value.indexOf(item.value) > -1).map(item => item.label)
                },
                set (value) {
                    this.item.value = this.item.options_parse.filter(item => value.indexOf(item.label) > -1).map(item => item.value)
                }
            }
        }
    }
</script>

<style scoped>

</style>

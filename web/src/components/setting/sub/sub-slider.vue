<template>
    <el-slider
        v-model="value"
        :disabled="item.attr.disabled || false"
        :range="item.attr.range || false"
        :show-stops="item.attr.show_stops || undefined"
        :max="item.attr.max || undefined"
        :min="item.attr.min || undefined"
        :step="item.attr.step || 1"
        :show-input="item.attr.show_input || false"
        :show-input-controls="item.attr.show_input_controls || false"
        :show-tooltip="item.attr.show_tooltip || true"
        :vertical="item.attr.vertical || false"
        :height="item.attr.height || '100px'"
    >
    </el-slider>
</template>

<script>
    export default {
        name: 'sub-slider',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        computed: {
            value: {
                get () {
                    if (this.item.value instanceof Array && this.item.value.length === 0) {
                        return [this.item.attr.min || 1, this.item.attr.max || 100]
                    }
                    return this.item.value
                },
                set (value) {
                    this.item.value = value
                }
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <el-color-picker
        v-model="item.value"
        :disabled="item.attr.disabled || false"
        :show-alpha="item.attr.show_alpha || false"
        :color-format="item.attr.color_format || undefined"
        :predefine="predefineColors"
    >
    </el-color-picker>
</template>

<script>
    export default {
        name: 'sub-color',
        props: {
            item: {
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

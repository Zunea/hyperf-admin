<template>
    <el-date-picker
        v-model="value"
        :placeholder="$i18n.t('PLEASE_SELECT', { value: item.title })"
        :disabled="item.attr.disabled || false"
        :readonly="item.attr.readonly || false"
        :editable="item.attr.editable || true"
        :clearable="item.attr.clearable || true"
        :start-placeholder="item.attr.start_placeholder || $i18n.t('PLEASE_SELECT', { value: item.title })"
        :end-placeholder="item.attr.end_placeholder || $i18n.t('PLEASE_SELECT', { value: item.title })"
        :type="item.attr.type || 'date'"
        :style="{ width: item.attr.type === 'dates' ? '100%' : 'auto' }"
        :format="item.attr.format || undefined"
        :align="item.attr.align || 'left'"
    >
    </el-date-picker>
</template>

<script>
    import moment from 'moment'

    export default {
        name: 'sub-date',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        computed: {
            value: {
                // 标准日期格式转Date对象
                get () {
                    switch (this.item.attr.type) {
                    case 'dates': // 数组类型
                    case 'daterange':
                    case 'datetimerange':
                    case 'monthrange':
                        // 当前值如果是数组类型，文本转Date对象后直接返回
                        if (this.item.value instanceof Array) {
                            if (this.item.value.length === 0) return []
                            return this.item.value.map(item => new Date(item.replace(/-/g, '/')))
                        }
                        // 当前数据如果是字符串类型，文本转Date对象后返回数组
                        if (typeof this.item.value === 'string') {
                            if (!this.item.value) return []
                            return [new Date(this.item.value.replace(/-/g, '/'))]
                        }
                        // 非数组非文本直接返回空数组
                        return []

                    default: // 文本类型
                        // 当前值如果是数组，取第一条Date对象转标准日期格式返回
                        if (this.item.value instanceof Array) {
                            if (this.item.value.length === 0) return null
                            return new Date(this.item.value[0].replace(/-/g, '/'))
                        }
                        // 当前值如果是文本，Date对象转标准日期格式返回
                        if (typeof this.item.value === 'string') {
                            if (!this.item.value) return null
                            return new Date(this.item.value.replace(/-/g, '/'))
                        }
                        // 非文本非数组类型直接返回null
                        return null
                    }
                },
                set (value) {
                    // Date对象转标准日期格式
                    if (value instanceof Array) {
                        this.item.value = value.map(item => this.formatDate(item))
                    } else {
                        this.item.value = this.formatDate(value)
                    }
                }
            }
        },
        methods: {
            // 格式化日期
            formatDate (dateObj) {
                const momentObj = moment(dateObj)
                switch (this.item.attr.type) {
                case 'year': // 年
                    return momentObj.format('YYYY')

                case 'month': // 月
                case 'monthrange':
                    return momentObj.format('YYYY-MM')

                case 'date': // 日期
                case 'dates':
                case 'daterange':
                    return momentObj.format('YYYY-MM-DD')

                case 'datetime': // 日期时间
                case 'datetimerange':
                    return momentObj.format('YYYY-MM-DD HH:mm:ss')

                default:
                    return momentObj.format('YYYY-MM-DD HH:mm:ss')
                }
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <el-time-picker
        v-model="value"
        :placeholder="$i18n.t('PLEASE_SELECT', { value: item.title })"
        :start-placeholder="item.attr.start_placeholder || $i18n.t('PLEASE_SELECT', { value: item.title })"
        :end-placeholder="item.attr.end_placeholder || $i18n.t('PLEASE_SELECT', { value: item.title })"
        :disabled="item.attr.disabled || false"
        :readonly="item.attr.readonly || false"
        :editable="item.attr.editable || true"
        :clearable="item.attr.clearable || true"
        :is-range="item.attr.range || false"
        :arrow-control="item.attr.arrow_control || false"
    >
    </el-time-picker>
</template>

<script>
    export default {
        name: 'sub-time',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        computed: {
            value: {
                get () {
                    if (this.item.value instanceof Array) {
                        if (this.item.value.length === 0) {
                            return [new Date(), new Date()]
                        }
                        return this.item.value.map(item => this.string2Date(item))
                    }
                    if (!this.item.value) return new Date()
                    return this.string2Date(this.item.value)
                },
                set (value) {
                    if (value instanceof Array) {
                        this.item.value = value.map(item => this.date2String(item))
                    } else {
                        this.item.value = this.date2String(value)
                    }
                }
            }
        },
        methods: {
            string2Date (value) {
                const splitTime = value.split(':')
                const date = new Date()
                date.setHours(splitTime[0])
                date.setMinutes(splitTime[1])
                date.setSeconds(splitTime[2])
                return date
            },
            date2String (value) {
                // 时补0
                let hours = value.getHours()
                hours = hours <= 9 ? `0${hours}` : hours

                // 分补0
                let minutes = value.getMinutes()
                minutes = minutes <= 9 ? `0${minutes}` : minutes

                // 秒补0
                let seconds = value.getSeconds()
                seconds = seconds <= 9 ? `0${seconds}` : seconds

                return `${hours}:${minutes}:${seconds}`
            }
        }
    }
</script>

<style scoped>

</style>

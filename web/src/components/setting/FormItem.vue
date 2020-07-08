<template>
    <div>
        <el-form-item v-for="(item, index) in data" :key="index" :label="item.title">
            <sub-input v-if="item.type === 'text'" :item="item"></sub-input>
            <sub-key-value v-if="item.type === 'keyValue'" :item="item"></sub-key-value>
            <sub-number v-if="item.type === 'number'" :item="item"></sub-number>
            <sub-checkbox v-if="item.type === 'checkbox'" :item="item"></sub-checkbox>
            <sub-radio v-if="item.type === 'radio'" :item="item"></sub-radio>
            <sub-date v-if="item.type === 'date'" :item="item"></sub-date>
            <sub-time v-if="item.type === 'time'" :item="item"></sub-time>
            <sub-switch v-if="item.type === 'switch'" :item="item"></sub-switch>
            <sub-select v-if="item.type === 'select'" :item="item"></sub-select>
            <sub-upload v-if="item.type === 'upload'" :item="item"></sub-upload>
            <sub-slider v-if="item.type === 'slider'" :item="item"></sub-slider>
            <sub-color v-if="item.type === 'color'" :item="item"></sub-color>
            <div class="tips">{{ item.tips }}</div>
        </el-form-item>
    </div>
</template>

<script>
    import SubInput from '@/components/setting/sub/sub-text'
    import SubNumber from '@/components/setting/sub/sub-number'
    import SubCheckbox from '@/components/setting/sub/sub-checkbox'
    import SubRadio from '@/components/setting/sub/sub-radio'
    import SubDate from '@/components/setting/sub/sub-date'
    import SubTime from '@/components/setting/sub/sub-time'
    import SubSwitch from '@/components/setting/sub/sub-switch'
    import SubSelect from '@/components/setting/sub/sub-select'
    import SubUpload from '@/components/setting/sub/sub-upload'
    import SubKeyValue from '@/components/setting/sub/sub-key-value'
    import SubSlider from '@/components/setting/sub/sub-slider'
    import SubColor from '@/components/setting/sub/sub-color'

    export default {
        name: 'FormItem',
        components: {
            SubColor,
            SubSlider,
            SubKeyValue,
            SubUpload,
            SubInput,
            SubNumber,
            SubCheckbox,
            SubRadio,
            SubSwitch,
            SubDate,
            SubTime,
            SubSelect
        },
        props: {
            data: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>
    .tips {
        color: #a4a4a4;
        font-size: 12px;
    }
</style>

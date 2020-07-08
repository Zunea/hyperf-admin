<template>
    <Form label-position="right" ref="formInline" :label-width="100" inline @keyup.enter.native="onSubmit">
        <form-item v-for="(item) in items" :key="item.key" :label="item.title">
            <!-- 输入框 -->
            <el-input
                v-if="item.type === 'input'"
                type="text"
                :placeholder="$i18n.t('PLEASE_INPUT', { value: item.title })"
                v-model="params[item.key]"
                clearable
                class="input-class"
                :maxlength="item.maxlength || undefined"
            >
            </el-input>

            <!-- 下拉框 -->
            <el-select
                v-else-if="item.type === 'select'"
                v-model="params[item.key]"
                :value="params[item.key]"
                :placeholder="$i18n.t('PLEASE_SELECT', { value: item.title })"
                class="input-class"
                clearable
            >
                <el-option v-for="(option, index) in item.options" :key="index" :label="option.label" :value="option.value"></el-option>
            </el-select>

            <!-- 日期范围 -->
            <date-picker
                v-else-if="item.type === 'date-range'"
                type="daterange"
                :placeholder="$i18n.t('PLEASE_SELECT_DATE_RANGE')"
                class="input-class"
                @on-change="(value) => { params[item.key] = value }"
            />
        </form-item>
        <form-item>
            <el-button type="primary" @click="onSubmit" :loading="btnLoading">{{ $i18n.t('SEARCH') }}</el-button>
        </form-item>
    </Form>
</template>

<script>
    export default {
        name: 'SearchForm',
        props: {
            items: {
                type: Array,
                required: true
            },
            params: {
                type: Object,
                required: true
            },
            onSubmit: {
                type: Function,
                required: true
            },
            btnLoading: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="less" scoped>
    @font-size: 12px;

    .input-class {
        font-size: @font-size;
        width: 180px;
    }
    /deep/ .el-input,.ivu-input {
        font-size: @font-size;
    }
    /deep/ .ivu-input {
        font-size: @font-size;
    }
    /deep/ .ivu-form-item-label {
        font-size: @font-size;
    }
</style>

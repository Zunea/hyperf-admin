<template>
    <div class="body">
        <el-form :model="mdl" ref="ruleForm" label-width="130px">
            <el-form-item required :label="$i18n.t('CONFIG_GROUP')" :error="errors.group">
                <el-select v-model="mdl.group" :value="mdl.group">
                    <el-option v-for="(option, index) in groupSelectList" :label="option.label" :value="option.value" :key="index"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label="$i18n.t('CONFIG_TITLE')" required :error="errors.title">
                <el-input v-model="mdl.title" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('CONFIG_TITLE') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('CONFIG_NAME')" required :error="errors.name">
                <el-input v-model="mdl.name" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('CONFIG_NAME') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('CONFIG_TYPE')" required :error="errors.type">
                <div style="width: 100%">
                    <el-select v-model="mdl.type" :value="mdl.type" :placeholder="$i18n.t('PLEASE_SELECT', { value: $i18n.t('CONFIG_TYPE') })" style="width: 68%" @change="mdl.attr = {}">
                        <el-option v-for="(option, index) in $store.getters.options.AdminConfigTypeList" :label="option.label" :value="option.value" :key="index"></el-option>
                    </el-select>
                    <el-button icon="el-icon-more" style="width: 29%;margin-left: 5px;" @click="onOpenAttributesConfig(mdl)"></el-button>
                </div>
            </el-form-item>
            <el-form-item :label="$i18n.t('CONFIG_OPTIONS')" :error="errors.options">
                <el-input type="textarea" v-model="mdl.options" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('CONFIG_OPTIONS') })" :autosize="{ minRows: 4 }"></el-input>
            </el-form-item>
            <el-form-item>
                <Alert show-icon style="font-size: 12px">{{ $i18n.t('CONFIG_OPTIONS_TIP') }}</Alert>
            </el-form-item>
            <el-form-item :label="$i18n.t('TIP')" :error="errors.tips">
                <el-input v-model="mdl.tips" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('TIP') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('CONFIG_FORMAT')" :error="errors.format">
                <el-input v-model="mdl.format" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('CONFIG_FORMAT') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('SORT')" :error="errors.sort">
                <el-input-number v-model="mdl.sort" controls-position="right" :min="0" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('SORT') })" @focus="errors.sort = ''"></el-input-number>
            </el-form-item>
            <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('IS_ENABLE')" :error="errors.is_enable">
                <el-select v-model="mdl.is_enable" :value="mdl.is_enable">
                    <el-option v-for="(option, index) in enableOptions" :label="option.label" :value="option.value" :key="index"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :error="errors.submit">
                <el-popconfirm
                    v-if="mdl.id && mdl.id > 0"
                    @onConfirm="submitForm"
                    :title="$i18n.t('CONFIG_SUBMIT_TIP')"
                    :confirmButtonText="$i18n.t('CONFIRM')"
                    :cancelButtonText="$i18n.t('CANCEL')"
                >
                    <el-button slot="reference" type="primary" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
                </el-popconfirm>
                <el-button v-else @click="submitForm" type="primary" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
                <el-button @click="handleCloseDrawer" style="margin-left: 10px;">{{ $i18n.t('CANCEL') }}</el-button>
            </el-form-item>
            <el-form-item v-if="errorMessage !== ''">
                <Alert type="error" show-icon>{{ errorMessage }}</Alert>
            </el-form-item>
        </el-form>
        <el-drawer
            :title="$i18n.t('ATTRIBUTE_CONFIG')"
            :visible.sync="attributeVisible"
            :append-to-body="true"
            size="400px"
        >
            <attribute-text v-if="mdl.type === 'text'" :attr="mdl.attr"></attribute-text>
            <attribute-key-value v-if="mdl.type === 'keyValue'" :attr="mdl.attr"></attribute-key-value>
            <attribute-number v-if="mdl.type === 'number'" :attr="mdl.attr"></attribute-number>
            <attribute-checkbox v-if="mdl.type === 'checkbox'" :attr="mdl.attr"></attribute-checkbox>
            <attribute-radio v-if="mdl.type === 'radio'" :attr="mdl.attr"></attribute-radio>
            <attribute-date v-if="mdl.type === 'date'" :attr="mdl.attr"></attribute-date>
            <attribute-time v-if="mdl.type === 'time'" :attr="mdl.attr"></attribute-time>
            <attribute-switch v-if="mdl.type === 'switch'" :attr="mdl.attr"></attribute-switch>
            <attribute-select v-if="mdl.type === 'select'" :attr="mdl.attr"></attribute-select>
            <attribute-upload v-if="mdl.type === 'upload'" :attr="mdl.attr"></attribute-upload>
            <attribute-slider v-if="mdl.type === 'slider'" :attr="mdl.attr"></attribute-slider>
            <attribute-color v-if="mdl.type === 'color'" :attr="mdl.attr"></attribute-color>
        </el-drawer>
    </div>
</template>

<script>
    import { addConfig, updateConfig } from '@/api/default/config'
    import AttributeText from '@/components/config/attributes/attribute-text'
    import AttributeNumber from '@/components/config/attributes/attribute-number'
    import AttributeCheckbox from '@/components/config/attributes/attribute-checkbox'
    import AttributeRadio from '@/components/config/attributes/attribute-radio'
    import AttributeDate from '@/components/config/attributes/attribute-date'
    import AttributeTime from '@/components/config/attributes/attribute-time'
    import AttributeSwitch from '@/components/config/attributes/attribute-switch'
    import AttributeSelect from '@/components/config/attributes/attribute-select'
    import AttributeUpload from '@/components/config/attributes/attribute-upload'
    import AttributeKeyValue from '@/components/config/attributes/attribute-key-value'
    import AttributeSlider from '@/components/config/attributes/attribute-slider'
    import AttributeColor from '@/components/config/attributes/attribute-color'

    export default {
        name: 'formModal',
        components: {
            AttributeColor,
            AttributeSlider,
            AttributeKeyValue,
            AttributeUpload,
            AttributeSelect,
            AttributeSwitch,
            AttributeTime,
            AttributeDate,
            AttributeRadio,
            AttributeCheckbox,
            AttributeNumber,
            AttributeText
        },
        props: {
            mdl: {
                type: Object,
                required: true
            },
            handleCloseDrawer: {
                type: Function,
                required: true
            }
        },
        watch: {
            mdl: {
                handler (newVal) {
                    console.log(newVal)
                    this.btnDisable = false
                },
                deep: true
            }
        },
        computed: {
            options () {
                let value = ''
                Object.keys(this.mdl.options).forEach((key) => {
                    value += (`${key}:${this.mdl.options[key]}\r\n`)
                })
                return value
            },
            // 配置类型
            groupSelectList () {
                return this.$store.getters.options.AdminConfigGroup
            }
        },
        data () {
            return {
                // 启用状态
                enableOptions: [
                    { label: this.$i18n.t('ENABLE'), value: true },
                    { label: this.$i18n.t('DISABLE'), value: false }
                ],
                rules: {},
                errors: {
                    name: '',
                    title: '',
                    group: '',
                    type: '',
                    tips: '',
                    format: ''
                },
                submitLogin: false,
                btnDisable: true,
                errorMessage: '',
                attributeVisible: false
            }
        },
        methods: {
            submitForm () {
                // 先清空表单错误提示
                this.errors = {}
                this.submitLogin = true
                // 设置手动接管message提示
                this.$store.dispatch('MessageTake', true)
                this.errorMessage = ''
                if (this.mdl.id === 0) {
                    addConfig(this.mdl).then(() => {
                        // 关闭抽屉
                        this.handleCloseDrawer()
                        this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                        // 调用爷爷组件刷新数据
                        this.$parent.$parent.fetch()
                    }).catch((error) => {
                        // 后端过来的表单错误回显
                        this.errors = Object.assign({}, { ...this.errors, ...error.errors })
                        // 手动处理错误
                        if (!error.errors) {
                            this.errorMessage = error.message
                        }
                    }).finally(() => {
                        this.submitLogin = false
                    })
                } else {
                    updateConfig(this.mdl.id, this.mdl).then(() => {
                        // 关闭抽屉
                        this.handleCloseDrawer()
                        this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                        // 调用爷爷组件刷新数据
                        this.$parent.$parent.fetch()
                    }).catch((error) => {
                        // 后端过来的表单错误回显
                        this.errors = Object.assign({}, { ...this.errors, ...error.errors })
                        // 手动处理错误
                        if (!error.errors) {
                            this.errorMessage = error.message
                        }
                    }).finally(() => {
                        this.submitLogin = false
                    })
                }
            },
            onCheckboxGroupChange (checked, key, options) {
                this.mdl[key] = checked.map((item) => {
                    const opt = options.filter(option => option.label === item)
                    return opt[0].value
                })
                console.log(this.mdl[key])
            },
            onOpenAttributesConfig () {
                this.attributeVisible = !this.attributeVisible
            }
        }
    }
</script>

<style lang="less" scoped>
    .body {
        padding-right: 25px;
        width: 100%;
    }
    /deep/ .el-checkbox__label {
        font-size: 12px;
    }
</style>

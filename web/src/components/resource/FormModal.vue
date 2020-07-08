<template>
    <div class="body">
        <el-form :model="mdl" ref="ruleForm" label-width="130px">
            <el-form-item label="ID" v-if="mdl.id && mdl.id > 0">
                <el-input :value="mdl.id" disabled></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('RESOURCE_NAME')" required :error="errors.name">
                <el-input v-model="mdl.name" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('RESOURCE_NAME') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('RESOURCE_PATH')" required :error="errors.path">
                <el-input v-model="mdl.path" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('RESOURCE_PATH') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('RESOURCE_METHOD')" required :error="errors.method">
                <el-select :placeholder="$i18n.t('PLEASE_SELECT', { value: $i18n.t('RESOURCE_METHOD') })" v-model="mdl.method" :value="mdl.method">
                    <el-option v-for="(option, index) in methodOptions" :label="option.label" :value="option.value" :key="index"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :error="errors.submit">
                <el-button type="primary" @click="submitForm('ruleForm')" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
                <el-button @click="handleCloseDrawer">{{ $i18n.t('CANCEL') }}</el-button>
            </el-form-item>
            <el-form-item v-if="errorMessage !== ''">
                <Alert type="error" show-icon>{{ errorMessage }}</Alert>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { addResource, updateResource } from '@/api/default/resource'

    export default {
        name: 'formModal',
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
                handler () {
                    this.btnDisable = false
                },
                deep: true
            }
        },
        data () {
            return {
                // 用户组状态
                methodOptions: [
                    { label: 'GET', value: 'GET' },
                    { label: 'POST', value: 'POST' },
                    { label: 'PUT', value: 'PUT' },
                    { label: 'DELETE', value: 'DELETE' }
                ],
                rules: {},
                errors: {
                    name: '',
                    path: '',
                    method: ''
                },
                submitLogin: false,
                btnDisable: true,
                errorMessage: ''
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
                    addResource(this.mdl).then(() => {
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
                    updateResource(this.mdl.id, this.mdl).then(() => {
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

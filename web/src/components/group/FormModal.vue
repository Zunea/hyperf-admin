<template>
    <div class="body">
        <el-form :model="mdl" ref="ruleForm" label-width="130px">
            <el-form-item label="ID" v-if="mdl.id && mdl.id > 0">
                <el-input :value="mdl.id" disabled></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('USER_GROUP_NAME')" required :error="errors.name">
                <el-input v-model="mdl.name" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('USER_GROUP_NAME') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('REMARK')" :error="errors.remark">
                <el-input type="textarea" v-model="mdl.remark" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('REMARK') })"></el-input>
            </el-form-item>
            <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('STATUS')">
                <el-select v-model="mdl.status" :value="mdl.status">
                    <el-option v-for="(option, index) in statusOptions" :label="option.label" :value="option.value" :key="index"></el-option>
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
    import { addGroup, updateGroup } from '@/api/default/group'

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
                statusOptions: [
                    { label: this.$i18n.t('NORMAL'), value: 1 },
                    { label: this.$i18n.t('DISABLE'), value: 2 }
                ],
                rules: {},
                errors: {
                    name: '',
                    remark: '',
                    status: ''
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
                    addGroup(this.mdl).then(() => {
                        // 关闭抽屉
                        this.handleCloseDrawer()
                        this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                        // 调用爷爷组件刷新数据
                        this.$parent.$parent.fetch()
                        // 刷新选项列表
                        this.$store.dispatch('GetOptions').then(() => {})
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
                    updateGroup(this.mdl.id, this.mdl).then(() => {
                        // 关闭抽屉
                        this.handleCloseDrawer()
                        this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                        // 调用爷爷组件刷新数据
                        this.$parent.$parent.fetch()
                        // 刷新选项列表
                        this.$store.dispatch('GetOptions').then(() => {})
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

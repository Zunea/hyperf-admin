<template>
    <div class="body">
        <el-form :model="mdl" ref="ruleForm" label-width="130px">
            <el-form-item label="UID" v-if="mdl.id && mdl.id > 0">
                <el-input :value="mdl.id" disabled></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('USERNAME')" required :error="errors.username">
                <el-input v-model="mdl.username" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('USERNAME') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('LOGIN_PASSWORD')" :required="!(mdl.id && mdl.id > 0)" :error="errors.password">
                <el-input v-model="mdl.password" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('LOGIN_PASSWORD') })" type="password"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('PHONE_NUMBER')" required :error="errors.phone">
                <el-input v-model="mdl.phone" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('PHONE_NUMBER') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('NICKNAME')" :error="errors.nickname">
                <el-input v-model="mdl.nickname" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('NICKNAME') })"></el-input>
            </el-form-item>
            <el-form-item :label="$i18n.t('EMAIL')" :error="errors.email">
                <el-input v-model="mdl.email" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('EMAIL') })"></el-input>
            </el-form-item>
            <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('STATUS')">
                <el-select v-model="mdl.status" :value="mdl.status">
                    <el-option v-for="(option, index) in statusOptions" :label="option.label" :value="option.value" :key="index"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-if="$store.getters.options.AdminGroupList.length > 0" :label="$i18n.t('USER_GROUP')">
                <el-checkbox-group v-model="groupIds">
                    <el-checkbox v-for="(option, index) in $store.getters.options.AdminGroupList" :label="option.label" :value="option.value" :key="index"></el-checkbox>
                </el-checkbox-group>
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
    import { addUser, updateUser } from '@/api/default/user'

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
                // 用户状态
                statusOptions: [
                    { label: this.$i18n.t('NORMAL'), value: 1 },
                    { label: this.$i18n.t('DISABLE'), value: 2 }
                ],
                rules: {},
                errors: {
                    username: '',
                    password: '',
                    nickname: '',
                    phone: '',
                    email: ''
                },
                submitLogin: false,
                btnDisable: true,
                errorMessage: ''
            }
        },
        computed: {
            groupIds: {
                get () {
                    return this.mdl.groups.map(item => item.name)
                },
                set (newVal) {
                    this.mdl.groups = newVal.map((item) => {
                        const option = this.$store.getters.options.AdminGroupList.filter(opt => item === opt.label)
                        return { id: option[0].value, name: item }
                    })
                }
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
                    addUser(this.mdl).then(() => {
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
                    updateUser(this.mdl.id, this.mdl).then(() => {
                        // 关闭抽屉
                        this.handleCloseDrawer()
                        this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                        // 调用爷爷组件刷新数据
                        this.$parent.$parent.fetch()
                    }).catch((error) => {
                        // 后端过来的表单错误回显
                        this.errors = Object.assign({}, { ...this.errors, ...error.errors })
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
}
/deep/ .el-checkbox__label {
    font-size: 12px;
}
</style>

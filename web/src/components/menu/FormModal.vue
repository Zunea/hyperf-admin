<template>
    <div class="body">
        <el-form :model="mdl" ref="ruleForm" label-width="130px" style="padding-right: 50px">
            <el-form-item label="ID" v-if="mdl.id && mdl.id > 0">
                <el-input :value="mdl.id" disabled style="width: 120px"></el-input>
            </el-form-item>
            <el-form-item v-if="!(mdl.id && mdl.id > 0)" :label="$i18n.t('MENU_TYPE')" required :error="errors.type">
                <el-radio v-model="mdl.type" :label="1">{{ $i18n.t('MENU') }}</el-radio>
                <el-radio v-model="mdl.type" :label="2">{{ $i18n.t('BUTTON') }}</el-radio>
            </el-form-item>
            <el-tooltip effect="dark" :content="$i18n.t('MENU_PARENT_TIP')" placement="top-end">
                <el-form-item :label="$i18n.t('PARENT_MENU')" :error="errors.parent_id">
                    <!-- 上级菜单 -->
                    <el-cascader
                        clearable
                        v-model="mdl.parent_id"
                        :options="menusTree"
                        :props="{ checkStrictly: true, expandTrigger: 'hover', emitPath: false }"
                        style="width: 100%"
                        :placeholder="$i18n.t('PLEASE_SELECT', { value: $i18n.t('PARENT_MENU') })"
                        filterable
                    >
                    </el-cascader>
                </el-form-item>
            </el-tooltip>
            <div v-if="mdl.type === 1">
                <el-form-item :label="$i18n.t('MENU_NAME')" required :error="errors.name">
                    <el-input v-model="mdl.name" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('MENU_NAME') })" @focus="errors.name = ''"></el-input>
                </el-form-item>
                <el-form-item>
                    <Alert show-icon style="font-size: 12px">{{ $i18n.t('MENU_NAME_TIP') }}</Alert>
                </el-form-item>
                <el-form-item :label="$i18n.t('ICON')" :error="errors.icon">
                    <el-input :prefix-icon="mdl.icon || 'el-icon-custom-menu'" v-model="mdl.icon" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('ICON') })" @focus="errors.icon = ''"></el-input>
                </el-form-item>
                <el-form-item :label="$i18n.t('MENU_PATH')" required :error="errors.path">
                    <el-input v-model="mdl.path" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('MENU_PATH') })" @focus="errors.path = ''"></el-input>
                </el-form-item>
                <el-form-item :label="$i18n.t('MENU_COMPONENT')" :error="errors.component">
                    <el-select :placeholder="$i18n.t('PLEASE_SELECT', { value: $i18n.t('MENU_COMPONENT') })" filterable clearable v-model="mdl.component" @visible-change="errors.component = ''" style="width: 100%">
                        <el-option v-for="component in components" :label="component" :value="component" :key="component"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('LEFT_SHOW')">
                    <el-switch v-model="mdl.left_show"></el-switch>
                </el-form-item>
                <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('TOP_SHOW')">
                    <el-switch v-model="mdl.top_show"></el-switch>
                </el-form-item>
                <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('PAGE_METHOD')" required :error="errors.target">
                    <el-select v-model="mdl.target" :value="mdl.status">
                        <el-option v-for="(option, index) in targetOptions" :label="option.label" :value="option.value" :key="index" @focus="errors.target = ''"></el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div v-if="mdl.type === 2">
                <el-form-item :label="$i18n.t('MENU_BUTTON_AUTH')" required :error="errors.path" @focus="errors.path = ''">
                    <el-input v-model="mdl.path" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('MENU_BUTTON_AUTH') })"></el-input>
                </el-form-item>
            </div>
            <el-form-item :label="$i18n.t('SORT')" :error="errors.sort">
                <el-input-number v-model="mdl.sort" controls-position="right" :min="0" :placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('SORT') })" @focus="errors.sort = ''"></el-input-number>
            </el-form-item>
            <el-form-item v-if="mdl.id && mdl.id > 0" :label="$i18n.t('STATUS')" required>
                <el-select v-model="mdl.status" :value="mdl.status">
                    <el-option v-for="(option, index) in statusOptions" :label="option.label" :value="option.value" :key="index"></el-option>
                </el-select>
            </el-form-item>
        </el-form>
        <div class="bottom">
            <el-button type="primary" @click="submitForm('ruleForm')" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
            <el-button @click="handleCloseDrawer">{{ $i18n.t('CANCEL') }}</el-button>
            <Alert v-if="errorMessage !== ''" type="error" show-icon>{{ errorMessage }}</Alert>
        </div>
    </div>
</template>

<script>
    import { addMenu, updateMenu } from '@/api/default/menu'
    import { constantRouterComponents } from '@/router/generator-routers'
    import { array2tree } from '@/utils'

    export default {
        name: 'formModal',
        props: {
            mdl: {
                type: Object,
                required: true
            },
            menus: {
                type: Array,
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
                // 页面打开方式
                targetOptions: [
                    { label: this.$i18n.t('SELF_PAGE_OPEN'), value: 1 },
                    { label: this.$i18n.t('NEW_PAGE_OPEN'), value: 2 }
                ],
                // 状态
                statusOptions: [
                    { label: this.$i18n.t('NORMAL'), value: 1 },
                    { label: this.$i18n.t('DISABLED'), value: 2 }
                ],
                rules: {},
                errors: {
                    name: '',
                    type: '',
                    parent_id: '',
                    icon: '',
                    path: '',
                    component: '',
                    sort: '',
                    status: '',
                    target: ''
                },
                submitLogin: false,
                btnDisable: true,
                errorMessage: ''
            }
        },
        computed: {
            components () {
                // 过滤组件
                const filter = ['BasicLayout', 'RouteView']
                return Object.keys(constantRouterComponents).filter(item => filter.indexOf(item) === -1)
            },
            menusTree () {
                const childrenNav = []
                array2tree(this.menus.filter(item => item.type === 1).map(item => ({
                    id: item.id,
                    value: item.id,
                    label: this.$i18n.t(`menu.${item.name}`),
                    parent_id: item.parent_id,
                    type: item.type
                })), childrenNav, 0)
                return childrenNav
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
                    addMenu(this.mdl).then(() => {
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
                    updateMenu(this.mdl.id, this.mdl).then(() => {
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
    width: 100%;
    .bottom {
        margin-top: 45px;
        text-align: center;
    }
}
/deep/ .el-checkbox__label {
    font-size: 12px;
}
</style>

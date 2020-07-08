<template>
    <div class="body">
        <div class="tree">
            <el-tree
                :data="menusTree"
                show-checkbox
                node-key="id"
                ref="tree"
                :expand-on-click-node="false"
                :indent="26"
                :default-checked-keys="checkedKeys"
                :default-expanded-keys="checkedKeys"
                check-strictly
                accordion
                @check-change="btnDisable = false"
                style="padding-right: 50px"
            >
                <span class="custom-tree-node" slot-scope="{ node, data }">
                    <i :class="data.type === 1 ? data.icon: 'el-icon-thumb'" style="font-size: 13px;padding-right: 4px;"></i>
                    <span style="font-size: 13px">{{ node.label }}</span>
                </span>
            </el-tree>
        </div>
        <div class="bottom">
            <el-button type="primary" @click="submitForm()" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
            <el-button @click="handleCloseDrawer">{{ $i18n.t('CANCEL') }}</el-button>
            <div v-if="errorMessage !== ''" style="margin: 20px auto 0">
                <Alert type="error" show-icon>{{ errorMessage }}</Alert>
            </div>
        </div>
    </div>
</template>

<script>
    import { updateGroupMenu } from '@/api/default/group'
    import { getMenu } from '@/api/default/menu'
    import { array2tree } from '@/utils'

    export default {
        name: 'menuFormModal',
        props: {
            groupId: {
                type: Number,
                required: true
            },
            groupMenus: {
                type: Array,
                required: true
            },
            handleCloseDrawer: {
                type: Function,
                required: true
            }
        },
        data () {
            return {
                submitLogin: false,
                btnDisable: true,
                menus: [],
                errorMessage: ''
            }
        },
        created () {
            getMenu().then((res) => {
                const { result } = res
                this.menus = result.map(item => ({
                    id: item.id,
                    parent_id: item.parent_id,
                    label: item.type === 1 ? this.$i18n.t(`menu.${item.name}`) : item.path,
                    icon: item.icon,
                    type: item.type
                }))
            })
        },
        computed: {
            menusTree () {
                const childrenNav = []
                array2tree(this.menus, childrenNav, 0)
                return childrenNav
            },
            checkedKeys () {
                return this.groupMenus.map(item => item.id)
            }
        },
        methods: {
            submitForm () {
                this.submitLogin = true
                // 设置手动接管message提示
                this.$store.dispatch('MessageTake', true)
                this.errorMessage = ''
                updateGroupMenu(this.groupId, { menuIds: this.$refs.tree.getCheckedKeys() }).then(() => {
                    // 关闭抽屉
                    this.handleCloseDrawer()
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    // 调用爷爷组件刷新数据
                    this.$parent.$parent.fetch()
                }).catch((error) => {
                    // 手动处理错误
                    this.errorMessage = error.message
                }).finally(() => {
                    this.submitLogin = false
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    .body {
        width: 300px;
        padding-right: 20px;
        padding-left: 20px;
        height: 100%;
        .tree {
            height: 75%;
            overflow-y: auto;
            padding-bottom: 50px;
        }
        .bottom {
            margin-top: 20px;
            height: 15%;
            text-align: center;
        }
    }
</style>

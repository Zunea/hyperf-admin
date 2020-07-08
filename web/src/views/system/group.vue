<template>
    <div>
        <!-- 表格区域 -->
        <div class="table-body">
            <!-- 左上角按钮 -->
            <div class="table-right-top">
                <el-button v-auth="'add'" type="success" icon="el-icon-plus" @click="onAdd">{{ $i18n.t('CREATE_USER_GROUP') }}</el-button>
            </div>

            <!-- 表格 -->
            <el-table border style="width: 100%;" stripe :data="data" v-loading="loading">
                <el-table-column align="center" sortable prop="id" label="ID" :width="100" fixed="left"></el-table-column>
                <el-table-column align="center" prop="name" :label="$i18n.t('USER_GROUP')" :min-width="200"></el-table-column>
                <el-table-column align="center" prop="remark" :label="$i18n.t('REMARK')" :min-width="250"></el-table-column>
                <el-table-column align="center" prop="status" :label="$i18n.t('STATUS')" :min-width="150">>
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.status === 1" type="success">{{ $i18n.t('NORMAL') }}</el-tag>
                        <el-tag v-else-if="scope.row.status === 2" type="info">{{ $i18n.t('DISABLED') }}</el-tag>
                        <el-tag v-else>-</el-tag>
                    </template>
                </el-table-column>
                <!-- 右侧按钮 -->
                <el-table-column align="center" :label="$i18n.t('ACTION')" fixed="right" :width="370">
                    <template slot-scope="scope">
                        <!-- 修改 -->
                        <span v-auth="'update'">
                            <el-link icon="el-icon-edit" @click="onUpdate(scope.row)">{{ $i18n.t('EDIT') }}</el-link>
                            <el-divider direction="vertical"></el-divider>
                        </span>
                        <!-- 菜单权限管理 -->
                        <span v-auth="'menu'">
                            <el-link icon="el-icon-menu" @click="onMenu(scope.row)">{{ $i18n.t('MENU_AUTH') }}</el-link>
                            <el-divider direction="vertical"></el-divider>
                        </span>
                        <!-- 资源权限管理 -->
                        <span v-auth="'resource'">
                            <el-link icon="el-icon-key" @click="onResource(scope.row)">{{ $i18n.t('RESOURCE_AUTH') }}</el-link>
                            <el-divider direction="vertical"></el-divider>
                        </span>
                        <!-- 删除 -->
                        <poptip
                            confirm
                            :title="$i18n.t('CONFIRM_DELETE_TIP')"
                            @on-ok="onDelete(scope.row)"
                            :ok-text="$i18n.t('CONFIRM')"
                            :cancel-text="$i18n.t('CANCEL')"
                        >
                            <el-link v-auth="'delete'" icon="el-icon-delete">{{ $i18n.t('DELETE') }}</el-link>
                        </poptip>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <!-- 编辑和修改 -->
        <el-drawer
            :title="$i18n.t(mdl.id > 0 ? 'EDIT' : 'ADD')"
            :visible.sync="drawer"
            destroy-on-close
            ref="drawer"
            size="400px"
        >
            <form-modal :mdl="mdl" :handleCloseDrawer="handleCloseDrawer"></form-modal>
        </el-drawer>

        <!-- 菜单权限管理 -->
        <el-drawer
            :title="$i18n.t('MENU_AUTH')"
            :visible.sync="menuDrawer"
            destroy-on-close
            ref="menuDrawer"
            size="360px"
        >
            <menu-form-modal :group-menus="mdl.menus" :group-id="mdl.id" :handle-close-drawer="handleCloseDrawer"></menu-form-modal>
        </el-drawer>

        <!-- 资源权限管理 -->
        <el-dialog
            :title="$i18n.t('RESOURCE_AUTH')"
            :visible.sync="resourceDialog"
            center
            width="800px"
        >
            <resource-form-modal v-if="resourceDialog" :group-resources="mdl.resources" :group-id="mdl.id" :handle-close-drawer="handleCloseDrawer"></resource-form-modal>
        </el-dialog>
    </div>
</template>

<script>
    import { getGroup, delGroup } from '@/api/default/group'
    import FormModal from '@/components/group/FormModal'
    import MenuFormModal from '@/components/group/MenuFormModal'
    import ResourceFormModal from '@/components/group/ResourceFormModal'

    export default {
        name: 'group',
        components: { FormModal, MenuFormModal, ResourceFormModal },
        data () {
            return {
                data: [],
                loading: false,
                drawer: false,
                mdl: {},
                menuDrawer: false,
                resourceDialog: false
            }
        },
        created () {
            this.fetch()
        },
        computed: {
            isShowDivider () {
                const { meta } = this.$store.getters.router
                return meta.actions.indexOf('update') > -1 && meta.actions.indexOf('delete') > -1
            }
        },
        methods: {
            // 搜索按钮
            onSearch () {
                this.fetch()
            },
            // 从接口拉取数据
            fetch () {
                this.loading = true
                getGroup({}).then((res) => {
                    this.data = res.result
                }).finally(() => {
                    this.loading = false
                })
            },
            // 修改
            onUpdate (row) {
                this.mdl = Object.assign({}, row)
                this.drawer = true
            },
            // 删除
            onDelete (row) {
                delGroup(row.id).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                }).finally(() => {
                    // 刷新选项列表
                    this.$store.dispatch('GetOptions').then(() => {})
                })
            },
            // 添加用户组
            onAdd () {
                this.mdl = Object.assign({}, {
                    id: 0,
                    name: '',
                    remark: '',
                    status: 1
                })
                this.drawer = true
            },
            // 修改菜单权限
            onMenu (row) {
                this.mdl = Object.assign({}, row)
                this.menuDrawer = true
            },
            // 修改资源权限
            onResource (row) {
                this.mdl = Object.assign({}, row)
                this.resourceDialog = true
            },
            // 关闭窗口
            handleCloseDrawer () {
                this.$refs.drawer.closeDrawer()
                this.$refs.menuDrawer.closeDrawer()
                this.resourceDialog = false
            }
        }
    }
</script>

<style lang="less" scoped>
</style>

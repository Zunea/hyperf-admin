<template>
    <div>
        <!-- 表格区域 -->
        <div class="table-body">
            <!-- 左上角按钮 -->
            <div class="table-left-top">
                <el-button v-auth="'add'" type="success" icon="el-icon-plus" @click="onAdd(0)">{{ $i18n.t('CREATE_MENU') }}</el-button>
                <el-button icon="el-icon-refresh-right" @click="fetch" :loading="loading">{{ $i18n.t('REFRESH') }}</el-button>
            </div>

            <!-- 表格 -->
            <el-table
                :data="data2tree"
                row-key="id"
                border
                style="width: 480px"
                :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
                v-loading="loading"
                :indent="28"
            >
                <el-table-column fixed="left" prop="name" :label="$i18n.t('MENU_NAME')">
                    <template slot-scope="scope">
                        <i :class="scope.row.type === 1 ? scope.row.icon: 'el-icon-thumb'" style="font-size: 13px;padding-right: 4px;"></i>
                        {{ scope.row.type === 1 ? $i18n.t('menu.' + scope.row.name) : scope.row.path }}
                        <div class="right-button">
                            <el-link v-auth="'add'" v-show="scope.row.type === 1" icon="el-icon-custom-add-big" @click="onAdd(scope.row.id)" style="margin-right: 6px"></el-link>
                            <el-link v-if="!scope.row.is_system" v-auth="'update'" icon="el-icon-custom-edit" @click="onUpdate(scope.row)" style="margin-right: 6px"></el-link>
                            <el-link v-if="!scope.row.is_system" v-auth="'delete'" icon="el-icon-custom-close" @click="onDelete(scope.row)"></el-link>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!-- 编辑或删除页 -->
        <el-dialog
            :title="$i18n.t(mdl.id > 0 ? 'EDIT' : 'ADD')"
            :visible.sync="dialog"
            width="650px"
            center
            top="10px"
            :close-on-click-modal="false"
            destroy-on-close
        >
            <form-modal :mdl="mdl" :menus="data" :handle-close-drawer="handleCloseDialog"></form-modal>
        </el-dialog>
    </div>
</template>

<script>
    import { delMenu, getMenu } from '@/api/default/menu'
    import { array2tree } from '@/utils'
    import FormModal from '@/components/menu/FormModal'

    export default {
        name: 'menus',
        components: {
            FormModal
        },
        data () {
            return {
                data: [],
                loading: false,
                expandAll: false,
                mdl: {},
                dialog: false
            }
        },
        computed: {
            data2tree () {
                const childrenNav = []
                array2tree(this.data, childrenNav, 0)
                return childrenNav
            }
        },
        created () {
            this.fetch()
        },
        methods: {
            fetch () {
                this.loading = true
                getMenu({ ...this.params }).then((res) => {
                    const { result } = res
                    this.data = result
                }).finally(() => {
                    this.loading = false
                })
            },
            onAdd (parentId = 0) {
                this.mdl = Object.assign({}, {
                    id: 0,
                    parent_id: parentId,
                    name: '',
                    type: 1,
                    icon: '',
                    path: '',
                    component: '',
                    left_show: true,
                    top_show: false,
                    target: 1,
                    status: 1,
                    sort: 0
                })
                this.dialog = true
            },
            onUpdate (row) {
                this.mdl = Object.assign({}, row)
                this.dialog = true
            },
            // 删除
            onDelete (row) {
                delMenu(row.id).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                })
            },
            // 关闭dialog方法
            handleCloseDialog () {
                this.dialog = false
            }
        }
    }
</script>

<style lang="less" scoped>
    .right-button {
        float: right;
        display: none;
    }
    .el-table__row:hover .right-button {
        display: inline;
    }
</style>

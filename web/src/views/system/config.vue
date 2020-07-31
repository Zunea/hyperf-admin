<template>
    <div>
        <el-tabs v-if="groupList.length > 0" v-model="group" :loading="loading">
            <el-tab-pane v-for="(item, index) in groupList" :label="item.label" :key="index" :name="item.value"></el-tab-pane>
        </el-tabs>
        <!-- 表格区域 -->
        <div class="table-body">
            <!-- 搜索组件 -->
            <search-form :items="formItems" :params="params" :on-submit="onSearch" :btn-loading="loading" />
            <!-- 左上角按钮 -->
            <div class="table-left-top">
                <el-button v-auth="'add'" type="primary" icon="el-icon-plus" @click="onAdd">{{ $i18n.t('CREATE_CONFIG') }}</el-button>
                <el-button v-auth="'enable'" type="success" icon="el-icon-circle-check" :disabled="checkedIds.length === 0" @click="onEnable()">{{ $i18n.t('ENABLE') }}</el-button>
                <el-button v-auth="'disable'" type="warning" icon="el-icon-custom-disable" :disabled="checkedIds.length === 0" @click="onDisable()">{{ $i18n.t('DISABLE') }}</el-button>
                <el-button v-auth="'delete'" type="danger" icon="el-icon-delete" :disabled="checkedIds.length === 0" @click="onDelete(checkedIds)">{{ $i18n.t('DELETE') }}</el-button>
            </div>
            <!-- 表格 -->
            <el-table border style="width: 100%;" stripe :data="data" v-loading="loading" @selection-change="handleSelectionChange">
                <el-table-column type="selection" :selectable="(row) => { return !row.is_system }" align="center" prop="id" label="ID" :width="100" fixed="left"></el-table-column>
                <el-table-column align="center" prop="title" :label="$i18n.t('CONFIG_TITLE')" :min-width="150"></el-table-column>
                <el-table-column align="center" prop="name" :label="$i18n.t('CONFIG_NAME')" :min-width="100"></el-table-column>
                <el-table-column align="center" prop="type" :label="$i18n.t('CONFIG_TYPE')" :min-width="100">
                    <template slot-scope="scope">
                        {{ getConfigTypeText(scope.row.type) }}
                    </template>
                </el-table-column>
                <el-table-column align="center" prop="status" :label="$i18n.t('STATUS')" :min-width="150">>
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.is_enable" type="success">{{ $i18n.t('ENABLE') }}</el-tag>
                        <el-tag v-else type="info">{{ $i18n.t('DISABLED') }}</el-tag>
                    </template>
                </el-table-column>
                <!-- 右侧按钮 -->
                <el-table-column align="center" :label="$i18n.t('ACTION')" fixed="right" :width="200">
                    <template slot-scope="scope">
                        <el-link v-auth="'update'" icon="el-icon-edit" @click="onUpdate(scope.row)">{{ $i18n.t('EDIT') }}</el-link>
                        <span v-auth="'delete'" >
                            <el-divider direction="vertical"></el-divider>
                            <poptip
                                confirm
                                :title="$i18n.t('CONFIRM_DELETE_TIP')"
                                @on-ok="onDelete([scope.row.id])"
                                :ok-text="$i18n.t('CONFIRM')"
                                :cancel-text="$i18n.t('CANCEL')"
                            >
                                <el-link icon="el-icon-delete">{{ $i18n.t('DELETE') }}</el-link>
                            </poptip>
                        </span>
                    </template>
                </el-table-column>
            </el-table>
            <!-- 分页 -->
            <div class="table-pagination">
                <el-pagination
                    background
                    layout="total, prev, pager, next, ->, sizes"
                    :page-size.sync="page.perPage"
                    :total="page.total"
                    :page-count="page.pageCount"
                    :current-page.sync="page.currentPage"
                    :page-sizes="PAGES_SIZE"
                    @current-change="onCurrentPageChange"
                    @size-change="onPerPageChange"
                >
                </el-pagination>
            </div>
        </div>
        <el-drawer
            :title="$i18n.t(mdl.id > 0 ? 'EDIT' : 'ADD')"
            :visible.sync="drawer"
            destroy-on-close
            ref="drawer"
            size="400px"
        >
            <form-modal :mdl="mdl" :handle-close-drawer="handleCloseDrawer"></form-modal>
        </el-drawer>
    </div>
</template>

<script>
    import SearchForm from '@/components/SearchForm'
    import { getConfig, deleteConfig, enableConfig, disableConfig } from '@/api/default/config'
    import FormModal from '@/components/config/FormModal'
    import { PAGES_SIZE } from '@/config/constants'

    export default {
        name: 'config',
        components: { SearchForm, FormModal },
        data () {
            return {
                PAGES_SIZE,
                params: {},
                data: [],
                page: {
                    total: 0,
                    currentPage: 1,
                    perPage: PAGES_SIZE[0],
                    pageCount: 1
                },
                loading: false,
                drawer: false,
                mdl: {},
                group: undefined,
                checkedIds: []
            }
        },
        watch: {
            group () {
                this.page.currentPage = 1
                this.fetch()
            }
        },
        created () {
            this.group = this.groupList.length === 0 ? '' : this.groupList[0].value
        },
        computed: {
            groupList () {
                return this.$store.getters.options.AdminConfigGroup
            },
            formItems () {
                // 构建搜索表单
                return [
                    { title: this.$i18n.t('CONFIG_NAME'), type: 'input', key: 'name', maxlength: 50 },
                    { title: this.$i18n.t('CONFIG_TITLE'), type: 'input', key: 'title', maxlength: 50 },
                    { title: this.$i18n.t('CONFIG_TYPE'), type: 'select', key: 'type', options: this.$store.getters.options.AdminConfigTypeList }
                ]
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
                getConfig({ ...this.params, page: this.page.currentPage, perPage: this.page.perPage, group: this.group }).then((res) => {
                    // eslint-disable-next-line camelcase
                    const { data, last_page, total } = res.result
                    this.data = data
                    this.page.total = total
                    // eslint-disable-next-line camelcase
                    this.page.pageCount = last_page
                }).finally(() => {
                    this.loading = false
                })
            },
            // 页数切换
            onCurrentPageChange (page) {
                this.page.currentPage = page
                this.fetch()
            },
            // 每页显示数量修改
            onPerPageChange (perPage) {
                this.page.perPage = perPage
                this.fetch()
            },
            // 修改
            onUpdate (row) {
                this.mdl = Object.assign({}, row)
                this.drawer = true
            },
            // 删除
            onDelete (ids) {
                deleteConfig(ids.join(',')).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                })
            },
            onAdd () {
                this.mdl = Object.assign({}, {
                    id: 0,
                    name: '',
                    title: '',
                    group: this.group,
                    type: 'text',
                    options: '',
                    attr: {},
                    tips: '',
                    format: '',
                    sort: ''
                })
                this.drawer = true
            },
            // 启用配置
            onEnable () {
                enableConfig(this.checkedIds.join(',')).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                })
            },
            // 禁用配置
            onDisable () {
                disableConfig(this.checkedIds.join(',')).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                })
            },
            // 关闭抽屉方法
            handleCloseDrawer () {
                this.$refs.drawer.closeDrawer()
            },
            // 选中的ID
            handleSelectionChange (rows) {
                this.checkedIds = rows.map(item => item.id)
            },
            // 显示配置项
            getConfigTypeText (type) {
                const option = this.$store.getters.options.AdminConfigTypeList.filter(item => item.value === type)
                return option.length > 0 ? option[0].label : type
            }
        }
    }
</script>

<style lang="less" scoped>
    /deep/ .el-button+.el-button {
        margin-left: 3px;
    }
</style>

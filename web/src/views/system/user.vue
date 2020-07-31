<template>
    <div>
        <!-- 搜索组件 -->
        <search-form :items="formItems" :params="params" :on-submit="onSearch" :btn-loading="loading" />

        <!-- 表格区域 -->
        <div class="table-body">
            <!-- 左上角按钮 -->
            <div class="table-right-top">
                <el-button v-auth="'add'" type="success" icon="el-icon-plus" @click="onAdd">{{ $i18n.t('CREATE_USER_ACCOUNT') }}</el-button>
            </div>

            <!-- 表格 -->
            <el-table border style="width: 100%;" stripe :data="data" v-loading="loading">
                <el-table-column align="center" sortable prop="id" label="ID" :width="100" fixed="left"></el-table-column>
                <el-table-column align="center" prop="username" :label="$i18n.t('USERNAME')" :min-width="200"></el-table-column>
                <el-table-column align="center" prop="nickname" :label="$i18n.t('NICKNAME')" :min-width="200"></el-table-column>
                <el-table-column align="center" prop="phone" :label="$i18n.t('PHONE')" :min-width="150"></el-table-column>
                <el-table-column align="center" prop="email" :label="$i18n.t('EMAIL')" :min-width="250"></el-table-column>
                <el-table-column align="center" prop="status" :label="$i18n.t('STATUS')" :min-width="150">>
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.status === 1" type="success">{{ $i18n.t('NORMAL') }}</el-tag>
                        <el-tag v-else-if="scope.row.status === 2" type="info">{{ $i18n.t('DISABLED') }}</el-tag>
                        <el-tag v-else>-</el-tag>
                    </template>
                </el-table-column>
                <el-table-column align="center" sortable prop="created_at" :label="$i18n.t('CREATE_TIME')" :min-width="170"></el-table-column>
                <el-table-column align="center" sortable prop="updated_at" :label="$i18n.t('UPDATE_TIME')" :min-width="170"></el-table-column>
                <!-- 右侧按钮 -->
                <el-table-column align="center" :label="$i18n.t('ACTION')" fixed="right" :width="200">
                    <template slot-scope="scope">
                        <div v-if="scope.row.id > 1">
                            <el-link v-auth="'update'" icon="el-icon-edit" @click="onUpdate(scope.row)">{{ $i18n.t('EDIT') }}</el-link>
                            <el-divider v-if="isShowDivider" direction="vertical"></el-divider>
                            <!-- 删除操作的确认对话框 -->
                            <poptip
                                confirm
                                :title="$i18n.t('CONFIRM_DELETE_TIP')"
                                @on-ok="onDelete(scope.row)"
                                :ok-text="$i18n.t('CONFIRM')"
                                :cancel-text="$i18n.t('CANCEL')"
                            >
                                <el-link v-auth="'delete'" icon="el-icon-delete">{{ $i18n.t('DELETE') }}</el-link>
                            </poptip>
                        </div>
                        <div v-else>-</div>
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
    import { getUser, delUser } from '@/api/default/user'
    import FormModal from '@/components/user/FormModal'
    import { PAGES_SIZE } from '@/config/constants'

    export default {
        name: 'user',
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
                mdl: {}
            }
        },
        created () {
            this.fetch()
        },
        computed: {
            formItems () {
                // 用户状态筛选
                const statusOptions = [
                    { label: this.$i18n.t('NORMAL'), value: 'normal' },
                    { label: this.$i18n.t('DISABLED'), value: 'disabled' }
                ]
                // 构建搜索表单
                return [
                    { title: this.$i18n.t('USERNAME'), type: 'input', key: 'username', maxlength: 50 },
                    { title: this.$i18n.t('NICKNAME'), type: 'input', key: 'nickname', maxlength: 50 },
                    { title: this.$i18n.t('PHONE'), type: 'input', key: 'phone', maxlength: 20 },
                    { title: this.$i18n.t('EMAIL'), type: 'input', key: 'email', maxlength: 50 },
                    { title: this.$i18n.t('STATUS'), type: 'select', key: 'status', options: statusOptions },
                    { title: this.$i18n.t('REG_TIME'), type: 'date-range', key: 'reg_date' },
                    { title: this.$i18n.t('USER_GROUP'), type: 'select', key: 'group_id', options: this.$store.getters.options.AdminGroupList }
                ]
            },
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
                getUser({ ...this.params, page: this.page.currentPage, perPage: this.page.perPage }).then((res) => {
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
            onDelete (row) {
                delUser(row.id).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    setTimeout(() => {
                        this.fetch()
                    }, 500)
                })
            },
            onAdd () {
                this.mdl = Object.assign({}, {
                    id: 0,
                    username: '',
                    password: '',
                    nickname: '',
                    phone: '',
                    email: '',
                    status: 1,
                    groups: []
                })
                this.drawer = true
            },
            // 关闭抽屉方法
            handleCloseDrawer () {
                this.$refs.drawer.closeDrawer()
            }
        }
    }
</script>

<style lang="less" scoped>
</style>

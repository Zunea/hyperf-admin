<template>
    <div class="body">
        <div class="tree">
            <el-transfer
                filterable
                :filter-method="filterMethod"
                :filter-placeholder="$i18n.t('PLEASE_INPUT', { value: $i18n.t('KEYWORDS') })"
                v-model="checkedKeys"
                :data="resources"
                :titles="[this.$i18n.t('RESOURCE_LIST'), this.$i18n.t('SELECTED_RESOURCE')]"
                @change="btnDisable = false"
            >
            </el-transfer>
        </div>
        <div class="bottom">
            <el-button type="primary" @click="submitForm()" :loading="submitLogin" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
            <el-button @click="handleCloseDrawer">{{ $i18n.t('CANCEL') }}</el-button>
            <div v-if="errorMessage !== ''" style="width: 50%;margin: 20px auto 0">
                <Alert type="error" show-icon>{{ errorMessage }}</Alert>
            </div>
        </div>
    </div>
</template>

<script>
    import { updateGroupResource } from '@/api/default/group'
    import { getResource } from '@/api/default/resource'

    export default {
        name: 'resourceFormModal',
        props: {
            groupId: {
                type: Number,
                required: true
            },
            groupResources: {
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
                resources: [],
                checkedKeys: [],
                errorMessage: ''
            }
        },
        created () {
            this.checkedKeys = this.groupResources.map(item => item.id)
            getResource().then((res) => {
                const { result } = res
                this.resources = result.map(item => ({
                    key: item.id,
                    label: item.name,
                    path: item.path,
                    method: item.method
                }))
            })
        },
        methods: {
            submitForm () {
                this.submitLogin = true
                // 设置手动接管message提示
                this.$store.dispatch('MessageTake', true)
                this.errorMessage = ''
                updateGroupResource(this.groupId, { resourceIds: this.checkedKeys }).then(() => {
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
            },
            // 搜索逻辑
            filterMethod (query, item) {
                return item.path.toUpperCase().indexOf(query.toUpperCase()) > -1 ||
                    item.method.toUpperCase().indexOf(query.toUpperCase()) > -1 ||
                    item.label.toUpperCase().indexOf(query.toUpperCase()) > -1
            }
        }
    }
</script>

<style lang="less" scoped>
    .body {
        width: 100%;
        padding-right: 20px;
        padding-left: 20px;
        height: 100%;
        .tree {
            display: flex;
            align-items: center;
            justify-content: center;
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
    /deep/ .el-transfer-panel {
        width: 250px;
    }
</style>

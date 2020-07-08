<template>
    <div>
        <!-- 配置分组 -->
        <el-tabs v-if="groupList.length > 0" v-model="group" :loading="loading" @tab-click="onTabClick">
            <el-tab-pane v-for="(item, index) in groupList" :label="item.label" :key="index" :name="item.value"></el-tab-pane>
        </el-tabs>
        <!-- 配置表单 -->
        <el-form ref="ruleForm" label-width="130px" style="width: 80%">
            <form-item :data="data"></form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmit" :loading="btnLoading" :disabled="btnDisable">{{ $i18n.t('SUBMIT') }}</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import FormItem from '@/components/setting/FormItem'
    import { getSetting, updateSetting } from '@/api/default/setting'

    export default {
        name: 'setting',
        components: {
            FormItem
        },
        data () {
            return {
                group: undefined,
                data: [],
                loading: false,
                btnLoading: false,
                btnDisable: true
            }
        },
        watch: {
            data: {
                handler () {
                    this.btnDisable = false
                },
                deep: true
            }
        },
        computed: {
            groupList () {
                return this.$store.getters.options.AdminConfigGroup
            }
        },
        created () {
            this.group = this.groupList.length === 0 ? '' : this.groupList[0].value
            this.fetch()
        },
        methods: {
            // 从接口拉取数据
            fetch () {
                this.loading = true
                getSetting({ ...this.params, group: this.group === 0 ? '' : this.group }).then((res) => {
                    const { result } = res
                    this.data = result
                }).finally(() => {
                    this.loading = false
                    this.btnDisable = true
                })
            },
            // 提交配置
            onSubmit () {
                const formData = {}
                this.data.forEach((item) => {
                    formData[item.name] = item.value
                })
                this.btnLoading = true
                updateSetting(formData).then(() => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    this.fetch()
                    this.$store.dispatch('GetOptions')
                }).finally(() => {
                    this.btnLoading = false
                })
            },
            onTabClick (tab) {
                this.group = tab.name
                this.fetch()
            }
        }
    }
</script>

<style scoped>

</style>

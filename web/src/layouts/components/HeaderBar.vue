<template>
    <div style="width: calc(100% - 50px);float: right">
        <el-row type="flex" class="row-bg" justify="end" style="padding-left: 20px">
            <el-col :xs="12" :sm="8" :md="6" :lg="4" :xl="2">
                <el-dropdown trigger="click" placement="bottom" @command="handleCommand">
                    <span class="el-dropdown-link" style="font-size: 14px;cursor: pointer;"><i class="el-icon-custom-globalization"></i>&nbsp;{{ $i18n.t('LANGUAGE') }}</span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item command="zh_CN">🇨🇳 简体中文</el-dropdown-item>
                        <el-dropdown-item command="en_US">🇬🇧 English</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-col>
            <el-col :xs="12" :sm="8" :md="6" :lg="4" :xl="2">
                <el-dropdown trigger="click" placement="bottom" @command="handleUserCommand">
                    <span class="el-dropdown-link" style="font-size: 14px;cursor: pointer;"><i class="el-icon-user-solid"></i>&nbsp;{{ user.nickname || user.username }}</span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item icon="el-icon-custom-change" command="changePassword">{{ $i18n.t('CHANGE_PASSWORD') }}</el-dropdown-item>
                        <el-dropdown-item icon="el-icon-custom-logout" command="logout">{{ $i18n.t('LOGOUT') }}</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        name: 'HeaderBar',
        methods: {
            // 退出登录
            logout () {
                this.$confirm(this.$i18n.t('CONFIRM_EXIT_TIP'), this.$i18n.t('TIP'), {
                    confirmButtonText: this.$i18n.t('CONFIRM'),
                    cancelButtonText: this.$i18n.t('CANCEL'),
                    type: 'warning',
                    customClass: 'confirm-box'
                }).then(() => {
                    this.Logout.then(() => {
                        this.$Message.info(`${this.$i18n.t('LOGOUT_SUCCESS')}`)
                        setTimeout(() => {
                            location.href = '/'
                        }, 1000)
                    })
                }).catch(() => {})
            },
            // 切换语言
            handleCommand (lang) {
                this.$store.dispatch('SetLang', lang)
                this.$store.dispatch('GetOptions')
            },
            handleUserCommand (command) {
                if (command === 'logout') {
                    this.logout()
                }
            }
        },
        computed: {
            ...mapActions(['Logout']),
            user () {
                return this.$store.getters.user
            }
        }
    }
</script>

<style lang="less">
    .confirm-box {
        position: absolute;
        left: 0;
        right: 0;
        top: 10%;
        margin: auto;
    }
</style>

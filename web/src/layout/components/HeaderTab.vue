<template>
    <Header class="header flexR">
        <div class="header_left flexR">
            <span>
                <Icon @click.native="collapsedSider" :class="rotateIcon" type="md-menu" size="24"></Icon>
            </span>
            <span @click="refresh()">
                <Icon type="ios-refresh" size="24"/>
            </span>
            <span>
                <Breadcrumb>
                    <BreadcrumbItem v-for="item in openNames" :key="item">
                        {{ $i18n.t('menu.' + item) }}
                    </BreadcrumbItem>
                </Breadcrumb>
            </span>
        </div>
        <!--头部右边-->
        <div class="header_right flexR">
            <span>
                <Icon :type="isMaxWindow ? 'md-contract' : 'md-expand'" @click="maxWindow" size="18"/>
            </span>
            <!--<span>
                <Dropdown trigger="click">
                    <Badge :count="3">
                        <Icon type="ios-notifications-outline" size="20"/>
                    </Badge>
                    <DropdownMenu slot="list">
                        <message></message>
                    </DropdownMenu>
                </Dropdown>
            </span>-->
            <span>
                <Dropdown @on-click="personalSettings">
                    <a href="javascript:void(0)" style="color: #515A6E;">
                        <el-avatar :src="user.avatar || ''" size="small" class="ivu-avatar ivu-avatar-small" style="margin-right: 5px" />
                        {{ user.nickname || user.username }}
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="logout" divided>{{ $i18n.t('LOGOUT') }}</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </span>
            <span @click="onOptionDrawer">
                <Icon type="md-more" size="20"/>
            </span>
        </div>
    </Header>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        name: 'HeaderTab',
        props: ['collapsedSider', 'isCollapsed', 'refresh', 'onOptionDrawer'],
        data () {
            return {
                isMaxWindow: false
            }
        },
        computed: {
            ...mapActions(['Logout']),
            openNames () {
                return this.$route.matched.map(item => item.name).filter(item => {
                    return item !== 'index'
                })
            },
            rotateIcon () {
                return [
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ]
            },
            user () {
                return this.$store.getters.user
            }
        },
        methods: {
            maxWindow () {
                if (this.isMaxWindow) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen()
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen()
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen()
                    }
                    this.isMaxWindow = false
                } else {
                    const element = document.documentElement
                    if (element.requestFullscreen) {
                        element.requestFullscreen()
                    } else if (element.mozRequestFullScreen) {
                        element.mozRequestFullScreen()
                    } else if (element.webkitRequestFullscreen) {
                        element.webkitRequestFullscreen()
                    } else if (element.msRequestFullscreen) {
                        element.msRequestFullscreen()
                    }
                    this.isMaxWindow = true
                }
            },
            personalSettings (name) {
                if (name === 'logout') {
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
                            }, 800)
                        }).catch(e => {})
                    }).catch(e => {})
                }
            }
        }
    }
</script>

<style lang="less" scoped>
    @import '../../assets/css/main';
</style>

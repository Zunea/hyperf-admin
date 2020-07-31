<template>
    <div class="layout-page">
        <Layout class="layout-div">
            <Sider
                ref="side1"
                hide-trigger
                collapsible
                :collapsed-width="78"
                :width="256"
                v-model="isCollapsed"
                :class="themeType === 'light' ? 'themeLight' : 'themeDark'"
            >
                <div class="logo" :class="headMaxWidth ? 'headMaxWidth' : ''">
                    <img v-if="!isCollapsed && headMaxWidth" :src="headMaxWidthLogoImg" alt="">
                    <img v-if="!isCollapsed && !headMaxWidth" :src="logoImg" alt="">
                    <img v-if="isCollapsed" :src="smallLogImg" alt="">
                </div>
                <el-menu
                    style="border-right: 0;"
                    :collapse="isCollapsed"
                    :class="'dark-menu'"
                    :background-color="themeType === 'dark' ? '#191a23' : ''"
                    :text-color="themeType === 'dark' ? '#fff' : ''"
                    :active-text-color="themeType === 'dark' ? '#ffd04b' : ''"
                    :collapse-transition="false"
                    :default-active="activeName"
                    @select="onMenuSelect"
                    unique-opened
                    router
                >
                    <el-menu-tree :data="this.mainMenu" />
                </el-menu>
            </Sider>
            <Layout>
                <!--头部-->
                <header-tab
                    :collapsedSider="collapsedSider"
                    :isCollapsed="isCollapsed"
                    :refresh="refresh"
                    :on-option-drawer="onOptionDrawer"
                >
                </header-tab>
                <!--多页标签-->
                <multi-tab ref="multiTab" v-if="isTabsShow"></multi-tab>
                <!-- 主题风格设置 -->
                <Drawer v-model="optionDrawer" class="optionDrawer">
                    <Divider slot="header" class="header">主题风格设置</Divider>
                    <div class="flexR imgs">
                        <Tooltip content="时尚酷黑" placement="top" transfer :class="themeType === 'dark' ? 'active' : ''">
                            <img src="@/assets/main/nav-theme-dark.svg" alt="" @click="themeSwitch(0)">
                        </Tooltip>
                        <Tooltip content="极简雅白" placement="top" transfer :class="themeType === 'light' ? 'active' : ''">
                            <img src="@/assets/main/nav-theme-light.svg" alt="" @click="themeSwitch(1)">
                        </Tooltip>
                    </div>
                    <Divider class="otherDiv">其他设置</Divider>
                    <div class="kaiguan flexR">
                        <span>开启多页签</span>
                        <i-switch v-model="isTabsShow" size="small"/>
                    </div>
                    <div class="kaiguan flexR">
                        <span>顶栏通顶</span>
                        <i-switch v-model="headMaxWidth" size="small"/>
                    </div>
                    <Divider class="otherDiv">{{ $i18n.t('CHANGE_PASSWORD') }}</Divider>
                    <el-form :model="password" label-position="right">
                        <el-form-item :label="$i18n.t('OLD_PASSWORD')" required :error="passwordError.old_password">
                            <el-input type="password" v-model="password.old_password" :placeholder="this.$i18n.t('PLEASE_INPUT', { value: this.$i18n.t('OLD_PASSWORD') })" show-password></el-input>
                        </el-form-item>
                        <el-form-item :label="$i18n.t('NEW_PASSWORD')" required :error="passwordError.new_password">
                            <el-input type="password" v-model="password.new_password" :placeholder="this.$i18n.t('PLEASE_INPUT', { value: this.$i18n.t('NEW_PASSWORD') })" show-password></el-input>
                        </el-form-item>
                        <el-form-item :label="$i18n.t('CONFIRM_NEW_PASSWORD')" required :error="passwordError.new_password_confirmation">
                            <el-input type="password" v-model="password.new_password_confirmation" :placeholder="this.$i18n.t('PLEASE_INPUT', { value: this.$i18n.t('CONFIRM_NEW_PASSWORD') })" show-password></el-input>
                        </el-form-item>
                        <el-form-item style="text-align: center">
                            <el-button type="primary" @click="onSubmitPassword" :loading="passwordSubmit" :disabled="passwordSubmit">{{ $i18n.t('SUBMIT') }}</el-button>
                        </el-form-item>
                    </el-form>
                </Drawer>
                <!-- 内容区 -->
                <el-card id="main_content"
                     :style="{
                        minHeight: (!isTabsShow ? 'calc(100vh - 0px)' : 'calc(100vh - 0px)'),
                        margin: (!isTabsShow ? '10px 0 10px 10px' :'0 0 10px 10px')
                     }"
                         style="overflow-y: auto"
                     class="main_content flexC"
                >
                    <RouteView v-if="isRouterViewShow"></RouteView>
                </el-card>
            </Layout>
        </Layout>
    </div>
</template>

<script>
    import darkImg from '@/assets/main/logo-dark.png'
    import lightImg from '@/assets/main/logo-light.png'
    import smallDarkImg from '@/assets/main/logo-small-dark.png'
    import smallLightImg from '@/assets/main/logo-small-light.png'
    import { themeData } from '@/config'
    import { mapState } from 'vuex'
    import ElMenuTree from '@/layout/components/ElMenuTree'
    import MultiTab from '@/layout/components/MultiTab'
    import HeaderTab from '@/layout/components/HeaderTab'
    import RouteView from '@/layout/RouteView'

    export default {
        name: 'main-page',
        components: { HeaderTab, MultiTab, ElMenuTree, RouteView },
        computed: {
            ...mapState({
                // 动态主路由
                mainMenu (state) {
                    return state.router.addRouters.find(item => item.path === '/').children
                }
            }),
            // 收缩的图标
            rotateIcon () {
                return [
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ]
            },
            // 当前选中菜单
            activeName () {
                return this.$route.path
            },
            // 是否开启多标签页
            isTabsShow: {
                get () {
                    return this.$store.getters.multiTab
                },
                set (value) {
                    this.$store.dispatch('ToggleMultiTab', value)
                }
            },
            // 页面风格类型
            themeType () {
                return this.$store.getters.themeType
            },
            logoImg () {
                return this.themeType === 'dark' ? lightImg : darkImg
            },
            smallLogImg () {
                return this.themeType === 'dark' ? smallLightImg : smallDarkImg
            }
        },
        data () {
            return {
                isCollapsed: false, // 是否收缩
                routersArr: [], // 路由数据
                transform: 0, // 导航左右方向移动距离
                optionDrawer: false, // 是否显示配置抽屉
                headMaxWidthLogoImg: darkImg, // 栏目是否通顶logo图片
                headMaxWidth: themeData.headMaxWidth, // 栏目是否通顶（最大宽度）
                isRouterViewShow: true, // 控制router-view的隐藏与展示
                password: {
                    old_password: '',
                    new_password: '',
                    new_password_confirmation: ''
                },
                passwordError: {},
                passwordSubmit: false
            }
        },
        methods: {
            // 收缩切换
            collapsedSider () {
                this.$refs.side1.toggleCollapse()
            },
            // 路由跳转
            onMenuSelect () {
                if (this.$refs.multiTab) {
                    this.$refs.multiTab.setTabSwitch()
                }
            },
            // 主题切换
            themeSwitch (type) {
                if (type === 0) {
                    this.$store.dispatch('ToggleThemeType', 'dark')
                } else {
                    this.$store.dispatch('ToggleThemeType', 'light')
                }
            },
            // 打开右侧抽屉
            onOptionDrawer () {
                this.optionDrawer = true
            },
            // 页面刷新
            refresh () {
                this.isRouterViewShow = false
                this.$nextTick(() => {
                    this.isRouterViewShow = true
                })
            },
            // 修改密码
            onSubmitPassword () {
                this.passwordError = {}
                this.passwordSubmit = true
                this.$store.dispatch('ChangePassword', this.password).then(e => {
                    this.$Message.success(this.$i18n.t('HANDLE_SUCCESS'))
                    this.password = {}
                    this.optionDrawer = false
                }).catch((error) => {
                    // 后端过来的表单错误回显
                    this.passwordError = Object.assign({}, { ...this.passwordError, ...error.errors })
                }).finally(() => {
                    this.passwordSubmit = false
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "../assets/css/main";
</style>

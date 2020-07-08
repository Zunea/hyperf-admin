<template>
    <el-container style="height: 100vh">
        <el-aside :class="sideClasses" :width="isCollapsed ? '64px' : '220px'" style="transition: width .2s ease;">
            <el-menu
                style="border-right: 0;"
                :background-color="styleSetting.navTheme === 'dark' ? '#545c64' : ''"
                :text-color="styleSetting.navTheme === 'dark' ? '#ffffff' : '#000000'"
                :active-text-color="styleSetting.navTheme === 'dark' ? '#ffd04b' : ''"
                :collapse="isCollapsed"
                :class="menuClasses"
                :collapse-transition="false"
                :default-active="currentRoute"
                router
                unique-opened
            >
                <menu-tree :data="this.mainMenu" />
            </el-menu>
        </el-aside>

        <el-container class="layout">
            <el-header class="el-header" :style="{ 'background-color': '#ffffff' }">
                <div style="width: 50px; float: left;">
                    <el-icon :class="rotateIcon" @click.native="onCollapsed" :style="{ color: '#6c6c6c' }"></el-icon>
                </div>
                <!-- 顶部内容 -->
                <header-bar />
            </el-header>
            <el-main style="padding: 10px;">
                <el-card class="box-card" style="min-height: 100%">
                    <!-- 路由页面 -->
                    <route-view />
                </el-card>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import styleSetting from '@/configs/styleSetting'
    import RouteView from '@/layouts/RouteView'
    import MenuTree from './components/MenuTree'
    import HeaderBar from './components/HeaderBar'
    import { mapState } from 'vuex'

    export default {
        name: 'BasicLayout',
        components: {
            RouteView,
            MenuTree,
            HeaderBar
        },
        data () {
            return {
                isCollapsed: false,
                styleSetting,
                currentRoute: ''
            }
        },
        computed: {
            ...mapState({
                // 动态主路由
                mainMenu (state) {
                    return state.router.addRouters.find(item => item.path === '/').children
                }
            }),
            rotateIcon () {
                return [
                    'el-icon-s-unfold',
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ]
            },
            sideClasses () {
                return [
                    this.styleSetting.navTheme === 'dark' ? 'dark-aside' : 'light-aside',
                    this.isCollapsed ? 'collapsed-side' : ''
                ]
            },
            menuClasses () {
                return [
                    this.styleSetting.navTheme === 'dark' ? 'dark-menu' : 'light-menu'
                ]
            },
            color () {
                return this.styleSetting.navTheme === 'dark' ? '#545c64' : '#ffffff'
            }
        },
        created () {
            this.currentRoute = this.$route.path
        },
        methods: {
            onCollapsed () {
                this.isCollapsed = !this.isCollapsed
            }
        }
    }
</script>

<style lang="less">
    .layout {
        background: #f5f7f9;
        position: relative;
        overflow: hidden;
    }
    .layout-logo {
        width: 100px;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
    }
    .layout-nav {
        width: 420px;
        margin: 0 20px 0 auto;
    }
    .el-header {
        color: #333;
        line-height: 60px;
        font-size: 12px;
        width: 100%;
        background-color: white;
        box-shadow: 2px 2px 5px #e8e8e8;
    }
    .dark-aside {
        color: #333;
        background-color: #545c64;
    }
    .light-aside {
        color: #333;
        background-color: #fff;
    }
    .menu-icon {
        transition: all .3s;
        color: #6c6c6c;
        font-size: 20px;
        cursor: pointer;
    }
    .rotate-icon{
        transform: rotate(-180deg);
    }
    .dark-menu {
        span {
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: bottom;
            transition: width .2s ease .2s;
        }
        i {
            transform: translateX(0px);
            transition: font-size .2s ease, transform .2s ease;
            vertical-align: middle;
            font-size: 16px;
            color: white;
        }
    }
    .light-menu {
        span {
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: bottom;
            transition: width .2s ease .2s;
        }
        i {
            transform: translateX(0px);
            transition: font-size .2s ease, transform .2s ease;
            vertical-align: middle;
            font-size: 16px;
            color: black;
        }
    }
    .el-menu-vertical-demo:not(.el-menu--collapse) {
        width: 200px;
        min-height: 400px;
    }
</style>

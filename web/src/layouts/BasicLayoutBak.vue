<template>
    <div class="layout">
        <Layout :style="{minHeight: '100vh'}">
            <Sider collapsible v-model="isCollapsed" hide-trigger :style="{background: '#fff'}">
                <Menu active-name="1-2" theme="light" width="auto" :class="menuItemClasses">
                    <Submenu :name="route.name" v-for="(route, index) in this.$router.options.routes" :key="index">
                        <template slot="title">
                            <Icon type="ios-analytics" /> {{ route.name }}
                        </template>
                        <Submenu name="3">
                            <template slot="title">Submenu</template>
                            <MenuItem name="3-1">Option 7</MenuItem>
                            <MenuItem name="3-2">Option 8</MenuItem>
                        </Submenu>
                    </Submenu>
                </Menu>
            </Sider>
            <Layout>
                <Header>
                    <Menu mode="horizontal" theme="dark" active-name="1">
                        <div class="layout-logo"></div>
                        <div class="layout-nav">
                            <menu-item name="1">
                                <Icon type="ios-navigate"></Icon>
                                Item 1
                            </menu-item>
                            <menu-item name="2">
                                <Icon type="ios-keypad"></Icon>
                                Item 2
                            </menu-item>
                            <menu-item name="3">
                                <Icon type="ios-analytics"></Icon>
                                Item 3
                            </menu-item>
                            <menu-item name="4">
                                <Icon type="ios-paper"></Icon>
                                Item 4
                            </menu-item>
                        </div>
                    </Menu>
                </Header>
                <Content :style="{padding: '0 16px 16px'}">
                    <Breadcrumb :style="{margin: '16px 0'}">
                        <BreadcrumbItem>Home</BreadcrumbItem>
                        <BreadcrumbItem>Components</BreadcrumbItem>
                        <BreadcrumbItem>Layout</BreadcrumbItem>
                    </Breadcrumb>
                    <Card>
                        {{ $t('test')}}
                        <Page :total="40" size="small" show-total />
                        <date-picker
                            v-model="value1"
                            type="date"
                            placeholder="选择日期">
                        </date-picker>
                        <Button type="primary">Primary</Button>
                        <el-button type="primary">主要按钮</el-button>
                    </Card>
                </Content>
            </Layout>
        </Layout>
    </div>
</template>

<script>
    import { setI18nLanguage } from '@/locales'
    import { getTaskList } from '@/api/loaned'
    import { DatePicker, Button } from 'element-ui'

    export default {
        data () {
            return {
                isCollapsed: false,
                value1: null
            }
        },
        components: {
            DatePicker,
            ElButton: Button
        },
        computed: {
            menuItemClasses () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        },
        created () {
        }
    }
</script>

<style scoped>
    .layout{
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }
    .layout-logo{
        width: 100px;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
    }
    .layout-nav{
        width: 420px;
        margin: 0 20px 0 auto;
    }
</style>

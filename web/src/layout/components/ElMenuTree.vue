<template>
    <fragment>
        <template v-for="(menu, index) in data.filter(item => item.meta && item.meta.left_show === true)">
            <el-submenu :index="menu.path" v-if="menu.children && menu.children.find(item => item.meta.left_show === true)" :key="index">
                <template slot="title">
                    <i :class="menu.meta.icon" style="margin-top: -2px;"></i>
                    <span slot="title">{{ $i18n.t('menu.' + menu.name) }}</span>
                </template>
                <label>
                    <el-menu-tree :data="menu.children"></el-menu-tree>
                </label>
            </el-submenu>
            <el-menu-item v-else :index="menu.path" :key="index">
                <i :class="menu.meta.icon" style="margin-top: -2px;"></i>
                <span slot="title">{{ $i18n.t('menu.' + menu.name) }}</span>
            </el-menu-item>
        </template>
    </fragment>
</template>

<script>
    import ElMenuTree from './ElMenuTree'

    export default {
        name: 'ElMenuTree',
        components: {
            ElMenuTree
        },
        props: ['data']
    }
</script>

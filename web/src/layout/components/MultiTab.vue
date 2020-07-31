<template>
    <div class="tabsNav flexR">
        <div class="xiala pointer" v-if="tabSwitch" @click="tabLeft">
            <Icon type="ios-arrow-back" size="16"/>
        </div>
        <div class="left flexR" id="tabsNav">
            <div class="flexR" id="tabsDiv" :style="{ transform: transform }">
                <div class="pointer flexR" v-for="(item, index) in routerArr" :key="index">
                    <span :class="activeName === item.path ? 'active': ''" @click="routerPush(item.path)">
                        {{ $i18n.t('menu.' + item.name) }}
                    </span>
                    <Icon v-if="item.path !== $indexPage" type="ios-close" size="24" @click="tabNavClose(item, index)"/>
                </div>
            </div>

        </div>
        <div class="xiala pointer" v-if="tabSwitch" @click="tabRight">
            <Icon type="ios-arrow-forward" size="16"/>
        </div>
        <div class="right">
            <Dropdown style="margin-left: 5px;" @on-click="tabRightClick">
                <div class="xiala pointer">
                    <Icon type="ios-arrow-down"/>
                </div>
                <DropdownMenu slot="list">
                    <DropdownItem name="qt">关闭其他</DropdownItem>
                    <DropdownItem name="qb">全部关闭</DropdownItem>
                </DropdownMenu>
            </Dropdown>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'MultiTab',
        data () {
            return {
                transform: 0,
                tabSwitch: false
            }
        },
        computed: {
            routerArr () {
                return this.$store.getters.routerArr
            },
            activeName () {
                return this.$route.path
            }
        },
        methods: {
            setTabSwitch () {
                this.$nextTick(() => {
                    const w = document.getElementById('tabsDiv').offsetWidth
                    const zw = document.getElementById('tabsNav').offsetWidth
                    this.tabSwitch = w > zw
                })
            },
            tabRight () {
                const w = document.getElementById('tabsDiv').offsetWidth
                const zw = document.getElementById('tabsNav').offsetWidth
                if (w > zw) {
                    const s = -(w - zw)
                    this.transform = 'translateX(' + s + 'px)'
                }
            },
            tabLeft () {
                this.transform = 'translateX(0px)'
            },
            // tab关闭
            tabNavClose (item, index) {
                // 判断是否是首页
                if (item.path === this.$indexPage) {
                    return
                }
                this.$store.dispatch('DEL_ROUTER', index)
                if (index !== 0) {
                    this.$router.push({
                        name: this.routerArr[index - 1].name
                    })
                }
            },
            // tab最右侧下拉框右键选中
            tabRightClick (type) {
                this.tabSwitch = false
                switch (type) {
                case 'qt':
                    for (let i = this.routerArr.length - 1; i > -1; i--) {
                        if (this.routerArr[i].path !== this.activeName) {
                            this.$store.dispatch('DEL_ROUTER', i)
                        }
                    }
                    break
                case 'qb':
                    if (this.routerArr.length === 1) {
                        if (this.$route.path !== this.$indexPage) {
                            for (let i = this.routerArr.length - 1; i > -1; i--) {
                                this.$store.dispatch('DEL_ROUTER', i)
                            }
                        }
                    } else {
                        for (let i = this.routerArr.length - 1; i > -1; i--) {
                            this.$store.dispatch('DEL_ROUTER', i)
                        }
                    }
                    break
                }
            },
            // 路由跳转
            routerPush (path) {
                this.$router.push({ path })
                this.setTabSwitch()
            }
        }
    }
</script>

<style lang="less" scoped>
    @import '../../assets/css/main';
</style>

import * as loginService from '@/api/default/account'
import { BasicLayout, RouteView } from '@/layouts'
// 路由表
const constantRouterComponents = {
    BasicLayout,
    RouteView,
    'dashboard/workplace': () => import('@/views/dashboard/workplace'),
    'dashboard/workplace2': () => import('@/views/dashboard/workplace2'),
    // 后台管理模块相关路由组件
    'system/setting': () => import('@/views/system/setting'),
    'system/config': () => import('@/views/system/config'),
    'system/user': () => import('@/views/system/user'),
    'system/group': () => import('@/views/system/group'),
    'system/menu': () => import('@/views/system/menu'),
    'system/resource': () => import('@/views/system/resource')
    // 业务组件
    // ...
}

// 404路由
const notFoundRouter = {
    path: '*',
    redirect: '/error/404',
    hidden: true
}

// 根菜单
const rootRouter = {
    key: '',
    name: 'index',
    path: '',
    component: 'BasicLayout',
    redirect: '/dashboard',
    meta: { title: 'home' },
    children: []
}

/**
 * 数组转树形结构
 * @param list 源数组
 * @param tree 树
 * @param parentId 父ID
 */
const listToTree = (list, tree, parentId) => {
    list.forEach((item) => {
        // 判断是否为父级菜单
        if (item.parent_id === parentId) {
            const child = {
                ...item,
                key: item.key || item.name,
                children: []
            }
            // 迭代 list， 找到当前菜单相符合的所有子菜单
            listToTree(list, child.children, item.id)
            // 删掉不存在 children 值的属性
            if (child.children.length <= 0) {
                delete child.children
            }
            // 加入到树中
            tree.push(child)
        }
    })
}

/**
 * 格式化树形结构数据 生成 vue-router 层级路由表
 *
 * @param routerMap
 * @param parent
 * @returns {*}
 */
export const generator = (routerMap, parent) => routerMap.map((item) => {
    const currentRouter = {
        // 如果路由设置了 path，则作为默认 path，否则 路由地址 动态拼接生成如 /dashboard/workplace
        path: (item.path && item.path.substring(0, 1) === '/') || `${(parent && parent.path) || ''}/${item.path}`,
        name: item.name,
        // 组件
        component: constantRouterComponents[item.component] || RouteView,
        meta: {
            title: item.name,
            icon: item.icon,
            target: item.target,
            left_show: item.left_show,
            actions: item.actions
        }
    }
    // 为了防止出现后端返回结果不规范，处理有可能出现拼接出两个 反斜杠
    if (!currentRouter.path.startsWith('http')) {
        currentRouter.path = currentRouter.path.replace('//', '/')
    }

    // 重定向
    // eslint-disable-next-line no-unused-expressions
    item.redirect && (currentRouter.redirect = item.redirect)

    // 是否有子菜单，并递归处理
    if (item.children && item.children.length > 0) {
        currentRouter.children = generator(item.children, currentRouter)
    }

    return currentRouter
})

export const generatorDynamicRouter = () => new Promise((resolve, reject) => {
    loginService.getMenu().then((res) => {
        const { result } = res
        const menuNav = []
        const childrenNav = []
        listToTree(result, childrenNav, 0)
        rootRouter.children = childrenNav
        menuNav.push(rootRouter)
        const routers = generator(menuNav)
        routers.push(notFoundRouter)
        resolve({ routers })
    }).catch((err) => {
        reject(err)
    })
})

export {
    constantRouterComponents
}

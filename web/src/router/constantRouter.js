import BasicLayout from '@/layout/BasicLayout'
import UserLayout from '@/layout/UserLayout'

/**
 * 基础路由
 * @type {{redirect: string, path: string, component: *, hidden: boolean, children: {path: string, component: (function()), name: string}[]}[]}
 */
export const constantRouterMap = [
    {
        path: '/auth',
        component: UserLayout,
        redirect: '/auth/login',
        hidden: true,
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => import('@/views/login/login')
            }
        ]
    },
    {
        path: '/error',
        name: 'error',
        component: BasicLayout,
        meta: { title: 'error' },
        redirect: '/error/404',
        children: [
            {
                path: '404',
                name: '404',
                component: () => import('@/views/error/404'),
                meta: { title: '404', keepAlive: true }
            }
        ]
    }
]

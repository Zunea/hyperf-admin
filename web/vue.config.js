const path = require('path')
function resolve (dir) {
    return path.join(__dirname, dir)
}

module.exports = {
    lintOnSave: true, // 使用带有浏览器内编译器的完整构建版本 // https://vuejs.org/v2/guide/installation.html#Runtime-Compiler-vs-Runtime-only
    runtimeCompiler: false, // babel-loader默认会跳过`node_modules`依赖. // 通过这个选项可以显示转译一个依赖
    transpileDependencies: [
        /* string or regex */
    ], // 是否为生产环境构建生成sourceMap?

    productionSourceMap: false, // 调整内部的webpack配置. // see https://github.com/vuejs/vue-cli/blob/dev/docs/webpack.md
    chainWebpack: (config) => {
        config.resolve.alias
            .set('@$', resolve('src'))
    },
    configureWebpack: () => {}, // CSS 相关选项
    css: {
        loaderOptions: {
            less: {
                modifyVars: {
                    // less vars，customize ant design theme

                    // 'primary-color': '#F5222D',
                    // 'link-color': '#F5222D',
                    // 'border-radius-base': '4px'
                },
                // DO NOT REMOVE THIS LINE
                javascriptEnabled: true
            }
        }
    },

    devServer: {
        port: 8088,
        open: true,
        proxy: {
            '/dev-api': {
                target: 'https://www.fastmock.site/mock/8b8187de5502cc6a522b78638621c2c4/HuiAdmin/',
                pathRewrite: { '^/dev-api': '' }
            }
        }
    }, // 第三方插件配置

    pluginOptions: {
        // ...
    }
};

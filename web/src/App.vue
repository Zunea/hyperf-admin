<template>
    <div id="app">
        <router-view/>
    </div>
</template>

<script>
    export default {
        name: 'Zunea'
    }
</script>

<style lang="less">
    input::-webkit-input-placeholder {
        font-size: 12px;
    }
    // 表格样式
    .table-body {
        margin-top: 20px;
        // 表格左上角
        .table-left-top {
            width: 100%;
            float: left;
            margin-bottom: 15px;
        }
        // 表格右上角
        .table-right-top {
            float: right;
            margin-bottom: 15px;
        }
        // 表格分页
        .table-pagination {
            margin-bottom: 100px;
            float: right;
            margin-top: 15px
        }
    }
</style>

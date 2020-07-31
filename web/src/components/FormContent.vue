<template>
    <table v-if="data.length > 0" class="tableStyle">
        <tr v-for="(item, index) in data" v-show="index % columnNum === 0" :key="index">
            <template v-for="(columnItem, colIndex) in columnNum">
                <td :key="colIndex + 'title'" class="title" v-if="data[index + colIndex]" :style="{ width : width }">{{ data[index + colIndex].title }}</td>
                <td :key="colIndex + 'item'" class="item" v-if="data[index + colIndex]">{{ data[index + colIndex].value }}</td>
            </template>
        </tr>
    </table>
    <el-alert v-else :title="$i18n.t('NO_DATA')" type="warning" effect="dark"></el-alert>
</template>

<script>
    export default {
        name: 'FormContent',
        props: {
            data: {
                type: Array,
                required: true
            },
            columnNum: {
                type: Number,
                default: () => { return 1 }
            }
        },
        computed: {
            width () {
                return (100 / (this.columnNum * 2)) + '%'
            }
        }
    }
</script>

<style scoped lang="less">
    .tableStyle {
        border-left: 1px solid #e9eaec;
        border-top: 1px solid #e9eaec;
        width: 100%;
        .title {
            text-align: center;
            background-color: #f8f8f9;
            font-weight: bold;
        }
        .item {
            text-align: center;
        }
        tr {
            td {
                font-size: 12px;
                padding: 10px;
                border-bottom: 1px solid #e9eaec;
                border-right: 1px solid #e9eaec;
            }
        }
    }
</style>

<template>
    <el-upload
        action=""
        :disabled="item.attr.disabled || false"
        :multiple="item.attr.multiple || undefined"
        :show-file-list="item.attr.show_file_list || true"
        :drag="item.attr.drag || false"
        :list-type="item.attr.list_type || 'text'"
        :limit="item.attr.limit || undefined"
        :accept="item.attr.accept || undefined"
        :file-list="defaultFileList"
        :on-success="onSuccess"
        :on-error="onError"
        :on-change="onChange"
        :on-remove="onRemove"
        :http-request="httpRequest"
    >
        <i class="el-icon-plus"></i>
        <!--<div slot="tip" class="el-upload__tip">{{ $i18n.t('ONLY_UPLOAD', { ext: item.attr.accept, size: '500kb' }) }}</div>-->
    </el-upload>
</template>

<script>
    import { upload } from '@/api/default/upload'
    import configs from '@/config'

    export default {
        name: 'sub-upload',
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        watch: {
            uploadList: {
                handler (uploadList) {
                    // 如果limit > 1: 返回数组
                    if (this.item.attr && this.item.attr.limit > 1) {
                        this.item.value = uploadList.map(item => item.name)
                    } else if (uploadList.length > 0) {
                        this.item.value = uploadList[0].name
                    }
                },
                deep: true
            }
        },
        data () {
            return {
                uploadList: [],
                defaultFileList: []
            }
        },
        created () {
            this.initDefaultFileList()
        },
        methods: {
            initDefaultFileList () {
                if (typeof this.item.value === 'string') {
                    if (this.item.value === '') {
                        this.defaultFileList = []
                    } else {
                        this.defaultFileList = [{ name: this.item.value, url: configs.staticUrl + this.item.value }]
                    }
                } else {
                    this.defaultFileList = this.item.value.map(item => ({ name: item, url: configs.staticUrl + item }))
                }
            },
            onSuccess (result, file) {
                // eslint-disable-next-line no-param-reassign
                file.name = result.uri
            },
            onError (err, file) {
                console.log(err, file)
            },
            onChange (file, fileList) {
                this.uploadList = fileList
            },
            onRemove (file, fileList) {
                this.uploadList = fileList
            },
            httpRequest (request) {
                const params = new FormData()
                params.append('file', request.file)
                upload(params).then((res) => {
                    request.onSuccess(res.result)
                }).catch((error) => {
                    request.onError(error)
                })
            }
        }
    }
</script>

<style scoped>

</style>

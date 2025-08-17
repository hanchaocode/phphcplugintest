<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="3">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="标题" field="title">
            <a-input v-model="searchForm.title" placeholder="请输入标题" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item field="create_time" label="创建时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="状态" field="status">
            <a-select
                v-model="searchForm.status"
                :options="statusTypes"
                placeholder="请选择状态"
                allow-clear
            />
          </a-form-item>
        </a-col>
      </template>
      <!-- 头像列 -->
      <template #image="{ record }">
        <a-avatar>
          <img :src="record.image ? $tool.showFile(record.image) : $url + 'avatar.jpg'" style="object-fit: cover" />
        </a-avatar>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/article'
import EditForm from './edit.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()


// Options for building types
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]

// 搜索表单
const searchForm = ref({
  id: '',
  name: '',
  status: '',
  type: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getArticleList, // Adjust API method as needed
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/article/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/article/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/article/destroy'],
    func: async (params) => {
      console.log(params)
      const resp = await api.deleteNotice(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'id', dataIndex: 'id', width: 100 },
  { title: '标题', dataIndex: 'title', width: 150 },
  { title: '图片', dataIndex: 'image', width: 150 },
  { title: '浏览量', dataIndex: 'views', width: 100 },
  { title: '状态', dataIndex: 'status_text',width: 120, },

])

// 页面数据初始化
const initPage = async () => {
  // Initialize any necessary data here
}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}

// 页面加载完成执行
onMounted(async () => {
  initPage()
  refresh()
})
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
}
</style>

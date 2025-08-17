<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="分类名称" field="name">
            <a-input v-model="searchForm.name" placeholder="请输入分类名称" allow-clear />
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

      <!-- 封面图列 -->
      <template #image="{ record }">
        <a-image
            width="80"
            height="60"
            :src="record.image ? $tool.showFile(record.image) : $url + 'default-image.jpg'"
            :preview="!!record.image"
            fit="cover"
        />
      </template>

      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="record.status === 'normal' ? 'green' : 'orange'">
          {{ record.status === 'normal' ? '正常' : '隐藏' }}
        </a-tag>
      </template>

      <!-- 类型列 -->
      <template #type="{ record }">
        <a-tag color="blue">
          {{ typeOptions.find(item => item.value === record.type)?.label || record.type }}
        </a-tag>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/goods/category'
import EditForm from './edit.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()

// 状态选项
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]

// 分类类型选项
const typeOptions = [
  { label: '商品分类', value: 'goods' },
  { label: '文章分类', value: 'article' },
  { label: '其他分类', value: 'other' },
]

// 搜索表单
const searchForm = ref({
  name: '',
  status: '',
  type: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getCategoryList, // 调整为获取分类列表API
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/goods/category/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/goods/category/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/goods/category/destroy'],
    func: async (params) => {
      const resp = await api.deleteCategory(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 80 },
  { title: '名称', dataIndex: 'name', width: 150 },
  { title: '简介', dataIndex: 'intro', width: 150 },
  { title: '图片', dataIndex: 'image', slotName: 'image', width: 120 },
  { title: '权重', dataIndex: 'weigh', width: 100 },
  { title: '状态', dataIndex: 'status', slotName: 'status', width: 100 },
  { title: '创建时间', dataIndex: 'createtime', width: 180 },
  { title: '更新时间', dataIndex: 'updatetime', width: 180 },
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
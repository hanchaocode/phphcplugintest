<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="名称" field="name">
            <a-input v-model="searchForm.name" placeholder="请输入名称" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="类型" field="type">
            <a-select
                v-model="searchForm.type"
                :options="categories"
                placeholder="请选择类型"
                allow-clear
            />
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


    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/area'
import EditForm from './edit.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()


const categories = [
  { label: '商铺', value: 'shop' },
  { label: '车位', value: 'parking' },
]
// Options for building types
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]

// 搜索表单
const searchForm = ref({
  name: '',
  status: '',
  type: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getAreaList, // Adjust API method as needed
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/buildings/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/build/area/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/build/area/destroy'],
    func: async (params) => {
      console.log(params)
      const resp = await api.deleteArea(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 120 },
  { title: '名称', dataIndex: 'name', width: 150 },
  { title: '类型', dataIndex: 'type_text',width: 120, },
  { title: '备注', dataIndex: 'remark', width: 100 },
  { title: '状态', dataIndex: 'status_text', width: 120, },

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

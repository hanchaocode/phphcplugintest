<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="储物间编号" field="build_id">
            <a-input v-model="searchForm.number" placeholder="请输入储物间编号" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="户主姓名" field="ownername">
            <a-input v-model="searchForm.ownername" placeholder="请输入户主姓名" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item field="create_time" label="添加时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
          </a-form-item>
        </a-col>
        <a-col :span="4">
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

      <!-- 操作前置扩展 -->
      <template #tableBeforeButtons>

        <a-button type="primary" v-if="options.add.show"  @click="openMultiAddModal()">
          <template #icon> <icon-plus /> </template> 批量增加
        </a-button>

      </template>
    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
    <multi-add-form ref="multiRef" @success="refresh" />

  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/garage.js'
import EditForm from './edit.vue'
import MultiAddForm from "@/views/xypm/build/garage/mulitadd.vue";
// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef  = ref()
const statusTypes = [
  { label: '已绑定户主', value: 1 },
  { label: '未绑定户主', value: 0 },
]

// 搜索表单
const searchForm = ref({
  build_id: '',
  number: '',
  createtime: '',
  status: '',
  ownername: '',
})

// 打开批量添加
const openMultiAddModal = (record) => {
  multiRef.value.open(record)
}


// SaTable 基础配置
const options = reactive({
  api: api.getGarageList, // 根據實際API方法調整
  rowSelection: { showCheckedAll: true },
  edit: {
    show: true,
    auth: ['/app/xypm/build/garage/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  add: {
    show: true,
    auth: ['/app/xypm/build/garage/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  batchadd: {
    show: true,
    auth: ['/app/xypm/build/garage/multiadd'],
    func: async () => {
      editRef.value?.open()
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/build/garage/destroy'],
    func: async (params) => {
      const resp = await api.deleteGarage(params)
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
  { title: '储物间编号', dataIndex: 'number', width: 120, customRender: 'number' },
  { title: '业主姓名', dataIndex: 'ownername', width: 120 },
  { title: '业主电话', dataIndex: 'mobile', width: 150 },
  { title: '建筑面积', dataIndex: 'buildarea', width: 120 },
  { title: '开始收费日期', dataIndex: 'billdate', width: 180 },
  { title: '添加时间', dataIndex: 'createtime', width: 180 },
  { title: '状态', dataIndex: 'status_text', width: 180 },
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
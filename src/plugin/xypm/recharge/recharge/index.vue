<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="4">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item label="面值" field="pay_fee">
            <a-input-number
                v-model="searchForm.facevalue[0]"
                placeholder="最小值"
                allow-clear
            />
            <a-input-number
                v-model="searchForm.facevalue[1]"
                placeholder="最大值"
                allow-clear
            />

          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item label="售价" field="buyprice">
            <a-input-number
                v-model="searchForm.buyprice[0]"
                placeholder="最小值"
                allow-clear
            />
            <a-input-number
                v-model="searchForm.buyprice[1]"
                placeholder="最大值"
                allow-clear
            />

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
        <a-col :span="6">
          <a-form-item label="创建时间" field="createtime">
            <a-range-picker
                v-model="searchForm.createtime"
                show-time
                format="YYYY-MM-DD HH:mm:ss"
                @change="handleDateChange"
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
import api from '../../api/recharge/recharge.js'
import EditForm from './edit.vue'


// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef = ref()

// 状态选项
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]




// 搜索表单
const searchForm = ref({
  id: '',
  facevalue: ['',''],
  buyprice: ['',''],
  status: '',
  createtime: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getList, // 根據實際API方法調整
  rowSelection: { showCheckedAll: true },
  edit: {
    show: true,
    auth: ['/app/xypm/recharge/recharge/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  add: {
    show: true,
    auth: ['/app/xypm/recharge/recharge/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/recharge/recharge/destroy'],
    func: async (params) => {
      const resp = await api.delete(params)
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
  { title: '面值', dataIndex: 'facevalue', width: 120 },
  { title: '售价', dataIndex: 'buyprice', width: 120 },
  { title: '套餐说明', dataIndex: 'remark', width: 120 },
  { title: '状态', dataIndex: 'status_text', width: 120 },
  { title: '创建时间', dataIndex: 'createtime', width: 120 },
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
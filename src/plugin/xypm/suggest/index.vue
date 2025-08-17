<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm"  @selection-change="setSelecteds">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="姓名" field="realname">
            <a-input v-model="searchForm.realname" placeholder="请输入姓名" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="电话" field="mobile">
            <a-input v-model="searchForm.mobile" placeholder="请输入电话" allow-clear />
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
      <!-- 操作前置扩展 -->
      <template #tableBeforeButtons>

        <a-button type="primary"   @click="openStatusModal()">
          <template #icon> <icon-settings /> </template> 更改状态
        </a-button>

      </template>
      <!-- 头像列 -->
      <template #image="{ record }">
        <a-avatar>
          <img :src="record.image ? $tool.showFile(record.image) : $url + 'avatar.jpg'" style="object-fit: cover" />
        </a-avatar>
      </template>

    </sa-table>
    <a-modal v-model:visible="isModalVisible" title="更改状态" @ok="handleOk" @cancel="handleCancel">
      <a-radio-group v-model="selectedStatus">
        <a-radio v-for="type in statusTypes" :key="type.value" :value="type.value">
          {{ type.label }}
        </a-radio>
      </a-radio-group>
    </a-modal>
    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/suggest'
import EditForm from './edit.vue'

// 引用定义
const selecteds = ref([])
const crudRef = ref()
const editRef = ref()
const isModalVisible = ref(false)
const selectedStatus = ref(null)

// Options for building types
const statusTypes = [
  { label: '待处理', value: 0 },
  { label: '处理中', value: 1 },
  { label: '已处理', value: 2 },
]

// 搜索表单
const searchForm = ref({
  id: '',
  realname: '',
  mobile: '',
  createtime: '',
})

const setSelecteds = (key) => {
  selecteds.value = key

}

// SaTable 基础配置
const options = reactive({
  api: api.getSuggestList, // Adjust API method as needed
  rowSelection: { showCheckedAll: true },

  edit: {
    show: true,
    auth: ['/app/xypm/suggest/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/suggest/destroy'],
    func: async (params) => {
      console.log(params)
      const resp = await api.deleteSuggest(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})
// Function to open the modal
const openStatusModal = () => {
  isModalVisible.value = true
}

// Function to handle modal OK button
const handleOk = async () => {
  if (selectedStatus.value === null) {
    Message.warning('请选择一条记录')
    return
  }


    const response = await api.handleSuggest({
      status: selectedStatus.value,
      ids: selecteds.value,
    })
    if (response.code === 200) {
      Message.success('状态更新成功')
      refresh()
    } else {
      Message.error('状态更新失败')
    }


  isModalVisible.value = false
}

// Function to handle modal Cancel button
const handleCancel = () => {
  isModalVisible.value = false
}
// SaTable 列配置
const columns = reactive([
  { title: 'id', dataIndex: 'id', width: 100 },
  { title: '房产', dataIndex: 'buildname', width: 150 },
  { title: '姓名', dataIndex: 'realname', width: 150 },
  { title: '电话', dataIndex: 'mobile', width: 100 },
  { title: '内容', dataIndex: 'content', width: 100 },
  { title: '创建时间', dataIndex: 'createtime','width': 120 },
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

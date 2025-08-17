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

        <a-col :span="4">
          <a-form-item label="楼宇名称" field="name">
            <a-input v-model="searchForm.name" placeholder="请输入楼宇名称" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="单元数量" field="unitnum">
            <a-input v-model="searchForm.unitnum" placeholder="单元数量" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="楼宇层数" field="floornum">
            <a-input v-model="searchForm.floornum" placeholder="楼宇层数" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="房屋数量" field="roomnum">
            <a-input v-model="searchForm.roomnum" placeholder="房屋数量" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="楼宇类型" field="status">
            <a-select
                v-model="searchForm.status"
                :options="buildingTypes"
                placeholder="请选择楼宇类型"
                allow-clear
            />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item field="create_time" label="添加时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
          </a-form-item>
        </a-col>

      </template>
      <!-- 操作前置扩展 -->
      <template #tableBeforeButtons>

        <a-button type="primary" v-if="options.add.show"  @click="openMultiAddModal()">
          <template #icon> <icon-plus /> </template> 批量增加
        </a-button>

      </template>
      <!-- Table 自定义渲染 -->
      <template #status="{ record }">
        <a-tag color="blue" v-if="record.status === 'high'">高层</a-tag>
        <a-tag color="green" v-if="record.status === 'multilayer'">多层</a-tag>
        <a-tag color="gold" v-if="record.status === 'villa'">别墅</a-tag>
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
import api from '../../api/build'
import EditForm from './edit.vue'
import MultiAddForm from './mulitadd.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef = ref()

// Options for building types
const buildingTypes = [
  { label: '高层', value: 'high' },
  { label: '多层', value: 'multilayer' },
  { label: '别墅', value: 'villa' },
]

// 搜索表单
const searchForm = ref({
  id: '',
  name: '',
  unitnum: '',
  floornum: '',
  roomnum: '',
  createtime: '',
  type: '',
})

// 打开批量添加
const openMultiAddModal = (record) => {
  multiRef.value.open(record)
}

// SaTable 基础配置
const options = reactive({
  api: api.getBuildList, // Adjust API method as needed
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
    auth: ['/app/xypm/build/build/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  batchadd: {
    show: true,
    auth: ['/app/buildings/multiadd'],
    func: async () => {
      editRef.value?.open()
    },
  },
  delete: {
    show: true,
    auth: ['/app/buildings/destroy'],
    func: async (params) => {
      console.log(params)
      const resp = await api.deleteBuild(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 100 },
  { title: '楼宇名称', dataIndex: 'name', width: 150 },
  { title: '单元数量', dataIndex: 'unitnum', width: 100 },
  { title: '楼宇层数', dataIndex: 'floornum', width: 100 },
  { title: '房屋数量', dataIndex: 'roomnum', width: 100 },
  { title: '权重', dataIndex: 'weigh', width: 100 },
  { title: '楼宇类型', dataIndex: 'status', width: 120, customRender: 'status' },
  { title: '添加时间', dataIndex: 'createtime', width: 180 },
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

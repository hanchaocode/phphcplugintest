<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="楼宇" field="build_id" >
            <a-select
                v-model="searchForm.build_id"
                placeholder="请选择楼宇"
                allow-search
                allow-clear
                :field-names="{ key: 'id', title: 'name' }"
                :loading="buildLoading"

            >
              <a-option v-for="item in buildOptions" :key="item.id" :value="item.id">
                {{ item.name }}
              </a-option>
            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="楼层" field="floor">
            <a-input v-model="searchForm.floor" placeholder="楼层" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="单元" field="unit">
            <a-input v-model="searchForm.unit" placeholder="单元" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="房号" field="number">
            <a-input v-model="searchForm.number" placeholder="房号" allow-clear />
          </a-form-item>
        </a-col>


        <a-col :span="8">
          <a-form-item label="户主姓名" field="ownername">
            <a-input v-model="searchForm.ownername" placeholder="户主姓名" allow-clear />
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
    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
    <multi-add-form ref="multiRef" @success="refresh" />

  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/house.js'
import buildApi from "@/views/xypm/api/build.js";
import EditForm from './edit.vue'
import MultiAddForm from './mulitadd.vue'


// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef = ref()
const buildLoading = ref(false)
const buildOptions = ref([])

// 打开批量添加
const openMultiAddModal = (record) => {
  multiRef.value.open(record)
}


// 搜索表单
const searchForm = ref({
  build_id: '',
  number: '',
  floor: '',
  unit: '',
  ownername: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getHouseList, // 根據實際API方法調整
  rowSelection: { showCheckedAll: true },
  edit: {
    show: true,
    auth: ['/app/xypm/build/house/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  add: {
    show: true,
    auth: ['/app/xypm/build/house/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  batchadd: {
    show: true,
    auth: ['/app/xypm/build/house/multiadd'],
    func: async () => {
      editRef.value?.open()
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/build/house/destroy'],
    func: async (params) => {
      const resp = await api.deleteHouse(params)
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
  { title: '楼宇', dataIndex: 'build.name', width: 150},
  { title: '单元', dataIndex: 'unit', width: 120 },
  { title: '楼层', dataIndex: 'floor', width: 120 },
  { title: '房号', dataIndex: 'number', width: 120 },
  { title: '户主姓名', dataIndex: 'ownername', width: 120 },
  { title: '户主电话', dataIndex: 'mobile', width: 150 },
  { title: '建筑面积', dataIndex: 'buildarea', width: 120 },
  { title: '开始收费日期', dataIndex: 'billdate', width: 150 },
  { title: '添加时间', dataIndex: 'createtime', width: 180 },
  { title: '状态', dataIndex: 'status_text', width: 180 },
])
// 页面数据初始化
const initPage = async () => {
  const deptResp = await buildApi.getBuildOptions()
  buildOptions.value = deptResp.data

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
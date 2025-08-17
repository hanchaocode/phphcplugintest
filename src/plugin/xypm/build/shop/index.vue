<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="4">
          <a-form-item label="商铺区域" field="area">

            <a-select
                v-model="searchForm.build_area_id"
                :options="areas"
                placeholder="请选择商铺区域"
                allow-clear
            >
              <a-option
                  v-for="a in areas"
                  :key="a.id"
                  :value="a.id"
                  :label="a.name" />

            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="户主姓名" field="ownername">
            <a-input v-model="searchForm.ownername" placeholder="请输入户主姓名" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="户主电话" field="mobile">
            <a-input v-model="searchForm.mobile" placeholder="请输入户主电话" allow-clear />
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
import areaApi from '../../api/build/area'
import api from '../../api/build/shop'
import EditForm from './edit.vue'
import * as vars from "@/views/tool/code/js/vars.js";
import MultiAddForm from "@/views/xypm/build/shop/mulitadd.vue";


// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef  = ref()
const areas = []
const statusTypes = [
  { label: '已绑定户主', value: 1 },
  { label: '未绑定户主', value: 0 },
]

// 搜索表单
const searchForm = ref({
  build_area_id: '',
  ownername: '',
  mobile:''
})
// 打开批量添加
const openMultiAddModal = (record) => {
  multiRef.value.open(record)
}

// SaTable 基础配置
const options = reactive({
  api: api.getShopList, // Adjust API method as needed
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/build/shop/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/build/shop/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/build/shop/destroy'],
    func: async (params) => {
      const resp = await api.deleteShop(params)
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
  { title: '商铺区域', dataIndex: 'area.name', width: 150 },
  { title: '商铺编号', dataIndex: 'number', width: 100 },
  { title: '所在楼层', dataIndex: 'floor', width: 100 },
  { title: '户主姓名', dataIndex: 'ownername', width: 150 },
  { title: '户主电话', dataIndex: 'mobile', width: 150 },
  { title: '建筑面积', dataIndex: 'buildarea', width: 100 },
  { title: '开始收费日期', dataIndex: 'billdate', width: 150 },
  { title: '添加时间', dataIndex: 'createtime', width: 180 },
  { title: '状态', dataIndex: 'status_text', width: 120},
])


// 页面数据初始化
const initPage = async () => {
  // Initialize any necessary data here

  const resp = await areaApi.getAreaOptions({type:'shop'})
  resp.data.map((item) => {
    areas.push(item)
  })
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

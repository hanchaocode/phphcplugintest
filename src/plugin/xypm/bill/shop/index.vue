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
        <a-col :span="5">
          <a-form-item label="商铺" field="build_id" >
            <a-select
                v-model="searchForm.build_id"
                placeholder="商铺"
                allow-search
                allow-clear
                :field-names="{ key: 'id', title: 'buildname' }"
                :loading="buildLoading"
            >
              <a-option v-for="item in houseOptions" :key="item.id" :value="item.id">
                {{ item.buildname }}
              </a-option>
            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item label="费用区间" field="money">
            <a-input
                v-model="searchForm.money[0]"
                placeholder="最小值"
                allow-clear
            />
            <a-input
                v-model="searchForm.money[1]"
                placeholder="最大值"
                allow-clear
            />

          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item field="create_time" label="创建时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="账单日期" field="billdate">
            <a-input v-model="searchForm.billdate" placeholder="请输入账单日期" allow-clear />
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

        <a-button type="primary"  @click="openBuildModal('shop')">
          <template #icon> <icon-plus /> </template> 生成账单
        </a-button>

      </template>
    </sa-table>

    <!-- 编辑表单 -->

    <build-bill ref="multiRef" @success="refresh"  />

  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/bill.js'
import shopApi from '../../api/build/shop.js'

import BuildBill from '../components/buildbill.vue'
import garageApi from "@/views/xypm/api/build/garage.js";

// 引用定义
const crudRef = ref()
const editRef = ref()
const multiRef = ref()
const buildLoading = ref(false)
const houseOptions = ref([])
const statusTypes = [
  { label: '未交费', value: 0 },
  { label: '已交费', value: 1 },
]

// 打开批量添加
const openBuildModal = (buildtype) => {
  multiRef.value.open(buildtype)
}


// 搜索表单
const searchForm = ref({
  id: '',
  buildtype: 'shop',
  money: ['',''],
  status: '',
  billdate: '',
  createtime: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getBillList, // 根據實際API方法調整
  rowSelection: { showCheckedAll: true },
  // edit: {
  //   show: true,
  //   auth: ['/app/xypm/bill/house/edit'],
  //   func: async (record) => {
  //     editRef.value?.open('edit', record.id)
  //   },
  // },

  delete: {
    show: true,
    auth: ['/app/xypm/build/bill/destroy'],
    func: async (params) => {
      const resp = await api.deleteBill(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: '商铺', dataIndex: 'buildname', width: 120 },
  { title: '账单日期', dataIndex: 'billdate', width: 120 },
  { title: '费用', dataIndex: 'money', width: 120 },
  { title: '生成时间', dataIndex: 'createtime', width: 180 },
  { title: '状态', dataIndex: 'status_text', width: 180 },
])
// 页面数据初始化
const initPage = async () => {
  const houseResp = await shopApi.getShopOption()
  houseOptions.value = houseResp.data


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
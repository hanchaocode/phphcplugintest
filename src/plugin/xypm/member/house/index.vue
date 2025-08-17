<template>
  <div class="ma-content-block">

    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm"  >
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>

        <a-col :span="5">
          <a-form-item label="房屋" field="build_id" required>
            <a-select
                v-model="searchForm.build_id"
                placeholder="请选择房屋"
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
        <a-col :span="4">
          <a-form-item label="姓名" field="realname">
            <a-input v-model="searchForm.realname" placeholder="姓名" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="手机号" field="mobile">
            <a-input v-model="searchForm.mobile" placeholder="手机号" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="身份证号" field="idcard">
            <a-input v-model="searchForm.idcard" placeholder="身份证号" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="备注" field="remark">
            <a-input v-model="searchForm.remark" placeholder="备注" allow-clear />
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
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini" v-if="record.id > 1">
          <a-link v-auth="['/app/xypm/member/family']"  @click="openFamilyList(record)"> <icon-menu /> 成员列表 </a-link>
        </a-space>
      </template>
    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />

    <!-- 成员列表 -->
    <family ref="mpRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/member'
import configApi from '../../api/config'
import EditForm from './edit.vue'

import Family from '../components/family.vue'
import houseApi from "@/views/xypm/api/build/house.js";
// 引用定义
const crudRef = ref()
const editRef = ref()
const mpRef = ref()
const houseOptions = ref([])
const env = import.meta.env
// let res = await configApi.cdnUrl()
// const baseUrl = res.data.cdnurl

const buildLoading = ref(false)

const isTableInitialized = ref(false)
const statusTypes = [
  { label: '已绑定户主', value: 1 },
  { label: '未绑定户主', value: 0 },
]

// 搜索表单
const searchForm = ref({
  realname: '',
  build_id: '',
  status: '',
  remark: '',
  buildtype: 'house',
})


const openFamilyList = (record) => {
  mpRef.value.open(record)
}

// SaTable 基础配置
const options = reactive({
  api: api.getMemberList, // Adjust API method as needed
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/member/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  import:{
    show: true,
    auth: ['/app/xypm/member/import'],
    url: '/app/xypm/admin/member/import?type=house',
    templateUrl: `${window.location.protocol}/xypm/file/房屋户主导入模板.xlsx`,
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/member/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },

  delete: {
    show: true,
    auth: ['/app/xypm/member/destroy'],
    func: async (params) => {
      console.log(params)
      const resp = await api.deleteMember(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: '房屋', dataIndex: 'buildname', width: 120 },
  { title: '姓名', dataIndex: 'realname', width: 100 },
  { title: '手机', dataIndex: 'mobile', width: 120 },
  { title: '身份证号', dataIndex: 'idcard', width: 150 },
  { title: '建筑面积', dataIndex: 'buildarea', width: 100 },
  { title: '开始收费日期', dataIndex: 'billdate', width: 130 },
  { title: '成员', dataIndex: 'mnums', width: 100 },
  { title: '备注', dataIndex: 'remark', width: 100 },
  { title: '添加时间', dataIndex: 'createtime', width: 180 },
  { title: '状态', dataIndex: 'status_text', width: 180 },
])

// 页面数据初始化
const initPage = async () => {
  const houseResp = await houseApi.getHouseOption()
  houseOptions.value = houseResp.data


  // let res = await configApi.cdnUrl()
  // baseUrl.value = res.data.cdnurl

  isTableInitialized.value = true
  crudRef.value?.refresh()

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

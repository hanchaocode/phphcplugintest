<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="昵称" field="nickname">
            <a-input v-model="searchForm.nickname" placeholder="昵称" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="手机号" field="mobile">
            <a-input v-model="searchForm.mobile" placeholder="手机号" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="注册时间" field="createtime">
            <a-range-picker
                v-model="searchForm.createtime"
                show-time
                style="width: 100%"
            />
          </a-form-item>
        </a-col>

        <a-col :span="6">
          <a-form-item label="支付平台" field="third_platform">
            <a-select
                v-model="searchForm.third_platform"
                :options="platformTypes"
                placeholder="平台"
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


      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini" >
          <a-link v-auth="['/app/xypm/user/money']"  @click="openSignupList(record)"> <icon-menu /> 余额明细 </a-link>
          <a-link v-auth="['/app/xypm/user/recharge']"  @click="openAdjust(record)"> <icon-edit /> 调整余额 </a-link>
        </a-space>
      </template>
      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="record.status === 'normal' ? 'green' : 'orange'">
          {{ record.status === 'normal' ? '正常' : '禁用' }}
        </a-tag>
      </template>

      <template #third_platform="{ record }">
        <a-tag :color="record.third_platform === 'wxMiniProgram' ? 'green' : 'orange'">
          {{ record.third_platform === 'wxMiniProgram' ? '微信小程序' : '微信公众号' }}
        </a-tag>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
    <money-record ref="mpRef" @success="refresh" />
    <money-adjust ref="adjustRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/user' 
import EditForm from './edit.vue'
import MoneyRecord from './moneyrecord.vue'
import MoneyAdjust from './adjust.vue'


// 引用定义
const crudRef = ref()
const editRef = ref()
const mpRef = ref()
const adjustRef = ref()

// 状态选项
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '禁用', value: 'hidden' },
]
const platformTypes = [
  { label: '全部', value: '' },
  { label: '微信小程序', value: 'wxMiniProgram' },
  { label: '微信公众号', value: 'wxOfficialAccount' },
]

// 搜索表单
const searchForm = ref({
  id: '',
  nickname: '',
  mobile:'',
  third_platform: '',
  status: '',
  createtime: '',
})


const openSignupList = (record) => {
  mpRef.value.open(record)
}


const openAdjust = (record) => {
  adjustRef.value.open(record)

}
// SaTable 基础配置
const options = reactive({
  api: api.getUserList,
  operationColumnWidth: 300,
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/user/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/user/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/user/destroy'],
    func: async (params) => {
      const resp = await api.deleteUser(params)
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
  { title: '头像', dataIndex: 'avatar', slotName: 'avatar', width: 120 },
  { title: '昵称', dataIndex: 'nickname', width: 120 },
  { title: '手机号', dataIndex: 'mobile', width: 150 },
  { title: '余额', dataIndex: 'money', width: 100 },
  { title: '注册时间', dataIndex: 'createtime', width: 150 },
  { title: '平台', dataIndex: 'third_platform', width: 100 },
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
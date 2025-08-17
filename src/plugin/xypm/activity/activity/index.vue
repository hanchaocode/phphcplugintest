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
          <a-form-item label="标题" field="title">
            <a-input v-model="searchForm.title" placeholder="请输入标题" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item field="create_time" label="创建时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="主办单位" field="organizer">
            <a-input v-model="searchForm.organizer" placeholder="请输入主办单位" allow-clear />
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

      <!-- 封面图列 -->
      <template #image="{ record }">
        <a-image
            width="80"
            height="60"
            :src="record.image ? $tool.showFile(record.image) : $url + 'default-image.jpg'"
            :preview="!!record.image"
            fit="cover"
        />
      </template>
      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini" >
          <a-link v-auth="['/app/xypm/activity/signup']"  @click="openSignupList(record)"> <icon-menu /> 报名管理 </a-link>
        </a-space>
      </template>
      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="record.status === 'normal' ? 'green' : 'orange'">
          {{ record.status === 'normal' ? '正常' : '隐藏' }}
        </a-tag>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
    <sign-up ref="mpRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/activity/activity' // 调整为活动API
import EditForm from './edit.vue'
import SignUp from './signup.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()
const mpRef = ref()

// 状态选项
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]

// 搜索表单
const searchForm = ref({
  id: '',
  title: '',
  status: '',
  organizer: '',
  createtime: '',
})


const openSignupList = (record) => {
  mpRef.value.open(record)
}

// SaTable 基础配置
const options = reactive({
  api: api.getActivityList, // 调整为获取活动列表API
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/activity/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/xypm/activity/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/xypm/activity/destroy'],
    func: async (params) => {
      const resp = await api.deleteActivity(params)
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
  { title: '封面图', dataIndex: 'image', slotName: 'image', width: 120 },
  { title: '标题', dataIndex: 'title', width: 200 },
  { title: '主办单位', dataIndex: 'organizer', width: 150 },
  { title: '活动人数', dataIndex: 'number', width: 100 },
  { title: '已报名人数', dataIndex: 'signup_num', width: 100 },
  { title: '浏览量', dataIndex: 'views', width: 100 },
  { title: '创建时间', dataIndex: 'createtime', width: 170 },

  { title: '状态', dataIndex: 'status', slotName: 'status', width: 100 },
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
<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="1000"
      title="选择链接"
      :mask-closable="false"

      @cancel="close"

  >
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="路径名称" field="name">
            <a-input v-model="searchForm.name" placeholder="请输入路径名称" allow-clear />
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

        <a-col :span="8">
          <a-form-item label="分类类型" field="type">
            <a-select
                v-model="searchForm.type"
                :options="typeOptions"
                placeholder="请选择分类类型"
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

      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="record.status === 'normal' ? 'green' : 'orange'">
          {{ record.status === 'normal' ? '正常' : '隐藏' }}
        </a-tag>
      </template>
      <!-- 操作前置扩展 -->
      <template #tableBeforeButtons>

        <a-button type="primary" v-if="options.add.show"  @click="buildUrl()">
          <template #icon> <icon-play-arrow /> </template> 生成链接
        </a-button>

      </template>


      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini">
          <a-link v-auth="['/app/xypm/link/index']"  @click="select(record)"> <icon-menu /> 选择 </a-link>
        </a-space>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
  </component>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/link'
import EditForm from './edit.vue'

// 引用定义
const crudRef = ref()
const editRef = ref()
const visible = ref(false)
const emit = defineEmits(['row-selected']);
// 状态选项
const statusTypes = [
  { label: '正常', value: 'normal' },
  { label: '隐藏', value: 'hidden' },
]

// 分类类型选项 basic=基础链接,user=会员中心,notice=公告链接,activity=活动链接
const typeOptions = [
  { label: '基础链接', value: 'basic' },
  { label: '会员中心', value: 'user' },
  { label: '公告链接', value: 'notice' },
  { label: '活动链接', value: 'activity' },
  { label: '商品链接', value: 'goods' },
]

// 搜索表单
const searchForm = ref({
  name: '',
  status: '',
  type: '',
})

const select = (record) => {
  visible.value = false
  // Emit an event with the selected URL
  emit('row-selected', record.url);
};
// 打开生成链接
const buildUrl = async (record) => {

    let result = {}

    result = await api.buildLink()
    if (result.code === 200) {
      Message.success('操作成功')
      await refresh()

    }
    // 防止连续点击提交
    setTimeout(() => {

    }, 500)


}
// SaTable 基础配置
const options = reactive({
  api: api.getLinkList, // 调整为获取分类列表API
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/xypm/link/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: false,
    auth: ['/app/xypm/link/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: false,
    auth: ['/app/xypm/link/destroy'],
    func: async (params) => {
      const resp = await api.deleteLink(params)
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
  { title: '类型', dataIndex: 'type_text', width: 150 },
  { title: '名称', dataIndex: 'name',  width: 120 },
  { title: '路径', dataIndex: 'url',  width: 160 },
])

// 页面数据初始化
const initPage = async () => {
  // Initialize any necessary data here
}

const close = () => {
  visible.value = false
  formRef.value.resetFields()
}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}

initPage()
defineExpose({ open: () => (visible.value = true) })
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
}
</style>
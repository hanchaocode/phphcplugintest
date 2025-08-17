<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="1000"
      title="历史记录"
      :mask-closable="false"

      @cancel="close"

  >
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
          <a-form-item label="搜索" field="name">
            <a-input v-model="searchForm.name" placeholder="搜索" allow-clear />
          </a-form-item>
        </a-col>

      </template>


      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="record.status === 'normal' ? 'green' : 'orange'">
          {{ record.status === 'normal' ? '正常' : '隐藏' }}
        </a-tag>
      </template>



      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini">
          <a-link v-auth="['/app/xypm/page/index']"  @click="select(record)"> <icon-check /> 还原 </a-link>
        </a-space>
      </template>

    </sa-table>

  </div>
  </component>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'

import api from '../api/page'


// 引用定义
const crudRef = ref()
const editRef = ref()
const visible = ref(false)
const pageToken = ref()
const emit = defineEmits(['row-selected']);



// 搜索表单
const searchForm = ref({
  page_token: '',
  name: '',

})

const select = (record) => {
  visible.value = false
  // Emit an event with the selected URL

  emit('row-selected', record.id);
};

const open = (value) => {
  pageToken.value =value
  visible.value = true
  searchForm.value.page_token = pageToken.value
  crudRef.value?.refresh()
}
// SaTable 基础配置
const options = reactive({
  api: api.getHistroy,
  singleLine: true,
  add: {
    show: false,

  },
  edit: {
    show: false,
  },
  delete: {
    show: false,


  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 80 },
  { title: '名称', dataIndex: 'name', width: 150 },
  { title: '创建时间', dataIndex: 'createtime', width: 150 },
  { title: '更新时间', dataIndex: 'updatetime',  width: 160 },
  { title: '删除时间', dataIndex: 'deletetime_text',  width: 160 },
])

// 页面数据初始化
const initPage = async () => {
  // Initialize any necessary data here
}

const close = () => {
  visible.value = false

}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}


defineExpose({ open })
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
}
</style>
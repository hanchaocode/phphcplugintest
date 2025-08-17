<template>
  <a-modal
      :visible="visible"
      title="选择文章"
      width="800px"
      :footer="null"

      @update:visible="handleVisibleChange"
      @open="handleModalOpen"
  >
    <div class="article-selector">
      <a-form layout="inline" :model="searchForm" class="search-form">
        <a-form-item label="标题">
          <a-input v-model:value="searchForm.title" placeholder="请输入标题" allow-clear />
        </a-form-item>
        <a-form-item label="状态">
          <a-select
              v-model:value="searchForm.status"
              :options="statusOptions"
              placeholder="请选择状态"
              style="width: 150px"
              allow-clear
          />
        </a-form-item>
        <a-form-item>
          <a-button type="primary" @click="handleSearch">搜索</a-button>
        </a-form-item>
      </a-form>

      <a-table
          :columns="columns"

          :pagination="pagination"
          :loading="loading"
          row-key="id"
          @change="handleTableChange"
      >
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'image'">
            <a-avatar :src="getImageUrl(record.image)" />
          </template>
          <template v-if="column.key === 'status'">
            {{ getStatusText(record.status) }}
          </template>
          <template v-if="column.key === 'action'">
            <a-button type="link" @click="handleSelect(record)">选择</a-button>
          </template>
        </template>
      </a-table>
    </div>
  </a-modal>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { Message, Modal } from '@arco-design/web-vue'
import api from '../api/article'

const props = defineProps({
  visible: Boolean
})

const emit = defineEmits(['update:visible', 'selected'])

// 状态选项
const statusOptions = ref([
  { value: 'normal', label: '正常' },
  { value: 'hidden', label: '隐藏' }
])

// 搜索表单
const searchForm = reactive({
  title: '',
  status: undefined
})

// 表格数据
const data = ref([])
const loading = ref(false)
const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showSizeChanger: true,
  pageSizeOptions: ['10', '20', '50']
})

// 表格列配置
const columns = [
  { title: 'ID', dataIndex: 'id', width: 80 },
  { title: '标题', dataIndex: 'title', ellipsis: true },
  { title: '封面', key: 'image', width: 100 },
  { title: '浏览量', dataIndex: 'views', width: 100 },
  { title: '状态', key: 'status', width: 100 },
  { title: '操作', key: 'action', width: 100 }
]
// 处理 visible 变化
const handleVisibleChange = (newVisible) => {
  emit('update:visible', newVisible)
}
// 获取图片URL
const getImageUrl = (image) => {
  return image ? $tool.showFile(image) : $url + 'avatar.jpg'
}

// 获取状态文本
const getStatusText = (status) => {
  const option = statusOptions.value.find(item => item.value === status)
  return option ? option.label : status
}

// 加载数据
const fetchData = async () => {
  try {
    loading.value = true
    const params = {
      page: pagination.current,
      pageSize: pagination.pageSize,
      ...searchForm
    }

    const response = await api.getArticleList(params)
    data.value = response.data.data || response.data
    pagination.total = response.data.total || 0

  } catch (error) {
    Message.error('加载文章列表失败')
    console.error(error)
  } finally {
    loading.value = false
  }
}

// 搜索
const handleSearch = () => {
  pagination.current = 1
  fetchData()
}

// 表格变化
const handleTableChange = (pag) => {
  pagination.current = pag.current
  pagination.pageSize = pag.pageSize
  fetchData()
}

// 选择文章
const handleSelect = (record) => {
  emit('selected', {
    id: record.id,
    title: record.title
  })
  emit('update:visible', false)
}

// 监听弹窗打开状态
watch(() => props.visible, (newVal) => {
  if (newVal) {
    fetchData() // 弹窗打开时加载数据
  }
})

// 或者使用 modal 的 open 事件
const handleModalOpen = () => {
  fetchData()
}
</script>

<style scoped>
.article-selector {
  padding: 10px;
}

.search-form {
  margin-bottom: 16px;
}
</style>
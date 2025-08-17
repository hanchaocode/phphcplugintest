<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="4">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="标题" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="商品标题" field="title">
            <a-input v-model="searchForm.title" placeholder="请输入商品标题" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="价格" field="price">
            <a-input-number v-model="searchForm.price" placeholder="价格" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item label="划线价格" field="line_price">
            <a-input-number
                v-model="searchForm.line_price[0]"
                placeholder="最小值"
                allow-clear
            />
            <a-input-number
                v-model="searchForm.line_price[1]"
                placeholder="最大值"
                allow-clear
            />

          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="商品状态" field="status">
            <a-select
                v-model="searchForm.status"
                :options="statusTypes"
                placeholder="请选择状态"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="4">
          <a-form-item label="商品分类" field="category_ids">
            <a-tree-select
                v-model="searchForm.category_ids"
                :data="categoryData"
                multiple
                :field-names="{ key: 'id', title: 'name', children: 'children', icon: 'customIcon' }"
                allow-clear
                placeholder="请选择商品分类"
            >
            </a-tree-select>
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="浏览量" field="views">
            <a-input-number v-model="searchForm.views" placeholder="浏览量" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="销量" field="sales">
            <a-input-number v-model="searchForm.sales" placeholder="销量" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item field="create_time" label="添加时间">
            <a-range-picker v-model="searchForm.createtime" show-time style="width: 100%" />
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
        <a-tag :color="record.status === 'up' ? 'green' : 'orange'">
          {{ record.status === 'up' ? '上架' : '下架' }}
        </a-tag>
      </template>

      <!-- 价格列 -->
      <template #price="{ record }">
        <span>¥{{ record.price }}</span>
        <div class="text-xs text-gray-500" v-if="record.line_price">划线价: ¥{{ record.line_price }}</div>
      </template>

    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/goods/goods'
import EditForm from './edit.vue'
import categoryApi from "@/views/xypm/api/goods/category.js";

// 引用定义
const crudRef = ref()
const editRef = ref()
const categoryData = ref([]);
// 状态选项
const statusTypes = [
  { label: '上架', value: 'up' },
  { label: '下架', value: 'down' },
]

// 分类选项


// 搜索表单
const searchForm = ref({
  id: '',
  title: '',
  price: '',
  line_price: ['',''],
  status: '',
  views: '',
  sales: '',
  category_ids: [],
  createtime: '',
})

// SaTable 基础配置
const options = reactive({
  api: api.getGoodsList,
  rowSelection: { showCheckedAll: true },
  add: {
    show: true,
    auth: ['/app/goods/save'],
    func: async () => {
      editRef.value?.open()
    },
  },
  edit: {
    show: true,
    auth: ['/app/goods/edit'],
    func: async (record) => {
      editRef.value?.open('edit', record.id)
    },
  },
  delete: {
    show: true,
    auth: ['/app/goods/destroy'],
    func: async (params) => {
      const resp = await api.deleteGoods(params)
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
  { title: '主图', dataIndex: 'image', slotName: 'image', width: 120 },
  { title: '商品标题', dataIndex: 'title', width: 200 },
  { title: '分类', dataIndex: 'category_ids_text', width: 150 },
  { title: '价格', dataIndex: 'price', slotName: 'price', width: 120 },
  { title: '浏览量', dataIndex: 'views', width: 100 },
  { title: '销量', dataIndex: 'sales', width: 100 },
  { title: '状态', dataIndex: 'status', slotName: 'status', width: 100 },
  { title: '创建时间', dataIndex: 'createtime', width: 180 },
])

// 页面数据初始化
const initPage = async () => {
  const resp = await categoryApi.getCategoryList();
  categoryData.value = resp.data;
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
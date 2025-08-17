<template>
  <a-modal v-model:visible="visible" fullscreen :footer="false" @close="handleClose">
    <template #title>余额明细</template>

    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>


          <a-col :span="8">
            <a-form-item label="类型" field="type">
              <a-select
                  v-model="searchForm.type"
                  :options="statusTypes"
                  placeholder="请选择类型"
                  allow-clear
              />
            </a-form-item>
          </a-col>


      </template>



    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" :pid="familyId" @success="refresh"  />
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../../api/user/money'
import { Message } from '@arco-design/web-vue'
import EditForm from "@/views/xypm/member/components/editfamily.vue";
// 定义 props


const emit = defineEmits(['success'])

const visible = ref(false)
const crudRef = ref()
const userId = ref()
const editRef = ref()
const users = ref([])


// 状态选项
const statusTypes = [
  { label: '后台操作', value: 'Sys' },
  { label: '购买商品', value: 'Pay_goods' },
  { label: '交物业费', value: 'Pay_bill' },
]

// 搜索表单
const searchForm = ref({
  type: '',


})
// 打开添加


// 打开弹框
const open = (row) => {
  userId.value = row.id
  visible.value = true
  searchForm.value.userId = userId.value
  crudRef.value?.refresh()
}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}


const handleClose = async () => {
  emit('success', true)
}

// SaTable 基础配置
const options = reactive({
  api: api.getList,
  rowSelection: { showCheckedAll: true },
  singleLine: true,
  delete: {
    show: true,
    auth: ['/app/xypm/user/money/destroy'],
    func: async (params) => {
      const resp = await api.delete(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'id', dataIndex: 'id' },
  { title: '变更类型', dataIndex: 'type_text' },
  { title: '变更费用', dataIndex: 'money' },
  { title: '变更前', dataIndex: 'before' },
  { title: '变更后', dataIndex: 'after' },
  { title: '备注', dataIndex: 'remark' },
  { title: '创建时间', dataIndex: 'createtime',width:200 },

])

defineExpose({ open })
</script>

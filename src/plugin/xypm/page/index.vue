<template>
  <div class="ma-content-block">
    <div class="panel panel-default panel-intro">
      <div class="panel-heading">
        <div class="panel-lead">
          <h3 class="panel-title">{{ title }}</h3>
          <p class="panel-subtitle">温馨提示：模板保存之后需要发布才能生效，未使用模板的页面均使用通用配置，前端没有实时更新后台清除一下缓存。</p>
        </div>

        <div class="panel-actions">
          <a-space>
            <a-button type="primary" @click="refresh">
              <template #icon><icon-refresh /></template> 刷新
            </a-button>
            <a-button type="primary"@click="handleAdd">
              <template #icon><icon-plus /></template> 新建模板
            </a-button>
            <a-button type="primary" @click="handleAppStyle">
              <template #icon><icon-common /></template> 通用配置
            </a-button>

          </a-space>
        </div>
      </div>

      <div class="panel-body">
        <a-tabs v-model="activeTab" @change="handleTabChange">
          <a-tab-pane key="" title="全部"></a-tab-pane>
          <a-tab-pane v-for="(label, value) in typeList" :key="value" :title="label"></a-tab-pane>
        </a-tabs>

        <a-row :gutter="16" v-if="loading" class="mt-20">
          <a-col :span="6" v-for="n in 4" :key="n">
            <a-skeleton :animation="true">
              <a-skeleton-line :rows="3" />
            </a-skeleton>
          </a-col>
        </a-row>

        <a-row :gutter="16" v-else-if="list.length > 0" class="mt-20">
          <a-col :xs="24" :sm="12" :md="8" :lg="6" v-for="item in list" :key="item.id">
            <div class="xypm_tem">
              <div class="main">
                <div class="bg">
                  <a-image
                      width="100%"

                      :src="item.cover ? $tool.showFile(item.cover) : $url + 'default-image.jpg'"
                      fit="cover"
                  />
                </div>
                <div class="mask"></div>
                <div class="operate">
                  <div class="text-center">
                    <a-space>

                      <a-button type="primary" size="mini" @click="handlePublish(item)">
                        <template #icon><icon-send /></template> 发布
                      </a-button>
                      <a-button type="primary" size="mini" @click="handleEdit(item)">
                        <template #icon><icon-edit /></template> 装修
                      </a-button>
                      <a-button type="primary" size="mini" @click="handleDelete(item)">
                        <template #icon><icon-delete /></template> 删除
                      </a-button>

                    </a-space>
                  </div>
                </div>
                <div class="info">
                  <a-tag color="blue">{{ item.name }}</a-tag>
                </div>
                <div class="use">
                  <a-tag :color="item.is_use == 1 ? 'red' : 'gray'">
                    {{ item.is_use == 1 ? '已发布' : '未发布' }}
                  </a-tag>
                </div>
              </div>
            </div>
          </a-col>
        </a-row>

        <a-empty v-else class="mt-20" description="暂无数据" />

        <a-pagination
            v-if="total > 0"
            class="mt-20"
            v-model:current="page"
            v-model:page-size="pageSize"
            :total="total"
            show-total
            show-jumper
            show-page-size
            @change="handlePageChange"
        />
      </div>
    </div>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" @success="refresh" v-show="showEdit" />
    <add-form ref="addRef" @success="refresh"  />
    <style-set ref="styledRef" @success="refresh"  />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import {Message, Modal} from '@arco-design/web-vue'
import api from '../api/page'
import EditForm from './edit.vue'
import AddForm from './add.vue'
import StyleSet from './styleset.vue'


const title = ref('页面模板管理')
const editRef = ref()
const addRef = ref()
const styledRef = ref()
const activeTab = ref('')
const loading = ref(false)
const showEdit = ref(false)
const list = ref([])
const total = ref(0)
const page = ref(1)
const pageSize = ref(12)
const nowTime = ref(0)

// 模板类型列表
const typeList = reactive({
  index: '首页模板',
  shop: '商城模板',
  user: '用户中心模板'
})

// 获取模板列表
const fetchList = async () => {
  try {
    loading.value = true
    const params = {
      page: page.value,
      pageSize: pageSize.value,
      type: activeTab.value
    }
    const res = await api.getPageList(params)
    if (res.code === 200) {
      list.value = res.data
      total.value = res.data.length
    }
  } finally {
    loading.value = false
  }
}

// 处理标签切换
const handleTabChange = (key) => {
  activeTab.value = key
  page.value = 1
  fetchList()
}

// 处理分页变化
const handlePageChange = (current, pageSize) => {
  page.value = current
  fetchList()
}

// 新增模板
function handleAdd() {

  addRef.value?.open('add')

}

// 编辑模板
function handleEdit(item) {
  editRef.value?.open('edit', item.id)

}
// 通用配置
function handleAppStyle() {

  styledRef.value?.open('add')
}


// 发布模板
async function handlePublish(item) {
  const resp = await api.publishPage({ ids: item.id })
  if (resp.code === 200) {
    Message.success('发布成功')
    fetchList()
  }
}

// 删除模板
async function handleDelete(item) {
  Modal.confirm({
    title: '删除模板',
    content: `确定要删除模板【${item.name}】吗？`,
    okText: '确定',
    cancelText: '取消',
    onOk: async () => {
      const resp = await api.deletePage({ ids: item.id })
      if (resp.code === 200) {
        Message.success('删除成功')
        fetchList()
        visible.value = false
      }

    }
  })

}


// 刷新列表
const refresh = () => {
  page.value = 1
  fetchList()
}

onMounted(() => {
  fetchList()

  // 每分钟更新时间
  setInterval(() => {
    const now = new Date()
    const hh = now.getHours().toString().padStart(2, '0')
    const mm = now.getMinutes().toString().padStart(2, '0')
    nowTime.value = `${hh}:${mm}`
  }, 60000)
})
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
}

.panel-intro {
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0,0,0,.1);
}

.panel-heading {
  padding: 15px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-lead .panel-subtitle {
  color: #f56c6c;
  font-size: 12px;
  margin-top: 5px;
}

.panel-body {
  padding: 15px;
}

.tips{color: red;padding: 10px;}
.table .row{
  margin: 0;
}
.xypm_tem {
  position: relative;
  width: 360px;
  height: 755px;
  margin-top: 10px;
  margin-right: 15px;
  margin-bottom: 5px;
  border-radius: 10px;
  padding: 10px;
  background-color: #e6e6e6;
  overflow: hidden;
}

.xypm_tem .main {
  position: relative;
  height: 100%;
  border-radius: 5px;
  background-color: #ffffff;
}
.xypm_tem .main .bg{
  width: 100%;
}
.xypm_tem .main .bg img{
  width: 100%;
  min-height: 400px;
}
.xypm_tem .main .mask{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
}

.xypm_tem .main:hover .mask
{
  background-color: rgba(0,0,0,.6);
}

.xypm_tem .main:hover .qrcode {
  top: 56px
}
.xypm_tem .main .qrcode {
  position: absolute;
  left: 50%;
  margin-left: -70px;
  width: 140px;
  height: 140px;
  border-radius: 3px;
  background: #fff;
  top: -140px;
  z-index: 5;
  overflow: hidden;
  transform-origin: center center;
  transform: scale(0.9109);
  transition: top .3s
}
.xypm_tem .main .qrcode img {
  width: 100%;
  height: 100%;
}
.xypm_tem .main:hover .operate{
  display: flex;
}
.xypm_tem .main .operate{
  position: absolute;
  top: 200px;
  left: 0;
  width: 100%;
  display: none;
  justify-content: center;
  z-index: 3;
}
.xypm_tem .main .operate .btn + .btn{
  margin-left: 5px;
}
.xypm_tem .main .operate .title{
  margin-bottom: 12px;
  font-size: 14px;
  color: #ffffff;
}


.xypm_tem .main .info{
  position: absolute;
  top: 10px;
  left: 10px;
}

.xypm_tem .main .use{
  position: absolute;
  top: 10px;
  right: 10px;
}

.mt-20 {
  margin-top: 20px;
}

:deep(.arco-tabs-content) {
  padding-top: 0;
}

:deep(.arco-tabs-nav-tab-list) {
  margin-bottom: 0;
}
</style>
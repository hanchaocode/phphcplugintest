<template>
  <div class="ma-content-block flex">
    <!-- 左侧一级配置组 -->
    <div class="lg:w-3/12 w-full h-full p-4 pr-2">
      <a-list :max-height="650" :scrollbar="true" size="small">
        <template #header>
          <div class="flex justify-between items-center" :style="{ height: '30px' }">
            <span>配置分组</span>
          </div>
        </template>
        <a-list-item v-for="(group, index) in configGroupData" :key="'group-'+index">
          <div class="flex justify-between items-center">
            <a-button
                :type="activeGroupId === group.code ? 'outline' : ''"
                @click="showGroupItems(group)"
            >
              {{ group.name }}
            </a-button>
          </div>
        </a-list-item>
      </a-list>
    </div>

    <!-- 右侧二级配置项 -->
    <div class="lg:w-3/12 w-full h-full p-4 pl-2 border-l" v-if="activeGroupItems.length > 0">
      <a-list :max-height="650" :scrollbar="true" size="small">
        <template #header>
          <div class="flex justify-between items-center" :style="{ height: '30px' }">
            <span>{{ activeGroupName }} - 配置项</span>
          </div>
        </template>
        <a-list-item v-for="(item, index) in activeGroupItems" :key="'item-'+index">
          <div class="flex justify-between items-center">
            <a-button
                :type="activeItemId === item.code ? 'outline' : ''"
                @click="getConfigData(item)"
            >
              {{ item.name }}<span class="text-gray-500 text-xs ml-1">{{ item.desc }}</span>
            </a-button>
            <div class="flex">
              <a-link v-auth="['/core/config/update']" >
                <template #icon>
                  <icon-edit />
                </template>
              </a-link>
            </div>
          </div>
        </a-list-item>
      </a-list>
    </div>

    <!-- 右侧配置内容区域 -->
    <div class="flex-1 p-4" v-if="activeItemId">
      <div v-if="loading" class="flex justify-center items-center h-64">
        <a-spin />
      </div>
      <div v-else class="config-set form-horizontal" v-cloak>
        <!-- 收费配置 -->
        <div v-if="activeItemCode === 'fees'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">高层房屋收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.house" placeholder="请输入高层房屋收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元1平方/月</label>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">多层房屋收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.house1" placeholder="请输入多层房屋收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元1平方/月</label>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">别墅房屋收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.house2" placeholder="请输入别墅收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元1平方/月</label>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">商铺收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.shop" placeholder="请输入商铺收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元1平方/月</label>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">车位收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.parking" placeholder="请输入车位收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元/月</label>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">储物间收费：</label>
            <div class="col-xs-12 col-sm-5">
              <a-input-number v-model="detailForm.garage" placeholder="请输入储物间收费标准" class="w-full"/>
            </div>
            <label class="control-label col-xs-12 col-sm-2" style="text-align: left;">元1平方/月</label>
          </div>
        </div>

        <!-- 分享配置 -->
        <div v-if="activeItemCode === 'share'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">分享标题：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.title" placeholder="请输入分享标题" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">默认分享图片：</label>
            <div class="col-xs-12 col-sm-7">
              <div class="flex items-center gap-2">
<!--                <a-input class="flex-1" v-model="detailForm.image"/>-->
<!--                <a-button type="primary" @click="attSelect('image')">-->

<!--                </a-button>-->
                <sa-upload-image v-model="detailForm.image"  />
                500px * 400px
              </div>
              <div class="pview mt-3" v-if="detailForm.image">
                <img :src="detailForm.image" class="max-w-[200px]"/>
              </div>
            </div>
          </div>
        </div>

        <!-- 小区配置 -->
        <div v-if="activeItemCode === 'xypm'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">小区名称：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.name" placeholder="请输入小区名称" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">物业名称：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.propertyname" placeholder="请输入物业名称" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">H5域名：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.domain" placeholder="请输入域名" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3"></label>
            <div class="col-xs-12 col-sm-7 text-gray-500 text-sm">
              1.请完整输入您的H5入口链接，如 https://h5.xypm.com/<br/>
              2.开启SSL并使用hash部署方式以#/结尾，如 https://h5.xypm.com/#/
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">Logo：</label>
            <div class="col-xs-12 col-sm-7">
              <div class="flex items-center gap-2">
<!--                <a-input class="flex-1" v-model="detailForm.logo"/>-->
                <sa-upload-image v-model="detailForm.logo"  />
<!--                <a-button type="primary" @click="attSelect('logo')">-->
<!--                  <template #icon><icon-upload /></template>选择图片-->
<!--                </a-button>-->
              </div>
              <div class="pview mt-3" v-if="detailForm.logo">
                <img :src="detailForm.logo" class="max-w-[200px]"/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">用户默认头像：</label>
            <div class="col-xs-12 col-sm-7">
              <div class="flex items-center gap-2">

                <sa-upload-image v-model="detailForm.useravatar"  />
              </div>

            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">用户协议：</label>
            <div class="col-xs-12 col-sm-7">
              <div class="flex items-center gap-2">
<!--                <a-input class="flex-1" v-model="detailForm.agreement"/>-->
<!--                <a-button type="primary" @click="showArticleSelect('agreement')">-->
<!--                  <template #icon><icon-search /></template>选择文章-->
<!--                </a-button>-->
                <a-select
                    v-model="detailForm.agreement"
                    placeholder="请选择文章"
                    allow-search
                    allow-clear
                    :field-names="{ key: 'id', title: 'title' }"


                >
                  <a-option v-for="item in articleOptions" :key="item.id" :value="item.id">
                    {{ item.title }}
                  </a-option>
                </a-select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">隐私政策：</label>
            <div class="col-xs-12 col-sm-7">
              <div class="flex items-center gap-2">
<!--                <a-input class="flex-1" v-model="detailForm.privacy"/>-->
<!--                <a-button type="primary" @click="showArticleSelect('privacy')">-->
<!--                  <template #icon><icon-search /></template>选择文章-->
<!--                </a-button>-->

                    <a-select
                        v-model="detailForm.privacy"
                        placeholder="请选择文章"
                        allow-search
                        allow-clear



                    >
                      <a-option v-for="item in articleOptions" :key="item.id" :value="item.id">
                        {{ item.title }}
                      </a-option>
                    </a-select>

              </div>
            </div>
          </div>
        </div>

        <!-- 微信小程序配置 -->
        <div v-if="activeItemCode === 'wxminiprogram'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">AppID：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.app_id" placeholder="请输入小程序 AppID" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">AppSecret：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.secret" placeholder="请输入小程序 AppSecret" class="w-full"/>
            </div>
          </div>
        </div>

        <!-- 微信公众号配置 -->
        <div v-if="activeItemCode === 'wxOfficialAccount'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">AppID：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.app_id" placeholder="请输入公众号  AppID" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">AppSecret：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.secret" placeholder="请输入公众号  AppSecret" class="w-full"/>
            </div>
          </div>
        </div>

        <!-- 微信支付配置 -->
        <div v-if="activeItemCode === 'wechat'">
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">商户号：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.mch_id" placeholder="请输入商户号" class="w-full"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3">支付密钥：</label>
            <div class="col-xs-12 col-sm-7">
              <a-input v-model="detailForm.key" placeholder="请输入支付密钥" class="w-full"/>
            </div>
          </div>
        </div>



        <ArticleSelectDialog
            v-model:visible="showSelectDialog"
            :current-field="currentField"
            @confirm="handleArticleSelect"
        />

        <!-- 底部操作按钮 -->
        <div class="form-group fixed bottom-0 left-0 right-0 bg-gray-100 py-4 px-8 flex justify-center gap-4">
          <a-button type="primary" @click="submitFrom('yes')">确定</a-button>
          <a-button @click="submitFrom">取消</a-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted,computed } from 'vue'
import { Message } from '@arco-design/web-vue'

import ArticleSelectDialog from '../article/select.vue'

import articleApi from "@/views/xypm/api/article.js";
import api from "@/views/xypm/api/config.js";

// 配置分组数据
const configGroupData = ref([
  {
    id: 1,
    name: '基础配置',
    code: 'basic',
    items: [
      { id: 101, name: '通用配置', code: 'xypm', desc: '系统通用配置' },
      { id: 102, name: '收费配置', code: 'fees', desc: '物业费收取标准配置' },
      { id: 103, name: '分享配置', code: 'share', desc: '配置分享标题、图片' },
    ]
  },
  {
    id: 2,
    name: '平台配置',
    code: 'platform',
    items: [
      { id: 201, name: '微信小程序', code: 'wxminiprogram', desc: '配置微信小程序' },
      { id: 202, name: '微信公众号', code: 'wxOfficialAccount', desc: '配置微信公众号' }
    ]
  },
  {
    id: 3,
    name: '支付配置',
    code: 'pay',
    items: [
      { id: 301, name: '微信配置', code: 'wechat', desc: '配置微信支付' }
    ]
  }
])

// 状态变量
const activeGroupId = ref(null)
const activeGroupName = ref('')
const activeGroupItems = ref([])
const activeItemId = ref(null)
const activeItemName = ref('')
const activeItemCode = ref('')
const loading = ref(false)
const detailForm = ref({})
const showSelectDialog = ref(false)
const currentField = ref('agreement')
const articleOptions = ref([])
const initPage = async () => {
  const articleResp = await articleApi.getArticleOption()
  articleOptions.value = articleResp.data

}



// 计算属性显示文章标题
const agreementTitle = computed(() => {
  return detailForm.value.agreement_id ? `${detailForm.value.agreement} (ID: ${detailForm.value.agreement_id})` : ''
})

const privacyTitle = computed(() => {
  return detailForm.value.privacy_id ? `${detailForm.value.privacy} (ID: ${detailForm.value.privacy_id})` : ''
})

// 显示文章选择对话框
const showArticleSelect = (field) => {
  currentField.value = field
  showSelectDialog.value = true
}

// 处理文章选择
const handleArticleSelect = (selected) => {
  if (selected.field === 'agreement') {
    detailForm.value.agreement = selected.title
    detailForm.value.agreement_id = selected.id
  } else {
    detailForm.value.privacy = selected.title
    detailForm.value.privacy_id = selected.id
  }
  showSelectDialog.value = false
}

// 显示分组下的配置项
const showGroupItems = (group) => {
  activeGroupId.value = group.code
  activeGroupName.value = group.name
  activeGroupItems.value = group.items || []
  activeItemId.value = null
  activeItemCode.value = ''
  detailForm.value = {}
}

// 获取配置数据
const getConfigData = async (item) => {
  activeItemId.value = item.code
  activeItemName.value = item.name
  activeItemCode.value = item.code
  loading.value = true

  try {
    // 这里模拟从API获取数据
    const response = await api.readConfig(activeItemCode.value)
    detailForm.value = response.data

    // // 临时模拟数据
    // detailForm.value = getDefaultFormData(item.code)
  } catch (error) {
    Message.error('获取配置数据失败')
    console.error(error)
  } finally {
    loading.value = false
  }
}

// 获取默认表单数据
const getDefaultFormData = (code) => {
  const forms = {
    payment: { house: '', house1: '', house2: '', shop: '', parking: '', garage: '' },
    share: { title: '智慧小区物业管理', image: '' },
    xypm: { name: '', propertyname: '', domain: '', logo: '', useravatar: '', agreement: '', privacy: '' },
    wxminiprogram: { app_id: '', secret: '' },
    wxOfficialAccount: { app_id: '', secret: '' },
    wechat: { mch_id: '', key: '' },
    appstyle: {
      mainColor: '#1890ff',
      navBarBgColor: '#1890ff',
      navBarFrontColor: '#ffffff',
      pageBgColor: '#f5f5f5',
      textMainColor: '#333333',
      textLightColor: '#666666',
      textPriceColor: '#ff4d4f',
      pageModuleBgColor: '#ffffff'
    }
  }
  return forms[code] || {}
}


// 提交表单
const submitFrom = async (confirm) => {

  if (confirm === 'yes') {

    console.log('保存配置:', detailForm.value)

    const submitData = {
      name: activeItemId.value,
      group: activeGroupId.value,
      value: JSON.stringify(detailForm.value),
      type: 'array'
    };

    const result = await api.editConfig(submitData);


    if (result.code === 200) {
      Message.success('配置保存成功')
      emit('success')

    }

  } else {

    Message.info('取消保存')
  }
}

// 页面加载完成执行
onMounted(() => {
  initPage()
  // 默认加载第一个配置组
  if (configGroupData.value.length > 0) {
    showGroupItems(configGroupData.value[0])
  }
})
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
  min-height: 70vh;
}

.text-gray-500 {
  color: #666;
}

.config-set {
  padding-bottom: 100px;
}

.form-group {
  @apply flex items-center mb-4;
}

.control-label {
  @apply text-right pr-4;
  width: 150px;
}

.col-xs-12 {
  @apply w-full;
}

.col-sm-3 {
  width: 25%;
}

.col-sm-5 {
  width: 41.66666667%;
}

.col-sm-7 {
  width: 58.33333333%;
}

.col-sm-2 {
  width: 16.66666667%;
}

.pview {
  max-width: 200px;
  background: #f2f2f2;
  margin-top: 15px;
}

.pview img {
  width: 100%;
}

[v-cloak] {
  display: none;
}
</style>
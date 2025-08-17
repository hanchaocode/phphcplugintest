<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="1000"
      :title="title"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-tabs default-active-key="1">
        <!-- 基础信息标签页 -->
        <a-tab-pane key="1" title="基础信息">
          <a-row :gutter="16">
            <a-col :span="12">
              <a-form-item label="上级分类" field="category_ids">
                <a-tree-select
                    v-model="formData.category_ids"
                    :data="categoryData"
                    multiple
                    :field-names="{ key: 'id', title: 'name', children: 'children', icon: 'customIcon' }"
                    allow-clear
                    placeholder="请选择上级分类"
                >
                </a-tree-select>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="商品标题" field="title" required>
                <a-input v-model="formData.title" placeholder="请输入商品标题" />
              </a-form-item>
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :span="12">
              <a-form-item label="商品标签" field="tags">
                <a-input-tag
                    v-model="formData.tags"
                    placeholder="请输入标签后回车"
                    allow-clear
                />
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="商品编号" field="sn">
                <a-input v-model="formData.sn" placeholder="请输入商品编号" />
              </a-form-item>
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :span="12">
              <a-form-item label="商品价格" field="price" required>
                <a-input-number
                    v-model="formData.price"
                    placeholder="请输入商品价格"
                    :min="0"
                    :precision="2"
                    style="width: 100%"
                >
                  <template #prefix>¥</template>
                </a-input-number>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="划线价格" field="line_price">
                <a-input-number
                    v-model="formData.line_price"
                    placeholder="请输入划线价格"
                    :min="0"
                    :precision="2"
                    style="width: 100%"
                >
                  <template #prefix>¥</template>
                </a-input-number>
              </a-form-item>
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :span="12">
              <a-form-item label="商品主图" field="image" required>
                <sa-upload-image v-model="formData.image" :limit="1" />
                <div class="text-xs text-gray-500 mt-1">建议尺寸：750x750px</div>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="商品状态" field="status" required>
                <a-radio-group v-model="formData.status">
                  <a-radio value="up">上架</a-radio>
                  <a-radio value="down">下架</a-radio>
                </a-radio-group>
              </a-form-item>
            </a-col>
          </a-row>

          <a-row :gutter="16">
            <a-col :span="24">
              <a-form-item label="轮播图" field="images">
                <sa-upload-image v-model="formData.images" :limit="10" multiple />
                <div class="text-xs text-gray-500 mt-1">建议尺寸：750x750px，最多上传10张</div>
              </a-form-item>
            </a-col>
          </a-row>
        </a-tab-pane>

        <!-- 规格库存标签页 -->
        <a-tab-pane key="2" title="规格库存">

            <a-row :gutter="16">
              <a-col :span="24">
                <a-form-item label="商品规格" field="is_sku">
                  <a-radio-group v-model="formData.is_sku" type="button" @change="handleSkuTypeChange">
                    <a-radio :value="0">单规格</a-radio>
                    <a-radio :value="1">多规格</a-radio>
                  </a-radio-group>
                </a-form-item>
              </a-col>
            </a-row>

            <!-- 单规格模式 -->
            <div v-if="formData.is_sku === 0">
              <a-row :gutter="16">
                <a-col :span="12">
                  <a-form-item label="库存" field="stock" required>
                    <a-input-number
                        v-model="formData.stock"
                        placeholder="请输入库存数量"
                        :min="0"
                        style="width: 100%"
                    />
                  </a-form-item>
                </a-col>

              </a-row>

            </div>

            <!-- 多规格模式 -->
            <div v-else>
              <a-alert type="info" class="mb-4">
                请设置多规格信息
              </a-alert>

              <!-- SKU管理 -->
              <div class="mb-6">
                <div class="flex items-center mb-4">
                  <a-input
                      v-model="skuModal"
                      placeholder="输入规格名称"
                      style="width: 300px; margin-right: 10px"
                  />
                  <a-button type="primary" @click="addSku">添加规格</a-button>
                </div>

                <!-- SKU列表 -->
                <div v-for="(sku, index) in skuList" :key="sku.temp_id" class="mb-6 p-4 border rounded">
                  <div class="flex justify-between items-center mb-3">
                    <span class="font-bold">{{ sku.name }}</span>
                    <a-button status="danger" @click="deleteSku(index)">删除规格</a-button>
                  </div>
                  <div class="flex items-center mb-3">
                    <a-input
                        v-model="skuValueModal[index]"
                        placeholder="输入规格值"
                        style="width: 300px; margin-right: 10px"
                    />
                    <a-button type="primary" @click="addSkuValue(index)">添加规格值</a-button>
                  </div>
                  <div class="flex flex-wrap gap-2">
                    <a-tag
                        v-for="(value, valueIndex) in sku.values"
                        :key="value.temp_id"
                        closable
                        @close="deleteSkuValue(index, valueIndex)"
                    >
                      {{ value.name }}
                    </a-tag>
                  </div>
                </div>
              </div>

              <!-- SKU组合表格 -->
              <div v-if="skuPrice.length && skuList.length" class="table-box">
                <a-table
                    :data="skuPrice"
                    :pagination="false"
                    :bordered="true"
                    row-key="temp_id"
                >
                  <template #columns>
                    <a-table-column
                        v-for="(sku, index) in skuList"
                        :key="index"
                        :title="sku.name"
                        :data-index="'spec_' + index"
                    >
                      <template #cell="{ record }">
                        <span>{{ record.goods_sku_text[index] }}</span>
                      </template>
                    </a-table-column>

                    <a-table-column title="图片" width="120">
                      <template #cell="{ record, rowIndex }">
                        <div class="table-upload-img">
                          <div class="sku-img" v-if="record.image">
                            <img :src="record.image" class="label-auto">
                            <i class="fa fa-close" @click="delImg(rowIndex)"></i>
                          </div>

<!--                          <sa-upload-image v-model="record.image" :limit="1"   />-->
<!--                          <a-button type="primary" @click="resVisible = true" returnType="url"><icon-experiment /> 上传</a-button>-->

                          <a-button type="primary" @click="openResourceSelector(rowIndex)" returnType="url">
                            <icon-experiment /> 上传
                          </a-button>









                        </div>
                      </template>
                    </a-table-column>

                    <a-table-column title="价格(元)" width="150">
                      <template #cell="{ record }">
                        <a-input
                            v-model="record.price"
                            :min="0"
                            :precision="2"
                            placeholder="价格"
                        >
                          <template #prefix>¥</template>
                        </a-input>
                      </template>
                    </a-table-column>

                    <a-table-column title="库存(个)" width="120">
                      <template #cell="{ record }">
                        <a-input-number
                            v-model="record.stock"
                            :min="0"
                            placeholder="库存"
                        />
                      </template>
                    </a-table-column>

                    <a-table-column title="编码" width="150">
                      <template #cell="{ record }">
                        <a-input v-model="record.sn" placeholder="商品编码" />
                      </template>
                    </a-table-column>

                    <a-table-column title="重量(KG)" width="120">
                      <template #cell="{ record }">
                        <a-input-number
                            v-model="record.weight"
                            :min="0"
                            :precision="2"
                            placeholder="重量"
                        />
                      </template>
                    </a-table-column>

                    <a-table-column title="状态" width="100">
                      <template #cell="{ record }">
                        <a-switch
                            v-model="record.status"
                            :checked-value="'up'"
                            :unchecked-value="'down'"
                            @change="editStatus(record)"
                        >
                          <template #checked>上架</template>
                          <template #unchecked>下架</template>
                        </a-switch>
                      </template>
                    </a-table-column>
                  </template>
                </a-table>
              </div>

              <a-alert v-else type="warning" class="mb-4">
                请先添加规格属性
              </a-alert>
            </div>
          </a-tab-pane>

        <!-- 商品详情标签页 -->
        <a-tab-pane key="3" title="商品详情">
          <a-row :gutter="16">
            <a-col :span="24">
              <a-form-item label="商品详情" field="content" required>
                <ma-wang-editor v-model="formData.content" height="500" />
              </a-form-item>
            </a-col>
          </a-row>
        </a-tab-pane>
      </a-tabs>
    </a-form>

    <a-modal style="z-index: 1000" v-model:visible="resVisible" :render-to-body="false" :width="1080" :footer="false" draggable @before-ok="confirmRes">
      <template #title>资源选择器</template>
      <sa-resource ref="imgRef" v-if="currentSkuIndex !== null" v-model="selectSkuImg" :multiple="false" :only-data="true" />
    </a-modal>

    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { Message } from '@arco-design/web-vue';
import api from '../../api/goods/goods';
import categoryApi from '../../api/goods/category';
import commonApi from '@/api/common';
import tool from '@/utils/tool';
import { useDictStore } from '@/store';

const emit = defineEmits(['success']);
const visible = ref(false);
const loading = ref(false);
const formRef = ref();
const selectSkuImg = ref('');
const mode = ref('');

const resVisible = ref(false);
const currentSkuIndex = ref(null); // Track current SKU index
const title = computed(() => {
  return '商品信息' + (mode.value === 'add' ? '-新增' : '-编辑');
});

watch(
    () => selectSkuImg.value,
    (vl) => {

      const selectedImage = selectSkuImg.value;

      if (selectedImage) {
        // Regular expression to match common image file extensions
        const imageExtensions = /\.(jpeg|jpg|gif|png|svg|webp|bmp|tiff)$/i;

        if (imageExtensions.test(selectedImage)) {
          if (currentSkuIndex.value !== null) {
            skuPrice.value[currentSkuIndex.value].image = selectedImage;
            currentSkuIndex.value = null; // Reset the index
          }
          resVisible.value = false;
        } else {
          Message.warning('请选择图片'); // Warn if the file is not a valid image
        }
      }
    },
    { deep: true }
);

// 表单信息
const formData = reactive({
  id: null,
  category_ids: [],
  title: '',
  tags: [],
  sn: '',
  price: 0,
  line_price: 0,
  image: '',
  images: [],
  status: 'up',
  is_sku: 0,
  stock: 0,
  sku_data: { listData: [], priceData: [] },
  content: ''
});
const categoryData = ref([]);

// SKU相关数据
const skuList = ref([]);
const skuPrice = ref([]);
const skuModal = ref('');
const skuValueModal = ref([]);
const imgRef = ref();
// 验证规则
const rules = {
  category_ids: [{ required: true, message: '请选择分类' }],
  title: [{ required: true, message: '商品标题不能为空' }],
  price: [{ required: true, message: '商品价格不能为空' }],
  image: [{ required: true, message: '请上传商品主图' }],
  content: [{ required: true, message: '商品详情不能为空' }]
};

const initPage = async () => {
  const resp = await categoryApi.getCategoryList();
  categoryData.value = resp.data;
};

const open = async (type = 'add', id = '') => {
  mode.value = type;
  formRef.value?.clearValidate();
  visible.value = true;
  await initPage();
  if (type === 'add') {
    Object.assign(formData, {
      id: null,
      category_ids: [],
      title: '',
      tags: [],
      sn: '',
      price: 0,
      line_price: 0,
      image: '',
      images: [],
      status: 'up',
      is_sku: 0,
      stock: 0,
      sku_data: { listData: [], priceData: [] },
      content: ''
    });
    skuList.value = [];
    skuPrice.value = [];
    skuValueModal.value = [];
  } else {
    const { data } = await api.read(id);
    data.price = parseFloat(data.price);
    data.line_price = parseFloat(data.line_price);
    data.is_sku = parseInt(data.is_sku);
    setFormData(data);
  }
};

const setFormData = async (data) => {
  for (const key in formData) {
    if (data[key] !== null && data[key] !== undefined) {
      if (key === 'category_ids' && typeof data[key] === 'string') {
        formData[key] = data[key].split(',').map(Number);
      } else if (key === 'tags' && typeof data[key] === 'string') {
        formData[key] = data[key].split(',');
      } else if (key === 'images' && typeof data[key] === 'string') {
        formData[key] = data[key].split(',');
      } else if (key === 'sku_data' && typeof data[key] === 'string') {
        try {
          formData[key] = JSON.parse(data[key]);
        } catch (e) {
          formData[key] = { listData: [], priceData: [] };
        }
      } else {
        formData[key] = data[key];
      }
    }
  }

  if (formData.is_sku === 1 && formData.sku_data) {
    const listData = typeof formData.sku_data.listData === 'string'
        ? JSON.parse(formData.sku_data.listData)
        : formData.sku_data.listData || [];

    const priceData = typeof formData.sku_data.priceData === 'string'
        ? JSON.parse(formData.sku_data.priceData)
        : formData.sku_data.priceData || [];

    skuList.value = listData.map((item, index) => ({
      ...item,
      temp_id: index + 1,
      values: item.values || []
    }));
    skuPrice.value = priceData;
  }
};

const handleSkuTypeChange = (value) => {
  if (value === 0) {
    skuList.value = [];
    skuPrice.value = [];
  }
};

const addSku = () => {
  if (!skuModal.value) {
    Message.warning('请输入规格名称');
    return;
  }

  skuList.value.push({
    temp_id: skuList.value.length + 1,
    name: skuModal.value,
    values: []
  });

  skuValueModal.value.push('');
  skuModal.value = '';
};

const addSkuValue = (index) => {
  if (!skuValueModal.value[index]) {
    Message.warning('请输入规格值');
    return;
  }

  const isExist = skuList.value[index].values.some(
      item => item.name === skuValueModal.value[index]
  );

  if (isExist) {
    Message.error('规格值已存在');
    return;
  }

  skuList.value[index].values.push({
    temp_id: skuList.value[index].values.length + 1,
    name: skuValueModal.value[index]
  });

  skuValueModal.value[index] = '';
  buildSkuPriceTable();
};

const deleteSku = (index) => {
  skuList.value.splice(index, 1);
  skuValueModal.value.splice(index, 1);
  buildSkuPriceTable();
};

const deleteSkuValue = (skuIndex, valueIndex) => {
  skuList.value[skuIndex].values.splice(valueIndex, 1);
  buildSkuPriceTable();
};

const buildSkuPriceTable = () => {
  const combinations = generateCombinations(skuList.value.map(sku => sku.values));
  skuPrice.value = combinations.map((combo, index) => ({
    temp_id: index + 1,
    goods_sku_text: combo.map(value => value.name),
    image: '',
    price: 0,
    stock: 0,
    sn: '',
    weight: 0,
    status: 'up'
  }));
};

const generateCombinations = (arrays) => {
  if (arrays.length === 0) return [[]];
  const [first, ...rest] = arrays;
  const combinations = generateCombinations(rest);
  return first.flatMap(value => combinations.map(combo => [value, ...combo]));
};

const delImg = (index) => {
  skuPrice.value[index].image = '';
};

const editStatus = (index) => {
  const item = skuPrice.value[index];
  item.status = item.status === 'up' ? 'down' : 'up';
};

const openResourceSelector = (index) => {
  currentSkuIndex.value = index;
  resVisible.value = true;
};

const confirmRes = async (done) => {
  const selectedImage = imgRef.value;
  if (currentSkuIndex.value !== null && selectedImage) {
    skuPrice.value[currentSkuIndex.value].image = selectedImage[0]; // Assuming single selection
    resVisible.value = false; // Close the resource selector modal
    currentSkuIndex.value = null; // Reset the index
  }
  done(true);
};


const submit = async (done) => {
  const validate = await formRef.value?.validate();
  if (!validate) {
    loading.value = true;
    const data = { ...formData };

    if (Array.isArray(data.category_ids)) {
      data.category_ids = data.category_ids.join(',');
    }

    if (Array.isArray(data.tags)) {
      data.tags = data.tags.join(',');
    }

    if (Array.isArray(data.images)) {
      data.images = data.images.join(',');
    }

    if (data.is_sku === 1) {
      data.sku_data = JSON.stringify({
        listData: JSON.stringify(skuList.value),
        priceData: JSON.stringify(skuPrice.value)
      });
    } else {
      data.sku_data = JSON.stringify({
        listData: [],
        priceData: []
      });
    }

    try {
      let result = {};

      if (mode.value === 'add') {
        data.id = undefined;
        result = await api.addGoods(data);
      } else {
        result = await api.editGoods(data.id, data);
      }

      if (result.code === 200) {
        Message.success('操作成功');
        emit('success');
        visible.value = false; // Close the modal
        done(true);
      }
    } finally {
      loading.value = false;
    }
  }
  done(false);
};

const close = () => (visible.value = false);

defineExpose({ open, setFormData });

</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 100px;
}

.sku-table :deep(.arco-table-th) {
  background-color: #f7f8fa;
}
</style>

<template>
  <div class="ma-content-block">
    <a-form layout="vertical" :model="refNavStyle">
    <a-row :gutter="16" id="bottomNav" v-cloak>
      <a-col :span="14">
        <a-card title="导航内容设置" :bordered="false">
          <div class="template-edit-title r">
            <ul>
              <li class="flex rt">
                <div>图标</div>
                <div>标题</div>
                <div>链接</div>
                <div>显示</div>
              </li>
              <li v-for="(item, key) in tabbarList.list" :key="key" class="flex">
                <div class="flex item">
                  <div class="uimg">
                    <input type="file" :ref="'iconPath'+key" :id="'iconPath'+key" accept="image/*"
                           @change="uploadImage($event,key,1)" style="display: none;">
                    <div>
                      <label :for="'iconPath'+key">
                        <img :src="item.iconPath" style="width: 50px; height: 50px" />
                      </label>
                    </div>
                    <label>未选中</label>
                  </div>
                  <div class="uimg">
                    <input type="file" :ref="'selectedIconPath'+key" :id="'selectedIconPath'+key"
                           accept="image/*" @change="uploadImage($event,key,2)" style="display: none;">
                    <div>
                      <label :for="'selectedIconPath'+key">
                        <img :src="item.selectedIconPath" style="width: 50px; height: 50px" />
                      </label>
                    </div>
                    <label>已选中</label>
                  </div>
                </div>
                <div class="flex item">
                  <a-input v-model="item.title" @input="tnameInput($event, key)" />
                </div>
                <div class="flex item">
                  <a-input v-model="item.link" placeholder="点击右侧选择按钮选择链接" />
                  <a-button type="primary" @click="openLinkModal(key)" style="margin-left: 8px">
                    选择
                  </a-button>
                </div>
                <div class="flex item">
                  <a-radio-group v-model="item.show">
                    <a-radio value="1">是</a-radio>
                    <a-radio value="0">否</a-radio>
                  </a-radio-group>
                </div>
              </li>
            </ul>
          </div>
        </a-card>
      </a-col>

      <a-col :span="10">
        <a-card title="导航样式设置" :bordered="false" style="margin-bottom: 16px">

            <a-form-item label="背景颜色">
              <div class="flex">
                <a-input v-model="tabbarList.backgroundColor" />
                <input type="color" v-model="tabbarList.backgroundColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
              </div>
            </a-form-item>

            <a-form-item label="文字颜色">
              <div class="flex">
                <a-input v-model="tabbarList.textColor" />
                <input type="color" v-model="tabbarList.textColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
              </div>
            </a-form-item>

            <a-form-item label="文字选中颜色">
              <div class="flex">
                <a-input v-model="tabbarList.textHoverColor" />
                <input type="color" v-model="tabbarList.textHoverColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
              </div>
            </a-form-item>

        </a-card>

        <a-card title="预览" :bordered="false">
          <div class="preview-block">
            <ul :style="{'background-color': tabbarList.backgroundColor}">
              <li v-for="item in tabbarList.list" :key="item.title">
                <div>
                  <img :src="item.iconPath" style="width: 20px; height: 20px" />
                </div>
                <span :style="{'color': tabbarList.textColor}">{{item.title}}</span>
              </li>
            </ul>
          </div>

          <div class="custom-save">
            <a-button type="primary" @click="submitFrom" style="margin-top: 16px">
              保存
            </a-button>
          </div>
        </a-card>
      </a-col>
    </a-row>
    </a-form>
  </div>
  <!-- 链接选择 -->
  <link-select ref="linkRef" @row-selected="updateLink"  />
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import { Message } from '@arco-design/web-vue'
import api from '../api/tabbar';
import commonApi from '../api/common';
import LinkSelect from "@/views/xypm/link/select.vue";
import file2md5 from "file2md5";

export default defineComponent({
  components: { LinkSelect },
  setup() {
    const linkRef = ref(null);
    const columns = [
      { title: '图标', key: 'icon', width: '30%' },
      { title: '标题', key: 'title', width: '20%' },
      { title: '链接', key: 'link', width: '35%' },
      { title: '显示', key: 'show', width: '15%' }
    ];
    const refNavStyle = ref({});
    const selectedTab = ref(0);
    const tabbarList = ref({
      backgroundColor: "#FFFFFF",
      textColor: "#333333",
      textHoverColor: "#00dd83",
      list: [
        { title: "首页", link: "/pages/index", iconPath: "/app/xypm/imgs/tabbar/index.png", selectedIconPath: "/app/xypm/imgs/tabbar/index-a.png", show: 1 },
        { title: "商城", link: "/pages/shop", iconPath: "/app/xypm/imgs/tabbar/shop.png", selectedIconPath: "/app/xypm/imgs/tabbar/shop-a.png", show: 1 },
        { title: "我的", link: "/pages/user", iconPath: "/app/xypm/imgs/tabbar/user.png", selectedIconPath: "/app/xypm/imgs/tabbar/user-a.png", show: 1 }
      ]
    });
    const updateLink = (url) => {

      tabbarList.value.list[selectedTab.value].link = url;
    };
    const fetchTabbarConfig = async () => {
      try {
        const { data } = await api.read();
        tabbarList.value = data;
      } catch (error) {
        console.error("Failed to fetch tabbar configuration:", error);
      }
    };

    onMounted(() => {
      fetchTabbarConfig();
    });

    const submitFrom = async () => {

      try {
        const {resp} = await api.save({data: JSON.stringify(tabbarList.value)});
        console.log(resp)
        Message.success(`保存成功！`)
      } catch (error) {
        console.error("Failed to fetch tabbar configuration:", error);
      }


    };

    const uploadImage = async (event, key, type) => {
      const files = event.target.files[0];




      const hash = await file2md5(files)
      const dataForm = new FormData()
      dataForm.append('image', files, files.name)
      dataForm.append('isChunk', false)
      dataForm.append('hash', hash)

      const item = tabbarList.value.list[key];
      try {

        const resp = await commonApi.uploadImage(dataForm)
        const result = resp.data
          console.log(result)
          if (type == 1) {

            item.iconPath = result.url;
          } else {
            item.selectedIconPath = result.url;
          }
          tabbarList.value.list[key] = {...item};

      } catch (error) {
        console.error("Failed to fetch tabbar configuration:", error);
      }




    };

    const handleUploadChange = (info, index, type) => {
      if (info.file.status === 'removed') {
        return;
      }
      const file = info.file.originFileObj;
      const event = { target: { files: [file] } };
      uploadImage(event, index, type);
    };

    const openLinkModal = (index) => {
      console.log(index)
      selectedTab.value = index
      console.log("LinkRef before opening modal:", linkRef.value);
      if (linkRef.value) {
        linkRef.value.open(index); // Call the open method
      } else {
        console.error("linkRef is undefined");
      }
    };




    const tnameInput = (event, key) => {
      const item = tabbarList.value.list[key];
      tabbarList.value.list[key] = {...item};
    };

    return {
      columns,
      refNavStyle,
      linkRef,
      tabbarList,
      submitFrom,
      uploadImage,
      handleUploadChange,
      openLinkModal,
      tnameInput,
      updateLink
    };
  }
});
</script>


<style scoped>
[v-cloak] {
  display: none;
}

.flex {
  display: flex;
}

ul, li {
  list-style: none;
  padding: 0;
  margin: 0;
}

.r .rt {
  font-weight: bold;
}
.r .rt div {
  width: 25%;
  text-align: center;
  padding: 10px 15px;
}
.r .rt div:nth-child(2) {
  width: 25%;
}
.r .rt div:nth-child(4) {
  width: 15%;
}
.r .rt div:nth-child(3) {
  width: 35%;
}
.r .item {
  width: 25%;
  text-align: center;
  justify-content: center;
  align-items: center;
  padding: 10px 15px;
}
.r .item:nth-child(2) {
  width: 25%;
}
.r .item:nth-child(4) {
  width: 15%;
}
.r .item:nth-child(3) {
  width: 35%;
}

.template-edit-title {
  border-top: 1px #e5e5e5 solid;
  border-bottom: 1px #e5e5e5 solid;
  padding: 15px 20px 20px 10px;
}

.uimg {
  text-align: center;
  margin: 0 10px;
}

.preview-block ul {
  overflow: hidden;
  display: flex;
  position: relative;
  width: 100%;
  border-top: 1px solid #e5e5e5;
  margin: 0;
  padding: 0;
}

.preview-block ul li {
  text-align: center;
  flex: 1;
  margin: 5px 0;
  list-style: none;
}

.preview-block ul li > div {
  height: 30px;
  line-height: 30px;
  width: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.preview-block ul li span {
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  display: block;
  font-size: 12px;
}

.custom-save {
  margin-top: 20px;
  padding: 0;
  text-align: center;
}
</style>

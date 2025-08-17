<template>
  <a-modal
      v-model:visible="visible"
      :width="'100%'"
      :title="title"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
      fullscreen
  >
    <div class="xypm-page">
      <div class="left">
        <div class="module">
          <div class="item" v-for="(item,index) in module" :key="index">
            <div class="item-name"><i></i> {{getParameter(index)}}</div>
            <div class="item-row">
              <div v-for="item in item" @click="showTemplate(item)">
                <i class="fa" :class="'fa-'+item.icon"></i>
                <span>{{item.name}}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="center">
        <div class="xypm-tool">

          <a-button type="primary" @click="historyPage()" size="mini">
            <template #icon>
              <icon-history />
            </template>
            历史记录
          </a-button>
          <a-button type="primary" @click="publish()"  size="mini">
          <template #icon>
            <icon-save />
          </template>
          保存
          </a-button>


        </div>
        <div class="preview">
          <div ref="parant" class="page"
               :style="{'background-color': pageData.page.style.pageBackgroundColor,'background-image': 'url('+pageData.page.style.pageBackgroundImage+')'}">
            <div class="navigation"
                 :style="{'background-color': pageData.page.style.navigationBarBackgroundColor, color:pageData.page.style.navigationBarTextStyle}"
                 @click="onType('page',0)">
              <div class="status-bar">
                <div class="time"> {{nowTime}} </div>
                <div class="device">
                  <i class="fa fa-signal"></i>
                  <i class="fa fa-wifi"></i>
                  <Icon icon="wifi"></Icon>
                  <i class="fa fa-battery"></i>
                </div>
              </div>
              <div class="page-head">
                <span class="title">
                  {{pageData.page.params.navigationBarTitleText}}
                </span>
              </div>
            </div>

            <!--            <vuedraggable class="draggable" v-model="pageData.item"-->
            <!--                          v-bind="{animation:500}">-->
            <div class="draggable">
              <div  v-bind="{animation:500}" class="xypm " v-for="(item,index) in pageData.item" :key="index" @click="onType(item['type'],index)"
                    draggable="true"
                    @dragstart="onDragStart($event, index)"
                    @dragover.prevent="onDragOver($event, index)"
                    @dragenter.prevent
                    @drop="onDrop($event, index)"
              >

                <!-- 搜索组件 -->
                <div class="search" v-if="item.type == 'search'">
                  <div class="search-box" :style="{'background':appStyle.pageModuleBgColor,'color':appStyle.textLightColor}">
                    <div>请输入搜索内容</div>
                    <icon-search class="icon" :style="{'color':appStyle.mainColor}" />
                  </div>
                </div>

                <!-- 轮播组件 -->
                <div class="banner" v-if="item.type == 'banner'" :style="{'margin':item.params.tbmargin/2+'px '+item.params.lrmargin/2+'px','border-radius':item.params.borderRadius+'px','overflow':'hidden'}">
                  <img :src="cdnurl(item.data[0].image)" :style="{height: item.params.height}">
                  <div class="indicator">
                    <div class="item">
                      <span v-for="(indic,index) in item.data" v-if="index == 0" :style="{'background':item.params.indicatorActiveColor}"></span>
                      <span v-for="(indic,index) in item.data" v-if="index > 0"></span>
                    </div>
                  </div>
                </div>

                <!-- 菜单组件 -->
                <div class="menu" v-if="item.type == 'menu'" :style="{'background':item.params.bgColor,'border-radius':item.params.borderRadius+'px'}">

                  <div class="menu-title" v-if="item.params.title">
                    <div class="c" :style="{'color':appStyle.textMainColor}">{{item.params.title}}</div>
                    <div class="r" v-if="item.params.linktitle" :style="{'color':appStyle.textLightColor}">{{item.params.linktitle}}<i class="fa fa-angle-right"></i></div>
                  </div>
                  <div class="grid text-center" :class="'col-' + item.params.colnum" >
                    <div class="item" v-for="menu in item.data">
                      <div class="inner">
                        <img :src="cdnurl(menu.image)" />
                        <div :style="{color:item.params.textColor}">{{menu.name}}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 图片櫥窗 -->
                <div class="image" :class="'layout-' + item.params.imgLayout" v-if="item.type == 'image'">
                  <div class="item" v-for="image in item.data" >
                    <img :src="cdnurl(image.image)">
                  </div>
                </div>

                <!-- 公告组件 -->
                <div class="notice flex" v-if="item.type == 'notice'" :style="{'background':item.params.bgColor,'border-radius':item.params.borderRadius+'px'}">
                  <div class="l"><img :src="cdnurl(item.params.lefticon)" /></div>
                  <div class="c">最新公告标题</div>
                  <div class="r"><i class="fa fa-angle-right"></i></div>
                </div>

                <!-- 活动组件 -->
                <div class="activity" v-if="item.type == 'activity'" >
                  <div class="item" :style="{'background':item.params.bgColor,'border-radius':item.params.borderRadius+'px'}">
                    <div class="t">
                      <img :src="cdnurl('/app/xypm/imgs/home-b3.png')" />
                    </div>
                    <div class="action">
                      <div class="at">吃西瓜大赛，邀所有户主一起参与!</div>
                      <div class="time flex" :style="{'color':appStyle.textLightColor}"><img :src="cdnurl('/app/xypm/imgs/time.png')" />2023-08-26 14:30:00 - 2023-11-01 16:00:00</div>
                      <div class="address flex" :style="{'color':appStyle.textLightColor}"><img :src="cdnurl('/app/xypm/imgs/address.png')" />湖南省/长沙市/天心区 未来云9013</div>
                    </div>
                  </div>
                </div>

                <!-- 标题组件 -->
                <div class="title" v-if="item.type == 'title'">
                  <div class="l" :style="{'background':appStyle.mainColor}"></div>
                  <div class="c" :style="{'color':appStyle.textMainColor}">{{item.params.title}}</div>
                  <div class="r" v-if="item.params.linktitle" :style="{'color':appStyle.textLightColor}">{{item.params.linktitle}}<i class="fa fa-angle-right"></i></div>
                </div>

                <!-- 空白组件 -->
                <div class="empty" v-if="item.type == 'empty'" :style="{'height':item.params.height/2 + 'px'}">
                </div>

                <!-- 商品组件 -->
                <div  v-if="item.type == 'goods'" class="product col-2" >
                  <div class="item" v-for="goods in item.data" :style="{'background':appStyle.pageModuleBgColor}">
                    <div class="item_pic">
                      <img :src="cdnurl('/app/xypm/imgs/goods.png')">
                    </div>
                    <div class="item_list">
                      <div class="name" :style="{'color':appStyle.textMainColor}">商品标题显示两行，超出两行显示三个点</div>
                      <div class="sales" :style="{'color':appStyle.textLightColor,'font-size':'12px'}">已售999</div>
                      <div class="price">
                        <span :style="{'color':appStyle.textPriceColor,'font-size':'20px'}">¥200</span>
                        <span class="yj" :style="{'color':appStyle.textLightColor,'font-size':'12px'}">¥399</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- 用户卡片 -->
                <div class="user-card flex" v-if="item.type == 'user-card'" :style="{'color':appStyle.textMainColor}">
                  <div class="l"><img :src="cdnurl('/app/xypm/imgs/avatar.png')" /></div>
                  <div class="c">
                    <div class="nickname">行云网络</div>
                    <div class="vip" style="color:#fea621;">
                      普通会员
                    </div>
                  </div>
                </div>

                <!-- 钱包模块 -->
                <div class="user-money flex" v-if="item.type == 'user-money'" :style="{'color':appStyle.textMainColor,'background-color':item.params.bgColor,'border-radius':item.params.borderRadius+'px'}">
                  <div class="yue">
                    <p class="num">99.80</p>
                    <span>账户余额</span>
                  </div>
                  <div class="wallet" :style="{'border-left':'solid 1px '+pageData.page.style.pageBackgroundColor}">
                    <p><img :src="cdnurl(item.params.rechargeicon)" /></p>
                    <span>充值</span>
                  </div>
                  <div class="wallet" :style="{'border-left':'solid 1px '+pageData.page.style.pageBackgroundColor}">
                    <p><img :src="cdnurl(item.params.walleticon)" /></p>
                    <span>钱包</span>
                  </div>
                </div>

                <div class="sub-del" @click="delModule(index)">
<!--                  <i class="fa fa-times"></i>-->
                  <icon-close></icon-close>
                </div>
              </div>
              <!--            </vuedraggable>-->
            </div>
          </div>
        </div>
      </div>

      <!-- 编辑器 -->
      <div class="right">
        <div class="xypm-tabs" v-if="type === 'page'">
          <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">页面配置</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="home">
              <div class="form-group">
                <label>页面名称:</label>
                <a-input type="text"  v-model="pageData.name" />
              </div>
              <div class="form-group">
                <label>页面封面:</label>
                <div class="xypm-upload">
                  <a-input  v-model="pageData.cover" />

                    <a-button type="primary"  @click="pageSelectImage('cover','page')">
                      <template #icon>
                        <icon-search />
                      </template>
                      选择
                    </a-button>
                </div>
                <div v-if="pageData.cover" class="show-img">
                  <img  :src="cdnurl(pageData.cover)" />
                </div>
              </div>

              <div class="form-group">
                <label>导航栏标题:</label>
                <a-input type="text"  v-model="pageData.page.params.navigationBarTitleText" />
              </div>

              <div class="form-group">
                <label>导航栏背景颜色:</label>
                <div class="form-inline">
                  <a-input type="text"  v-model="pageData.page.style.navigationBarBackgroundColor" />
                  <a-input id="color"  type="color" v-model="pageData.page.style.navigationBarBackgroundColor" />
                </div>
              </div>
              <div class="form-group">
                <label>导航栏字体颜色:</label>
                <select 
                        v-model="pageData.page.style.navigationBarTextStyle">
                  <option value="#ffffff">白色</option>
                  <option value="#000000">黑色</option>
                </select>
              </div>

              <div class="form-group">
                <label>页面背景颜色:</label>
                <div class="form-inline">
                  <a-input type="text"
                         v-model="pageData.page.style.pageBackgroundColor" />
                  <a-input id="color"  type="color"
                         v-model="pageData.page.style.pageBackgroundColor" />

                </div>
              </div>
              <div class="form-group">
                <label>页面背景图:</label>
                <div class="xypm-upload">

                  <a-input  v-model="pageData.page.style.pageBackgroundImage" />
                  <a-button type="primary"  @click="pageSelectImage('pageBackgroundImage','page')">
                    <template #icon>
                      <icon-search />
                    </template>
                    选择
                  </a-button>
                </div>
                <div v-if="pageData.page.style.pageBackgroundImage" class="show-img">
                  <img  :src="cdnurl(pageData.page.style.pageBackgroundImage)" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- 组件 -->
<!--        v-if="item.type === type"-->
        <div class="xypm-module" v-for="(item,index) in pageData.item" :key="index">
          <div   v-if=" index == currentIndex">

          <ul class="nav nav-tabs" role="tablist">
            <li class="active" v-if="item.data !== undefined"><a href="#home" data-toggle="tab"
                                                                 aria-expanded="true">{{item.name}}数据</a></li>
            <li :class="item.data !== undefined?'':'active'" v-if="item.params !== undefined"><a href="#params" data-toggle="tab"
                                                                                                 aria-expanded="false">配置参数</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="home" v-if="item.data !== undefined">
              <div class="add-data" >
<!--                <a class="btn btn-sm btn-success btn-append"><i class="fa fa-plus"></i>添加</a>-->

                <a-button type="primary"  @click="addData(index,item.data[0])" size="mini">
                  <template #icon>
                    <icon-plus />
                  </template>
                  添加
                </a-button>
              </div>
              <div class="panel-group" id="accordion" role="tablist"
                   aria-multiselectable="true">
                <a-tabs class="tabs" >
                  <a-tab-pane  :title="item.name+''+(num+1)"  v-for="(lists,num) in item.data"  :key="num">

                    <a-button type="primary"  @click="delData(index,num)" size="mini">
                      <template #icon>
                        <icon-delete />
                      </template>
                      删除
                    </a-button>
                    <div class="panel-body">
                      <div v-for="(datas,type) in lists" :key="type">
                        <div class="form-group" v-if="type == 'image'">
                          <label>图片:</label>
                          <div class="xypm-upload">
                            <a-input  type="text"
                                      v-model="lists[type]"
                                      placeholder="点击右侧选择按钮选择图片" />



                            <a-button type="primary"  @click="dataSelectImage(index,num,type)" size="mini">
                              <template #icon>
                                <icon-upload />
                              </template>
                              选择
                            </a-button>
                          </div>
                          <ul class="row list-inline plupload-preview">
                            <li class="col-xs-3">
                              <a class="thumbnail"><img
                                  :src="cdnurl(lists[type])"
                                  class="img-responsive"></a>
                            </li>
                          </ul>
                        </div>
                        <!-- 链接地址 -->
                        <div class="form-group" v-else-if="type == 'link'">
                          <h5 class="page-header"></h5>
                          <label>链接地址:</label>
                          <div class="xypm-upload">
                            <a-input type="text"
                                     v-model="lists[type]"
                                     placeholder="点击右侧选择按钮选择链接" />


                            <a-button type="primary"  @click="selectLink(index,num,type,'false')" size="mini">
                              <template #icon>
                                <icon-search />
                              </template>
                              选择
                            </a-button>
                          </div>
                        </div>


                        <!-- 选择商品 -->
                        <div class="form-group" v-else-if="type == 'goods_id'">
                          <label>商品:</label>
                          <div class="xypm-upload">
                            <a-input type="text"  v-model="lists[type]" placeholder="点击右侧按钮选择商品" />

                            <a-button type="primary"  @click="selectGoods(index,num,type,'false')" size="mini">
                              <template #icon>
                                <icon-search />
                              </template>
                              选择
                            </a-button>
                          </div>
                        </div>
                        <!-- 普通表单 -->
                        <div class="form-group" v-else>
                          <label>{{getParameter(type)}}:</label>
                          <a-input type="text"  v-model="lists[type]" :placeholder="'请输入' + getParameter(type)" />
                        </div>
                      </div>
                    </div>
                  </a-tab-pane>

                </a-tabs>





              </div>
            </div>
            <div :class="'tab-pane fade'+(item.data !== undefined?'':' active in')" id="params">
              <div class="form-group" v-for="(params,type) in item.params" :key="type">
                <!-- 轮播是否自动切换 -->
                <div v-if="type == 'autoplay'">
                  <label>是否自动切换:</label>
                  <select  v-model="item.params[type]">
                    <option value="true">是</option>
                    <option value="false">否</option>
                  </select>
                </div>
                <div v-else-if="type == 'indicatorDots'">
                  <label>是否显示面板指示点:</label>
                  <select  v-model="item.params[type]">
                    <option value="true">是</option>
                    <option value="false">否</option>
                  </select>
                </div>
                <div class="form-inline" v-else-if="type == 'indicatorColor'">
                  <label>指示点颜色:</label>
                  <a-input type="text"  v-model="item.params[type]" />
                  <a-input id="color"    type="color" v-model="item.params[type]" />
                </div>
                <div class="form-inline" v-else-if="type == 'indicatorActiveColor'">
                  <label>当前选中的指示点颜色:</label>
                  <a-input type="text"  v-model="item.params[type]" /> <a-input id="color"
                                                                                              type="color" v-model="item.params[type]" />
                </div>
                <div v-else-if="type == 'colnum'">
                  <label>每行数量:</label>
                  <select  v-model="item.params[type]">
                    <option value="3">每行 3 列</option>
                    <option value="4">每行 4 列</option>
                    <option value="5">每行 5 列</option>
                  </select>
                </div>
                <div v-else-if="type == 'scroll'">
                  <label>滚动方式:</label>
                  <select  v-model="item.params[type]">
                    <option value="tb">上下滚动</option>
                    <option value="lr">左右滚动</option>
                  </select>
                </div>
                <div class="form-inline" v-else-if="type == 'bgColor'">
                  <label>背景颜色:</label>
                  <a-input type="text"  v-model="item.params[type]" />
                  <a-input id="color"      type="color" v-model="item.params[type]" />
                </div>
                <div class="form-inline" v-else-if="type == 'lefticon'">
                  <label>左侧图标:</label>
                  <div class="xypm-upload">
                    <a-input  type="text"
                           v-model="item.params[type]"
                           placeholder="点击右侧选择按钮选择图片" />

                    <a-button type="primary"  @click="paramsSelectImage(index,type)">
                      <template #icon>
                        <icon-upload />
                      </template>
                      选择
                    </a-button>
                  </div>
                </div>

                <div class="form-inline" v-else-if="type == 'rechargeicon'">
                  <label>充值图标:</label>
                  <div class="xypm-upload">
                    <a-input  type="text"
                           v-model="item.params[type]"
                           placeholder="点击右侧选择按钮选择图片" />

                    <a-button type="primary"  @click="paramsSelectImage(index,type)">
                      <template #icon>
                        <icon-upload />
                      </template>
                      选择
                    </a-button>
                  </div>
                </div>

                <div class="form-inline" v-else-if="type == 'walleticon'">
                  <label>钱包图标:</label>
                  <div class="xypm-upload">
                    <a-input  type="text"
                           v-model="item.params[type]"
                           placeholder="点击右侧选择按钮选择图片" />
                    <a-button type="primary"  @click="paramsSelectImage(index,type)">
                      <template #icon>
                        <icon-upload />
                      </template>
                      选择
                    </a-button>
                  </div>
                </div>

                <div class="form-inline" v-else-if="type == 'textColor'">
                  <label>文字颜色:</label>
                  <a-input type="text"  v-model="item.params[type]" /> <a-input id="color"
                                                                                              type="color" v-model="item.params[type]" />
                </div>
                <div v-else-if="type == 'imgLayout'">
                  <label>布局方式:</label>
                  <select  v-model="item.params[type]">
                    <option value="1">一张大图</option>
                    <option value="2">左一右一</option>
                    <option value="3">左一右二</option>
                    <option value="4">左二右一</option>
                  </select>
                </div>
                <div class="form-group" v-else-if="type == 'link'">
                  <label>链接地址:</label>
                  <div class="xypm-upload">
                    <a-input type="text"  v-model="item.params[type]" placeholder="点击右侧按钮选择链接" />
                     <a-button type="primary"  @click="selectLink(index,-1,type,'false')" size="mini">
                      <template #icon>
                        <icon-search />
                      </template>
                      选择
                    </a-button>
                  </div>
                </div>
                <div v-else>
                  <label>{{getParameter(type)}}:</label>
                  <a-input type="text"  v-model="item.params[type]" />
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>

    <template #footer>
      <a-space>
        <a-button @click="close">取消</a-button>
        <a-button type="primary" @click="submit">保存</a-button>
      </a-space>
    </template>
  </a-modal>
  <a-modal style="z-index: 9999" v-model:visible="resVisible" :render-to-body="false" :width="1080" :footer="false" draggable>
    <template #title>资源选择器</template>
    <sa-resource ref="imgRef" v-if="currentImgKey !== null" v-model="selectImg" :multiple="false" :only-data="true" />
  </a-modal>
  <!-- 链接选择 -->
  <link-select ref="linkRef" @row-selected="updateLink"  />
  <!-- 商品选择 -->
  <goods-select ref="goodsRef" @row-selected="updateGoods"  />

  <!-- 历史选择 -->
  <page-history ref="historyRef" @row-selected="updateHistory"  />
</template>

<script setup>
import {ref, reactive, computed, onMounted, provide, watch} from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/page'
import configApi from '../api/config'
import {Icon} from "@iconify/vue";
import LinkSelect from "@/views/xypm/link/select.vue";
import GoodsSelect from "@/views/xypm/goods/goods/select.vue";
import PageHistory from "./history.vue";

const emit = defineEmits(['success'])
const visible = ref(false)
const loading = ref(false)
const mode = ref('add')
const type = ref('page')
const nowTime = ref('10:00')
const baseUrl = ref('')
const  currentIndex = ref(0)
const selectImg = ref('');
const resVisible = ref(false);
const imgRef = ref();

const currentImgKey = ref(null);
const currentImgNum = ref(null);
const currentImgType = ref(null);
const selectedLinkKey = ref(null);
const selectedLinkType = ref(null);
const selectedLinkNum = ref(null);

const selectedGoodsKey = ref(null);
const selectedGoodsNum = ref(null);
const selectedGoodsType = ref(null);
const linkRef = ref(null);
const goodsRef = ref(null);
const historyRef = ref();

const appStyle = ref({
  pageModuleBgColor: '#ffffff',
  textLightColor: '#999999',
  mainColor: '#56a3ff',
  textMainColor: '#333333',
  textPriceColor: '#f56c6c'
})
// provide('appStyle', appStyle)


watch(
    () => selectImg.value,
    (vl) => {

      const selectedImage = selectImg.value;

      if (selectedImage) {
        // Regular expression to match common image file extensions
        const imageExtensions = /\.(jpeg|jpg|gif|png|svg|webp|bmp|tiff)$/i;

        if (imageExtensions.test(selectedImage)) {
          if (currentIndex.value !== null) {

            //  alert(currentImgType.value+currentImgKey.value)
            if(currentImgType.value == 'page'){
              if(currentImgKey.value == 'cover'){
                pageData.cover = selectedImage
              }else{
                pageData.page.style[currentImgKey.value] =selectedImage
              }
            } else{
               if(currentImgNum.value){
                 pageData.item[currentImgKey.value].data[currentImgNum.value][currentImgType.value] = selectedImage
               } else{
                 pageData.item[currentImgKey.value].params[currentImgType.value] =selectedImage
               }

            }

            currentImgKey.value = null; // Reset the index
            currentIndex.value = null; // Reset the index
            currentImgType.value = null; // Reset the index
          }
          selectImg.value = null;
          resVisible.value = false;
        } else {
          Message.warning('请选择图片'); // Warn if the file is not a valid image
        }
      }
    },
    { deep: true }
);
const module = reactive({
  basics: [
    {
      name: "轮播组件",
      type: "banner",
      icon: "random",
      params: {
        autoplay: true,
        interval: "3000",
        height: "370",
        indicatorDots: true,
        indicatorColor: "#333333",
        indicatorActiveColor: "#56a3ff",
        tbmargin: 0,
        lrmargin: 0,
        borderRadius: 0,
      },
      data: [
        {
          image: "/app/xypm/imgs/banner.png",
          link: ""
        }
      ]
    },
    {
      name: "菜单组件",
      type: "menu",
      icon: "list",
      params: {
        title: "",
        linktitle: "",
        link: "",
        colnum: "5",
        borderRadius: 10,
        bgColor: "#ffffff",
        textColor: "#333333"
      },
      data: [
        {
          name: "菜单选项",
          image: "/app/xypm/imgs/home-nav1.png",
          link: ""
        }
      ]
    },
    {
      name: "搜索组件",
      type: "search",
      icon: "search",
      params: {}
    },
    {
      name: "标题组件",
      type: "title",
      icon: "header",
      params: {
        title: "标题",
        linktitle: "更多",
        link: "",
      }
    },
    {
      name: "公告组件",
      type: "notice",
      icon: "bullhorn",
      params: {
        bgColor: "#edf3fd",
        lefticon: "/app/xypm/imgs/notice.jpg",
        nums: 3,
        borderRadius: 10,
        scroll: "tb"
      }
    },
    {
      name: "活动组件",
      type: "activity",
      icon: "arrows-alt",
      params: {
        bgColor: "#edf3fd",
        nums: 3,
        borderRadius: 10,
      }
    },
    {
      name: "图片橱窗",
      type: "image",
      icon: "photo",
      params: {
        imgLayout: 1,
      },
      data: [{
        image: "/app/xypm/imgs/ads.png",
        link: ""
      }]
    },
    {
      name: "空白行",
      type: "empty",
      icon: "window-maximize",
      params: {
        height: "30"
      },
    }
  ],
  shop: [
    {
      name: "商品组件",
      type: "goods",
      icon: "archive",
      data: [{
        goods_id: ''
      }]
    }
  ],
  usercenter: [
    {
      name: "用户卡片",
      type: "user-card",
      icon: "user",
      params: {}
    },
    {
      name: "钱包模块",
      type: "user-money",
      icon: "jpy",
      params: {
        bgColor: "#ffffff",
        borderRadius: 10,
        rechargeicon: "/app/xypm/imgs/recharge.png",
        walleticon: "/app/xypm/imgs/wallet.png"
      }
    }
  ]
})
const updateLink = (url) => {

    if (selectedLinkNum.value == -1) {
      pageData.item[selectedLinkKey.value].params[selectedLinkType.value] = url
    } else {
      pageData.item[selectedLinkKey.value].data[selectedLinkNum.value][selectedLinkType.value] = url
    }
};
const updateGoods = (id) => {
  pageData.item[selectedGoodsKey.value].data[selectedGoodsNum.value][selectedGoodsType.value] = id

};

const updateHistory = async (id) => {
  try {
    const resp = await api.recoverPage(id)
    if (resp.code === 200) {
      setFormData(resp.data)

      Message.success(resp.message)
      emit('success')

    }
  } catch (error) {
    console.error(error)
  }

};

const pageData = reactive({
  id: null,
  name: "",
  cover: "",
  page: {
    name: "",
    type: "index",
    page_token: "",
    params: {
      navigationBarTitleText: "页面标题"
    },
    style: {
      navigationBarBackgroundColor: "#ffffff",
      navigationBarTextStyle: "#000000",
      pageBackgroundColor: "#f5f5f5",
      pageBackgroundImage: ""
    }
  },
  item: []
})



const title = computed(() => {
  return '页面装修' + (mode.value === 'add' ? '-新增' : '-编辑')
})

function onDragStart(e, index) {
  e.dataTransfer.setData('text/plain', index);
  e.dataTransfer.effectAllowed = 'move';
}

function onDragOver(e, index) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  // 可以添加视觉反馈
}

function onDrop(e, targetIndex) {
  e.preventDefault();
  const sourceIndex = e.dataTransfer.getData('text/plain');
  if (sourceIndex !== targetIndex) {
    // 重新排序数组
    const item = pageData.item.splice(sourceIndex, 1)[0];
    pageData.item.splice(targetIndex, 0, item);
  }
}
const open = async (type = 'add', id = '') => {
  mode.value = type
  visible.value = true


  const { data } = await api.read(id)

  setFormData(data)
  let res = await configApi.cdnUrl()
  baseUrl.value = res.data.cdnurl

  const styleRes = await configApi.readConfig('appstyle')

  appStyle.value = styleRes.data


}

function resetFormData() {
  pageData.id = null
  pageData.name = ""
  pageData.cover = ""
  pageData.page = {
    name: "",
    type: "index",
    page_token: generateToken(),
    params: {
      navigationBarTitleText: "页面标题"
    },
    style: {
      navigationBarBackgroundColor: "#ffffff",
      navigationBarTextStyle: "#000000",
      pageBackgroundColor: "#f5f5f5",
      pageBackgroundImage: ""
    }
  }
  pageData.item = []
}

function setFormData(data) {
  pageData.id = data.id
  pageData.name = data.name
  pageData.cover = data.cover
  pageData.page = data.page
  pageData.item = data.item || []
  pageData.page_token = data.page_token || []
  pageData.type = data.type
  pageData.is_use = data.is_use
}

function generateToken() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    const r = Math.random() * 16 | 0
    const v = c === 'x' ? r : (r & 0x3 | 0x8)
    return v.toString(16)
  })
}

function onType(t,index) {
  // this.type = e

  currentIndex.value = index;
  type.value = t
  // console.log(this.t,this.currentIndex)
}

function addData(key, arr) {
  pageData.item[key].data.push(JSON.parse(JSON.stringify(arr)))
}

function delData(key, num) {
  if (pageData.item[key].data.length == 1) {
    Message.warning("最后一个不能删除")
  } else {
    pageData.item[key].data.splice(num, 1)
  }
}

function delModule(key) {
  pageData.item.splice(key, 1)
  type.value  = type.value - 1
}

function showTemplate(arr) {


  //   this.type = this.pageData.item.length;

  currentIndex.value = pageData.item.length;
  type.value = arr['type']

  pageData.item.push(JSON.parse(JSON.stringify(arr)));


}

async function pageSelectImage(key, type = '') {
  try {

    console.log(key,type)
    resVisible.value = true;
    currentImgKey.value = key;
    currentImgType.value = type;


  } catch (error) {
    console.error(error)
  }
}

async function dataSelectImage(key, num, type) {

  currentImgKey.value = key;
  currentImgNum.value = num;
  currentImgType.value = type;
  resVisible.value = true;
}


async function paramsSelectImage(index, type) {


  currentImgKey.value = index
  currentImgType.value = type;
  resVisible.value = true;
}

async function selectLink(key, num, type, multiple) {


  selectedLinkKey.value =key
  selectedLinkNum.value =num
  selectedLinkType.value =type

  if (linkRef.value) {
    linkRef.value.open(key); // Call the open method
  } else {
    console.error("linkRef is undefined");
  }
  console.log(key,num,type)

}

async function selectGoods(key, num, type, multiple) {

  selectedGoodsKey.value = key
  selectedGoodsNum.value =num
  selectedGoodsType.value =type

  if (goodsRef.value) {
    goodsRef.value.open(key);
  }

}

async function publish() {
  try {
    const resp = await api.editPage(pageData.id,pageData)
    if (resp.code === 200) {
      Message.success(resp.message)
      emit('success')
      visible.value = false
    }
  } catch (error) {
    console.error(error)
  }
}

async function historyPage() {
  // try {
  //   await parent.Fast.api.open(`xypm/page/history/token/${pageData.page_token}`, __('历史记录'), {
  //     area: ['900px', '600px'],
  //     callback: function(id) {
  //       recover(id)
  //     }
  //   })
  // } catch (error) {
  //   console.error(error)
  // }
  if (historyRef.value) {
    historyRef.value.open(pageData.page_token); // Call the open method
  }


}


async function submit(done) {
  loading.value = true
  try {
    let result = {}
    result = await api.editPage(pageData.id,pageData)

    if (result.code === 200) {
      Message.success(result.message)
      emit('success')
      done(true)
    }
  } catch (error) {
    console.error(error)
    done(false)
  } finally {
    loading.value = false
  }
}

function close() {
  visible.value = false
}

function timeFormate(timeStamp) {
  const hh = new Date(timeStamp).getHours() < 10 ? "0" + new Date(timeStamp).getHours() : new Date(timeStamp).getHours()
  const mm = new Date(timeStamp).getMinutes() < 10 ? "0" + new Date(timeStamp).getMinutes() : new Date(timeStamp).getMinutes()
  nowTime.value = hh + ":" + mm
}

function nowTimes() {
  timeFormate(new Date())
  setInterval(nowTimes, 50 * 1000)
}

function cdnurl(url) {
  // Check if the URL starts with "http" or "https"
  if (!/^http?:\/\//i.test(url)) {
    return baseUrl.value + url;
  }
  return url;
}


function getParameter(name) {
  const language = {
    'basics': '基础板块',
    'shop': '商城板块',
    'interval': '轮播速度（毫秒）',
    'height': '组件高度（px）',
    'colnum': '每行数量',
    'nums': '显示数量',
    'borderRadius': '圆角(px)',
    'name': '名称',
    'title': '标题',
    'intro': '简介',
    'tbmargin': '上下间距(px)',
    'lrmargin': '左右间距(px)',
    'tiptext': '提示文字',
    'linktitle': '链接文字',
    'usercenter': '会员中心',
    'scroll': '滚动方式'
  }
  return language.hasOwnProperty(name) ? language[name] : name
}

onMounted(() => {
  nowTimes()
})

defineExpose({ open })
</script>

<style scoped>
[v-cloak] {
  display: none;
}
.flex{display: flex;}

/* 公告组件 */
.notice{padding: 10px 15px;border-radius:10px;margin: 0 15px;line-height: 38px;}
.notice .l img{width: 38px;height: 38px;}
.notice .c{margin-left: 15px;border-left: solid 1px #333333;padding-left: 25px;height: 14px;line-height: 14px;margin-top: 12px;}
.notice .r{margin-left: auto;}


/* 活动组件 */
.activity{margin: 0 15px;}
.activity .item{margin-bottom: 15px;overflow: hidden;}
.activity .item .action{padding: 15px 10px;line-height: 16px;}
.activity .item .action .at{font-size: 16px;margin-bottom: 10px;}
.activity .item .time{margin-bottom: 5px;}
.activity .item .time img,.activity .item .address img{vertical-align: middle;width: 14px;height: 14px;margin-right: 3px;}

/* 钱包模块 */
.user-money{border-radius: 10px;margin: 0 15px;padding: 10px 15px;}

.user-money .wallet{margin-left: auto;text-align: center;}
.user-money .wallet img{width: 25px;}
.user-money p{margin-bottom: 5px;}
.user-money .yue{padding-left: 15px;}
.user-money .yue .num{font-weight: bold;font-size: 18px;}


/* 用户卡片 */
.user-card{margin: 0 15px;}
.user-card .l{width: 60px;margin-right: 10px;}
.user-card .l img{width: 60px;height: 60px;}
.user-card .c img{width: 15px;vertical-align: middle;margin-right: 4px;height: 15px;}
.user-card .c .nickname{font-weight: bold;font-size: 16px;line-height: 34px;}
.user-card .c .vip{line-height: 18px;}
.user-card .r{margin-left: auto;}
.user-card .r .role{width: 78px;height: 26px;border-radius: 13px;background: #fea621;line-height: 26px;text-align: center;color: #fff;}


/* 搜索组件 */
.search{margin: 15px;height: 40px;line-height: 40px;border-radius: 20px;display: flex;}
.search .city{margin-right: 15px;flex-shrink: 0;}
.search .search-box{width: 100%;position: relative;border-radius: 20px;padding: 0 15px;}
.search .icon{font-size: 16px;margin-left: auto;margin-top: 12px;position: absolute;right: 15px;top: 0px;}

/* 商品组件 */
.product{margin: 0 15px;display: flex;flex-wrap: wrap;}
.product .item{width: 48%;margin-bottom: 10px;border-radius: 10px;}
.product .item:nth-child(2n){margin-left: auto;}
.product .item_list{padding: 10px;}
.product .item .item_list .sales{margin: 3px 0 7px;}
.product .item .tags span{margin-right: 10px;}
.product .item .price span{margin-right: 10px;}
.product .item .price span.yj{text-decoration: line-through;}


/* 标题组件 */
.menu-title{padding: 15px 0 20px;}
.xypm .title,.menu-title{font-size: 16px;display: flex;font-weight: bold;height: 20px;line-height: 20px;margin:0 15px;}
.xypm .title .l{font-size: 16px;display: flex;width: 4px;border-radius: 4px;margin-right: 10px;}
.xypm .title .r,.menu-title .r{font-size: 12px; margin-left: auto;font-weight: normal;display: flex;}
.xypm .title .r i,.menu-title .r i{font-size: 20px;line-height: 20px;margin-left: 4px;}


.no-data{padding: 30px;text-align: center;border-radius: 10px;margin: 15px;}

.page-head {
  width: 100%;
  height: 44px;
  overflow: hidden;
  text-align: center;
}

.xy-select{display: flex;}

.page-head .title {
  font-size: 14px;
}

.xypm-page {
  display: flex;
  height: calc(100vh - 120px);
}

/** 左边模块 */
.xypm-page .left {
  width: 28%;
  border: #e6e6e6 solid 1px;
  overflow-y: auto;
}

.xypm-page .left .module {
  padding: 15px;
}

.xypm-page .left .module::-webkit-scrollbar {
  display: none;
}

.xypm-page .left .module .item {
  margin: 20px 0;
}

.xypm-page .left .module .item-name {
  line-height: 14px;
  margin: 10px 0;
  border-bottom: solid 1px #e6e6e6;
  padding-bottom: 10px;
  font-size: 14px;
  font-weight: bold;
}

.xypm-page .left .module .item-name i {
  height: 12px;
  width: 2px;
  background-color: #56a3ff;
  margin-right: 4px;
  display: inline-flex;
}


.xypm-page .left .module .item .item-row {
  display: flex;
  display: -webkit-flex;
  justify-content: flex-start;
  flex-direction: row;
  flex-wrap: wrap;
}

.xypm-page .left .module .item .item-row>div {
  width: 22%;
  margin: 1.5%;
  border: 1px solid #e6e6e6;
  text-align: center;
  padding: 10px 0;
  line-height: 1;
  border-radius: 5px;
}

.xypm-page .left .module .item .item-row>div:hover {
  border: 1px solid #56a3ff;
  color: #56a3ff;
  cursor: pointer;
}

.xypm-page .left .module .item .item-row>div:active {
  background-color: #56a3ff;
  color: #ffffff;
}

.xypm-page .left .module .item .item-row>div i {
  font-size: 18px;
}

.xypm-page .left .module .item .item-row>div span {
  display: block;
  font-size: 10px;
  margin-top: 5px;
}

/** 中间预览 */
.xypm-page .center {
  width: 44%;
  border-top: #e6e6e6 solid 1px;
  border-bottom: #e6e6e6 solid 1px;
  display: flex;
  flex-direction: column;
}

.xypm-page .center .preview {
  display: flex;
  justify-content: center;
  width: 100%;
  flex: 1;
  overflow: auto;
}

.xypm-page .center .xypm-tool {
  padding: 0px 20px;
  border-bottom: #e6e6e6 solid 1px;
  border-right: #e6e6e6 solid 1px;
  background-color: #f3f3f3;
  width: 100%;
  height: 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.xypm-page .center .preview .page {
  background-repeat: no-repeat;
  background-position: 0 70px;
  box-shadow: 0 0 20px #efeaea12;
  background-size: 100% auto;
  margin: 30px 15px;
  width: 465px;
  height: 880px;
  overflow-y: scroll;
  overflow-x: hidden;
}

.xypm-page .center .preview .page::-webkit-scrollbar {
  display: none;
}

.xypm-page .center .preview .page .draggable>div {
  position: relative;
}

.xypm-page .center .preview .page .draggable>div:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 1px dashed #f5f5f5;
  cursor: move;
}

.xypm-page .center .preview .page .draggable>div:hover:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 1px dashed #56a3ff;
  cursor: move;
}

.xypm-page .center .preview .page .draggable>div.action:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 1px solid #00a0e9;
  cursor: move;
  z-index: 8000;
}

.xypm-page .center .preview .page .draggable>div .sub-del {
  display: none;
  position: absolute !Important;
  cursor: pointer !important;
  z-index: 9000 !important;
  right: 2px !Important;
  top: 2px !Important;
  height: 20px !Important;
  width: 20px !Important;
  text-align: center !Important;
  line-height: 20px !Important;
  background-color: rgba(0, 0, 0, .3) !Important;
  border-radius: 50% !Important;
}

.xypm-page .center .preview .page .draggable>div:hover .sub-del {
  display: block !Important;
}

.xypm-page .center .preview .page .navigation {
  position: sticky;
  background-size: 100% auto;
  background-repeat: no-repeat;
  top: 0;
  z-index: 9999;
}

.xypm-page .center .preview .page .navigation .status-bar {
  height: 26px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px;
}


/** 右边编辑 */
.xypm-page .right {
  background-color: #ffffff;
  width: 28%;
  border: 1px solid #e6e6e6;
  overflow-y: auto;
}
.xypm-page .right .xypm-tabs .tab-content {
  background-color: #ffffff;
  padding: 20px;
  overflow-x: hidden;
}

.xypm-page .right .nav-tabs {
  padding: 10px 20px 0;
  margin-bottom: 20px;
}

.xypm-page .right .xypm-module .tab-content {
  background-color: #ffffff;
  padding: 20px;
  overflow-x: hidden;
}

.xypm-page .right .xypm-module .tab-content .panel {
  background: #ffffff;
}

.xypm-page .right .xypm-module .tab-content .panel .panel-heading {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.xypm-page .right .xypm-module .tab-content .panel .panel-heading .panel-title {
  width: 95%;
}


.xypm-layout>div {
  overflow: hidden;
}

.xypm-layout .xypm-radio {
  background: #fafafa;
  margin: 8px;
  border-radius: 8px;
  margin-bottom: 0;
  width: calc(100% - 8px);
  height: 100px;
  border: 1px solid #f5f5f5;
  background-size: 70%;
  background-repeat: no-repeat;
  background-position: center center;
}

.xypm-layout .active .xypm-radio {
  border: 1px solid #56a3ff;
}

.xypm-upload {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.xypm-upload .form-control {
  width: calc(100% - 70px);
}

.xypm-multi {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.xypm-multi input {
  border: 0;
  outline: none;
}

.xypm-multi select {
  border: 0;
  border-left: 1px solid #ccc;
  background-color: #f5f5f5;
  outline: none;
  padding: 0 10px;
  height: 29px;
  width: 70px;
  margin-right: -12px;
}

#color {
  width: 31px;
  height: 31px;
  background-color: #fff;
  cursor: default;
  border-width: 1px;
  border-style: solid;
  border-color: #e6e6e6;
  border-image: initial;
  padding: 0;
}

.xypm img{width: 100%;}

/* grid布局 */
.grid {
  display: flex;
  flex-wrap: wrap;
}

.grid.grid-square {
  overflow: hidden;
}

.grid.grid-square .cu-tag {
  position: absolute;
  right: 0;
  top: 0;
  border-bottom-left-radius: 3px;
  padding: 3px 6px;
  height: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.grid.grid-square>div {
  margin-right: 10px;
  margin-bottom: 10px;
  border-radius: 3px;
  position: relative;
  overflow: hidden;
}

.grid.grid-square>div.bg-img img {
  width: 100%;
  height: 100%;
  position: absolute;
}


.grid.col-3.grid-square>div {
  padding-bottom: calc((100% - 20px)/3);
  height: 0;
  width: calc((100% - 20px)/3);
}

.grid.col-4.grid-square>div {
  padding-bottom: calc((100% - 30px)/4);
  height: 0;
  width: calc((100% - 30px)/4);
}

.grid.col-5.grid-square>div {
  padding-bottom: calc((100% - 40px)/5);
  height: 0;
  width: calc((100% - 40px)/5);
}

.grid.col-3.grid-square>div:nth-child(3n),
.grid.col-4.grid-square>div:nth-child(4n),
.grid.col-5.grid-square>div:nth-child(5n) {
  margin-right: 0;
}

.grid.col-3>div {
  width: 33.33%;
}

.grid.col-4>div {
  width: 25%;
}

.grid.col-5>div {
  width: 20%;
}

/* 轮播组件 */
.xypm .banner {
  position: relative;
}

.xypm .banner .indicator {
  position: absolute;
  width: 100%;
  bottom: 10px;
}

.xypm .banner .indicator .item {
  display: flex;
  justify-content: center;
}

.xypm .banner .indicator .item span {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin: 8px 2px;
  background-color: #eee;
}

/* 菜单组件 */
.xypm .menu{
  margin: 0 15px;
  padding-bottom: 15px;
}
.xypm .menu .item .inner{width: 65%;margin: 15px auto 0;}
.xypm .menu .item .inner div{line-height: 25px;height: 25px;}

/* 图片组件 */
.xypm .image {
  margin: 0 15px;
  display: flex;
  overflow: hidden;
}

.xypm .image.layout-1 {
  display: block;
}

.xypm .image.layout-2 {
  flex-wrap: wrap;
}

.xypm .image.layout-2>div {
  width: 49%;
}

.xypm .image.layout-2>div:nth-child(2n){margin-left: auto;}

.xypm .image.layout-3 {
  display: block;
}
.xypm .image.layout-3>div {
  width: 49%;
  float: left;
}
.xypm .image.layout-3>div:nth-child(2){float: right;}
.xypm .image.layout-3>div:last-child{float: right;margin-top: 10px;}

.xypm .image.layout-4 {
  display: block;
}
.xypm .image.layout-4>div {
  width: 49%;
  float: left;
}
.xypm .image.layout-4>div:nth-child(2){float: right;}
.xypm .image.layout-4>div:last-child{margin-top: 10px;}
.show-img{width: 120;height: 120;overflow: hidden;margin-top: 10px;}
.show-img img{width: 120px;height: 120px;}
.add-data{margin-bottom: 20px;}



</style>
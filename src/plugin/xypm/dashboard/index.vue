<template>
  <a-card class="panel-intro">
    <template #title>
      <a-tabs default-active-key="1">
        <a-tab-pane key="1" tab="Dashboard" />
      </a-tabs>
    </template>

    <div class="panel-body">
      <a-row :gutter="16" class="nums">
        <!-- 楼宇 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon red"><a-icon type="bank" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalBuild" :precision="0">
                <template #suffix><span class="unit">栋</span></template>
              </a-statistic>
              <div class="label">楼宇</div>
            </div>
          </div>
        </a-col>

        <!-- 房屋 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon orange"><a-icon type="home" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalHouse" :precision="0">
                <template #suffix><span class="unit">间</span></template>
              </a-statistic>
              <div class="label">房屋</div>
            </div>
          </div>
        </a-col>

        <!-- 商铺 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon green"><a-icon type="border" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalShop" :precision="0">
                <template #suffix><span class="unit">间</span></template>
              </a-statistic>
              <div class="label">商铺</div>
            </div>
          </div>
        </a-col>

        <!-- 车位 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon pink"><a-icon type="car" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalParking" :precision="0">
                <template #suffix><span class="unit">个</span></template>
              </a-statistic>
              <div class="label">车位</div>
            </div>
          </div>
        </a-col>

        <!-- 储物间 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon yellow"><a-icon type="inbox" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalGarage" :precision="0">
                <template #suffix><span class="unit">间</span></template>
              </a-statistic>
              <div class="label">储物间</div>
            </div>
          </div>
        </a-col>

        <!-- 业主 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon red"><a-icon type="user" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalm1" :precision="0" />
              <div class="label">业主</div>
            </div>
          </div>
        </a-col>

        <!-- 家庭成员 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon bule"><a-icon type="team" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalm2" :precision="0" />
              <div class="label">家庭成员</div>
            </div>
          </div>
        </a-col>

        <!-- 租户 -->
        <a-col :xs="12" :sm="6" :md="3">
          <div class="sm-st">
            <span class="sm-st-icon orange"><a-icon type="user" /></span>
            <div class="sm-st-info">
              <a-statistic :value="totalm3" :precision="0" />
              <div class="label">租户</div>
            </div>
          </div>
        </a-col>
      </a-row>

      <a-row :gutter="16" class="sales-row">
        <!-- 交费订单 -->
        <a-col :xs="24" :sm="12" :md="8">
          <div class="sales-box">
            <h4>
              交费订单
              <router-link to="/app/xypm/bill/order/index?ref=addtabs">
                查看订单 <a-icon type="right" />
              </router-link>
            </h4>
            <div class="top text-center">
              <div class="title">累计</div>
              <div>
                <a-statistic
                    :value="orderTotalMoney"
                    :precision="2"
                    prefix="¥"
                    class="total-money"
                />
                <p>(订单：{{ orderTotalNum }})</p>
              </div>
            </div>
            <div class="flex btm">
              <div class="item">
                <div class="title">
                  今日<strong>¥{{ orderTodayMoney }}</strong>
                </div>
                <p>(订单：{{ orderTodayNum }})</p>
              </div>
              <div class="item r">
                <div class="title">
                  本月<strong>¥{{ orderMonthMoney }}</strong>
                </div>
                <p>(订单：{{ orderMonthNum }})</p>
              </div>
            </div>
          </div>
        </a-col>

        <!-- 充值订单 -->
        <a-col :xs="24" :sm="12" :md="8">
          <div class="sales-box">
            <h4>
              充值订单
              <router-link to="/app/xypm/recharge/order/index?ref=addtabs">
                查看订单 <a-icon type="right" />
              </router-link>
            </h4>
            <div class="top text-center">
              <div class="title">累计</div>
              <div>
                <a-statistic
                    :value="rechargeTotalMoney"
                    :precision="2"
                    prefix="¥"
                    class="total-money"
                />
                <p>(订单：{{ rechargeTotalNum }})</p>
              </div>
            </div>
            <div class="flex btm">
              <div class="item">
                <div class="title">
                  今日<strong>¥{{ rechargeTodayMoney }}</strong>
                </div>
                <p>(订单：{{ rechargeTodayNum }})</p>
              </div>
              <div class="item r">
                <div class="title">
                  本月<strong>¥{{ rechargeMonthMoney }}</strong>
                </div>
                <p>(订单：{{ rechargeMonthNum }})</p>
              </div>
            </div>
          </div>
        </a-col>

        <!-- 商品订单 -->
        <a-col :xs="24" :sm="12" :md="8">
          <div class="sales-box">
            <h4>
              商品订单
              <router-link to="/app/xypm/goods/order/index?ref=addtabs">
                查看订单 <a-icon type="right" />
              </router-link>
            </h4>
            <div class="top text-center">
              <div class="title">累计</div>
              <div>
                <a-statistic
                    :value="goodsTotalMoney"
                    :precision="2"
                    prefix="¥"
                    class="total-money"
                />
                <p>(订单：{{ goodsTotalNum }})</p>
              </div>
            </div>
            <div class="flex btm">
              <div class="item">
                <div class="title">
                  今日<strong>¥{{ goodsTodayMoney }}</strong>
                </div>
                <p>(订单：{{ goodsTodayNum }})</p>
              </div>
              <div class="item r">
                <div class="title">
                  本月<strong>¥{{ goodsMonthMoney }}</strong>
                </div>
                <p>(订单：{{ goodsMonthNum }})</p>
              </div>
            </div>
          </div>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <!-- 订单趋势图表 -->
        <a-col :xs="24" :lg="12">
          <div class="sales-box">
            <h4>订单趋势 (近七日)</h4>
            <sa-chart
                :options="orderChartOptions"
                height="300px"
            />
          </div>
        </a-col>

        <!-- 金额图表 -->
        <a-col :xs="24" :lg="12">
          <div class="sales-box">
            <h4>金额 (元)</h4>
            <sa-chart
                :options="amountChartOptions"
                height="300px"
            />
          </div>
        </a-col>
      </a-row>
    </div>
  </a-card>
</template>

<script>
import {
  Card,
  Row,
  Col,
  Tabs,
  Icon,
  Statistic
} from '@arco-design/web-vue';
import api from '../api/dashboard'

export default {
  components: {
    'a-card': Card,
    'a-row': Row,
    'a-col': Col,
    'a-tabs': Tabs,
    'a-tab-pane': Tabs.TabPane,
    'a-icon': Icon,
    'a-statistic': Statistic,
  },
  data() {
    return {
      // 统计数据
      totalBuild: 0,
      totalHouse: 0,
      totalShop: 0,
      totalParking: 0,
      totalGarage: 0,
      totalm1: 0,
      totalm2: 0,
      totalm3: 0,

      // 订单数据
      orderTotalMoney: 0,
      orderTotalNum: 0,
      orderTodayMoney: 0,
      orderTodayNum: 0,
      orderMonthMoney: 0,
      orderMonthNum: 0,

      rechargeTotalMoney: 0,
      rechargeTotalNum: 0,
      rechargeTodayMoney: 0,
      rechargeTodayNum: 0,
      rechargeMonthMoney: 0,
      rechargeMonthNum: 0,

      goodsTotalMoney: 0,
      goodsTotalNum: 0,
      goodsTodayMoney: 0,
      goodsTodayNum: 0,
      goodsMonthMoney: 0,
      goodsMonthNum: 0,

      // 图表配置
      orderChartOptions: {
        legend: {
          data: ['交费订单', '充值订单', '商品订单'],
          bottom: 0
        },
        tooltip: {
          trigger: 'axis',
          formatter: "{b}<br>{a0} : {c0} 个 <br>{a1} : {c1} 个 <br>{a2} : {c2} 个"
        },
        toolbox: {
          show: true,
          feature: {
            magicType: { show: true, type: ['line', 'bar'] },
            restore: { show: true },
            saveAsImage: { show: true }
          }
        },
        xAxis: {
          type: 'category',
          data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },
        yAxis: { type: 'value' },
        series: [
          {
            name: '交费订单',
            type: 'line',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#F05050'
            }
          },
          {
            name: '充值订单',
            type: 'line',
            smooth: true,
            symbol: 'none',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#fa8564'
            }
          },
          {
            name: '商品订单',
            type: 'line',
            smooth: true,
            symbol: 'none',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#86ba41'
            }
          }
        ]
      },
      amountChartOptions: {
        legend: {
          data: ['交费订单', '充值订单', '商品订单'],
          bottom: 0
        },
        tooltip: {
          trigger: 'axis',
          formatter: "{b}<br>{a0} : {c0} 元 <br>{a1} : {c1} 元 <br>{a2} : {c2} 元"
        },
        toolbox: {
          show: true,
          feature: {
            magicType: { show: true, type: ['line', 'bar'] },
            restore: { show: true },
            saveAsImage: { show: true }
          }
        },
        xAxis: {
          type: 'category',
          data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },
        yAxis: { type: 'value' },
        series: [
          {
            name: '交费订单',
            type: 'bar',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#F05050'
            }
          },
          {
            name: '充值订单',
            type: 'bar',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#fa8564'
            }
          },
          {
            name: '商品订单',
            type: 'bar',
            data: [],
            markPoint: {
              data: [
                { type: 'max', name: '最大值' },
                { type: 'min', name: '最小值' }
              ]
            },
            itemStyle: {
              color: '#86ba41'
            }
          }
        ]
      }
    };
  },
  async mounted() {
    try {
      const resp = await api.getDashboardData();
      if (resp.code === 200) {
        const data = resp.data;
        this.totalBuild = data.totalBuild;
        this.totalHouse = data.totalHouse;
        this.totalShop = data.totalShop;
        this.totalParking = data.totalParking;
        this.totalGarage = data.totalGarage;
        this.totalm1 = data.totalm1;
        this.totalm2 = data.totalm2;
        this.totalm3 = data.totalm3;

        this.orderTotalMoney = data.orderTotalMoney;
        this.orderTotalNum = data.orderTotalNum;
        this.orderTodayMoney = data.orderTodayMoney;
        this.orderTodayNum = data.orderTodayNum;
        this.orderMonthMoney = data.orderMonthMoney;
        this.orderMonthNum = data.orderMonthNum;

        this.rechargeTotalMoney = data.rechargeTotalMoney;
        this.rechargeTotalNum = data.rechargeTotalNum;
        this.rechargeTodayMoney = data.rechargeTodayMoney;
        this.rechargeTodayNum = data.rechargeTodayNum;
        this.rechargeMonthMoney = data.rechargeMonthMoney;
        this.rechargeMonthNum = data.rechargeMonthNum;

        this.goodsTotalMoney = data.goodsTotalMoney;
        this.goodsTotalNum = data.goodsTotalNum;
        this.goodsTodayMoney = data.goodsTodayMoney;
        this.goodsTodayNum = data.goodsTodayNum;
        this.goodsMonthMoney = data.goodsMonthMoney;
        this.goodsMonthNum = data.goodsMonthNum;

        this.orderChartOptions.series[0].data = data.orderDayTotalNum;
        this.orderChartOptions.series[1].data = data.rechargeDayTotalNum;
        this.orderChartOptions.series[2].data = data.goodsDayTotalNum;

        this.amountChartOptions.series[0].data = data.orderDayTotalMoney;
        this.amountChartOptions.series[1].data = data.rechargeDayTotalMoney;
        this.amountChartOptions.series[2].data = data.goodsDayTotalMoney;
      }
    } catch (error) {
      console.error("Failed to fetch dashboard data:", error);
    }
  }
};
</script>

<style scoped>
.flex {
  display: flex;
}
.nums {
  margin: 20px 0 30px;
}
.sales-box {
  padding: 15px 20px;
  margin-bottom: 20px;
  background: #f1f4f6;
  border-radius: 5px;
}
.sales-box h4 {
  font-size: 18px;
  line-height: 34px;
  color: #232529;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.sales-box h4 a {
  font-size: 14px;
  font-weight: normal;
}
.sales-box .total-money {
  font-size: 30px;
  font-weight: bold;
}
.sales-box .top {
  margin-top: 20px;
}
.sales-box p {
  font-size: 16px;
  color: #8a8d99;
  margin: 0;
}
.sales-box .title {
  font-size: 18px;
  color: #8a8d99;
}
.sales-box .title strong {
  margin-left: 3px;
  color: #232529;
  font-weight: bold;
}
.sales-box .btm {
  margin-top: 30px;
}
.sales-box .btm .item {
  width: 50%;
  text-align: center;
}
.sales-box .btm .r {
  border-left: 1px solid rgb(220, 223, 230);
}

.sm-st {
  clear: both;
  display: flex;
  align-items: center;
}
.sm-st-icon {
  width: 60px;
  height: 60px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  background: #eee;
  border-radius: 5px;
  margin-right: 10px;
  color: #fff;
}

.sm-st-info {
  flex: 1;
}
.sm-st-info .ant-statistic {
  line-height: 1;
}
.sm-st-info .ant-statistic-content {
  font-size: 24px;
  font-weight: 600;
}
.sm-st-info .unit {
  font-size: 14px;
  margin-left: 3px;
  font-weight: normal;
}
.sm-st-info .label {
  font-size: 14px;
  color: #666;
  margin-top: 4px;
}

.orange {
  background: #fa8564 !important;
}

.red {
  background: #F05050 !important;
}

.green {
  background: #86ba41 !important;
}

.pink {
  background: #AC75F0 !important;
}

.yellow {
  background: #fdd752 !important;
}

.bule {
  background: #23b7e5 !important;
}

.text-center {
  text-align: center;
}
</style>

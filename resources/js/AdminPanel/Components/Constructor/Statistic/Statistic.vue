<script setup>
import TrafficStatistic from "@/AdminPanel/Components/Constructor/Statistic/TrafficStatistic.vue";
</script>
<template>
    <div class="container">
        <div class="row my-3">
            <div class="col-md-4" v-if="need_date_range">
                <VueDatePicker v-model="date" locale="ru" range></VueDatePicker>

            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           v-model="need_date_range"
                           type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">
                        Использовать временной период
                    </label>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-6">
                <h6 class="my-3 fw-bold">Основная статистика</h6>
                <p class="fst-italic">
                    Сводка всех показателей эффективности работы системы
                </p>
                <table class="table" v-if="statistic">
                    <thead>
                    <tr class="bg-light">
                        <th scope="col" style="width:200px;">Ключ</th>
                        <th scope="col">Значение</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Всего пользователей в БД</th>
                        <td class="font-weight-bold">{{ statistic.users_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Всего VIP</th>
                        <td class="font-weight-bold">{{ statistic.vip_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Администраторы в БД</th>
                        <td class="font-weight-bold">{{ statistic.admin_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Администраторы за работой</th>
                        <td class="font-weight-bold">{{ statistic.work_admin_in_bd || 0 }}</td>
                    </tr>


                    <tr>
                        <th scope="row">Всего кэшбэка на счету у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.summary_cashback || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.summary_cashback_people_count">({{
                                    statistic.summary_cashback_people_count
                                }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка начислено пользователям, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_up || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_summary_up_people_count">({{
                                    statistic.cashback_summary_up_people_count
                                }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка списано у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_down || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_summary_down_people_count">({{
                                    statistic.cashback_summary_down_people_count
                                }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего начислено кэшбэка первого уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_up_level_1 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_up_level_1_people_count">({{
                                    statistic.cashback_up_level_1_people_count
                                }}
                                чел)</strong>
                        </td>

                    </tr>

                    <tr>
                        <th scope="row">Всего начислено кэшбэка второго уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_up_level_2 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_up_level_2_people_count">({{
                                    statistic.cashback_up_level_2_people_count
                                }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего начислено кэшбэка третьего уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_up_level_3 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_up_level_3_people_count">({{
                                    statistic.cashback_up_level_3_people_count
                                }}
                                чел)</strong>
                        </td>
                    </tr>

                    </tbody>
                </table>


                <a href="javascript:void(0)"
                   @click="downloadBotStatistic"
                   class="btn w-100 btn-outline-info p-3">
                    <i class="fa-regular fa-file-excel mr-2"></i> Скачать статистику
                </a>
            </div>
            <div class="col-6">
                <div class="form-check form-switch my-3">
                    <input class="form-check-input"
                           v-model="need_charts"
                           type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Отобразить графики</label>
                </div>

                <div class="row my-3 sticky-charts ">
                    <div class="col-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link"
                                   @click="tab=0"
                                   v-bind:class="{'active':tab===0}"
                                   aria-current="page" href="#">Пользователи</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   @click="tab=1"
                                   v-bind:class="{'active':tab===1}"
                                   aria-current="page" href="#">Бонусы</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   @click="tab=2"
                                   v-bind:class="{'active':tab===2}"
                                   aria-current="page" href="#">Продажи</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   @click="tab=3"
                                   v-bind:class="{'active':tab===3}"
                                   aria-current="page" href="#">Переходы</a>
                            </li>

                        </ul>
                    </div>

                    <div class="col-12" v-if="tab===0">
                        <div class="d-flex justify-content-center mb-3" v-if="need_charts">
                            <Chart
                                v-if="loadedChart&&(users||[]).length>0"
                                :size="{ width: 500, height: 320 }"
                                :data="users"
                                :margin="margin"
                                :direction="direction"
                                :axis="axis">

                                <template #layers>

                                    <Grid strokeDasharray="2,2"/>
                                    <Bar :dataKeys="['m','count']" :barStyle="{ fill: '#ffe775' }"/>
                                    <Marker :value="1000" label="Avg." color="#e76f51" strokeWidth="2"
                                            strokeDasharray="6 6"/>
                                </template>

                                <template #widgets>
                                    <Tooltip
                                        borderColor="#48CAE4"
                                        :config="tooltipConfig"
                                    />
                                </template>

                            </Chart>
                            <p class="text-danger my-2" v-else>Статистика еще не загружена или её нет</p>
                        </div>
                        <div class="w-100 overflow-x-scroll"
                             v-if="(users||[]).length>0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Год</th>
                                    <th scope="col">Месяц</th>
                                    <th scope="col">Число пользователей</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in users">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ item.y }}</td>
                                    <td>{{ item.m }}</td>
                                    <td>{{ item.count }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-12" v-if="tab===1">
                        <template v-if="need_charts">
                        <h6 class="my-2">Начисления</h6>
                        <div class="d-flex justify-content-center mb-3">

                            <Chart
                                v-if="loadedChart&&(cashback_up||[]).length>0"
                                :size="{ width: 500, height: 320 }"
                                :data="cashback_up"
                                :margin="margin"
                                :direction="direction"
                                :axis="axis">

                                <template #layers>

                                    <Grid strokeDasharray="2,2"/>
                                    <Bar :dataKeys="['m','sum']" :barStyle="{ fill: '#ffe775' }"/>
                                    <Marker :value="1000" label="Avg." color="#e76f51" strokeWidth="2"
                                            strokeDasharray="6 6"/>
                                </template>

                                <template #widgets>
                                    <Tooltip
                                        borderColor="#48CAE4"
                                        :config="tooltipConfig"
                                    />
                                </template>

                            </Chart>
                            <p class="text-danger my-3" v-else>Статистика еще не загружена или её нет</p>
                        </div>
                        <h6 class="my-2">Списания</h6>
                        <div class="d-flex justify-content-center mb-3">

                            <Chart
                                v-if="loadedChart&&(cashback_down||[]).length>0"
                                :size="{ width: 500, height: 320 }"
                                :data="cashback_down"
                                :margin="margin"
                                :direction="direction"
                                :axis="axis">

                                <template #layers>

                                    <Grid strokeDasharray="2,2"/>
                                    <Bar :dataKeys="['m','sum']" :barStyle="{ fill: '#ffe775' }"/>
                                    <Marker :value="1000" label="Avg." color="#e76f51" strokeWidth="2"
                                            strokeDasharray="6 6"/>
                                </template>

                                <template #widgets>
                                    <Tooltip
                                        borderColor="#48CAE4"
                                        :config="tooltipConfig"
                                    />
                                </template>

                            </Chart>
                            <p class="text-danger my-3" v-else>Статистика еще не загружена или её нет</p>
                        </div>
                        </template>

                        <div class="w-100 overflow-x-scroll"
                             v-if="(preparedCashback||[]).length>0">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Год</th>
                                    <th scope="col">Месяц</th>
                                    <th scope="col">Начислено</th>
                                    <th scope="col">Списано</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in preparedCashback">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ item.y }}</td>
                                    <td>{{ item.m }}</td>
                                    <td>{{ item.up }}</td>
                                    <td>{{ item.down }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12" v-if="tab===2">

                        <div
                            v-if="need_charts"
                            class="d-flex justify-content-center mb-3">
                            <Chart
                                v-if="loadedChart&&(orders||[]).length>0"
                                :size="{ width: 500, height: 320 }"
                                :data="orders"
                                :margin="margin"
                                :direction="direction"
                                :axis="axis">

                                <template #layers>

                                    <Grid strokeDasharray="2,2"/>
                                    <Bar :dataKeys="['m','sump']" :barStyle="{ fill: '#ffe775' }"/>
                                    <Marker :value="1000" label="Avg." color="#e76f51" strokeWidth="2"
                                            strokeDasharray="6 6"/>
                                </template>

                                <template #widgets>
                                    <Tooltip
                                        borderColor="#48CAE4"
                                        :config="tooltipConfig"
                                    />
                                </template>

                            </Chart>
                            <p class="text-danger my-3" v-else>Статистика еще не загружена или её нет</p>
                        </div>

                        <divs
                            v-if="need_charts"
                            class="d-flex">
                            <Responsive class="w-full">
                                <template #main="{ width }">
                                    <Chart
                                        direction="circular"
                                        :size="{ width, height: 400 }"
                                        :data="products"
                                        :margin="{
                                              left: Math.round((width - 360)/2),
                                              top: 50,
                                              right: 0,
                                              bottom: 0
                                            }"
                                        :axis="axis"
                                        :config="{ controlHover: false }"
                                    >
                                        <template #layers>
                                            <Pie
                                                :dataKeys="['title', 'count','price']"
                                                :pie-style="{ innerRadius: 100, padAngle: 0.05 }"/>
                                        </template>
                                        <template #widgets>
                                            <Tooltip
                                                :config="{
                                                  title: {  label: 'Название'},
                                                  price: {  label: 'Выручено средств'},
                                                  count: {  label: 'Кол-во' },
                                                   volume_count_ratio: { hide: true},
                                                   volume_price_ratio: { hide: true},
                                                }"
                                                hideLine
                                            />
                                        </template>
                                    </Chart>
                                </template>
                            </Responsive>
                        </divs>


                        <table class="table" v-if="(products||[]).length>0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    <a href="javascript:void(0)"
                                       @click="changeSort('title')">
                                        <template v-if="sort.key==='title'">
                                            <i v-if="sort.direction==='asc'"
                                               class="fa-solid fa-arrow-up-wide-short"></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-arrow-up-short-wide"></i>
                                        </template>
                                        Название
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="javascript:void(0)"
                                       @click="changeSort('price')">
                                        <template v-if="sort.key==='price'">
                                            <i v-if="sort.direction==='asc'"
                                               class="fa-solid fa-arrow-up-wide-short"></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-arrow-up-short-wide"></i>
                                        </template>
                                        Объем продаж
                                    </a>

                                </th>
                                <th scope="col">
                                    <a href="javascript:void(0)"
                                       @click="changeSort('count')">
                                        <template v-if="sort.key==='count'">
                                            <i v-if="sort.direction==='asc'"
                                               class="fa-solid fa-arrow-up-wide-short"></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-arrow-up-short-wide"></i>
                                        </template>
                                        Продано ед.
                                    </a>

                                </th>
                                <th scope="col">
                                    <a href="javascript:void(0)"
                                       @click="changeSort('volume_count_ratio')">
                                        <template v-if="sort.key==='volume_count_ratio'">
                                            <i v-if="sort.direction==='asc'"
                                               class="fa-solid fa-arrow-up-wide-short"></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-arrow-up-short-wide"></i>
                                        </template>
                                        % от объема
                                    </a>

                                </th>
                                <th scope="col">
                                    <a href="javascript:void(0)"
                                       @click="changeSort('volume_price_ratio')">
                                        <template v-if="sort.key==='volume_price_ratio'">
                                            <i v-if="sort.direction==='asc'"
                                               class="fa-solid fa-arrow-up-wide-short"></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-arrow-up-short-wide"></i>
                                        </template>
                                        % от числа
                                    </a>


                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in products">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>{{ item.title }}</td>
                                <td>{{ item.price }}</td>
                                <td>{{ item.count }}</td>
                                <td>{{ item.volume_count_ratio }}</td>
                                <td>{{ item.volume_price_ratio }}</td>
                            </tr>

                            </tbody>
                        </table>
                        <p class="text-danger my-3" v-else>Нет данных по продажам за выбранный период:(</p>
                    </div>
                    <div class="col-12" v-if="tab===3">
                        <TrafficStatistic
                            :date="date"
                            :bot="bot"></TrafficStatistic>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import {Chart, Grid, Line, Bar, Tooltip, Pie, Responsive} from 'vue3-charts'

export default {
    props: ["bot"],
    components: {Chart, Grid, Line, Bar, Tooltip, Responsive, Pie, VueDatePicker},
    data() {
        return {
            sort: {
                key: 'price',
                direction: 'asc'
            },
            need_charts: false,
            need_date_range: false,
            date: null,
            tab: 0,
            botUser: null,
            loadedChart: false,
            statistic: null,
            loading: false,
            tooltipConfig: {
                sump: {label: 'Сумма продаж', color: '#5d1010'},
                m: {label: 'Месяц', color: '#54a375'},
                y: {label: 'Год', color: '#0ea9cb'},

            },
            products: [
                {title: '1', count: 1, price: 2020,},
                {title: '2', count: 2, price: 2020,},
                {title: '3', count: 3, price: 2020,},
                {title: '4', count: 4, price: 2020,},
                {title: '5', count: 5, price: 2020,},
            ],
            orders: [
                {sump: 0, m: 1, y: 2020,},
                {sump: 111, m: 2, y: 2020,},
                {sump: 222, m: 3, y: 2020,},
                {sump: 333, m: 4, y: 2020,},
                {sump: 444, m: 5, y: 2020,},

            ],
            users: [
                {count: 0, m: 1, y: 2020,},
                {count: 111, m: 2, y: 2020,},
                {count: 222, m: 3, y: 2020,},
                {count: 333, m: 4, y: 2020,},
                {count: 444, m: 5, y: 2020,},

            ],

            cashback_up: [
                {sum: 0, m: 1, y: 2020,},
                {sum: 111, m: 2, y: 2020,},
                {sum: 222, m: 3, y: 2020,},
                {sum: 333, m: 4, y: 2020,},
                {sum: 444, m: 5, y: 2020,},


            ],

            cashback_down: [
                {sum: 0, m: 1, y: 2020,},
                {sum: 111, m: 2, y: 2020,},
                {sum: 222, m: 3, y: 2020,},
                {sum: 333, m: 4, y: 2020,},
                {sum: 444, m: 5, y: 2020,},


            ],
            direction: 'horizontal',
            margin: {
                left: 0,
                top: 20,
                right: 20,
                bottom: 0
            },
            axis: {
                primary: {
                    type: 'band'
                },
                secondary: {
                    domain: ['dataMin', 'dataMax + 100'],
                    type: 'linear',
                    ticks: 11
                }
            }
        }
    },
    computed: {
        preparedCashback() {

            if (this.cashback_up.length === 0 && this.cashback_down.length === 0)
                return []
            let tmp = []
            for (let i = 0; i < this.cashback_up.length; i++) {
                let tmpCashBackDown = null;
                for (let j = 0; j < this.cashback_down.length; j++) {
                    let down = this.cashback_down[j];
                    let up = this.cashback_up[i];
                    if (up.m === down.m && up.y === down.y) {
                        tmpCashBackDown = this.cashback_down[j]
                        break;
                    }

                }

                tmp.push({
                    y: this.cashback_up[i].y || 0,
                    m: this.cashback_up[i].m || 0,
                    up: this.cashback_up[i].sum || 0,
                    down: tmpCashBackDown === null ? 0 : tmpCashBackDown.sum,
                })
            }


            return tmp
        },
    },
    watch: {
        'sort': {
            handler: function (newValue) {
                this.prepareStatistic()
            },
            deep: true
        },
        'need_date_range': function () {
            this.prepareStatistic()
        },
        'date': function () {
            this.prepareStatistic()
        }
    },
    mounted() {

        const startDate = new Date();
        const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
        this.date = [startDate, endDate];

        this.prepareStatistic()
    },
    methods: {
        changeSort(param) {
            this.sort.key = param
            this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc'
        },
        prepareStatistic() {
            this.loadedChart = false
            return this.$store.dispatch("statisticLoad", {
                bot_id: this.bot.id,
                date: this.date,
                need_all: !this.need_date_range,
                sort: this.sort
            })
                .then((response) => {
                    this.statistic = response.statistic
                    this.orders = this.statistic.orders.sum
                    this.products = this.statistic.orders.products
                    this.users = this.statistic.users.sum
                    this.cashback_up = this.statistic.cashback_up.sum
                    this.cashback_down = this.statistic.cashback_down.sum
                    this.loadedChart = true
                })
        },
        downloadBotStatistic() {
            this.$notify({
                title: "Внимание!",
                text: "Начался формироваться документ статистики!",
            });

            this.$store.dispatch("downloadBotStatistic").then((resp) => {
                // saveAs(resp.data, 'result.xlsx');

                this.$notify({
                    title: "Отлично!",
                    text: "Документ успешно сформирован",
                    type: "success"
                });
            }).catch(() => {

                this.$notify({
                    title: "Упс!",
                    text: "Что-то пошло не так...",
                    type: "error"
                });

            })
        },

    }
}
</script>
<style>
.sticky-charts {
    position: sticky;
    top: 100px;
}
</style>

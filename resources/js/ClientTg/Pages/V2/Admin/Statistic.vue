<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="container" v-if="botUser">

        <div class="row">
            <div class="col-12">
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
                        <th scope="row">Пользователей за день</th>
                        <td class="font-weight-bold">{{ statistic.users_in_bd_today || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">VIP за день</th>
                        <td class="font-weight-bold">{{ statistic.vip_in_bd_today || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Выдано кэшбэка за день, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_day_up_people_count">({{ statistic.cashback_day_up_people_count }}
                                чел)</strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Списано кэшбэка за день, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_down || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_day_down_people_count">({{ statistic.cashback_day_down_people_count }}
                                чел)</strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Всего кэшбэка на счету у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.summary_cashback || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.summary_cashback_people_count">({{ statistic.summary_cashback_people_count }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка начислено пользователям, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_up || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_summary_up_people_count">({{ statistic.cashback_summary_up_people_count }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка списано у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_down || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_summary_down_people_count">({{ statistic.cashback_summary_down_people_count }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка первого уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_1 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_day_up_level_1_people_count">({{ statistic.cashback_day_up_level_1_people_count }}
                                чел)</strong>
                        </td>

                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка второго уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_2 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_day_up_level_2_people_count">({{ statistic.cashback_day_up_level_2_people_count }}
                                чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка третьего уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_3 || 0).toFixed(2) }}
                            <strong
                                v-if="statistic.cashback_day_up_level_3_people_count">({{ statistic.cashback_day_up_level_3_people_count }}
                                чел)</strong>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <div class="d-flex justify-content-center mb-3">
                    <Chart
                        v-if="loadedChart"
                        :size="{ width: 340, height: 320 }"
                        :data="chart"
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
                    <p class="text-danger" v-else>Статистика еще не загружена</p>
                </div>


                <Chart
                    :size="{ width: 340, height: 420 }"
                    :data="chart"
                    :margin="margin"
                    :direction="direction"
                    :axis="axis">

                    <template #layers>
                        <Grid strokeDasharray="2,2"/>
                        <Area :dataKeys="['m', 'sump']" type="monotone" :areaStyle="{ fill: 'url(#grad)' }"/>
                        <Line
                            :dataKeys="['m', 'sump']"
                            type="monotone"
                            :lineStyle="{
          stroke: '#9f7aea'
        }"
                        />

                        <defs>
                            <linearGradient id="grad" gradientTransform="rotate(90)">
                                <stop offset="0%" stop-color="#be90ff" stop-opacity="1"/>
                                <stop offset="100%" stop-color="white" stop-opacity="0.4"/>
                            </linearGradient>
                        </defs>
                    </template>

                    <template #widgets>
                        <Tooltip
                            borderColor="#48CAE4"
                            :config="{
          sump: { color: '#9f7aea' },
          y: { hide: true },
          y: { hide: true }
        }"
                        />
                    </template>

                </Chart>


                <a href="javascript:void(0)"
                   @click="downloadBotStatistic"
                   class="btn w-100 btn-outline-info p-3">
                    <i class="fa-regular fa-file-excel mr-2"></i> Скачать статистику
                </a>
            </div>
        </div>

    </div>

</template>
<script>
import {mapGetters} from "vuex";
import {saveAs} from 'file-saver';
import {Chart, Grid, Line, Bar, Tooltip} from 'vue3-charts'

export default {
    components: {Chart, Grid, Line, Bar, Tooltip},
    data() {
        return {
            botUser: null,
            loadedChart: false,
            statistic: null,
            loading: false,
            tooltipConfig: {
                sump: {label: 'Сумма продаж', color: '#5d1010'},
                m: {label: 'Месяц', color: '#54a375'},
                y: {label: 'Год', color: '#0ea9cb'},

            },
            chart: [
                {sump: 0, m: 1000, y: 500,},
                {sump: 111, m: 1000, y: 500,},
                {sump: 222, m: 1000, y: 500,},
                {sump: 333, m: 1000, y: 500,},
                {sump: 444, m: 1000, y: 500,},

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
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
            this.prepareStatistic()
        }
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
            this.prepareStatistic()
        }

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        prepareStatistic() {
            this.loadedChart = false
            return this.$store.dispatch("statisticLoad")
                .then((response) => {
                    this.statistic = response.statistic
                    this.chart = this.statistic.orders.sum
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

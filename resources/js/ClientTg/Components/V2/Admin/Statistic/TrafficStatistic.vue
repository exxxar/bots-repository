<template>

    <div class="form-check form-switch mb-2">
        <input class="form-check-input"
               v-model="sort.is_individual"
               type="checkbox" role="switch" id="need-individual">
        <label class="form-check-label" for="need-individual">Индивидуальные переходы</label>
    </div>

    <div class="form-check form-switch mb-2">
        <input class="form-check-input"
               v-model="need_chart"
               type="checkbox" role="switch"
               id="need-chart-display">
        <label class="form-check-label" for="need-chart-display">Отображать диаграмму</label>
    </div>

    <template  v-if="(traffics||[]).length>0">
        <table

            class="table">
            <thead>
            <tr>
                <th scope="col">
                    <a href="javascript:void(0)"
                       @click="changeSort('id')">
                        <template v-if="sort.key==='id'">
                            <i v-if="sort.direction==='asc'"
                               class="fa-solid fa-arrow-up-wide-short"></i>
                            <i
                                v-else
                                class="fa-solid fa-arrow-up-short-wide"></i>
                        </template>
                        #
                    </a>
                </th>
                <th scope="col">
                    <a href="javascript:void(0)"
                       @click="changeSort('source')">
                        <template v-if="sort.key==='source'">
                            <i v-if="sort.direction==='asc'"
                               class="fa-solid fa-arrow-up-wide-short"></i>
                            <i
                                v-else
                                class="fa-solid fa-arrow-up-short-wide"></i>
                        </template>
                        Источник
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
                        Число переходов
                    </a>
                </th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="source in traffics">
                <th scope="row">{{ source.id }}</th>
                <td>{{ source.source }}</td>
                <td>{{ source.count }}</td>


            </tr>

            </tbody>
        </table>

        <div
            v-if="need_chart"
            class="d-flex justify-content-center mb-3">
            <Chart
                direction="circular"
                :size="{ width:400, height: 400 }"
                :data="traffics"
                :margin="margin"
                :axis="axis"
                :config="{ controlHover: false }"
            >
                <template #layers>
                    <Pie
                        :dataKeys="['source', 'count']"
                        :pie-style="{ innerRadius: 10, padAngle: 0.05 }"/>
                </template>
                <template #widgets>
                    <Tooltip
                        :config="tooltipConfig"
                        hideLine
                    />
                </template>
            </Chart>
        </div>
    </template>

    <template v-else>
        <p class="text-danger my-3 text-center">Нет данных по переходам:(</p>
    </template>




</template>
<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {mapGetters} from "vuex";
import {saveAs} from 'file-saver';
import {Chart, Grid, Line, Bar, Tooltip, Pie, Responsive} from 'vue3-charts'

export default {
    components: {Chart, Grid, Line, Bar, Tooltip, Responsive, Pie},
    props: ["date"],
    data() {
        return {
            traffics: [],
            tooltipConfig: {
                source: {label: 'Источник', color: '#5d1010'},
                count: {label: 'Кол-во', color: '#5d1010'},
            },
            margin:{
                left: 0,
                top: 20,
                right: 0,
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
            },
            need_chart:false,
            sort: {
                is_individual: false,
                key: 'created_at',
                direction: 'asc'
            },
        }
    },
    watch: {
        'sort': {
            handler: function (newValue) {
                this.prepareStatistic()
            },
            deep: true
        }
    },
    mounted() {

        this.prepareStatistic()
    },
    methods: {
        changeSort(param) {
            this.sort.key = param
            this.sort.direction = this.sort.direction === 'asc' ? 'desc' : 'asc'
        },
        prepareStatistic() {
            this.loadedChart = false
            return this.$store.dispatch("trafficLoad", {
                date: this.date,
                need_all: !this.need_date_range,
                sort: this.sort
            })
                .then((response) => {
                    this.traffics = response.traffics
                })
        },
    }
}
</script>

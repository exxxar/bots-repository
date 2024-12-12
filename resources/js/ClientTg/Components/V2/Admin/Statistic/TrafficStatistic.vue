<template>

    <table
        v-if="(traffics||[]).length>0"
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
    <p class="text-danger my-3 text-center" v-else>Нет данных по переходам:(</p>
</template>
<script>
export default {
    props: [ "date"],
    data() {
        return {
            traffics: [],
            sort: {
                key: 'created_at',
                direction: 'asc'
            },
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

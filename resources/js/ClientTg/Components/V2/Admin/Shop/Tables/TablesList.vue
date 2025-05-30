<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>

    <template v-if="tables.length>0">
        <div class="row row-cols-1">
            <div class="col mb-2" v-for="(table,index) in tables">
                <div
                    v-bind:class="{'bg-primary text-white':table.officiant_id!=null&&table.officiant_id===self.id}"
                    class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <p class="mb-2" style="font-size:12px;">Столик #{{
                                        (parseInt(table.number || '0') + 1)
                                    }}</p>
                            </div>
                            <div class="col-8" v-if="table.officiant">
                                <p class="mb-2" style="font-size:12px;"><i class="fa-solid fa-bell-concierge"></i>
                                    {{ table.officiant?.name || table.officiant?.fio_from_telegram || 'Официант' }}</p>
                            </div>
                            <div class="col-12">
                                <p class="mb-2" style="font-size:12px;"><i class="fa-solid fa-people-group"></i>
                                    Клиентов за столиком <strong
                                        class="fw-bold">{{ table.clients?.length || 0 }}</strong></p>
                            </div>
                            <div class="col-12">
                                <p class="mb-0" style="font-size:12px;">Начало обслуживания {{
                                        timeAgo(table.start_at)
                                    }}</p>
                            </div>
                        </div>

                        <div class="btn-group w-100 mb-0">
                            <button type="button"
                                    v-if="table.officiant_id == null"
                                    @click="takeATable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary " style="font-size:12px;"><i
                                class="fa-solid fa-arrow-right-to-bracket"></i> В работу
                            </button>
                            <button type="button"
                                    v-if="self.id === table.officiant_id"
                                    @click="changeTableWaiter(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary " style="font-size:12px;"><i
                                class="fa-solid fa-arrow-right-to-bracket"></i> Выйти
                            </button>
                            <button type="button"
                                    @click="goToTable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary " style="font-size:12px;"><i
                                class="fa-solid fa-eye"></i> Просмотр
                            </button>
                            <button type="button"
                                    v-if="self.id === table.officiant_id"
                                    @click="closeTable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary " style="font-size:12px;"><i
                                class="fa-solid fa-xmark"></i> Закрыть
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <Pagination
            :simple="true"
            v-on:pagination_page="nextTables"
            v-if="paginate"
            :pagination="paginate"/>
    </template>
    <p
        v-else
        class="alert alert-light">
        В данный момент нет ваших столиков в обслуживании и клиентов без официанта.
    </p>
</template>
<script>
import {mapGetters} from "vuex";
import moment from 'moment'
import 'moment/locale/ru'


export default {
    props: ["selected"],
    data() {
        return {
            search: null,
            tables: [],
            paginate: null,
            sort: {
                param: null,
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getTables', 'getTablesPaginateObject']),
        self() {
            return window.self || null
        },

        tg() {
            return window.Telegram.WebApp;
        },

    },
    mounted() {
        this.loadTables()

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        timeAgo(datetime) {
            moment.locale('ru')
            //return moment(datetime).fromNow() // например: "3 минуты назад"
            return moment(datetime).format('D-MM-YYYY [в] HH:mm')
        },
        closeTable(tableId) {
            this.$store.dispatch("closeTableOrder", {
                dataObject: {
                    table_id: tableId,
                }
            }).then(resp => {

                this.$notify({
                    title: 'Заказ',
                    text: "Столик успешно закрыт",
                    type: 'success'
                })

                this.loadTable()
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка завершения работы столика",
                    type: 'error'
                })
            })
        },
        takeATable(id) {
            this.changeTableWaiter(id).then(() => {
                this.goToTable(id)
            })
        },
        changeTableWaiter(id) {
            return this.$store.dispatch("changeTableWaiter", {
                dataObject: {
                    table_id: id
                }
            }).then(resp => {

                this.$notify({
                    title: 'Смена официанта',
                    text: "Официант успешно изменен",
                    type: 'success'
                })
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка смены официанта",
                    type: 'error'
                })
            })
        },
        goToTable(tableId) {
            this.$router.push({name: 'TableV2', params: {tableId: tableId}})
        },
        nextTables(index) {
            this.loadTables(index)
        },
        loadTables(page = 0) {
            return this.$store.dispatch("loadTables", {
                dataObject: {},
                page: page,
                size: 100
            }).then((resp) => {
                this.tables = this.getTables
                this.paginate = this.getTablesPaginateObject
            }).catch(() => {

            })
        }
    }
}
</script>


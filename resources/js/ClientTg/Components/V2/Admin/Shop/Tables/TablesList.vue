<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>

    <template v-if="tables.length>0">
        <div class="row row-cols-2">
            <div class="col" v-for="(table,index) in tables">
                <div
                    v-bind:class="{'bg-primary text-white':table.officiant_id!=null}"
                    class="card">
                    <div class="card-body">
                        <h6 class="text-center">Столик #{{ table.number || '-' }}</h6>
                        <div class="btn-group w-100">

                            <button type="button"
                                    @click="changeTableWaiter(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary "><i
                                class="fa-solid fa-right-to-bracket"></i></button>
                            <button type="button"
                                    @click="goToTable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary "><i class="fa-solid fa-eye"></i></button>
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
    },
    mounted() {
        this.loadTables()

    },
    methods: {
        changeTableWaiter(id) {
            this.$store.dispatch("changeTableWaiter", {
                dataObject: {
                    table_id: id
                }
            }).then(resp => {

                this.$notify({
                    title: 'Смена официанта',
                    text: "Официант успешно изменен",
                    type: 'success'
                })

                this.table = resp.data
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


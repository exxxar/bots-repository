<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>

    <template v-if="tables.length>0">
        <div class="row row-cols-1">
            <div class="col mb-2" v-for="(table,index) in tables">
                <div
                    v-bind:class="{'bg-primary text-white':table.officiant_id!=null}"
                    class="card">
                    <div class="card-body">
                        <h6 class="text-center">Столик #{{ table.number || '-' }}</h6>
                        <p class="text-center">Обслуживает столик {{ table.officiant?.name || '-' }}</p>
                        <p class="text-center">Клиентов за столиком {{ table.clients?.length || 0 }}</p>
                        <div class="btn-group w-100">
                            <button type="button"
                                    v-if="table.officiant_id == null"
                                    @click="takeATable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary "><i
                                class="fa-solid fa-right-to-bracket"></i> </button>
                            <button type="button"
                                    @click="goToTable(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary "><i class="fa-solid fa-eye"></i></button>
                            <button type="button"
                                    v-if="self.id === table.officiant_id"
                                    @click="changeTableWaiter(table.id)"
                                    v-bind:class="{'btn-light text-primary':table.officiant_id!=null}"
                                    class="btn btn-outline-primary "><i class="fa-solid fa-xmark"></i></button>
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
        self() {
            return window.self || null
        },
    },
    mounted() {
        this.loadTables()

    },
    methods: {
        takeATable(id){
            this.changeTableWaiter(id).then(()=>{
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


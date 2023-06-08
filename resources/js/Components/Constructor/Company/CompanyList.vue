<script setup>
import Pagination from '@/Components/Pagination.vue';
</script>
<template>

    <div class="row mb-2">
        <div class="col-md-12 col-12">
            <button type="button"
                    @click="show=!show"
                    class="btn btn-outline-success p-3 w-100">
                <span v-if="!show"><i class="fa-regular fa-building"></i> Открыть список компаний</span>
                <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть список компаний</span>
            </button>
        </div>
    </div>

    <div v-if="show">
        <div class="row">
            <div class="input-group mb-3">
                <input type="search" class="form-control"
                       placeholder="Поиск компании"
                       aria-label="Поиск компании"
                       v-model="search"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary"
                        @click="loadCompanies"
                        type="button"
                        id="button-addon2">Найти
                </button>
            </div>
        </div>
        <div class="row" v-if="companies.length>0">
            <div class="col-12 mb-3">
                <ul class="list-group w-100">
                    <li class="list-group-item"
                        v-for="(company, index) in companies"
                        @click="selectCompany(company)">{{ company.title || 'Не указано' }}
                        ({{ company.slug || 'Не указано' }})
                    </li>
                </ul>

            </div>

            <div class="col-12">
                <Pagination

                    v-on:pagination_page="nextCompanies"
                    v-if="companies_paginate_object"
                    :pagination="companies_paginate_object"/>
            </div>

        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            show: false,
            loading: true,
            companies: [],
            search: null,
            companies_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getCompanies', 'getCompaniesPaginateObject']),
    },
    mounted() {
        this.loadCompanies();
    },
    methods: {
        selectCompany(company) {
            this.$emit("callback", company)
            this.show = false
            this.$notify("Вы выбрали компанию из спика! Все остальные действия будут производится для этой компании.");
        },
        nextCompanies(index) {
            this.loadCompanies(index)
        },
        loadCompanies(page = 0) {
            this.loading = true
            this.$store.dispatch("loadCompanies", {
                dataObject: {
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.companies = this.getCompanies
                this.companies_paginate_object = this.getCompaniesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>

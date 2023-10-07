<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>


    <div v-if="show">


        <!--        <div class="d-flex">
                    <div class="btn-wrapper p-1 w-100" v-for="item in filters">
                        <a class="btn  w-100"
                           v-bind:class="{'btn-primary':selectedFilters.indexOf(item.slug)!=-1}"
                           @click="selectFilter(item.slug)"
                           href="javascript:void(0)"><i
                            v-bind:class="item.icon"
                            class="mr-2"></i> {{ item.name || 'Не указано' }}</a>

                    </div>


                </div>-->


        <input type="search" class="form-control w-100 mb-2"
               placeholder="Поиск компании"
               aria-label="Поиск компании"
               v-model="search"
               aria-describedby="button-addon2">


        <button class="w-100 btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark"
                @click="loadCompanies(0)"
                type="button"
                id="button-addon2"><i class="fa-solid fa-magnifying-glass mr-1"></i> Найти
        </button>

        <!--        <p v-if="selectedFilters.length>0" class="mt-2">
                            <span class="badge bg-info mr-1" v-for="filter in selectedFilters">{{ filter.name || 'не указан' }}
                             <a
                                 @click="removeSelectedFilter(filter.slug)"
                                 class="ml-1 text-white" href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                            </span>
                </p>-->

        <div v-if="companies.length>0&&!visualMode">


            <a href="javascript:void(0)"
               class="btn btn-border btn-xs btn-full mb-2 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme mb-1 d-flex justify-content-between align-items-center"
               v-bind:class="{'btn-outline-info':company.deleted_at==null,'btn-outline-danger border-danger':company.deleted_at!=null}"
               v-for="(company, index) in filteredCompanies"
            >

                        <span
                            class="mb-0"
                            @click="selectCompany(company)"
                            v-bind:class="{'text-danger':company.deleted_at!=null}">
                            {{ company.title || 'Не указано' }}

                        </span>
                <button
                    class="btn btn-border btn-xs btn-full rounded-xl text-uppercase font-900 border-red2-dark color-red2-dark bg-theme"
                    type="button"
                    @click="addToArchive(company.id)"
                    title="В архив" v-if="company.deleted_at==null"><i
                    class="fa-solid fa-boxes-packing"></i></button>
                <button
                    class="btn btn-border btn-xs btn-full rounded-xl text-uppercase font-900 border-green2-dark color-green2-dark bg-theme"
                    type="button"
                    @click="extractFromArchive(company.id)"
                    title="Из архива" v-if="company.deleted_at!=null"><i
                    class="fa-solid fa-box-open"></i></button>
            </a>


            <Pagination

                v-on:pagination_page="nextCompanies"
                v-if="companies_paginate_object"
                :pagination="companies_paginate_object"/>


        </div>

        <div v-if="companies.length>0&&visualMode"
             class="row text-center row-cols-3 mb-n4">
            <a class="col mb-4 default-link"
               href="javascript:void(0)"
               v-for="item in companies"
               @click="selectCompany(item)"
               :title="item.title||'Без названия'">
                <img class="img-fluid rounded-xs preload-img"
                     :alt="item.title||'Не указано'"

                     v-lazy="'/images-by-company-id/'+item.id+'/'+item.image">

                <span
                    v-bind:class="{'company-border-select':selectedCompanyId==item.id}"
                    class="company-image-badge">{{ item.title || 'Без названия' }}</span>
                <span class="company-bot-count-badge rounded-circle bg-highlight text-white"
                      v-if="item.bot_count>0">{{ item.bot_count || 0 }}</span>
            </a>

           <div class="col-12 mb-2"   v-if="selectedCompanyId">
               <a class="col mb-4 default-link"
                  href="javascript:void(0)"
                  @click="selectCompany(null)">
                   Сброс
               </a>
           </div>

            <div class="col-12 mb-3">

                <Pagination

                    v-on:pagination_page="nextCompanies"
                    v-if="companies_paginate_object"
                    :pagination="companies_paginate_object"/>
            </div>
        </div>

        <div v-else class="alert alert-warning" role="alert">
            Вы еще не завели ни одного клиента!
        </div>


    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["visualMode"],
    data() {
        return {
            selectedCompanyId: null,
            show: true,
            loading: true,
            companies: [],
            search: null,
            filters: [
                {
                    name: 'Активные',
                    icon: 'fa-brands fa-telegram',
                    slug: 'active'
                },
                {
                    name: 'Архивные',
                    icon: 'fa-solid fa-box-archive',
                    slug: 'archive'
                }
            ],
            selectedFilters: [],
            companies_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getCompanies', 'getCompaniesPaginateObject']),
        filteredCompanies() {
            if (!this.companies)
                return [];

            if (this.selectedFilters.length === 0 && this.search == null)
                return this.companies

            if (this.selectedFilters.length === 0 && this.search != null)
                return this.companies.filter(item => (item.title || '')
                    .trim()
                    .toLowerCase()
                    .indexOf(this.search
                        .trim()
                        .toLowerCase()) !== -1)

            let tmpCompanies = [];
            this.selectedFilters.forEach(filter => {
                switch (filter.slug) {
                    case 'active':
                        this.companies.filter(item => item.deleted_at == null).forEach(item => {
                            tmpCompanies.push(item)
                        })
                        break;
                    case 'archive':
                        this.companies.filter(item => item.deleted_at != null).forEach(item => {
                            tmpCompanies.push(item)
                        })
                        break;
                }
            })

            if (this.search == null)
                return tmpCompanies

            return tmpCompanies.filter(item => (item.title || '')
                .trim()
                .toLowerCase()
                .indexOf(this.search
                    .trim()
                    .toLowerCase()) !== -1)


        }
    },
    mounted() {
        this.loadCompanies();
        this.selectFilter('active')
    },
    methods: {
        addToArchive(id) {
            this.$store.dispatch("removeManagerCompany", {
                companyId: id
            }).then(resp => {
                let currentPage = this.companies_paginate_object.meta.current_page || 0
                this.loadCompanies(currentPage)
                this.$notify("Указанный клиент успешно перемещен в архив");
            })
        },
        extractFromArchive(id) {
            this.$store.dispatch("restoreCompany", {
                companyId: id
            }).then(resp => {
                let currentPage = this.companies_paginate_object.meta.current_page || 0
                this.loadCompanies(currentPage)
                this.$notify("Указанный клиент успешно перемещен из архива");
            })
        },
        selectFilter(slug) {
            let tmpFilter = this.filters.find(item => item.slug === slug)
            let tmpSelectedFilterIndex = this.selectedFilters.findIndex(item => item.slug === slug)

            if (tmpFilter && tmpSelectedFilterIndex === -1)
                this.selectedFilters.push(tmpFilter)
            else
                this.selectedFilters.splice(tmpSelectedFilterIndex, 1)

        },
        removeSelectedFilter(slug) {
            let index = this.selectedFilters.findIndex(item => item.slug === slug)
            this.selectedFilters.splice(index, 1)
        },
        selectCompany(company) {

            this.selectedCompanyId = company ? company.id : null
            this.$emit("callback", company)
        },
        nextCompanies(index) {
            this.loadCompanies(index)
        },
        loadCompanies(page = 0) {
            this.loading = true
            this.$store.dispatch("loadManagersCompanies", {
                dataObject: {
                    search: this.search
                },
                page: page,
                size: 12
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
<style>
.company-image-badge {
    font-size: 12px;
    line-height: 100%;
    margin: 0;
    padding: 0;
    display: inline-block;
}

.company-bot-count-badge {
    position: absolute;
    top: 4px;
    right: 20px;
    width: 25px;
    height: 25px;
}

.company-border-select {
    border-bottom: 2px dashed #8BC34A;
    padding-bottom: 5px;
}
</style>

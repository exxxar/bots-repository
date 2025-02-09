<script setup>
import Pagination from '@/AdminPanel/Components/SimplePagination.vue';
import CompanyForm from "@/AdminPanel/Components/Constructor/Company/CompanyForm.vue";
</script>
<template>

    <template v-if="show">
            <div class="d-flex">
                <div class="dropdown mr-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Фильтры
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li v-for="item in filters"><a class="dropdown-item"
                                                       @click="selectFilter(item.slug)"
                                                       href="#filter"><i
                            v-bind:class="item.icon"
                            class="mr-2"></i> {{ item.name || 'Не указано' }}</a></li>

                    </ul>
                </div>

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
            <p v-if="selectedFilters.length>0" class="mt-2">
                    <span class="badge bg-info mr-1" v-for="filter in selectedFilters">{{ filter.name || 'не указан' }}
                     <a
                         @click="removeSelectedFilter(filter.slug)"
                         class="ml-1 text-white" href="#filter"><i class="fa-solid fa-xmark"></i></a>
                    </span>
            </p>

        <template v-if="companies.length>0">
            <ul class="list-group w-100 p-0 m-0">
                <li class="list-group-item cursor-pointer"
                    v-bind:class="{'btn-outline-info':company.deleted_at==null,
                        'btn-outline-danger border-danger':company.deleted_at!=null,
                        'bg-success':selected==company.id,
                        'btn d-flex justify-between':!isSimple
                        }"
                    v-for="(company, index) in filteredCompanies"
                >


                        <span
                            @click="selectCompany(company)"
                            v-bind:class="{'text-danger':company.deleted_at!=null}">
                            #{{company.id || '-'}}
                            {{ company.title || 'Не указано' }}
                        ({{ company.slug || 'Не указано' }})

                        </span>

                    <div v-if="!isSimple">
                        <button class="btn btn-info mr-1"
                                type="button"
                                @click="editClient(company)"
                                title="В архив"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-outline-info"
                                type="button"
                                @click="addToArchive(company.id)"
                                title="В архив" v-if="company.deleted_at==null"><i
                            class="fa-solid fa-boxes-packing"></i></button>
                        <button class="btn btn-outline-info"
                                type="button"
                                @click="extractFromArchive(company.id)"
                                title="Из архива" v-if="company.deleted_at!=null"><i
                            class="fa-solid fa-box-open"></i></button>
                    </div>

                </li>
            </ul>

            <Pagination
                v-on:pagination_page="nextCompanies"
                v-if="companies_paginate_object"
                :pagination="companies_paginate_object"/>
        </template>

    </template>


    <!-- Modal -->
    <div class="modal fade" id="edit-company-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Сохранение клиента</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-if="selectedCompany">
                        <div class="col-12 mb-2 ">
                            <CompanyForm
                                :company="selectedCompany"
                                :editor="false"
                                v-on:callback="companyCallback"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Не сохранять</button>
                </div>
            </div>
        </div>
    </div>




</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:['selected','isSimple'],
    data() {
        return {
            show: true,
            loading: true,
            companies: [],
            search: null,
            editCompanyModal:null,
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
            selectedCompany:null,
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

        this.editCompanyModal = new bootstrap.Modal(document.getElementById('edit-company-modal'), {})
    },
    methods: {
        companyCallback(company) {
            this.selectedCompany = null
            this.editCompanyModal.hide();
        },
        editClient(company){
            this.selectedCompany = company
            this.editCompanyModal.show();
        },
        addToArchive(id) {
            this.$store.dispatch("removeCompany", {
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

            if (tmpFilter && this.selectedFilters.filter(item => item.slug === slug).length === 0)
                this.selectedFilters.push(tmpFilter)

        },
        removeSelectedFilter(slug) {
            let index = this.selectedFilters.findIndex(item => item.slug === slug)
            this.selectedFilters.splice(index, 1)
        },
        selectCompany(company) {

            this.$store.dispatch("updateCurrentCompany", {
                company: company
            })

            this.$emit("callback", company)

            this.show = false

            this.$notify("Вы выбрали клиента из списка! Все остальные действия будут производится для этой компании.");
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

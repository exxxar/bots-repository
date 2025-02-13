<template>

    <vue3-simple-typeahead
        :items="regions"
        v-if="load_regions"
        :defaultItem="region"
        class="form-control w-100 p-3 mb-2"
        placeholder="Выбор региона"
        @selectItem="selectRegion"
        @onInput="onInput"
        @onBlur="onBlur"
        :minInputLength="1"
        :itemProjection="
						(item) => {
							return item.region;
						}
					"
    />
    <div class="alert alert-light" v-else>
        <p class="text-center">Загружаем данные по регионам...</p>
        <div class="d-flex justify-content-center w-100">
            <div class="spinner-border color-orange-dark" role="status">
                <span class="sr-only">Загрузка...</span>
            </div>
        </div>
    </div>
    <template v-if="region">
        <vue3-simple-typeahead
            :items="cities"
            :defaultItem="city"
            v-if="load_cities"
            class="form-control w-100 p-3 mb-2"
            placeholder="Выбор города в регионе"
            @selectItem="selectCity"
            @onInput="onInputCity"
            @onBlur="onBlur"
            :minInputLength="1"
            :itemProjection="
						(item) => {
							return item.city;
						}
					"
        />
        <div class="alert alert-light" v-else>
            <p class="text-center">Загружаем данные по городам...</p>
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border color-orange-dark" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
            </div>
        </div>
    </template>

    <button
        v-if="city"
        type="button"
        :disabled="offices.length===0"
        @click="openOfficeModal"
        class="btn btn-light w-100 p-3">
        <span v-if="!office"><i class="fa-solid fa-city text-primary"></i> Выбрать офис
            <span class="fw-bold">({{ offices.length }})</span>
        </span>
        <span v-else><i class="fa-solid fa-building-circle-arrow-right"></i>
            {{ office.location.address_full }}
        </span>
    </button>

    <!-- Modal -->
    <div class="modal fade" :id="'select-cdek-office'+uuid" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Выбор офиса</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="offices.length>0">
                    <div class="input-group mb-2">
                        <div class="form-floating">
                            <input type="search"
                                   v-model="search_office"
                                   class="form-control border-light" id="search-product" placeholder="name@example.com">
                            <label for="search-product">Поиск по адресам</label>
                        </div>
                        <button class="btn btn-outline-light" type="button" id="button-addon2"><i
                            class="fa-solid fa-magnifying-glass-arrow-right"></i></button>
                    </div>

                    <template v-for="office in filteredOffices">
                        <div class="card w-100 mb-2 border-light"

                        >
                            <template v-if="(office.office_image_list||[]).length>0">
                                <img
                                    class="card-img-top mb-2"
                                    style="max-height:150px;object-fit:cover;"
                                    :src="office.office_image_list[0].url" alt="">
                                <!--                                <img
                                                                    v-for="img in office.office_image_list"
                                                                    :src="img.url" class="card-img-top mb-2" alt="">-->
                            </template>

                            <div class="card-body">
                                <h5 class="card-title">{{ office.name || '-' }}</h5>
                                <div class="card-text small fst-italic">
                                    {{ office.location.address_full || '-' }}
                                </div>
                                <p class="card-text">

                                    {{ office.address_comment || '-' }}
                                </p>
                                <a
                                    class="btn btn-link text-center mb-3 w-100"
                                    :href="'https://yandex.ru/maps/?ll='+office.location.longitude+'%2C'+office.location.latitude+'&z=15&mode=whatshere&whatshere%5Bpoint%5D='+office.location.longitude+'%2C'+office.location.latitude+'&whatshere%5Bzoom%5D=15&z=15'"
                                    target="_blank"><i class="fa-solid fa-map-location-dot"></i> На карте</a>
                                <a
                                    @click="selectOffice(office)"
                                    href="javascript:void(0)" class="btn btn-primary w-100"><i
                                    class="fa-solid fa-building-circle-arrow-right"></i> Выбрать</a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["config"],
    data() {
        return {
            officeModal: null,
            load_regions: false,
            load_cities: false,
            search_office: null,
            regions: [],
            cities: [],
            offices: [],

            region: null,
            city: null,
            office: null,
        }
    },
    computed: {
        filteredOffices() {
            if (!this.search_office)
                return this.offices

            return this.offices.filter(item => item.location.address_full.toLowerCase().indexOf(this.search_office.toLowerCase().trim()) !== -1)
        },
        uuid() {
            return uuidv4();
        }
    },
    mounted() {

        if (this.config) {
            this.region = this.config.region || null
            this.city = this.config.city || null
            this.office = this.config.office || null
        }

        this.officeModal = new bootstrap.Modal(document.getElementById('select-cdek-office' + this.uuid), {})
        //     this.loadCdekCities()
        this.loadCdekRegions()
        //   this.loadCdekOffices()
    },
    methods: {
        onInputCity() {
            this.offices = []
        },
        openOfficeModal() {
            this.officeModal.show()
        },
        selectOffice(event) {
            this.office = event

            this.$emit("callback", {
                city: this.city,
                region: this.region,
                office: this.office
            })

            this.officeModal.toggle();
        },
        selectCity(event) {
            this.city = event

            this.loadCdekOffices()
        },
        selectRegion(event) {
            this.region = event

            this.loadCdekCities()
        },
        loadCdekRegions() {
            this.load_regions = false
            this.$store.dispatch("loadCdekRegions").then((resp) => {
                this.regions = resp || []

                if (this.region) {
                    let index = this.regions.findIndex(item => item.region_code === this.region.region_code)

                    if (index === -1)
                        this.region = null
                }


                this.load_regions = true


            })
        },
        loadCdekOffices() {
            this.$store.dispatch("loadCdekOffices", {
                page: 0,
                size: 100,
                city_code: this.city.code,
            }).then((resp) => {
                this.offices = resp

                if (this.office) {
                    let index = this.offices.findIndex(item => item.code === this.office.code)

                    if (index === -1)
                        this.office = null
                }
            })
        },
        loadCdekCities() {
            if (this.region == null||!this.region.region_code)
                return

            this.load_cities = false

            this.$store.dispatch("loadCdekCities", {
                region_code: this.region.region_code
            }).then((resp) => {
                this.cities = resp

                if (this.city) {
                    let index = this.cities.findIndex(item => item.code === this.city.code)

                    if (index === -1)
                        this.city = null
                }

                this.load_cities = true
            })
        },

    }
}
</script>
<style>
.simple-typeahead-list,
.simple-typeahead-list-item {
    background-color: var(--bs-body-bg);
}
.simple-typeahead-list-item-text {

    color: var(--bs-primary);
}
</style>

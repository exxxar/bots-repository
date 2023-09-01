<script setup>

import Pagination from "ClientTg@/Components/Pagination.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style bg-theme pb-0">
        <div class="content">
            <p class="mb-0">Введите информацию о событии или о пользователе</p>

            <form v-on:submit.prevent="loadActions(0)">
                <div class="search-box bg-theme rounded-s shadow-xl bottom-0">
                    <i class="fa fa-search"></i>
                    <input type="text"
                           v-model="search"
                           class="border-0" :placeholder="preparedFilterName || 'Поиск по событию'">
                </div>

                <p class="mt-2 mb-0">Фильтры:
                    <span
                        style="color:red;"
                        v-if="filter">{{ preparedFilterName }}
                        <i
                            @click="filter = null"
                            class="ml-2 fa-solid fa-xmark"></i>
                    </span>
                </p>
                <div class="row mt-2 mb-2">
                    <div class="col-4">
                        <a href="javascript:void(0)"
                           @click="filter = 'users'"
                           v-bind:class="{'border-red2-dark color-red2-dark':filter === 'users', 'border-gray2-dark color-gray2-dark':filter !== 'users'}"
                           class="btn btn-border btn-m btn-full  rounded-s text-uppercase font-900  bg-theme">
                            <i class="fa-solid fa-users"></i>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="javascript:void(0)"
                           @click="filter = 'event'"
                           v-bind:class="{'border-red2-dark color-red2-dark':filter === 'event', 'border-gray2-dark color-gray2-dark':filter !== 'event'}"
                           class="btn btn-border btn-m btn-full rounded-s text-uppercase font-900 border-red2-dark color-red2-dark bg-theme">
                            <i class="fa-solid fa-bolt"></i>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="javascript:void(0)"
                           @click="filter = 'phone'"
                           v-bind:class="{'border-red2-dark color-red2-dark':filter === 'phone', 'border-gray2-dark color-gray2-dark':filter !== 'phone'}"
                           class="btn btn-border btn-m btn-full  rounded-s text-uppercase font-900 border-red2-dark color-red2-dark bg-theme">
                            <i class="fa-solid fa-phone"></i>
                        </a>
                    </div>

                </div>
                <button type="submit"
                        class="btn btn-m btn-full my-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>Искать
                </button>
            </form>

            <div class="mx-0 mt-3 px-2" v-if="actions.length>0">
                <small class="w-100 text-center p-2 mb-0">Найдено {{ paginate_object.meta.total }} записей</small>

                <div class="w-100" style="overflow-y: scroll">
                    <table class="table text-center rounded-sm">
                        <thead>
                        <tr>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Ф.И.О.</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Телефон</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Название события</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">№ попытки</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Номер приза</th>

                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Подверждено</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Дата розыгрыша</th>
                            <th scope="col" class="bg-highlight border-dark1-dark color-white">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-theme" v-for="(item, index) in actions" @click="openActionDetails(item)">

                            <td style="min-width:200px;" class=" border-dark1-dark text-left">
                                <span v-for="(data, i) in item.data">{{ data.name }}</span>
                            </td>
                            <td style="min-width:200px;" class=" border-dark1-dark">
                                <span v-for="(data, i) in item.data">{{ data.phone }}</span>
                            </td>

                            <td style="min-width:200px;" class=" border-dark1-dark">{{ item.slug.command }}
                                (#{{ item.slug.id }})
                            </td>
                            <td class=" border-dark1-dark">{{ item.current_attempts }}</td>
                            <td class=" border-dark1-dark">
                                <span v-for="(data, i) in item.data">{{ data.win }}</span>
                            </td>

                            <td style="min-width:200px;" class=" border-dark1-dark">
                                <span v-for="(data, i) in item.data">

                                    <i class="fa-regular fa-circle-check color-green2-light"
                                       v-if="data.answered_at"></i>
                                    <i class="fa-regular fa-circle-xmark color-red2-light" v-else></i>
                                </span>
                            </td>
                            <td class=" border-dark1-dark">{{ $filters.current(item.completed_at) }}</td>
                            <td class=" border-dark1-dark">{{ $filters.current(item.completed_at) }}</td>

                        </tr>

                        </tbody>
                    </table>
                </div>
                <!--                <div class="list-group list-custom-large">
                                    <a href="#"
                                       @click.prevent="openActionDetails(item)"
                                       v-for="(item, index) in actions">
                                        <i class="fa-solid fa-a color-red2-dark"></i>
                                        <span>{{item.slug.command || 'Не указано'}}</span>
                                        <strong>Тест</strong>
                                        <i class="fa fa-angle-right mr-2"></i>
                                    </a>

                                </div>-->


                <Pagination
                    class="mt-4"
                    :simple="true"
                    v-on:pagination_page="nextActions"
                    v-if="paginate_object"
                    :pagination="paginate_object"/>

                <ReturnToBot/>
            </div>

            <p v-else>К сожалению никаких подходящих событий не найдено</p>

        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            loading: true,
            actions: [],
            search: null,
            filter: null,
            paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getActions',
            'getActionsPaginateObject']),
        preparedFilterName(){
            if (!this.filter)
                return null

            let arr = [
                {
                    slug:'users',
                    text:'Ф.И.О. пользователя'
                },
                {
                    slug:'event',
                    text:'по типу события'
                },
                {
                    slug:'phone',
                    text:'по номеру телефона'
                }
            ]

            return arr.find(item=>item.slug === this.filter).text
        }
    },
    mounted() {
        this.loadActions(0)
    },
    methods: {
        nextActions(index) {
            this.loadActions(index)
        },
        openActionDetails(item) {
            this.$cashback.eventInfo(item)
        },
        loadActions(page = 0) {
            this.loading = true
            this.$store.dispatch("loadActions", {
                dataObject: {
                    search: this.search,
                    filter:  this.filter
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.actions = this.getActions
                this.paginate_object = this.getActionsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>

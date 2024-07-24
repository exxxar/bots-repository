<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
</script>
<template>
    <!--    <div class="row mb-2">
            <div class="col-12">
                <button type="button"
                        @click="show=!show"
                        class="btn btn-outline-success p-3 w-100">
                    <span v-if="!show"><i class="fa-solid fa-robot"></i> Открыть список ботов</span>
                    <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть список ботов</span>
                </button>
            </div>
        </div>-->

    <div v-if="show">




        <div class="d-flex flex-column mt-2">

            <input type="search" class="form-control mb-2"
                   placeholder="Поиск бота"
                   aria-label="Поиск бота"
                   v-model="search"
                   aria-describedby="button-addon2">

            <button class="btn btn-m btn-full mb-0 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark"
                    @click="loadBots"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
        <div class="d-flex my-3 w-100">
            <div class="pt-1">
                <h5 data-activate="toggle-id-2" class="font-500 font-13">
                    <strong v-if="need_self_bots">Все боты в системе</strong>
                    <strong v-if="!need_self_bots">Только мои боты</strong>
                </h5>
            </div>
            <div class="ml-auto mr-4 pr-2">
                <div class="custom-control ios-switch ios-switch-icon">
                    <input type="checkbox"
                           v-model="need_self_bots"
                           class="ios-input" id="toggle-id-2">
                    <label class="custom-control-label" for="toggle-id-2"></label>
                    <i class="fa fa-check font-11 color-white"></i>
                    <i class="fa fa-times font-11 color-white"></i>
                </div>
            </div>
        </div>

        <div v-if="bots.length>0" >
            <div class="divider divider-small my-3 bg-highlight "></div>

                <a
                    href="javascript:void(0)"
                    class="d-flex justify-content-between align-items-center btn btn-border px-2 py-1 btn-full mb-2 rounded-xl text-uppercase font-900 bg-theme"
                    v-bind:class="{' border-blue2-dark color-blue2-dark':selectedBotId!=bot.id,' border-green2-dark color-green2-dark':selectedBotId===bot.id}"
                    v-for="(bot, index) in filteredBots"
                >
                    <strong
                        @click="selectBot(bot)"
                        class="font-12 w-100 text-center"
                        style="word-wrap: break-word;"><i
                        v-bind:class="{'text-danger':bot.deleted_at!=null}"
                        class="fa-solid fa-robot mr-2"></i>{{
                            bot.bot_domain || 'Не указано'
                        }}
                    </strong>

                    <a href="#" class="btn btn-m btn-full rounded-xl text-uppercase font-900 shadow-s bg-red1-light"><i class="fa-solid fa-arrow-right"></i></a>

                </a>

            <Pagination

                v-on:pagination_page="nextBots"
                v-if="bots_paginate_object"
                :pagination="bots_paginate_object"/>
        </div>

        <div v-else>
            <div class="alert alert-warning mt-2" role="alert">
                К сожалению мы не нашли нужных ботов...
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: {
        companyId: null,
        selectedBotId: null
    },
    data() {
        return {
            need_self_bots:false,
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
            show: true,
            loading: true,
            bots: [],
            search: null,
            bots_paginate_object: null,
        }
    },
    watch:{
        need_self_bots:function (){
            this.loadBots();
        }
    },
    computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject', 'getCurrentCompany']),
        filteredBots() {
            if (!this.bots)
                return [];

            return this.bots.filter(item=>item.deleted_at == null)
        }
    },
    mounted() {
        this.loadBots();

    },
    methods: {
        duplicate(id) {
            if (!this.getCurrentCompany) {
                this.$notify("У Вас не выбран клиент!");
                return;
            }
            this.$store.dispatch("duplicateBot", {
                dataObject: {
                    bot_id: id,
                    company_id: this.getCurrentCompany.id
                }
            }).then(resp => {
                let currentPage = this.bots_paginate_object.meta.current_page || 0
                this.loadBots(currentPage)
                this.$notify("Указанный бот успешно продублирован");
            })
        },

        selectBot(bot) {
            this.$emit("callback", bot)
            this.$notify("Вы выбрали бота из списка! Все остальные действия будут производится для этого бота.");
        },
        nextBots(index) {
            this.loadBots(index)
        },
        loadBots(page = 0) {
            this.loading = true
            this.$store.dispatch("loadSimpleBots", {
                dataObject: {
                    companyId: this.companyId || null,
                    needSelfBots: this.need_self_bots ,
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.bots = this.getBots
                this.bots_paginate_object = this.getBotsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>

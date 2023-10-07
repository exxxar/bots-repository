<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';
import BotEditor from "@/ClientTg/Components/Manager/Bots/BotEditor.vue";
</script>
<template>

    <div v-if="show">




        <div class="d-flex flex-column mt-2">

            <input type="search" class="form-control mb-2"
                   placeholder="Поиск бота"
                   aria-label="Поиск бота"
                   v-model="search"
                   aria-describedby="button-addon2">

            <button class="btn btn-m btn-full mb-0 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark"
                    @click="loadBots(0)"
                    type="button"
                    id="button-addon2">Найти
            </button>
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

    <BotEditor
        v-on:remove="removeBotCallback"
        v-if="selectedBotId&&!loading"
        :bot-id="selectedBotId"></BotEditor>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: {
        companyId: null,
        selectedBot: null
    },
    data() {
        return {
            selectedBotId:null,
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
        removeBotCallback(){
          this.loadBots();
            this.selectedBotId = null
        },
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
           /// this.$emit("callback", bot)
            this.loading = true
            this.selectedBotId = bot.id

            this.$nextTick(()=>{
                this.loading = false
            })
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
                    needSelfBots: true ,
                    search: this.search
                },
                size:5,
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

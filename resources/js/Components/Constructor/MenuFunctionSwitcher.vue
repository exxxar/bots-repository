<script setup>
import PagesList from "@/Components/Constructor/Pages/PagesList.vue";
import BotDialogGroupListSimple from "@/Components/Constructor/Dialogs/BotDialogGroupListSimple.vue";
import BotSlugListSimple from "@/Components/Constructor/BotSlugListSimple.vue";
</script>

<template>
    <div class="row">
        <div class="col-12">
            <h6>Привязывание действий к кнопкам</h6>
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                 К кнопке возможно привязать только 1 действие! Название привыязываемых кнопок должно быть уникальным.

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                <button type="button"
                        @click="part=0"
                        v-bind:class="{'btn-success text-white':part===0}"
                        :disabled="associateForm.page_id||associateForm.dialog_id"
                        class="btn btn-outline-success"><i class="fa-solid fa-scroll mr-1"></i> Скрипт </button>
                <button type="button"
                        @click="part=1"
                        v-bind:class="{'btn-success text-white':part===1}"
                        :disabled="associateForm.slug_id||associateForm.dialog_id"
                        class="btn btn-outline-success"><i class="fa-solid fa-file-lines mr-1"></i>Страница</button>
                <button type="button"
                        @click="part=2"
                        v-bind:class="{'btn-success text-white':part===2}"
                        :disabled="associateForm.page_id||associateForm.slug_id"
                        class="btn btn-outline-success"><i class="fa-solid fa-file-audio mr-1"></i>Диалог</button>
            </div>

        </div>

        <div class="col-12 mt-2" v-if="part===0">
            <div
                v-if="associateForm.slug_id"
                class="alert alert-dark d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <span> Вы привязали скрипт #{{associateForm.slug_id}} к пункту меню "{{associateForm.text}}"</span>
                    <button type="button"
                            @click="resetAssociate"
                            class="btn btn-link"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <BotSlugListSimple v-if="bot"
                               v-on:callback="associateSlug"
                               :bot-id="bot.id"></BotSlugListSimple>
        </div>

        <div class="col-12 mt-2" v-if="part===1">
            <div
                v-if="associateForm.page_id"
                class="alert alert-dark d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <span> Вы привязали страницу #{{associateForm.page_id}} к пункту меню "{{associateForm.text}}"</span>
                   <button type="button"
                           @click="resetAssociate"
                           class="btn btn-link"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <PagesList v-if="bot"
                       v-on:callback="associatePage"
                       :bot-id="bot.id"/>
        </div>

        <div class="col-12 mt-2" v-if="part===2">
            <div
                v-if="associateForm.dialog_id"
                class="alert alert-dark d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <span> Вы привязали диалог #{{associateForm.dialog_id}} к пункту меню "{{associateForm.text}}"</span>
                    <button type="button"
                            @click="resetAssociate"
                            class="btn btn-link"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <BotDialogGroupListSimple  v-if="bot"
                                       v-on:select-dialog="associateDialog"
                                       :bot-id="bot.id"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";


export default {
    components: {},

    props:["selectedData"],
    data(){
      return {
          part:0,
          bot:null,
          pages:[],
          pages_paginate_object:[],

          associateForm:{
              slug_id:null,
              page_id:null,
              dialog_id: null,
              text: null,
              bot_id: null,
              type: null,
              row: null,
              col: null
          },

      }
    },

    mounted() {
        this.loadCurrentBot().then(()=>{
            this.loadPages()


            this.associateForm.bot_id = this.bot.id
            this.associateForm.text = this.selectedData.text
            this.associateForm.type = this.selectedData.type || 'reply'
            this.associateForm.row = this.selectedData.row || 0
            this.associateForm.col = this.selectedData.col || 0
        })


    },
    computed: {
        ...mapGetters(['getCurrentBot','getPages','getPagesPaginateObject']),
    },
    methods: {

        loadPages(pageIndex = 0) {
            this.loading = true;
            this.$store.dispatch("loadPages", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search
                },
                page: pageIndex
            }).then((resp) => {
                this.loading = false;
                this.pages = this.getPages;
                this.pages_paginate_object = this.getPagesPaginateObject;
            }).catch(() => {
                this.loading = false;
            });
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot

                this.associateForm.bot_id = this.bot.id
            })
        },

        associatePage(page){
            this.associateForm.page_id = page.id

            this.$emit("change-associate", this.associateForm)
        },
        associateDialog(dialog){
            this.associateForm.dialog_id = dialog.id

            this.$emit("change-associate", this.associateForm)
        },
        associateSlug(slug){
            this.associateForm.slug_id = slug.id

            this.$emit("change-associate", this.associateForm)
        },
        resetAssociate(){
            this.associateForm.slug_id = null
            this.associateForm.dialog_id = null
            this.associateForm.page_id = null

            this.$emit("change-associate", this.associateForm)
        },
        loadScriptsByCommand(){

        },
        storePageToCommand(){

        },
        loadPagesByCommand(){

        },
        storeDialogToCommand(){

        },
        loadDialogsByCommand(){

        }
    }
}
</script>

<script setup>

import GlobalSlugList from "@/AdminPanel/Components/Constructor/Slugs/GlobalSlugList.vue";

import Slug from '@/AdminPanel/Components/Constructor/Slugs/Slug.vue'
</script>
<template>
    <div
        v-if="bot"
        class="row">

        <div class="col-12 mb-2">
            <button type="button"
                    @click="show=!show"
                    class="btn btn-outline-success p-3 w-100">
                <span v-if="!show"><i class="fa-solid fa-scroll"></i> Добавить глобальный скрипт</span>
                <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть форму добавления</span>
            </button>
        </div>
        <div class="col-12" v-if="show">
            <GlobalSlugList :can-add="true"
                            v-if="bot"
                            :bot="bot"
                            v-on:callback="loadSlugs"/>
        </div>

        <div class="col-12">
            <div class="form-floating mb-3">
                <input type="search"
                       v-model="ownSearch"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Быстрый поиск команды</label>
            </div>
        </div>

        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_global"
                       type="checkbox"
                       id="need_global">
                <label class="form-check-label" for="need_global">
                    <span v-if="need_global">Глобальные</span>
                    <span v-else>Локальные</span>
                </label>
            </div>

        </div>

        <div class="mb-3 col-12 col-lg-4 col-md-6 col-sm-12"
             v-if="slugs&&bot"
             v-for="(slug, index) in filteredSlugs">
            <Slug :item="slug"
                  :bot="bot"
                  v-on:callback="callbackSlugs"
                  v-on:select="selectSlug"/>
        </div>

        <div class="mb-3 col-md-12" v-if="filteredSlugs.length===0">

                <div class="alert alert-danger" role="alert">
                    У вас еще нет добавленных скриптов!
                </div>

        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: [ "command"],
    data() {
        return {
            bot: null,
            need_global:true,
            show: false,
            slugs:[],
            ownSearch: null,
            slugForm: {
                command: null,
                comment: null,
                slug: null,

            }
        }
    },
    watch:{
      'need_global':function (oldV, newV){
          this.loadSlugs()

      }
    },
    computed: {

        ...mapGetters(['getCurrentBot']),

        filteredSlugs() {
            if (this.slugs.length === 0)
                return [];

            if (this.ownSearch == null)
                return this.slugs

            return this.slugs.filter(item => {
                let slug = item.slug || ''
                let command = item.command || ''
                let comment = item.comment || ''

                return command.toLowerCase().indexOf(this.ownSearch.toLowerCase()) !== -1 ||
                    comment.toLowerCase().indexOf(this.ownSearch.toLowerCase()) !== -1 ||
                    slug.toLowerCase().indexOf(this.ownSearch.toLowerCase()) !== -1
            })

        }

    },
    mounted() {
        this.loadCurrentBot().then(() => {
            this.loadSlugs();

            if (this.command) {
                this.$nextTick(() => {
                    this.slugForm.command = this.command
                })
            }
        })

    },
    methods: {
        loadSlugs() {
            this.$store.dispatch("loadBotSlugs", {
                botId: this.bot.id,
                isGlobal: this.need_global
            }).then((resp) => {
                this.slugs = resp
            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        callbackSlugs(){
            this.loadSlugs()
        },
        selectSlug(item) {
            this.$emit("select", item)
        },

        loadAllSlugs() {
            this.$store.dispatch("loadAllSlugs").then(resp => {
                this.allSlugs = resp.data
            })
        },
        addSlug(slug) {

        }
    }
}
</script>

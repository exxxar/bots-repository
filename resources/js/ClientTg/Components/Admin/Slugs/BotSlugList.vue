<script setup>

import Pagination from '@/ClientTg/Components/Pagination.vue';


import GlobalSlugList from "@/ClientTg/Components/Admin/Slugs/GlobalSlugList.vue";

import Slug from '@/ClientTg/Components/Admin/Slugs/Slug.vue'
</script>
<template>

    <button type="button"
            @click="show=!show"
            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
        <span v-if="!show"><i class="fa-solid fa-scroll"></i> Добавить глобальный скрипт</span>
        <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть форму добавления</span>
    </button>

    <div class="mb-2" v-if="show">
        <GlobalSlugList :can-add="true"

                        v-if="bot"
                        :bot="bot"
                        v-on:callback="loadSlugs"/>
    </div>

    <div class="form-floating mb-3">
        <label for="floatingInput">Быстрый поиск команды</label>
        <input type="search"
               v-model="ownSearch"
               class="form-control" id="floatingInput" placeholder="Название команды">

    </div>

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


    <div class="mb-1 "
         v-if="slugs&&bot"
         v-for="(slug, index) in filteredSlugs">
        <Slug :item="slug"
              :bot="bot"
              v-on:callback="callbackSlugs"
              v-on:select="selectSlug"/>
    </div>

    <div class="mb-3">
        <Pagination
            :simple="true"
            v-on:pagination_page="nextSlugs"
            v-if="paginate"
            :pagination="paginate"/>
    </div>

    <div class="mb-3" v-if="filteredSlugs.length===0">

        <div class="alert alert-danger" role="alert">
            У Вас еще нет добавленных скриптов!
        </div>

    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: [ "command","bot"],
    data() {
        return {

            need_global:true,
            show: false,
            slugs:[],
            paginate:null,
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

        ...mapGetters([ 'getSlugs', 'getSlugsPaginateObject']),

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
        this.loadSlugs();

        if (this.command) {
            this.$nextTick(() => {
                this.slugForm.command = this.command
            })
        }
    },
    methods: {
        nextSlugs(index) {
            this.loadSlugs(index)
        },
        loadSlugs(index) {
            this.$store.dispatch("loadSlugs", {
                dataObject:{
                    needGlobal: this.need_global
                },
                page: index
            }).then((resp) => {
                this.slugs = this.getSlugs
                this.paginate = this.getSlugsPaginateObject


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

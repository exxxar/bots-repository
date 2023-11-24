<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>


    <div class="list-group list-custom-large d-flex flex-wrap" v-if="filteredAllSlugs.length>0">

        <div v-for="(item, index) in filteredAllSlugs" class="w-100">
            <a href="javascript:void(0)"
               v-if="item"
               @click="selectSlug(item)" class="d-block w-100" style="min-height: 70px;">
                <i class=" font-14 fa-solid fa-scroll rounded-xl shadow-xl bg-blue2-dark"></i>
                <span style="line-height: 100%;padding-top:10px;    padding-right: 51px;">{{item.command || 'Не указана'}}</span>
                <strong>{{ item.slug }}</strong>
                <span class="badge bg-red2-dark font-8">#{{item.id}}</span>
                <i class="fa-solid fa-scroll"></i>
            </a>

            <form v-on:submit.prevent="addSlug"
                  v-if="slugForm.id==item.id&&filteredAllSlugs.length>0"
                  class="mb-0 d-flex flex-wrap">


                    <div class="mb-3">
                        <div class="border-green1-dark pl-3 border-left border-top-0 border-bottom-0 border-right-0 my-3">
                            <p>
                                Вы можете добавить данный скрипт изменив сразу название команды на нужное непосредственно ВАМ!
                                Меняется только название.
                            </p>
                        </div>

                        <input type="text" class="form-control w-100"
                               placeholder="Команда"
                               aria-label="Команда"
                               v-model="slugForm.command"
                               maxlength="255"
                               aria-describedby="bot-domain" required>
                    </div>


                    <button
                        :disabled="slugForm.slug==null"
                        class="btn btn-m btn-full mb-0 rounded-xs text-uppercase font-900 shadow-s bg-green1-dark w-100 p-3">Добавить скрипт в бота
                    </button>




            </form>
        </div>



    </div>


    <Pagination

        v-on:pagination_page="nextSlugs"
        v-if="slugs_paginate_object"
        :pagination="slugs_paginate_object"/>

    <div class="mb-2" v-else>

        <div class="alert alert-warning" role="alert">
            К сожалению еще нет глобальных скриптов
        </div>

    </div>


</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["canAdd", "bot"],
    data() {
        return {
            search: null,
            slugs: [],
            slugs_paginate_object: null,
            slugForm: {
                command: null,
                comment: null,
                slug: null,
                config: [],
                is_global: true,
                bot_id: null,
            }
        }
    },
    computed: {
        ...mapGetters(['getGlobalSlugs', 'getGlobalSlugsPaginateObject']),
        filteredAllSlugs() {
            if (this.slugs.length === 0)
                return [];

            if (this.search == null)
                return this.slugs

            return this.slugs.filter(item => {
                let slug = item.slug || ''
                let command = item.command || ''
                let comment = item.comment || ''

                return command.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    comment.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    slug.toLowerCase().indexOf(this.search.toLowerCase()) !== -1
            })

        },
    },
    mounted() {
        this.loadAllSlugs()
    },
    methods: {
        loadAllSlugs(page = 0) {
            this.$store.dispatch("loadGlobalSlugs", {
                dataObject:{
                    search: this.search || null,
                    needGlobal: true,
                },
                page: page
            }).then(resp => {
                this.slugs = this.getGlobalSlugs
                this.slugs_paginate_object = this.getGlobalSlugsPaginateObject
            })
        },
        removeSlug(item) {
            this.$store.dispatch("removeSlug", {
                dataObject: {
                    slugId: item.id
                }
            }).then((response) => {

                this.$botNotification.notification(
                    "Команды",
                    "Команда успешно удалена",
                );


                this.loadAllSlugs()

            }).catch(err => {
                this.loadAllSlugs()
            })
        },
        nextSlugs(index) {
            this.loadAllSlugs(index)
        },
        selectSlug(item) {
            this.slugForm.id = item.id || null
            this.slugForm.slug = item.slug
            this.slugForm.comment = item.comment
            this.slugForm.command = this.command || item.command
            this.slugForm.config = item.config || []
            this.slugForm.is_global = item.is_global || false

            this.$emit("select", item)


        },
        addSlug() {


            let data = new FormData();
            Object.keys(this.slugForm)
                .forEach(key => {
                    const item = this.slugForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.bot)
                data.append("bot_id", this.bot.id)


            this.$store.dispatch("createSlug",
                {
                    slugForm: data
                }
            ).then((response) => {

                this.$botNotification.notification(
                     "Конструктор команд",
                     "Команда успешно обновлена",
                );


                this.slugForm.id = null
                this.slugForm.command = null
                this.slugForm.comment = null
                this.slugForm.bot_id = null
                this.slugForm.slug = null
                this.slugForm.config = []
                this.slugForm.is_global = true

                this.$emit("callback")
            }).catch(err => {

            })


        }
    }
}
</script>

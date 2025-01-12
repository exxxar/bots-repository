<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
</script>
<template>


    <ul class="list-group " v-if="filteredAllSlugs.length>0">

        <li v-for="(item, index) in filteredAllSlugs" class="w-100 list-group-item">
            <p v-if="item"
               @click="selectSlug(item)" class="mb-2">
                <i class="fa-solid fa-scroll text-primary"></i>
                <span >{{item.command || 'Не указана'}}</span>
            </p>

            <form v-on:submit.prevent="addSlug"
                  v-if="slugForm.id==item.id&&filteredAllSlugs.length>0"
                  class="mb-0 d-flex flex-wrap">


                    <div class="mb-2">
                        <div class="alert alert-light mb-2">
                            Вы можете добавить данный скрипт изменив сразу название команды на нужное непосредственно ВАМ!
                            Меняется только название.
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control"
                                   placeholder="Команда"
                                   aria-label="Команда"
                                   v-model="slugForm.command"
                                   maxlength="255"
                                   aria-describedby="bot-domain" required>
                            <label for="">Команда</label>
                        </div>

                    </div>


                    <button
                        :disabled="slugForm.slug==null"
                        class="btn btn-primary w-100 p-3">Добавить скрипт в бота
                    </button>




            </form>
        </li>



    </ul>


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

                this.$notify({
                    title:'Конструктор команд',
                    text: "Команда успешно удалена!",
                    type:'success'
                })

                this.loadAllSlugs()

            }).catch(err => {
                this.loadAllSlugs()
            })
        },
        nextSlugs(index) {
            this.loadAllSlugs(index)
        },
        selectSlug(item) {

            if ( this.slugForm.id === item.id)
            {
                this.slugForm.id = null
                this.slugForm.slug = null
                this.slugForm.comment = null
                this.slugForm.command = null
                this.slugForm.config = []
                this.slugForm.is_global = false
                return
            }

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

                this.$notify({
                    title:'Конструктор команд',
                    text: "Команда успешно обновлена!",
                    type:'success'
                })

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

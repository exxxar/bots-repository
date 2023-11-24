<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>


    <div class="row" v-if="filteredAllSlugs.length>0">
        <div class="col-12 mb-3">
            <ul class="list-group w-100">

                <li class="list-group-item cursor-pointer btn mb-1 d-flex  align-items-center justify-between"
                    v-bind:class="{'btn-outline-info':item.deleted_at==null,'btn-outline-danger border-danger':item.deleted_at!=null}"
                    v-for="(item, index) in filteredAllSlugs"
                >
                    <strong
                        @click="selectSlug(item)"
                        style="word-wrap: break-word;"><i
                        v-bind:class="{'text-danger':item.deleted_at!=null}"
                        class="fa-solid fa-scroll"></i>
                        {{ item.command }} (<span>{{ item.slug }}</span>)
                    </strong>

                    <span class="badge bg-info"
                          v-if="item.comment">{{ item.comment || 'Пояснение не указано' }}
                    </span>


                    <button class="btn btn-outline-danger"
                            @click="removeSlug(item)" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>


                </li>
            </ul>

        </div>

        <div class="col-12">
            <Pagination

                v-on:pagination_page="nextSlugs"
                v-if="slugs_paginate_object"
                :pagination="slugs_paginate_object"/>
        </div>

    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                К сожалению еще нет глобальных скриптов
            </div>
        </div>
    </div>


    <p v-else>Глобальных скриптов не обноружено</p>
    <form v-on:submit.prevent="addSlug"
          v-if="canAdd&&filteredAllSlugs.length>0"
          class="card mb-3">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label" id="bot-domain">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Измените только текст команды,<br>
                                если хотите чтоб скрипт вызывался по кнопке из меню. <br>
                                Или оставьте как есть. <br>
                                Текст скрипта нужно также указать в качестве пункта меню.
                            </div>
                        </template>
                    </Popper>
                    Команда
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="text" class="form-control"
                       placeholder="Команда"
                       aria-label="Команда"
                       v-model="slugForm.command"
                       maxlength="255"
                       aria-describedby="bot-domain" required>
            </div>


            <button
                :disabled="slugForm.slug==null"
                class="btn btn-outline-success mt-2 mb-2 w-100 p-3">Добавить скрипт в бота
            </button>

        </div>


    </form>

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
                config:[],
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
                dataObject: {
                    needGlobal: true,
                   /// botId: this.bot.id
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
                    title: "Конструктор команд",
                    text: "Команда успешно удалена",
                    type: 'success'
                });
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
            this.slugForm.config =  item.config || []
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
                    title: "Конструктор команд",
                    text: "Команда успешно обновлена",
                    type: 'success'
                });


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

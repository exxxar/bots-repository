<script setup>
import BotDialogGroupListSimple from "@/Components/Constructor/Dialogs/BotDialogGroupListSimple.vue";
import Slug from '@/Components/Constructor/Slugs/Slug.vue'
</script>
<template>
    <div
        v-if="bot"
        class="row">
        <div class="col-12 mb-2">
            <div class="alert alert-warning" role="alert">
                Если вы боитесь последствий модификации команды, то продублируйте нужную и внесите коррективы!
                Работать будут обе команды как оригинал, так и дубль!
            </div>
        </div>

        <div class="col-12 mb-2">
            <button type="button"
                    @click="show=!show"
                    class="btn btn-outline-success p-3 w-100">
                <span v-if="!show"><i class="fa-solid fa-scroll"></i> Добавить глобальный скрипт</span>
                <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть форму добавления</span>
            </button>
        </div>
        <div class="col-12" v-if="show">
            <form v-on:submit.prevent="addSlug"
                  v-if="filteredAllSlugs.length>0"
                  class="card mb-3">

                <div class="card-body">
                    <h6>Добавление нового скрипта в бота</h6>
                    <div>
                        <input type="text"
                               class="form-control mt-1 mb-1"
                               v-model="search"
                               placeholder="Поиск нужного скрипта по описанию">
                    </div>
                    <label class="form-label" id="bot-level-2">
                        Выберите скрипт
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <ul class="list-group" style="overflow-y: auto; height: 300px; padding:10px;">
                        <li class="list-group-item cursor-pointer"

                            v-bind:class="{'active':slugForm.slug === item.slug}"
                            @click="selectSlug(item)"
                            v-for="(item, index) in filteredAllSlugs">
                            <p> {{ item.command }} (<strong>{{ item.slug }}</strong>)</p>
                            <p v-if="item.page"><span class="badge bg-success">Привязано к странице</span></p>
                            <p>{{ item.comment || 'Пояснение не указано' }}</p>
                        </li>


                    </ul>

                    <div class="row">
                        <div class="col-md-12 col-12">
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

                        </div>


                    </div>
                    <button
                        class="btn btn-outline-success mt-2 mb-2 w-100">Добавить скрипт в бота
                    </button>
                </div>

            </form>
            <p v-else>Глобальных скриптов не обноружено</p>
        </div>

        <div class="col-12">
            <div class="form-floating mb-3">
                <input type="search"
                       v-model="ownSearch"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Быстрый поиск команды</label>
            </div>
        </div>
        <div class="mb-3 col-12 col-md-4"
             v-if="slugs&&bot"
             v-for="(slug, index) in filteredSlugs">
            <Slug :item="slug"
                  :bot="bot"
                  v-on:callback="callbackSlugs"
                  v-on:select="selectSlug"/>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["slugs", "command"],
    data() {
        return {
            bot: null,
            show: false,
            search: null,
            ownSearch: null,
            allSlugs: [],
            slugForm: {
                command: null,
                comment: null,
                slug: null,

            }
        }
    },
    computed: {

        ...mapGetters(['getCurrentBot']),

        filteredAllSlugs() {
            if (this.allSlugs.length === 0)
                return [];

            if (this.search == null)
                return this.allSlugs

            return this.allSlugs.filter(item => {
                let slug = item.slug || ''
                let command = item.command || ''
                let comment = item.comment || ''

                return command.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    comment.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    slug.toLowerCase().indexOf(this.search.toLowerCase()) !== -1
            })

        },
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
            this.loadAllSlugs()

            if (this.command) {
                this.$nextTick(() => {
                    this.slugForm.command = this.command
                })
            }
        })

    },
    methods: {
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        callbackSlugs(){
          this.$emit("callback")
        },
        selectSlug(item) {
            this.slugForm.id = item.id || null
            this.slugForm.slug = item.slug
            this.slugForm.comment = item.comment
            this.slugForm.command = this.command || item.command
            this.slugForm.bot_dialog_command_id = item.bot_dialog_command_id
        },

        loadAllSlugs() {
            this.$store.dispatch("loadAllSlugs").then(resp => {
                console.log(resp)
                this.allSlugs = resp.data
            })
        },
        addSlug() {
            const slug = this.slugForm

            this.$emit("add", slug)

            this.slugForm.slug = null
            this.slugForm.comment = null
            this.slugForm.command = null
            this.slugForm.bot_dialog_command_id = null
        }
    }
}
</script>

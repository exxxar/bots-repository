<script setup>
import BotDialogGroupListSimple from "@/Components/Constructor/Dialogs/BotDialogGroupListSimple.vue";
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
                <span v-if="!show"><i class="fa-solid fa-scroll"></i> Добавить новый скрипт</span>
                <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть форму добавления</span>
            </button>
        </div>
        <div class="col-12" v-if="show">
            <form v-on:submit.prevent="addSlug"
                  class="card mb-3"
                  v-if="allSlugs.length>0">

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
        </div>

        <div class="col-12 mb-3">
            <input type="checkbox"
                   v-model="simple"
                   class="btn-check" id="btn-check-2" autocomplete="off">
            <label class="btn"
                   v-bind:class="{'btn-outline-primary':!simple,'btn-primary':simple}"
                   for="btn-check-2">

                <span v-if="simple">Расширенный вид</span>
                <span v-else>Упрощенный вид</span>
            </label>
        </div>

        <div class="mb-3"
             v-bind:class="{'col-12':!simple,'col-md-6':simple}"
             v-if="slugs"
             v-for="(slug, index) in slugs">
            <div class="card" @click="selectSlug(slug)">
                <div class="card-body">
                    <div class="row">
                        <div
                            v-if="!simple"
                            class="col-12">
                            <button
                                @click="duplicateSlug(index)"
                                type="button"
                                class="btn btn-outline-success mr-2"
                            >
                                Дублировать
                            </button>
                            <button
                                @click="removeSlug(index)"
                                type="button"
                                class="btn btn-outline-danger"
                            >
                                Удалить
                            </button>

                            <button
                                type="button"
                                data-bs-toggle="modal" data-bs-target="#attach-command"
                                class="btn btn-outline-primary ml-2">Привязать диалог
                            </button>

                        </div>
                        <div
                            v-bind:class="{'col-md-6':!simple}"
                            class="col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Команда <span
                                    v-if="slugs[index].page"
                                    class="badge bg-success">Привязано к странице</span></label>
                                <input type="text" class="form-control"
                                       placeholder="Команда"
                                       aria-label="Команда"
                                       v-model="slugs[index].command"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>

                        </div>

                        <div
                            v-if="!simple"
                            class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Мнемоническое имя</label>
                                <input type="text" class="form-control"
                                       :disabled="true"
                                       placeholder="Мнемоническое имя"
                                       aria-label="Мнемоническое имя"
                                       v-model="slugs[index].slug"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>
                        </div>
                        <div
                            v-if="!simple"
                            class="col-12">
                            <p v-if="slugs[index].bot_dialog_command_id">Данная команда начинает диалог
                                #{{ slugs[index].bot_dialog_command_id }}</p>
                        </div>
                        <div
                            v-if="!simple"
                            class="col-12">
                            <p>{{ slugs[index].comment || 'Пояснение не указано' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="attach-command" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Привязываем старт диалога к команде</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogGroupListSimple
                        v-if="bot"
                        v-on:select-dialog="selectDialog"
                        :bot-id="bot.id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["slugs", "command"],
    data() {
        return {
            bot:null,
            show: false,
            simple: true,
            search: null,
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
        selectDialog(command) {

            this.$store.dispatch("attachDialogCommandToSlug", {
                dataObject: {
                    dialogCommandId: command.id,
                    slugId: this.slugForm.id
                }
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно связана!",
                    type: 'success'
                });

                this.slugs.forEach(item => {
                    if (item.id === this.slugForm.id)
                        item.bot_dialog_command_id = command.id
                })

                this.slugForm = {
                    id: null,
                    command: null,
                    comment: null,
                    slug: null,
                    bot_dialog_command_id: null
                }

            }).catch(err => {

            })

        },
        selectSlug(item) {
            this.slugForm.id = item.id || null
            this.slugForm.slug = item.slug
            this.slugForm.comment = item.comment
            this.slugForm.command = this.command || item.command
            this.slugForm.bot_dialog_command_id = item.bot_dialog_command_id
        },
        duplicateSlug(index) {
            this.$emit("duplicate", index)
        },
        removeSlug(index) {
            this.$emit("remove", index)
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

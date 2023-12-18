<script setup>
import SlugForm from "@/AdminPanel/Components/Constructor/Slugs/SlugForm.vue";
import GlobalSlugList from "@/AdminPanel/Components/Constructor/Slugs/GlobalSlugList.vue";
import Pagination from '@/AdminPanel/Components/Pagination.vue';
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

        <div class="col-12 mb-2 ">
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

            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_show_deleted"
                       type="checkbox"
                       id="need_show_deleted">
                <label class="form-check-label" for="need_show_deleted">
                    Удаленные
                </label>
            </div>
        </div>

        <div class="mb-3 col-12 col-sm-12"
             v-if="slugs&&bot">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Команда</th>
                    <th scope="col">Пояснение</th>
                    <th scope="col">Глобальный</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr

                    v-bind:class="{'border-info':item.deleted_at==null,'border-danger':item.deleted_at!=null}"
                    v-for="(item, index) in slugs">
                    <th scope="row" v-bind:class="{'text-danger':item.deleted_at!=null}">
                        {{ item.id || 'Нет идентификатора' }}
                    </th>
                    <td
                        @click="selectSlug(item)"
                        data-bs-toggle="modal"
                        data-bs-target="#edit-table-slug"
                        v-bind:class="{'text-danger':item.deleted_at!=null}"> {{ item.command || 'Нет команды' }}
                    </td>
                    <td v-bind:class="{'text-danger':item.deleted_at!=null}"> {{
                            item.comment || 'Пояснение не указано'
                        }}
                    </td>
                    <td>
                        <span
                            v-if="item.is_global"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="canSelect"><a
                                    @click="selectSlug(item)"
                                    title="Выбрать команду"
                                    class="dropdown-item cursor-pointer"><i class="fa-solid fa-arrow-left mr-1"></i>
                                    Выбрать команду
                                </a></li>
                                <hr v-if="canSelect">
                                <li @click="selectSlug(item)"><a class="dropdown-item cursor-pointer"
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#edit-table-slug"> <i
                                    class="fa-regular fa-pen-to-square mr-1"></i>
                                    Редактировать</a></li>
                                <li><a class="dropdown-item cursor-pointer" @click="duplicateSlug(item)"><i
                                    class="fa-solid fa-clone mr-1"></i> Дублировать</a></li>
                                <li><a class="dropdown-item cursor-pointer"
                                       target="_blank"
                                       :href="'/admin/slugs/action-data-export/'+item.id">
                                    <i class="fa-regular fa-file-excel mr-1"></i> Скачать данные участников</a></li>
                                <li v-if="item.deleted_at==null"><a class="dropdown-item cursor-pointer"
                                                                    @click="removeSlug(item)"> <i
                                    class="fa-regular fa-trash-can text-danger mr-1"></i> Удалить</a></li>
                                <li v-if="item.deleted_at!=null"><a class="dropdown-item cursor-pointer"
                                                                    @click="restoreSlug(item)"> <i
                                    class="fa-regular fa-trash-can text-danger mr-1"></i> Восстановить</a></li>

                            </ul>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>


        <Pagination
            v-on:pagination_page="nextSlugs"
            v-if="paginate"
            :pagination="paginate"/>
        <div class="mb-3 col-md-12" v-if="slugs.length===0">

            <div class="alert alert-danger" role="alert">
                У Вас еще нет добавленных скриптов!
            </div>

        </div>
    </div>

    <div class="modal fade" id="edit-table-slug" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" v-if="selectedSlug&&!load">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Редактирование команды #<span>{{
                            selectedSlug.id
                        }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <SlugForm
                                :item="selectedSlug"
                                v-on:callback="slugFormCallback"
                            />
                        </div>
                        <div class="col-4">
                            <ol class="list-group list-group-numbered">
                                <li
                                    v-if="selectedSlug.config"
                                    class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Набор параметров скрипта</div>
                                        <p v-if="(selectedSlug.config||[]).length>0"
                                           v-for="param in selectedSlug.config">
                                            <strong>{{ param.key || 'Ключ не найден' }}:</strong>{{
                                                param.value || 'Не указано'
                                            }}
                                        </p>
                                        <p v-else>Отсутствует</p>
                                    </div>
                                </li>
                                <li
                                    v-if="selectedSlug.page"
                                    class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Привязана страница</div>
                                    </div>

                                </li>
                                <li
                                    v-if="selectedSlug.is_global"
                                    class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">Является глобальным скриптом</div>
                                    </div>

                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto w-100">
                                        <form v-on:submit.prevent="submitRelocateData">
                                            <div class="fw-bold">Перенести данные из:</div>
                                            <select class="form-control w-100" v-model="relocated_slug_id" required>
                                                <option :value="null">Не выбрано</option>
                                                <option :value="slug.id" v-for="slug in slugs">#{{ slug.id }}
                                                    {{ slug.slug || 'Не указан' }}
                                                </option>
                                            </select>
                                            <p class="my-2"><em>Перенос данных затирает текущие данные в данном
                                                скрипте</em></p>
                                            <button
                                                :disabled="relocated_slug_id == null"
                                                class="btn btn-outline-warning w-100">
                                                Выполнить перенос
                                            </button>
                                        </form>

                                    </div>

                                </li>
                            </ol>
                        </div>
                    </div>

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
    props: ["command", "canSelect"],
    data() {
        return {
            bot: null,
            relocated_slug_id: null,
            need_global: true,
            need_show_deleted: true,
            show: false,
            load: false,
            slugs: [],
            paginate: [],
            selectedSlug: null,
            ownSearch: null,
            slugForm: {
                command: null,
                comment: null,
                slug: null,

            }
        }
    },
    watch: {
        'ownSearch': function (oldV, newV) {
            this.loadSlugs()
        },
        'need_global': function (oldV, newV) {
            this.loadSlugs()

        },
        'need_show_deleted': function (oldV, newV) {
            this.loadSlugs()

        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getSlugs', 'getSlugsPaginateObject']),
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
        nextSlugs(index) {
            this.loadSlugs(index)
        },
        loadSlugs(page = 0) {
            return this.$store.dispatch("loadSlugs", {
                dataObject: {
                    botId: this.bot.id,
                    needGlobal: this.need_global,
                    needDeleted: this.need_show_deleted,
                    search: this.ownSearch
                },
                page: page
            }).then((resp) => {
                this.slugs = this.getSlugs
                this.paginate = this.getSlugsPaginateObject


            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        callbackSlugs() {
            this.loadSlugs()
        },
        selectSlug(item) {

            this.load = true
            this.$nextTick(() => {
                this.selectedSlug = item
                this.load = false
            })

            this.$emit("select", item)
        },


        selectCard() {
            this.$emit("select", this.item)
        },
        slugFormCallback() {
            this.load = true
            const tmpSelectSlug = this.selectedSlug
            this.loadSlugs().then(() => {
                let slug = this.slugs.find(item => item.id === tmpSelectSlug.id)
                this.selectSlug(slug ? slug : null)
            })

            this.$emit("callback")
            this.$nextTick(() => {

                this.load = false
            })
        },

        editSlug() {
            this.load = true
            this.$nextTick(() => {
                this.load = false
            })
        },

        duplicateSlug(item) {
            this.load = true
            // this.$emit("duplicate", index)
            this.$store.dispatch("duplicateSlug", {
                dataObject: {
                    slugId: item.id
                }
            }).then((response) => {


                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно продублирована",
                    type: 'success'
                });

                this.$emit("callback")

                this.$nextTick(() => {
                    this.load = false
                })

            }).catch(err => {
                this.$emit("callback")

                this.$nextTick(() => {
                    this.load = false
                })
            })
            this.loadSlugs()
            this.$emit("callback")

            this.$nextTick(() => {
                this.load = false
            })
        },
        submitRelocateData() {
            this.$store.dispatch("relocateSlugActionData", {
                slug_sender_id: this.relocated_slug_id,
                slug_recipient_id: this.item.id,
                bot_id: this.bot.id
            }).then((response) => {
                this.$notify({
                    title: "Конструктор команд",
                    text: "Данные команды успешно перенесены",
                    type: 'success'
                });

            }).catch(err => {
                this.$notify({
                    title: "Конструктор команд",
                    text: "Ошибка переноса данных",
                    type: 'error'
                });
            })

        },
        restoreSlug(item) {
            this.load = true
            this.$store.dispatch("restoreSlug", {
                dataObject: {
                    slugId: item.id
                }
            }).then((response) => {
                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно восстановлена",
                    type: 'success'
                });
                this.loadSlugs()

                this.$nextTick(() => {
                    this.load = false
                })
            }).catch(err => {
                this.$emit("callback")

                this.$nextTick(() => {
                    this.load = false
                })
            })
        },
        removeSlug(item) {

            this.load = true
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
                this.loadSlugs()

            }).catch(err => {
                this.$emit("callback")

                this.$nextTick(() => {
                    this.load = false
                })
            })
        },


    }
}
</script>

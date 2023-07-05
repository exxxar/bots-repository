<script setup>
import SlugForm from '@/Components/Constructor/Slugs/SlugForm.vue'
</script>
<template>
    <div class="card"
         v-bind:class="{'deprecated-slug':item.deprecated_at!=null,'global-slug':item.is_global}">
        <div class="card-header d-flex justify-content-between">


            <div class="dropdown">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a
                        @click="selectSlug"
                        title="Выбрать команду"
                        class="dropdown-item cursor-pointer"><i class="fa-solid fa-arrow-left mr-1"></i> Выбрать команду </a></li>
                    <hr>
                    <li><a class="dropdown-item cursor-pointer" data-bs-toggle="modal"
                           :data-bs-target="'#edit-slug-'+item.id"> <i class="fa-regular fa-pen-to-square mr-1"></i>
                        Редактировать</a></li>
                    <li><a class="dropdown-item cursor-pointer" @click="duplicateSlug"><i
                        class="fa-solid fa-clone mr-1"></i> Дублировать</a></li>
                    <li><a class="dropdown-item cursor-pointer" @click="removeSlug"> <i
                        class="fa-regular fa-trash-can text-danger mr-1"></i> Удалить</a></li>
                    <li><a class="dropdown-item cursor-pointer" data-bs-toggle="modal"
                           :data-bs-target="'#attach-command-'+item.id"> <i class="fa-solid fa-link mr-1"></i> Связать с
                        командой</a></li>
                </ul>
            </div>


            <button
                type="button"
                @click="simple=!simple"
                class="btn btn-outline-primary ml-2"

            >
                <i v-if="simple" class="fa-solid fa-arrow-up-short-wide"></i>
                <i v-else class="fa-solid fa-arrow-down-short-wide"></i>
            </button>


        </div>
        <div class="card-body">
            <div class="row">

                <div

                    class="col-12">
                    <p v-if="simple"> {{ item.command || 'Нет команды' }}</p>
                    <ol v-else class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Идентификатор</div>
                                №{{ item.id || 'Нет идентификатора' }}
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Команда</div>
                                {{ item.command || 'Нет команды' }}
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Мнемоническое имя</div>
                                {{ item.slug || 'Slug' }}
                            </div>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Пояснение</div>
                                {{ item.comment || 'Пояснение не указано' }}
                            </div>

                        </li>
                        <li
                            v-if="item.config"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Набор параметров скрипта</div>
                                <p v-if="item.config" v-for="param in item.config"><strong>{{param.key || 'Ключ не найден'}}:</strong>{{param.value||'Не указано'}}</p>
                            </div>
                        </li>
                        <li
                            v-if="item.page"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Привязана страница</div>
                            </div>

                        </li>
                        <li
                            v-if="item.is_global"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Глобальный скрипт</div>
                            </div>

                        </li>
                    </ol>


                </div>

            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'attach-command-'+item.id" tabindex="-1">
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
                        :bot="bot"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" :id="'edit-slug-'+item.id" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Редактирование команды #<span>{{ item.id }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <SlugForm :item="item"
                              v-on:callback="slugFormCallback"
                    />
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
    props: ["item", "bot", "selectMode"],
    data() {
        return {
            simple: true,
        }
    },

    mounted() {

    },

    methods: {
        selectCard() {
            this.$emit("select", this.item)
        },
        slugFormCallback() {
            this.$emit("callback")
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


                this.slugForm = {
                    id: null,
                    command: null,
                    comment: null,
                    slug: null,

                }

            }).catch(err => {

            })

        },
        selectSlug() {
            this.$emit("select", this.item)
        },
        duplicateSlug() {
            // this.$emit("duplicate", index)
            this.$store.dispatch("duplicateSlug", {
                dataObject: {
                    slugId: this.item.id
                }
            }).then((response) => {

                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно продублирована",
                    type: 'success'
                });

            }).catch(err => {

            })

            this.$emit("callback")
        },
        removeSlug() {
            this.$store.dispatch("removeSlug", {
                dataObject: {
                    slugId: this.item.id
                }
            }).then((response) => {
                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно удалена",
                    type: 'success'
                });
                this.$emit("callback")

            }).catch(err => {
                this.$emit("callback")
            })
        },


    }
}
</script>

<style lang="scss">
.deprecated-slug {
    border: 1px red solid !important;
}

.global-slug {
    border: 1px green solid !important;
}
</style>

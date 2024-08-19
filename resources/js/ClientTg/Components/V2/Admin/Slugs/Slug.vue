<script setup>
import SlugForm from '@/ClientTg/Components/V2/Admin/Slugs/SlugForm.vue'

</script>
<template>
    <div class="alert alert-light w-100 mb-1" type="button">
        <p @click="toggleMenu" class="w-100 mb-0">
            <i v-if="showMenu" class="fa-solid fa-chevron-up"></i>
            <i v-else class="fa-solid fa-chevron-down"></i>
            {{ item.command || 'Нет команды' }}
        </p>

        <template v-if="showMenu">
                <a
                    data-bs-toggle="modal" :data-bs-target="'#slug-modal-editor-'+item.id"
                    href="javascript:void(0)"
                    title="Редактировать команду"
                    style="width:auto;"
                    class="btn btn-outline-light text-primary mr-2"

                >
                    <i class="fa-regular fa-pen-to-square" v-if="!edit"></i>
                    <i class="fa-solid fa-xmark" v-else></i>
                </a>

                <a href="javascript:void(0)"
                   @click="switchSimple"
                   class="btn btn-outline-light text-primary mr-2"

                >
                    <i v-if="simple" class="fa-solid fa-arrow-up-short-wide"></i>
                    <i v-else class="fa-solid fa-arrow-down-short-wide"></i>
                </a>


                <a
                    href="javascript:void(0)"
                    title="Дублировать команду"
                    class="btn btn-outline-light text-primary mr-2"
                    @click="duplicateSlug">
                    <i class="fa-solid fa-clone"></i>
                </a>

                <a
                    href="javascript:void(0)"
                    title="Дублировать команду"
                    class="btn btn-outline-light text-primary mr-2"
                    @click="removeSlug">
                    <i class="fa-regular fa-trash-can color-white"></i>
                </a>
        </template>

        <ol v-if="!simple" class="list-group my-2 p-0">
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
                    <p v-if="item.config" v-for="param in item.config">
                        <strong>{{
                                param.key || 'Ключ не найден'
                            }}:</strong>{{ param.value || 'Не указано' }}</p>
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

    <div class="modal fade" :id="'slug-modal-editor-'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <SlugForm :item="item"
                              v-if="!load"
                              v-on:callback="slugFormCallback"
                    />
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
            showMenu: false,
            edit: false,
            load: false,
            simple: true,
        }
    },

    mounted() {

    },

    methods: {
        toggleMenu() {
            this.showMenu = !this.showMenu

            this.simple = true
            this.edit = false
        },
        switchEditor() {
            this.simple = true
            this.edit = !this.edit
        },
        switchSimple() {
            this.simple = !this.simple
            this.edit = false
        },
        selectCard() {
            this.$emit("select", this.item)
        },
        slugFormCallback() {
            this.load = true
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
        selectSlug() {
            this.load = true
            this.$emit("select", this.item)
            this.$nextTick(() => {
                this.load = false
            })
        },
        duplicateSlug() {
            this.load = true
            // this.$emit("duplicate", index)
            this.$store.dispatch("duplicateSlug", {
                dataObject: {
                    slugId: this.item.id
                }
            }).then((response) => {


                this.$botNotification.success(
                    "Конструктор команд",
                    "Команда успешно продублирована",
                );

                this.$nextTick(() => {
                    this.load = false
                })

            }).catch(err => {
                this.$emit("callback")

                this.$nextTick(() => {
                    this.load = false
                })
            })

            this.$emit("callback")

            this.$nextTick(() => {
                this.load = false
            })
        },
        removeSlug() {

            this.load = true
            this.$store.dispatch("removeSlug", {
                dataObject: {
                    slugId: this.item.id
                }
            }).then((response) => {

                this.$botNotification.success(
                    "Конструктор команд",
                    "Команда успешно удалена",
                );

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

.gray-slug {
    border: 1px lightgray solid !important;
}
</style>

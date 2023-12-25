<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
import DialogCommandCard from "@/AdminPanel/Components/Constructor/Cards/DialogCommandCard.vue";
import BotDialogCommandForm from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogCommandForm.vue";

</script>
<template>


    <div class="row">
        <div class="col-md-2 col-12">
            <button
                type="button"
                @click="openEditor(null)"
                data-bs-toggle="modal" data-bs-target="#dialog-command-modal-editor"
                class="btn btn-outline-success mb-2 w-100">
                <i class="fa-regular fa-comment-dots" style="margin-right:10px;"></i>Новый диалог
            </button>
        </div>
        <div class="col-md-10 col-12">
            <div class="input-group mb-3">
                <input type="search" class="form-control"
                       placeholder="Поиск диалога"
                       aria-label="Поиск диалога"
                       v-model="search"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary"
                        @click="loadDialogs(0)"
                        type="button"
                        id="button-addon2">Найти
                </button>
            </div>
        </div>
    </div>

    <div class="row" v-if="dialog_commands.length>0">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Текст диалога</th>
                    <th class="text-center" scope="col">Цепочки</th>
                    <th class="text-center" scope="col">Текст успеха</th>
                    <th class="text-center" scope="col">Текст ошибки</th>
                    <th class="text-center" scope="col">Канал результата</th>
                    <th class="text-center" scope="col">Флаги</th>
                    <th class="text-center" scope="col">Сохранить в</th>
                    <th class="text-center" scope="col">Есть изображения</th>
                    <th class="text-center" scope="col">Есть паттерны</th>
                    <th class="text-center" scope="col">Пустой</th>
                    <th class="text-center" scope="col">Есть кнопки</th>
                    <th class="text-center" scope="col">Есть меню</th>
                    <th class="text-center" scope="col">Команды</th>

                </tr>
                </thead>
                <tbody>
                <tr v-for="(command,index) in dialog_commands">
                    <th scope="row">{{ command.id }}</th>
                    <td class="text-center"><a
                        data-bs-toggle="modal"
                        @click="openEditor(command)"
                        data-bs-target="#dialog-command-modal-editor"
                        href="javascript:void(0)">{{ command.pre_text || '-' }}</a></td>
                    <td class="text-center">
                            <span v-if="command.next_bot_dialog_command_id"
                                  class="badge bg-primary">#{{ command.next_bot_dialog_command_id || '-' }}</span>
                        <span v-else>
                              <i class="fa-solid fa-xmark text-danger"></i>
                        </span>
                    </td>
                    <td class="text-center">{{ command.post_text || '-' }}</td>
                    <td class="text-center">{{ command.error_text || '-' }}</td>
                    <td class="text-center">{{ command.result_channel || '-' }}</td>
                    <td class="text-center">
                        <p v-if="command.result_flags.length > 0">
                            <span v-for="flag in command.result_flags">{{ flag || '-' }}</span>
                        </p>

                    </td>
                    <td class="text-center">{{ command.store_to || '-' }}</td>
                    <td class="text-center">
                        <i class="fa-solid fa-check text-success" v-if="command.images.length > 0"></i>
                        <i class="fa-solid fa-xmark text-danger" v-else></i>
                    </td>
                    <td class="text-center">{{ command.input_pattern || '-' }}</td>
                    <td class="text-center">
                        <i class="fa-solid fa-check text-success" v-if="command.is_empty"></i>
                        <i class="fa-solid fa-xmark text-danger" v-else></i>
                    </td>
                    <td class="text-center">
                        <i class="fa-solid fa-check text-success" v-if="command.inline_keyboard_id != null"></i>
                        <i class="fa-solid fa-xmark text-danger" v-else></i>
                    </td>
                    <td class="text-center">
                        <i class="fa-solid fa-check text-success" v-if="command.reply_keyboard_id != null"></i>
                        <i class="fa-solid fa-xmark text-danger" v-else></i>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button
                                :disabled="loading"
                                class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a
                                    @click="selectDialog(command)"
                                    title="Выбрать диалог"
                                    class="dropdown-item cursor-pointer"><i class="fa-solid fa-arrow-left mr-1"></i>
                                    Выбрать диалог </a></li>
                                <hr>

                                <li><a
                                    title="Дублирование команды"
                                    @click="duplicate(command.id)"
                                    class="dropdown-item cursor-pointer"> <i class="fa-solid fa-clone mr-1"></i>
                                    Дублирование диалога </a></li>
                                <li><a
                                    @click="removeCommand(command.id)"
                                    title="Удаление команды"
                                    class="dropdown-item cursor-pointer"> <i class="fa-solid fa-trash-can mr-1"></i>
                                    Удаление диалога </a></li>
                            </ul>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>

            <Pagination
                v-on:pagination_page="nextDialogs"
                v-if="dialog_commands_paginate_object"
                :pagination="dialog_commands_paginate_object"/>
        </div>
    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
               Не найдено ни одного диалога!
            </div>
        </div>
    </div>


    <div class="modal fade" id="dialog-command-modal-editor"
         tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Создание диалогового сообщения
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogCommandForm
                        :item="selected"
                        v-if="bot&&!loading"
                        v-on:callback="loadDialogs"
                        v-on:select-element="selectElement"
                        :bot="bot"/>
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


    data() {
        return {
            bot: null,
            selected: null,
            loading: true,
            dialog_commands: [],
            search: null,
            dialog_commands_paginate_object: null,

        }
    },
    computed: {
        ...mapGetters(['getDialogCommands', 'getCurrentBot', 'getDialogCommandsPaginateObject']),
    },
    mounted() {

        this.loadCurrentBot().then(() => {
            this.loadDialogs();
        })
    },
    methods: {
        openEditor(command) {
            this.loading = true
            this.$nextTick(() => {
                this.selected = command
                this.loading = false
            })
        },
        removeCommand(commandId) {
            this.loading = true

            this.$store.dispatch("removeDialogCommand", {
                dataObject: {
                    dialogCommandId: commandId
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно удалена!",
                    type: 'success'
                });

                this.loadDialogs();

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        duplicate(commandId) {
            this.loading = true

            this.$store.dispatch("duplicateDialogCommand", {
                dataObject: {
                    dialogCommandId: commandId
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно продублирована!",
                    type: 'success'
                });

                this.loadDialogs();

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        selectDialog(command) {
            this.$emit("select-dialog", command)
            this.$notify("Вы выбрали диалог из списка!");
        },
        nextDialogs(index) {
            this.loadDialogs(index)
        },
        selectElement(id){
            this.loading = true
            this.$nextTick(() => {
                this.selected = this.dialog_commands.find(command => command.id === id) || null
                this.loading = false
            })
        },
        loadDialogs(page = 0) {


            this.$store.dispatch("loadDialogCommands", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search
                },
                page: page
            }).then(resp => {
                const tmpSelected = this.selected
                this.dialog_commands = this.getDialogCommands
                this.dialog_commands_paginate_object = this.getDialogCommandsPaginateObject

                this.loading = true
                this.$nextTick(() => {
                    if (tmpSelected)
                        this.selected = this.dialog_commands.find(command => command.id === tmpSelected.id) || null
                    this.loading = false

                })


            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>

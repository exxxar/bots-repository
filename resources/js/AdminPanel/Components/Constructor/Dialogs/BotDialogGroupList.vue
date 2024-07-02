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
                    <th class="text-left" scope="col" style="width:40px;"></th>
                    <th class="text-left" scope="col" style="width:80px;"> #</th>
                    <th class="text-left" scope="col" style="min-width:400px;">Текст диалога</th>

                    <th class="text-center" scope="col" style="width:40px;">Команды</th>

                </tr>
                </thead>
                <tbody>
                <template v-for="(command,index) in dialog_commands">
                    <tr>
                        <td  style="width:40px;">
                          <span
                              @click="toggleEditMode(command)"
                              v-if="!command.in_edit_mode"><i
                              class="fa-solid fa-toggle-off cursor-pointer text-secondary"></i></span>
                            <span
                                @click="toggleEditMode(command)"
                                v-else><i class="fa-solid fa-toggle-on cursor-pointer text-primary"></i></span>
                        </td>
                        <th scope="row" class="text-left" style="width:80px;">
                            <span class="d-flex justify-content-start align-items-center">
                            {{ command.id }}
                            <i class="fa-solid fa-flag-checkered ml-2"
                               v-if="command.is_empty"></i>
                                </span>
                        </th>

                        <td class="text-left" style="min-width:400px;">

                            <p class="mb-0 px-2">Результат будет сохранен в <strong>{{ command.use_result_as || 'не задана' }}</strong></p>

                            <div class="dropdown">
                                <button
                                    style="text-align:left !important;"
                                    class="btn text-decoration-none color-black w-100 d-flex justify-content-between align-items-center"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ command.pre_text || '-' }}
                                </button>
                                <div class="dropdown-menu p-4 text-body-secondary"
                                     style="max-width: 400px; min-width:400px; height:200px; overflow-y:scroll;">
                                    <h6>Основные параметры диалога</h6>
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>Есть ответы</td>
                                            <td>
                                                <p v-if="(command.answers||[]).length>0">
                                                    {{ command.answers.length }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Цепочки</td>
                                            <td>
                                                <span v-if="command.next_bot_dialog_command_id"
                                                      class="badge bg-primary">#{{
                                                        command.next_bot_dialog_command_id || '-'
                                                    }} ({{ command.chain.length || 0 }})</span>
                                                <span v-else>
                                                      <i class="fa-solid fa-xmark text-danger"></i>
                                                </span>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Текст успеха</td>
                                            <td>
                                                <p v-if="command.post_text">{{ command.post_text }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Текст ошибки</td>
                                            <td>
                                                <p v-if="command.error_text">{{ command.error_text }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Канал результата</td>
                                            <td>
                                                <p v-if="command.result_channel">{{ command.result_channel }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Флаги</td>
                                            <td>
                                                <p v-if="command.result_flags.length > 0">
                                                    <span v-for="flag in command.result_flags" class="badge bg-primary">{{
                                                            flag || '-'
                                                        }}</span>
                                                </p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Сохранить в</td>
                                            <td>
                                                <p v-if="command.store_to">{{ command.store_to }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Есть изображения</td>
                                            <td>
                                                <i class="fa-solid fa-check text-success"
                                                   v-if="(command.images||[]).length > 0"></i>
                                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Есть паттерны</td>
                                            <td>
                                                <p v-if="command.input_pattern">{{ command.input_pattern }}</p>
                                                <p v-else>
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                </p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Пустой</td>
                                            <td>
                                                <i class="fa-solid fa-check text-success" v-if="command.is_empty"></i>
                                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Есть кнопки</td>
                                            <td>
                                                <i class="fa-solid fa-check text-success"
                                                   v-if="command.inline_keyboard_id != null"></i>
                                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Есть меню</td>
                                            <td>
                                                <i class="fa-solid fa-check text-success"
                                                   v-if="command.reply_keyboard_id != null"></i>
                                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>

                        <td class="text-center" style="width:40px;">
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
                    <tr v-if="command.in_edit_mode">
                        <td colspan="17">
                            <BotDialogCommandForm
                                :item="selected"
                                v-if="bot&&!loading"
                                v-on:callback="loadDialogs"
                                v-on:select-element="selectElement"
                                :bot="bot"/>
                        </td>
                    </tr>
                </template>

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
            variables: [],
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
            if (!command)
                this.dialog_commands.forEach(item => {
                    item.in_edit_mode = false
                })
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
        toggleEditMode(command) {
            this.selected = command

            command.in_edit_mode = !(command.in_edit_mode || false)
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
        selectElement(id) {
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
                    if (tmpSelected) {
                        this.selected = this.dialog_commands.find(command => command.id === tmpSelected.id) || null
                        this.selected.in_edit_mode = false
                    }

                    this.loading = false

                })


            }).catch(() => {
                this.loading = false
            })
        },

    }
}
</script>

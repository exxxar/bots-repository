<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import RegularExpressionHelper from "@/AdminPanel/Components/Constructor/Helpers/RegularExpressionHelper.vue";
import Pagination from '@/AdminPanel/Components/Pagination.vue';

</script>
<template>
    <form v-on:submit.prevent="submit">
        <div class="mb-2 d-flex justify-content-start flex-wrap" v-if="commandForm.next_bot_dialog_command_id">
            <p>Связь цепочек: </p>
            <div v-for="(element, index) in item.chain">
                <span class="badge cursor-pointer"
                      @click="selectElementById(element)"
                      v-bind:class="{'bg-success':commandForm.id==element,'bg-primary':commandForm.id!=element}">
                    {{ element }}
                </span>
                <i class="fa-solid fa-arrow-right font-12" v-if="index<item.chain.length-1"></i>
            </div>

        </div>
        <div class="form-floating mb-2">
            <textarea class="form-control" :id="'commandForm-pre-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.pre_text" required>
            </textarea>
            <label :for="'commandForm-pre-text-'+commandForm.id">Текст диалога</label>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" v-model="commandForm.is_empty" id="need-empty-dialog"
                   checked>
            <label class="form-check-label" for="need-empty-dialog">
                Диалог без ожидания ответа
            </label>
        </div>


        <div class="form-floating mb-2" v-if="!commandForm.is_empty">
            <textarea class="form-control"
                   :id="'commandForm-post-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.post_text">
            </textarea>
            <label :for="'commandForm-post-text-'+commandForm.id">Текст после успешного завершения
                диалога</label>
        </div>

        <div class="form-floating mb-2" v-if="!commandForm.is_empty">
            <textarea type="text" class="form-control" :id="'commandForm-error-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.error_text">
            </textarea>
            <label :for="'commandForm-error-text-'+commandForm.id">Текст на случай ошибки корректности
                данных</label>
        </div>


        <div class="mb-2" v-if="!commandForm.is_empty">
            <div class="d-flex justify-content-between">
                <label class="form-label" :for="'commandForm-result-channel-'+commandForm.id">Регулярное выражение для
                    валидации данных

                </label>

                <RegularExpressionHelper
                    :param="'input_pattern'"
                    v-on:callback="addTextTo"
                />
            </div>
            <input type="text" class="form-control"
                   placeholder="Регулярное выражение"
                   aria-label="Регулярное выражение"
                   v-model="commandForm.input_pattern"
                   maxlength="255"
                   :id="'commandForm-input-pattern-'+commandForm.id"
                   aria-describedby="commandForm-input-pattern">
        </div>


        <div class="mb-2">
            <div class="d-flex justify-content-between">
                <label class="form-label" :for="'commandForm-result-channel-'+commandForm.id">Канал для отправки данных

                </label>

                <TelegramChannelHelper
                    v-if="bot"
                    :token="bot.bot_token"
                    :param="'result_channel'"
                    v-on:callback="addTextTo"
                />
            </div>
            <input type="number" class="form-control"
                   placeholder="id канала"
                   aria-label="id канала"
                   v-model="commandForm.result_channel"
                   :id="'commandForm-result-channel-'+commandForm.id"
                   aria-describedby="bot-order-channel">
        </div>

        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       v-model="need_images"
                       id="need-dialog-image" checked>
                <label class="form-check-label" for="need-dialog-image">
                    В диалоге нужно изображение
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="need_inline_keyboard"
                       id="need-dialog-menu-inline" checked>
                <label class="form-check-label" for="need-dialog-menu-inline">
                    В диалоге нужно меню тексту
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="need_reply_keyboard"
                       id="need-dialog-menu-reply" checked>
                <label class="form-check-label" for="need-dialog-menu-reply">
                    В диалоге нужно нижнее меню
                </label>
            </div>
        </div>

        <div class="card mb-2" v-if="need_images">
            <div class="card-body">
                <h6>Фотографии к диалогу</h6>
                <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                    <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                        <span>+</span>
                        <input type="file" id="location-photos" multiple accept="image/*"
                               @change="onChangePhotos"
                               style="display:none;"/>

                    </label>
                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                         v-for="(img, index) in photos"
                         v-if="photos.length>0">
                        <img v-lazy="getPhoto(img).imageUrl">
                        <div class="remove">
                            <a @click="removePhoto('photos',index)">Удалить</a>
                        </div>
                    </div>
                    <div class="mb-2 img-preview"
                         v-if="commandForm.images&&bot"
                         style="margin-right: 10px;"
                         v-for="(img, index) in commandForm.images">
                        <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                        <div class="remove">
                            <a @click="removePhoto('images',index)">Удалить</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card mb-2" v-if="need_inline_keyboard">
            <div class="card-header">
                <h6>Кнопки к тексту вопроса</h6>
            </div>
            <div class="card-body">
                <BotMenuConstructor
                    :type="'inline'"
                    v-on:save="saveInlineKeyboard"
                    :edited-keyboard="commandForm.inline_keyboard"/>
            </div>

        </div>

        <div class="card mb-2" v-if="need_reply_keyboard">
            <div class="card-header">
                <h6>Кнопки в виде нижнего меню</h6>
            </div>
            <div class="card-body">
                <BotMenuConstructor
                    :type="'reply'"
                    v-on:save="saveReplyKeyboard"
                    :edited-keyboard="commandForm.reply_keyboard"/>
            </div>

        </div>

        <div class=" mb-2">
            <label class="form-check-label" for="need-empty-dialog">
                Сохранить в переменную
            </label>
            <select class="form-control" v-model="commandForm.store_to">
                <option selected>Не выбрано</option>
                <option :value="item.key" v-for="item in store_variants">{{ item.title || 'Не указано' }}</option>
            </select>

        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" v-model="need_chains" id="need-chains"
                   checked>
            <label class="form-check-label" for="need-chains">
                Нужны многоуровневые диалоги
            </label>
        </div>

        <div class="mb-2" v-if="need_chains">
            <button type="button" class="btn btn-outline-primary" @click="addAnswer">Добавить вариант ответа</button>
            <table class="table" v-if="commandForm.answers.length>0">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ответ</th>
                    <th scope="col">Паттерн</th>
                    <th scope="col">Следующий диалог</th>
                    <th scope="col" class="text-center">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in commandForm.answers">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>
                        <div class="form-floating">
                            <input type="text"
                                   v-model="commandForm.answers[index].answer"
                                   class="form-control" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Точный текст ответа</label>
                        </div>
                    </td>
                    <td>

                        <div class="input-group">
                            <div class="form-floating">
                                <input type="text"
                                       v-model="commandForm.answers[index].pattern"
                                       class="form-control" id="floatingInput"
                                       placeholder="name@example.com">
                                <label for="floatingInput">Шаблон предполагаемого текста</label>
                            </div>
                            <div class="dropdown d-flex">
                                <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                           @click="commandForm.answers[index].pattern = null"
                                           href="javascript:void(0)">Не выбран</a></li>
                                    <li><a class="dropdown-item"
                                           @click="commandForm.answers[index].pattern = pattern.value"
                                           v-for="pattern in patterns"
                                           href="javascript:void(0)">{{ pattern.title || '-' }}</a></li>
                                </ul>
                            </div>

                        </div>


                    </td>
                    <td>
                        <div class="input-group">


                            <div class="form-floating">
                                <input type="text" class="form-control"
                                       v-model="commandForm.answers[index].next_bot_dialog_command_id"
                                       id="floatingInput"
                                       placeholder="name@example.com" required>
                                <label for="floatingInput">Выберите диалог или введите его номер</label>
                            </div>
                            <div class="dropdown d-flex">
                                <button class="btn btn-outline-secondary w-100"
                                        data-bs-auto-close="outside"
                                        type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                </button>
                                <div class="dropdown-menu p-2" style="width:400px;max-height:300px; overflow-y:auto;">
                                    <ul class="list-group">
                                        <li class="list-group-item cursor-pointer font-12"
                                            @click="commandForm.answers[index].next_bot_dialog_command_id = null">Не
                                            выбран
                                        </li>
                                        <li class="list-group-item cursor-pointer font-12"
                                            style="line-height:100%;text-align:left;"
                                            @click="commandForm.answers[index].next_bot_dialog_command_id = command.id"
                                            v-for="command in getDialogCommands">
                                            #{{ command.id || '-' }} {{ command.pre_text || '-' }}
                                        </li>
                                    </ul>
                                    <div class="px-3 pt-2">

                                            <Pagination
                                                v-on:pagination_page="nextDialogs"
                                                v-if="dialog_commands_paginate_object"
                                                :pagination="dialog_commands_paginate_object"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                    <td>
                        <div class="dropdown d-flex justify-content-center align-items-center">
                            <button class="btn btn-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                       @click="removeDialogAnswer(item, index)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="alert alert-primary mt-2" role="alert" v-else>
                Добавьте один или несколько вариантов ответов
            </div>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" v-model="need_set_flags" id="need-set-flags"
                   checked>
            <label class="form-check-label" for="need-set-flags">
                Установить флаги в соответствующее значение
            </label>
        </div>


        <div class=" mb-2" v-if="need_set_flags">
            <p>Выбрать флаги</p>
            <span class="badge text-info mr-2"
                  v-bind:class="{'bg-info text-white':commandForm.result_flags.indexOf(item.key)!=-1}"
                  @click="selectFlag(item)"
                  v-for="item in flags_variants">{{ item.title }}</span>

        </div>

        <div class="mb-2" v-if="commandForm.id">
            <div class="row" v-if="filteredCommands.length>0">
                <div class="col-12">
                    <p>Доступные для связывания диалоги:</p>
                </div>

                <div class="col-md-12 mb-1" v-for="(command, index) in filteredCommands">
                    <button type="button"
                            class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center"
                            style="text-align:left;">
                        <span @click="doCommandLink(command.id)">
                            <i class="fa-solid fa-link mr-2 text-success"
                               v-if="commandForm.next_bot_dialog_command_id == command.id"></i> #{{
                                command.id
                            }} {{ command.pre_text || 'Без текста' }}
                        </span>


                        <i class="fa-solid fa-unlink text-danger"
                           @click="doCommandLink(null)"
                           v-if="commandForm.next_bot_dialog_command_id == command.id"></i>

                    </button>
                </div>

                <Pagination
                    v-on:pagination_page="nextDialogs"
                    v-if="dialog_commands_paginate_object"
                    :pagination="dialog_commands_paginate_object"/>
            </div>
            <div class="row" v-else>
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        Диалогов для связывания нет
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-2">
            <button type="submit" class="btn btn-outline-success p-3 w-100">
                <span v-if="commandForm.id">Обновить диалог</span>
                <span v-else>Добавить диалог</span>
            </button>
        </div>
    </form>
</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["item", "bot"],
    data() {
        return {
            patterns: [
                {
                    title: 'Номер телефона РФ',
                    value: '/^\\s?(\\+\\s?7|8)([- ()]*\\d){10}$/'
                },
                {
                    title: 'Дата в формате ДД/ММ/ГГГГ',
                    value: '/(\\d{2})\\/(\\d{2})\\/(\\d{4})$/'
                },
                {
                    title: 'Время в формате ЧЧ:ММ[:СС]',
                    value: '#^[01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#'
                }


            ],
            loading: true,
            dialog_commands: [],
            dialog_commands_paginate_object: null,
            need_images: false,
            need_inline_keyboard: false,
            need_reply_keyboard: false,
            need_chains: false,
            need_set_flags: false,
            //сохранить данные в параметр
            //установить флаги
            flags_variants: [
                {
                    title: 'Является VIP',
                    key: 'is_vip',
                    value: null,
                },
                {
                    title: 'Является Админом',
                    key: 'is_admin',
                    value: null,
                },
                {
                    title: 'За работой',
                    key: 'is_work',
                    value: null,
                },
                {
                    title: 'Является менеджером',
                    key: 'is_manager',
                    value: null,
                },
            ],
            store_variants: [
                {
                    title: 'Имя',
                    key: 'name',
                },
                {
                    title: 'Почта',
                    key: 'email',
                },
                {
                    title: 'Возраст',
                    key: 'age',
                },
                {
                    title: 'Город',
                    key: 'city',
                },
                {
                    title: 'Страна',
                    key: 'country',
                },
                {
                    title: 'Адрес',
                    key: 'address',
                },
                {
                    title: 'Телефон',
                    key: 'phone',
                },
                {
                    title: 'День рождение',
                    key: 'birthday',
                }
            ],
            commandForm: {
                id: null,
                slug: null,
                pre_text: null,
                post_text: null,
                error_text: null,
                bot_id: null,
                input_pattern: null,
                inline_keyboard_id: null,
                reply_keyboard_id: null,
                images: null,
                next_bot_dialog_command_id: null,
                bot_dialog_group_id: null,
                is_empty: false,

                result_flags: [],
                store_to: null,

                result_channel: null,
                inline_keyboard: null,
                reply_keyboard: null,

                answers: [],
            },
            photos: []
        }
    },
    watch: {

        'need_inline_keyboard': function (newVal, oldVal) {
            if (!this.need_inline_keyboard) {
                this.commandForm.inline_keyboard_id = null
                this.commandForm.inline_keyboard = null
            }

        },
        'need_reply_keyboard': function (newVal, oldVal) {
            if (!this.need_reply_keyboard) {
                this.commandForm.reply_keyboard_id = null
                this.commandForm.reply_keyboard = null
            }

        },
        'need_set_flags': function (newVal, oldVal) {
            if (!this.need_set_flags) {
                this.commandForm.result_flags = []
            }

        },
        'need_images': function (newVal, oldVal) {
            if (!this.need_images) {
                this.commandForm.images = null
            }

        },
    },
    computed: {
        ...mapGetters(['getDialogCommands', 'getDialogCommandsPaginateObject']),
        filteredCommands() {
            if (!this.dialog_commands)
                return []

            return this.dialog_commands.filter(command => command.id != this.commandForm.id)

        }
    },
    mounted() {


        if (this.item) {
            this.$nextTick(() => {
                this.commandForm = {
                    id: this.item.id || null,
                    slug: this.item.slug || null,
                    pre_text: this.item.pre_text || null,
                    post_text: this.item.post_text || null,
                    error_text: this.item.error_text || null,
                    is_empty: this.item.is_empty || false,

                    input_pattern: this.item.input_pattern || null,
                    inline_keyboard_id: this.item.inline_keyboard_id || null,
                    reply_keyboard_id: this.item.reply_keyboard_id || null,
                    images: this.item.images || [],
                    next_bot_dialog_command_id: this.item.next_bot_dialog_command_id || null,
                    bot_dialog_group_id: this.item.bot_dialog_group_id || null,
                    result_channel: this.item.result_channel || null,
                    inline_keyboard: this.item.inline_keyboard || null,
                    reply_keyboard: this.item.reply_keyboard || null,

                    result_flags: this.item.result_flags || [],
                    store_to: this.item.store_to || null,
                    answers: this.item.answers || [],
                }

                if (this.bot)
                    this.commandForm.bot_id = this.bot.id || null

                if (this.commandForm.inline_keyboard_id != null)
                    this.need_inline_keyboard = true

                if (this.commandForm.reply_keyboard_id != null)
                    this.need_reply_keyboard = true

                if (this.commandForm.images.length > 0)
                    this.need_images = true

                if (this.commandForm.result_flags.length > 0)
                    this.need_set_flags = true

                if (this.commandForm.answers.length > 0)
                    this.need_chains = true

            })
        }

        if (this.bot)
            this.$nextTick(() => {
                this.commandForm.bot_id = this.bot.id
            })

        if (this.item) {
            this.dialog_commands = this.getDialogCommands
            this.dialog_commands_paginate_object = this.getDialogCommandsPaginateObject
        }
        /*else
            this.loadGroups();*/

    },
    methods: {
        addAnswer() {
            this.commandForm.answers.push({
                id: null,
                bot_dialog_command_id: null,
                answer: null,
                pattern: null,
                next_bot_dialog_command_id: null,
            })
        },
        nextDialogs(index) {
            this.loadDialogs(index)
        },
        removeDialogAnswer(item, index) {

            if (item.id == null) {
                this.commandForm.answers.splice(index, 1)
                return;
            }

            this.commandForm.answers.splice(index, 1)
            this.loading = true
            this.$store.dispatch("removeDialogAnswer", {
                dataObject: {
                    dialogAnswerId: item.id,
                },
            }).then(resp => {
                this.loading = false
                this.loadDialogs()
            }).catch(() => {
                this.loading = false
            })
        },
        loadDialogs(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogCommands", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.dialog_commands = this.getDialogCommands
                this.dialog_commands_paginate_object = this.getDialogCommandsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
        doCommandLink(commandId) {
            this.loading = true

            this.commandForm.next_bot_dialog_command_id = commandId
        },
        submit() {
            this.loading = true

            let data = new FormData();
            Object.keys(this.commandForm)
                .forEach(key => {
                    const item = this.commandForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.photos) {
                for (let i = 0; i < this.photos.length; i++)
                    data.append('files[]', this.photos[i]);

                data.delete("photos")
            }

            this.$store.dispatch((this.commandForm.id ?
                    "updateDialogCommand" :
                    "createDialogCommand")
                , {
                    dialogCommandForm: data
                }).then((response) => {

                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Успешная обработка диалоговой команды",
                    type: 'success'
                });

                if (this.commandForm.id == null) {
                    this.commandForm = {
                        id: null,
                        slug: null,
                        pre_text: null,
                        post_text: null,
                        error_text: null,
                        input_pattern: null,
                        inline_keyboard_id: null,
                        images: null,
                        next_bot_dialog_command_id: null,
                        bot_dialog_group_id: null,
                        result_channel: null,
                        inline_keyboard: null,
                        is_empty: false,
                        result_flags: [],
                        answers: [],
                        store_to: null,
                    }

                    this.photos = []

                }

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(name, index) {
            this[name].splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },
        saveInlineKeyboard(keyboard) {
            this.commandForm.inline_keyboard = keyboard
        },
        saveReplyKeyboard(keyboard) {
            this.commandForm.reply_keyboard = keyboard
        },
        addTextTo(object = {param: null, text: null}) {
            this.commandForm[object.param] = object.text;

        },
        selectElementById(id) {
            this.$emit("select-element", id)
        },
        selectFlag(item) {

            if (!this.commandForm.result_flags || !Array.isArray(this.commandForm.result_flags))
                this.commandForm.result_flags = []


            let index = this.commandForm.result_flags.indexOf(item.key)
            if (index === -1)
                this.commandForm.result_flags.push(item.key)
            else
                this.commandForm.result_flags.splice(index, 1)
        }


    }
}
</script>

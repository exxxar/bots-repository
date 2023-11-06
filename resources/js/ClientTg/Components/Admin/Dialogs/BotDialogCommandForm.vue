<script setup>
import BotMenuConstructor from "@/ClientTg/Components/Admin/Keyboards/KeyboardConstructor.vue";

</script>
<template>
    <form v-on:submit.prevent="submit">

        <p class="mb-2" v-if="commandForm.id">
            <span class="badge bg-success text-white">#{{ commandForm.id }}</span>
            <span class="ml-1 mr-1"><i class="fa-solid fa-arrow-right"></i></span>
            <span v-if="commandForm.next_bot_dialog_command_id"
                  class="badge bg-primary text-white">#{{ commandForm.next_bot_dialog_command_id || '-' }}

                <i class="ml-2 fa-solid fa-link-slash" @click="unlinkCommand"></i>
            </span>
            <span v-else>не связан</span>
        </p>


        <div class="form-floating mb-2">
            <label :for="'commandForm-pre-text-'+commandForm.id">Текст диалога</label>
            <textarea
                style="min-height:200px;"
                class="form-control" :id="'commandForm-pre-text-'+commandForm.id"
                placeholder="Начни с малого..." v-model="commandForm.pre_text" required>

            </textarea>

        </div>

        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       v-model="commandForm.is_empty"
                       id="need-empty-dialog" checked>
                <label class="form-check-label" for="need-empty-dialog">
                    Диалог без ожидания ответа
                </label>
            </div>

        </div>

        <div class="form-floating mb-2" v-if="!commandForm.is_empty">
            <label :for="'commandForm-post-text-'+commandForm.id">Текст после успешного завершения
                диалога</label>
            <textarea
                style="min-height:200px;"
                class="form-control"
                :id="'commandForm-post-text-'+commandForm.id"
                placeholder="Начни с малого..." v-model="commandForm.post_text" required>
            </textarea>

        </div>

        <div class="form-floating mb-2" v-if="!commandForm.is_empty">
            <label :for="'commandForm-error-text-'+commandForm.id">Текст на случай ошибки корректности
                данных</label>
            <textarea
                style="min-height:200px;"
                class="form-control" :id="'commandForm-error-text-'+commandForm.id"
                placeholder="Начни с малого..." v-model="commandForm.error_text" required>
            </textarea>

        </div>



        <div class="mb-2" v-if="!commandForm.is_empty">


            <div class="mb-2">
                <label class="form-label" :for="'commandForm-result-channel-'+commandForm.id">Регулярное выражение для
                    автоматической проверки данных

                </label>
                <select id="next-dialog-select" class="form-select form-control" v-model="commandForm.input_pattern">
                    <option :value="item.expression"
                            v-for="item in expressions">{{ item.description || '-' }}
                    </option>
                </select>
            </div>

            <label :for="'custom-regular-expression'+commandForm.id">или впишите своё</label>
            <input :id="'custom-regular-expression'+commandForm.id" type="text" class="form-control"
                   placeholder="Регулярное выражение"
                   aria-label="Регулярное выражение"
                   v-model="commandForm.input_pattern"
                   maxlength="255"
                   aria-describedby="commandForm-input-pattern">
        </div>

        <div class="mb-2" v-if="!commandForm.is_empty">
            <label :for="'next-dialog-select'+commandForm.id">Следующий диалог</label>
            <select :id="'next-dialog-select'+commandForm.id" class="form-select form-control"
                    aria-label="Default select example">
                <option :value="null" selected>Не указан</option>
                <option :value="command.id" v-for="(command, index) in commands">#{{ command.id }}
                    {{ command.pre_text || 'Без текста' }}
                </option>

            </select>
        </div>

        <div class="mb-2">
            <div class="d-flex justify-content-between">
                <label class="form-label" :for="'commandForm-result-channel-'+commandForm.id">Канал для отправки данных

                </label>

                <span @click="requestChannelId('result_channel')"><i
                    class="fa-brands fa-telegram ml-2 color-blue2-dark"></i></span>

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
                    В диалоге нужно меню к тексту
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

        <div class="mb-2" v-if="need_images">

            <h6>Фотографии к диалогу</h6>
            <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
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

        <div class=" mb-2" v-if="need_inline_keyboard">

            <h6>Кнопки к вопросу</h6>


            <BotMenuConstructor
                v-on:save="saveInlineKeyboard"
                :edited-keyboard="commandForm.inline_keyboard"/>


        </div>

        <div class=" mb-2" v-if="need_reply_keyboard">

            <h6>Кнопки нижнего меню к вопросу</h6>


            <BotMenuConstructor
                v-on:save="saveReplyKeyboard"
                :edited-keyboard="commandForm.reply_keyboard"/>


        </div>

        <div class="mb-2">
            <button type="submit"
                    class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark w-100">
                <span v-if="commandForm.id">Обновить диалог</span>
                <span v-else>Добавить диалог</span>
            </button>
        </div>

        <div class="divider divider-small my-3 bg-highlight " v-if="commandForm.id"></div>

        <a v-if="commandForm.id"
           @click="removeCommand"
           title="Удаление команды"
           class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark">
            <i class="fa-solid fa-trash-can mr-1"></i> Удалить диалог
        </a>
    </form>
</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["item", "bot"],
    data() {
        return {
            need_images: false,
            need_inline_keyboard: false,
            need_reply_keyboard: false,
            commands: [],
            expressions: [
                {
                    expression: null,
                    description: 'Нет проверки'
                },
                {
                    expression: '/([a-z0-9]+)/i',
                    description: 'Проверка набора из латинских букв и цифр'
                },
                {
                    expression: '/([а-яё0-9]+)/iu',
                    description: 'Проверка на кириллицу и цифры'
                },
                {
                    expression: '/(?P<digit>\\d+)/',
                    description: 'Проверка на число'
                },
                {
                    expression: '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/',
                    description: 'Проверка Email'
                },
                {
                    expression: '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',
                    description: 'Проверка номера телефона'
                },
                {
                    expression: '/^(0[1-9]|[12][0-9]|3[01])[\.](0[1-9]|1[012])[\.](19|20)\d\d$/',
                    description: 'Проверка даты по формату'
                },
                {
                    expression: '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/',
                    description: 'Проверка даты по формату YYYY-MM-DD'
                },
                {
                    expression: '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                    description: 'Проверка доменного имени'
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
                result_channel: null,
                inline_keyboard: null,
                reply_keyboard: null,
                is_empty: false,
            },
            photos: []
        }
    },
    computed: {
        ...mapGetters(['getDialogs']),
    },
    mounted() {
        window.addEventListener("select-telegram-channel-id", (e) => {
            this.commandForm[e.detail.param] = e.detail.channel
        });

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
                }

                if (this.bot)
                    this.commandForm.bot_id = this.bot.id || null

                if (this.commandForm.inline_keyboard_id != null)
                    this.need_inline_keyboard = true

                if (this.commandForm.reply_keyboard_id != null)
                    this.need_reply_keyboard = true

                if (this.commandForm.images.length > 0)
                    this.need_images = true
            })
        }

        if (this.bot)
            this.$nextTick(() => {
                this.commandForm.bot_id = this.bot.id
            })

        this.loadGroups()
    }, methods: {
        unlinkCommand() {

            this.loading = true

            this.$store.dispatch("unlinkDialogCommand", {
                dataObject: {
                    dialogCommandId: this.item.id
                }
            }).then((response) => {
                this.loading = false

                this.$botNotification.success(
                    "Команды",
                    "Диалоговая команда успешно продублирована!",
                );

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        removeCommand() {
            this.loading = true

            this.$store.dispatch("removeDialogCommand", {
                dataObject: {
                    dialogCommandId: this.item.id
                }
            }).then((response) => {
                this.loading = false

                this.$botNotification.success(
                    "Команды",
                    "Диалоговая команда успешно удалена!",
                );


                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        loadGroups(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogs", {
                dataObject: {
                    simple: true
                },
                page: page,
                size: 100,
            }).then(resp => {
                this.loading = false
                this.commands = this.getDialogs
            }).catch(() => {
                this.loading = false
            })
        },
        requestChannelId(param) {
            this.$botPages.telegramChannelHelper(param);
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

                this.$botNotification.success(
                    "Команды",
                    "Успешная обработка диалоговой команды!",
                );

                if (this.commandForm.id == null) {
                    this.commandForm = {
                        id: null,
                        slug: null,
                        pre_text: null,
                        post_text: null,
                        error_text: null,
                        input_pattern: null,
                        inline_keyboard_id: null,
                        is_empty: false,
                        images: null,
                        next_bot_dialog_command_id: null,
                        bot_dialog_group_id: null,
                        result_channel: null,
                        inline_keyboard: null,
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


    }
}
</script>

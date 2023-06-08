<script setup>
import BotMenuConstructor from "@/Components/Constructor/BotMenuConstructor.vue";
import TelegramChannelHelper from "@/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import RegularExpressionHelper from "@/Components/Constructor/Helpers/RegularExpressionHelper.vue";

</script>
<template>
    <form v-on:submit.prevent="submit">
        <div class="form-floating mb-2">
            <input type="text" class="form-control" :id="'commandForm-slug-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.slug" required>
            <label :for="'commandForm-slug-'+commandForm.id">Идентификатор команды (на англ)</label>
        </div>


        <div class="form-floating mb-2">
            <input type="text" class="form-control" :id="'commandForm-pre-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.pre_text" required>
            <label :for="'commandForm-pre-text-'+commandForm.id">Текст диалога</label>
        </div>

        <div class="form-floating mb-2">
            <input type="text" class="form-control"
                   :id="'commandForm-post-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.post_text" required>
            <label :for="'commandForm-post-text-'+commandForm.id">Текст после успешного завершения
                диалога</label>
        </div>

        <div class="form-floating mb-2">
            <input type="text" class="form-control" :id="'commandForm-error-text-'+commandForm.id"
                   placeholder="Начни с малого..." v-model="commandForm.error_text" required>
            <label :for="'commandForm-error-text-'+commandForm.id">Текст на случай ошибки корректности
                данных</label>
        </div>


        <div class="mb-2">
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
                <input class="form-check-input" type="checkbox" v-model="need_keyboard" id="need-dialog-menu" checked>
                <label class="form-check-label" for="need-dialog-menu">
                    В диалоге нужно меню
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
                            <a @click="removePhoto(index)">Удалить</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card mb-2" v-if="need_keyboard">
            <div class="card-header">
                <h6>Кнопки к вопросу</h6>
            </div>
            <div class="card-body">
                <BotMenuConstructor
                    v-on:save="saveInlineKeyboard"
                    :edited-keyboard="commandForm.inline_keyboard"/>
            </div>

        </div>

        <div class="mb-2">
            <button type="submit" class="btn btn-outline-primary p-3 w-100">
                <span v-if="commandForm.id">Обновить диалог</span>
                <span v-else>Добавить диалог</span>
            </button>
        </div>
    </form>
</template>
<script>


export default {
    props: ["item","bot"],
    data() {
        return {
            need_images:false,
            need_keyboard:false,
            commandForm: {
                id: null,
                slug: null,
                pre_text: null,
                post_text: null,
                error_text: null,
                bot_id: null,
                input_pattern: null,
                inline_keyboard_id: null,
                images: null,
                next_bot_dialog_command_id: null,
                bot_dialog_group_id: null,
                result_channel: null,
                inline_keyboard: null,
            },
            photos: []
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
                    bot_id: this.item.bot.id || null,
                    input_pattern: this.item.input_pattern || null,
                    inline_keyboard_id: this.item.inline_keyboard_id || null,
                    images: this.item.images || [],
                    next_bot_dialog_command_id: this.item.next_bot_dialog_command_id || null,
                    bot_dialog_group_id: this.item.bot_dialog_group_id || null,
                    result_channel: this.item.result_channel || null,
                    inline_keyboard: this.item.inline_keyboard || null,
                }

                if (this.commandForm.inline_keyboard_id!=null)
                    this.need_keyboard = true

                if (this.commandForm.images.length>0)
                    this.need_images = true
            })
        }

        if (this.bot)
            this.$nextTick(() => {
                this.commandForm.bot_id = this.bot.id
            })

    }, methods: {
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
                    text:  "Успешная обработка диалоговой команды" ,
                    type: 'success'
                });

                if (this.commandForm.id ==null ) {
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
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },
        saveInlineKeyboard(keyboard) {
            this.commandForm.inline_keyboard = keyboard
        },
        addTextTo(object = {param: null, text: null}) {
            this.commandForm[object.param] = object.text;

        },


    }
}
</script>

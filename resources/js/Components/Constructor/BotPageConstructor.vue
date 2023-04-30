<script setup>
import MediumEditor from 'vuejs-medium-editor'
import 'medium-editor/dist/css/medium-editor.css'
import 'vuejs-medium-editor/dist/themes/default.css'
// for the code highlighting
import 'highlight.js/styles/github.css'
import BotList from "@/Components/Constructor/BotList.vue";
import BotMenuConstructor from "@/Components/Constructor/BotMenuConstructor.vue";
import PagesList from "@/Components/Constructor/PagesList.vue";
</script>
<template>
    <div class="row pb-2 pt-2">
        <div class="col-12">
            <BotList
                v-if="!load"
                :editor="true"
                v-on:callback="botListCallback"/>
        </div>

        <div class="col-12" v-if="bot">
            <PagesList
                v-if="!load&&bot"
                :bot-id="bot.id"
                :editor="true"
                v-on:callback="pageListCallback"/>
        </div>

        <div v-if="bot&&!load">
            <form v-on:submit.prevent="submitPage">
                <div class="col-12 mb-2">
                    <h6>Вы создаете страницу для {{ bot.bot_domain }}</h6>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label" id="bot-domain">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div> Команда должна начинаться с символва . и *<br>
                                    Например: .*Меню<br>
                                </div>
                            </template>
                        </Popper>
                        Команда
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Команда"
                           aria-label="Команда"
                           v-model="pageForm.command"
                           maxlength="255"
                           aria-describedby="bot-domain" required>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label" id="bot-domain">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Напишите для себя пояснение для чего нужна данная страница.
                                    Это поможет другим менеджерам лучше понять вас.
                                </div>
                            </template>
                        </Popper>
                        Описание страницы
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание страницы"
                              aria-label="Описание страницы"
                              v-model="pageForm.comment"
                              maxlength="255"
                              aria-describedby="bot-domain" required>
            </textarea>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label" id="bot-domain">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>
                                    Текстовый редактор. Данный текст будет в таком<br>
                                    же виде отображен в посте в телеграм.
                                </div>
                            </template>
                        </Popper>
                        Текстовое содержимое страницы
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>


                    <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="pageForm.content"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px"></textarea>
                        <label for="floatingTextarea2">Содержимое страницы</label>
                    </div>

                </div>
                <div class="col-12 mb-2">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Изобаржения на странице</h6>
                        </div>
                        <div class="card-body d-flex justify-content-start">

                            <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                +
                                <input type="file" id="photos"
                                       multiple
                                       accept="image/*" @change="onChangePhotos"
                                       style="display:none;"/>

                            </label>
                            <div class="row">
                                <div class="col-12 d-flex" v-if="photos.length>0">
                                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                                         v-for="(img, index) in photos">
                                        <img v-lazy="getPhoto(img).imageUrl"/>

                                        <div class="remove">
                                            <a @click="removePhoto(index)">Удалить</a>
                                        </div>
                                    </div>
                                </div>
                                <h6 v-if="pageForm.images.length>0">Ранее загруженные фотографии</h6>
                                <div class="col-12 d-flex " v-if="pageForm.images.length>0">

                                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                                         v-for="(img, index) in pageForm.images">
                                        <img
                                            v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                                        <div class="remove">
                                            <a @click="removeImage(index)">Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <div class="card">
                        <div class="card-header">
                            <h6>Конструктор меню в сообщении</h6>
                        </div>

                        <div class="card-body">
                            <BotMenuConstructor
                                v-on:save="saveInlineKeyboard"
                                :edited-keyboard="pageForm.inline_keyboard"/>
                        </div>
                    </div>


                </div>
                <div class="col-12 mb-2">
                    <div class="card">
                        <div class="card-header">
                            <h6>Конструктор нижнего меню</h6>
                        </div>

                        <div class="card-body">
                            <BotMenuConstructor
                                v-on:save="saveReplyKeyboard"
                                :edited-keyboard="pageForm.reply_keyboard"/>
                        </div>
                    </div>


                </div>
                <div class="col-12">
                    <button class="btn btn-outline-primary w-100 p-3">Сохранить страницу</button>
                </div>
            </form>

        </div>


    </div>
</template>
<script>

export default {

    data() {
        return {
            bot: null,
            pages: [],
            load: false,
            photos: [],
            pageForm: {
                content: '',
                command: null,
                slug: null,
                comment: null,

                images: [],
                reply_keyboard: null,
                inline_keyboard: null
            },
            options: {
                placeholder: {
                    text: 'Начни создавать контент!',
                },
                toolbar: {
                    buttons: [
                        'bold',
                        'italic',
                        'underline',
                    ]
                }
            }
        }
    },

    methods: {
        submitPage() {
            let data = new FormData();
            Object.keys(this.pageForm)
                .forEach(key => {
                    const item = this.pageForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            if (this.bot)
                data.append("bot_id", this.bot.id)

            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

            if (this.pageForm.images.length === 0 || typeof this.pageForm.images == 'string')
                data.delete("images")

            this.$store.dispatch((this.pageForm.id == null ? "createPage" : "updatePage"), {
                pageForm: data
            }).then((response) => {
                this.load = true

                this.$nextTick(() => {
                    this.load = false

                    this.photos = []
                    this.pageForm = {
                        content: null,
                        command: null,
                        slug: null,
                        comment: null,
                        images: [],
                        reply_keyboard: null,
                        inline_keyboard: null
                    }
                })

                this.$emit("callback", response.data)
                this.$notify({
                    title: "Конструктор ботов",
                    text: (this.pageForm.id == null ? "Страница успешно создана!" : "Страница успешно обновлена!"),
                    type: 'success'
                });
            }).catch(err => {

            })

        },
        saveInlineKeyboard(keyboard) {
            this.pageForm.inline_keyboard = keyboard
        },
        saveReplyKeyboard(keyboard) {
            this.pageForm.reply_keyboard = keyboard
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        removeImage(index) {
            this.pageForm.images.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },
        botListCallback(bot) {
            this.load = true
            this.bot = bot
            this.$nextTick(() => {
                this.load = false

            })
        },
        pageListCallback(page) {
            this.load = true
            if (page) {

                console.log("pageListCallback", page)

                this.photos = []
                this.pageForm = {
                    id: page.id,
                    slug_id: page.slug.id || null,
                    content: page.content,
                    command: page.slug.command || null,
                    slug: page.slug.slug || null,
                    comment: page.slug.comment || null,
                    images: page.images || [],
                    reply_keyboard_id: page.reply_keyboard_id || null,
                    inline_keyboard_id: page.inline_keyboard_id || null,
                    reply_keyboard: page.replyKeyboard || null,
                    inline_keyboard: page.inlineKeyboard || null
                }
            } else {

                this.photos = []
                this.pageForm = {
                    content: null,
                    command: null,
                    slug: null,
                    comment: null,
                    images: [],
                    reply_keyboard: null,
                    inline_keyboard: null
                }
                this.photos = []
            }

            this.$nextTick(() => {

                this.load = false
            });

        },

        onChange(data) {
            this.pageForm.content = data
        },

    }
}
</script>

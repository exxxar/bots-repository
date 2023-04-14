<template>
    <div class="row">
        <div class="col-12">
            <h6>Создаем бот к компании #{{ companyId || 'Не установлен' }}</h6>
        </div>
    </div>
    <div class="row mb-3 mt-3">
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                <button type="button"
                        v-bind:class="{'btn-primary text-white':step===0}"
                        @click="step=0"
                        class="btn btn-outline-primary">Информация о боте
                </button>
                <button type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        v-bind:class="{'btn-primary text-white':step===1}"
                        @click="step=1"
                        class="btn btn-outline-primary">Меню бота
                </button>
                <button type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        v-bind:class="{'btn-primary text-white':step===2}"
                        @click="step=2"
                        class="btn btn-outline-primary">Команды бота
                </button>
            </div>
        </div>
    </div>
    <form
        v-on:submit.prevent="addBot">
        <div v-if="step===0">

            <div class="row" v-if="templates.length>0">
                <div class="col-12">
                    <div class="card border-success mb-3 mt-3">
                        <div class="card-body">
                            <label class="form-label" id="bot-level-2">Выберите шаблон!</label>
                            <select class="form-control"
                                    aria-label="Шаблон бота"
                                    v-model="botForm.selected_bot_template_id"
                                    aria-describedby="bot-level-2">
                                <option :value="bot.id" v-for="(bot, index) in templates">{{ bot.bot_domain }}</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-domain">Доменное имя бота из BotFather</label>
                        <input type="text" class="form-control"
                               placeholder="Имя бота"
                               aria-label="Имя бота"
                               v-model="botForm.bot_domain"
                               maxlength="255"
                               aria-describedby="bot-domain" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-description">Описание бота</label>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое описание бота"
                                  aria-label="Текстовое описание бота"
                                  v-model="botForm.description"
                                  maxlength="255"
                                  aria-describedby="bot-description" required>
                    </textarea>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-token">Токен бота</label>
                        <input type="text" class="form-control"
                               placeholder="Токен"
                               aria-label="Токен"
                               v-model="botForm.bot_token"
                               maxlength="255"
                               aria-describedby="bot-token" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-token-dev">Токен бота (для тестирования)</label>
                        <input type="text" class="form-control"
                               placeholder="Токен"
                               aria-label="Токен"
                               v-model="botForm.bot_token_dev"
                               maxlength="255"
                               aria-describedby="bot-token-dev">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-order-channel">Канал для заказов</label>
                        <input type="text" class="form-control"
                               placeholder="Номер канала"
                               aria-label="Номер канала"
                               v-model="botForm.order_channel"
                               maxlength="255"
                               aria-describedby="bot-order-channel" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-main-channel">Канал для постов (рекламный)</label>
                        <input type="text" class="form-control"
                               placeholder="Номер канала"
                               aria-label="Номер канала"
                               v-model="botForm.main_channel"
                               maxlength="255"
                               aria-describedby="bot-main-channel" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-balance">Баланс бота, руб</label>
                        <input type="number" class="form-control"
                               placeholder="Баланс"
                               aria-label="Баланс"
                               v-model="botForm.balance"
                              min="0"
                               aria-describedby="bot-balance" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-tax-per-day">Списание за сутки, руб</label>
                        <input type="number" class="form-control"
                               placeholder="Списание"
                               aria-label="Списание"
                               v-model="botForm.tax_per_day"
                               min="0"
                               aria-describedby="bot-tax-per-day" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-level-1">Уровень 1 CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_1"
                               maxlength="255"
                               aria-describedby="bot-level-1" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-level-2">Уровень 2 CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_2"
                               maxlength="255"
                               aria-describedby="bot-level-2" required>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-level-3">Уровень 3 CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_3"
                               maxlength="255"
                               aria-describedby="bot-level-3" required>
                    </div>
                </div>


                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-maintenance-message">Сообщение для режима тех.
                            работ</label>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое сообщение"
                                  aria-label="Текстовое сообщение"
                                  v-model="botForm.maintenance_message"
                                  maxlength="255"
                                  aria-describedby="bot-maintenance-message" required>
                    </textarea>
                    </div>
                </div>

                <div class="col-12 ">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Информационная ссылка: создайте контент в <a target="_blank" href="https://telegra.ph">https://telegra.ph</a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h6>Ссылка</h6>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                               placeholder="Ссылка ресурс telegraph"
                                               aria-label="Ссылка ресурс telegraph"
                                               maxlength="255"
                                               v-model="botForm.info_link"
                                               :aria-describedby="'bot-info-link'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 ">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Ссылки на соц. сети</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h6>Ссылка</h6>
                                </div>

                            </div>
                            <div class="row"
                                 :key="'social-link'+index"
                                 v-for="(item, index) in botForm.social_links">
                                <div class="col-10">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                               placeholder="Ссылка на соц.сеть"
                                               aria-label="Ссылка на соц.сеть"
                                               maxlength="255"
                                               v-model="botForm.social_links[index]"
                                               :aria-describedby="'bot-social-link-'+index" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button
                                        type="button"
                                        @click="removeItem('social_links', index)"
                                        class="btn btn-outline-danger w-100">Удалить
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button
                                        type="button"
                                        @click="addItem('social_links')"
                                        class="btn btn-outline-success w-100">Добавить еще ссылку
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                        <label for="bot-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            <span>+</span>
                            <input type="file" id="bot-photos" multiple accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>
                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in botForm.photos"
                             v-if="botForm.photos.length>0">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto(index)">Удалить</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </div>

        <div v-if="step===1">

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="alert alert-warning" role="alert">
                        Если меняете текст в "Нижней клавиатуре", то найдите и поменяйте его также в разделе "Команды
                        бота"
                    </div>
                </div>
                <div class="col-12 mb-3"
                     v-if="botForm.keyboards"
                     v-for="(slug, index) in botForm.keyboards">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" id="bot-domain">Тип</label>
                                        <select
                                            :disabled="true"
                                            v-model="botForm.keyboards[index].type"
                                            class="form-control">
                                            <option value="reply">Нижняя клавиатура</option>
                                            <option value="inline">Встроенная клавиатура</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" id="bot-domain">Мнемоническое имя</label>
                                        <input type="text" class="form-control"
                                               placeholder="Мнемоническое имя"
                                               :disabled="true"
                                               aria-label="Мнемоническое имя"
                                               v-model="botForm.keyboards[index].slug"
                                               maxlength="255"
                                               aria-describedby="bot-domain" required>
                                    </div>
                                </div>

                                <div class=" col-12">
                                    <div class="mb-3">
                                        <label class="form-label" id="bot-domain">JSON-код клавиатуры</label>
                                        <Vue3JsonEditor
                                            :mode="'code'"
                                            v-model="botForm.keyboards[index].menu"
                                            :show-btns="false"
                                            :expandedOnStart="true"
                                            @click="selectMenuIndex = index"
                                            @json-change="onJsonChange"
                                        />
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Демонстрация меню</h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="row" v-for="(row, rowIndex) in botForm.keyboards[index].menu">
                                                <div class="col" v-for="(col, colIndex) in row">

                                                    <button
                                                        type="button"
                                                        @click="editBtn(index, rowIndex,colIndex)"
                                                        class="btn btn-outline-primary w-100 mb-2">
                                                        {{ col.text }}
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="step===2">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="alert alert-warning" role="alert">
                        Если вы боитесь последствий модификации команды, то продублируйте нужную и внесите коррективы!
                        Работать будут обе команды как оригинал, так и дубль!
                    </div>
                </div>
                <div class="col-12 mb-3"
                     v-if="botForm.slugs.length>0"
                     v-for="(slug, index) in botForm.slugs">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
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
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" id="bot-domain">Команда</label>
                                        <input type="text" class="form-control"
                                               placeholder="Команда"
                                               aria-label="Команда"
                                               v-model="botForm.slugs[index].command"
                                               maxlength="255"
                                               aria-describedby="bot-domain" required>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label" id="bot-domain">Мнемоническое имя</label>
                                        <input type="text" class="form-control"
                                               :disabled="true"
                                               placeholder="Мнемоническое имя"
                                               aria-label="Мнемоническое имя"
                                               v-model="botForm.slugs[index].slug"
                                               maxlength="255"
                                               aria-describedby="bot-domain" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">Добавить бота
                </button>
            </div>
        </div>
    </form>

</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'

export default {
    props: ["companyId"],
    components: {
        Vue3JsonEditor
    },
    setup() {

    },
    data() {
        return {
            step: 0,
            templates: [],
            selectMenuIndex: null,

            botForm: {
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                main_channel: null,
                balance: null,
                tax_per_day: null,

                image: null,

                description: null,

                info_link: null,

                social_links: [],

                maintenance_message: null,

                level_1: 10,
                level_2: 0,
                level_3: 0,

                photos: [],

                selected_bot_template_id: null,

                slugs: [],

                keyboards: [],
            },

            editedButton: {
                keyboardIndex: null,
                rowIndex: null,
                colIndex: null,
                button: null
            }

        }
    },
    watch: {
        'botForm.selected_bot_template_id': function (oVal, nVal) {
            if (this.botForm.selected_bot_template_id != null) {
                this.loadMenusByBotTemplate(this.botForm.selected_bot_template_id)
                this.loadSlugsByBotTemplate(this.botForm.selected_bot_template_id)
            }
        }
    },
    mounted() {
        this.loadBotTemplates()
    },
    methods: {
        duplicateSlug(index) {
            const slug = JSON.stringify(this.botForm.slugs[index])
            this.botForm.slugs.splice(index, 0, JSON.parse(slug))
        },
        removeSlug(index) {
            this.botForm.slugs.splice(index, 1)
        },
        editBtn(keyboardIndex, rowIndex, colIndex) {
            this.editedButton.button = this.botForm.keyboards[keyboardIndex].menu[rowIndex][colIndex]
            this.editedButton.colIndex = colIndex
            this.editedButton.rowIndex = rowIndex
            this.editedButton.keyboardIndex = keyboardIndex

            console.log(this.editedButton)
            /*  Object
                  .keys(this.botForm.keyboards[keyboardIndex].menu[rowIndex][colIndex])
                  .forEach(item=>{
                      console.log("item", this.botForm.keyboards[keyboardIndex].menu[rowIndex][colIndex][item])
                  })*/
            //console.log()
        },
        onJsonChange(value) {
            this.botForm.keyboards[this.selectMenuIndex].menu = value
        },
        loadMenusByBotTemplate(botId) {
            this.$store.dispatch("loadKeyboards", {
                botId: botId
            }).then((resp) => {
                this.botForm.keyboards = resp.data
            })
        },
        loadSlugsByBotTemplate(botId) {
            this.$store.dispatch("loadSlugs", {
                botId: botId
            }).then((resp) => {
                this.botForm.slugs = resp.data
            })
        },
        loadBotTemplates() {
            this.$store.dispatch("loadTemplates").then((resp) => {
                this.templates = resp.data

            })
        },
        getPhoto(img){
          return {imageUrl: URL.createObjectURL(img)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.botForm.photos.push(files[i])
        },
        addItem(name) {
            this.botForm[name].push("")
        },
        removeItem(name, index) {
            this.botForm[name].splice(index, 1)
        },
        removePhoto(index) {
            this.botForm.photos.splice(index, 1)
        },
        addBot() {
            let data = new FormData();
            Object.keys(this.botForm)
                .forEach(key => {
                    const item = this.botForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            data.append("company_id", this.companyId)

            for (let i = 0; i < this.botForm.photos.length; i++)
                data.append('images[]', this.botForm.photos[i]);

            data.delete("photos")

            this.$store.dispatch("createBot", {
                botForm: data
            }).then((response) => {
                this.$emit("callback", response.data)
            }).catch(err => {

            })

            this.botForm = {
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                main_channel: null,
                balance: null,
                tax_per_day: null,

                image: null,

                description: null,

                info_link: null,

                social_links: [],

                maintenance_message: null,

                level_1: 10,
                level_2: 0,
                level_3: 0,

                photos: [],

                selected_bot_template_id: null,

                slugs: [],

                keyboards: [],
            }
        },
    }
}
</script>

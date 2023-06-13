<script setup>
import BotMenuList from "@/Components/Constructor/BotMenuList.vue";
import BotSlugList from "@/Components/Constructor/BotSlugList.vue";
import BotUserList from "@/Components/Constructor/BotUserList.vue";
import TextHelper from "@/Components/Constructor/Helpers/TextHelper.vue";
import TelegramChannelHelper from "@/Components/Constructor/Helpers/TelegramChannelHelper.vue";

import PagesList from "@/Components/Constructor/Pages/PagesList.vue";
import Page from "@/Components/Constructor/Pages/Page.vue"
import ImageMenu from "@/Components/Constructor/ImageMenu.vue";
import BotDialogGroupList from "@/Components/Constructor/Dialogs/BotDialogGroupList.vue";
</script>
<template>
    <div class="row" v-if="company">
        <div class="col-12">
            <h6>Создаем бот к компании {{ company.title || 'Не установлен' }}</h6>
        </div>
    </div>
    <div class="row mb-3 mt-3 bot-sub-menu" v-if="editor">
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                <button type="button"

                        v-bind:class="{'btn-primary text-white':step===0}"
                        @click="step=0"
                        class="btn btn-outline-primary">Информация о боте
                </button>
                <button type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        v-bind:class="{'btn-primary text-white':step===4}"
                        @click="step=4"
                        class="btn btn-outline-primary">Страницы
                </button>
                <div class="dropdown">
                    <button
                        type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        class="btn btn-outline-primary dropdown-toggle custom-group-dropdown-btn" href="#" role="button"
                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="#bot-image-menu" @click="step=5">Меню заведения</a></li>
                        <li><a class="dropdown-item" href="#bot-menu-template" @click="step=1">Шаблон клавитатур
                            бота</a></li>
                        <li><a class="dropdown-item" href="#bot-slugs" @click="step=2">Скрипты</a></li>
                        <li><a class="dropdown-item" href="#bot-dialogs" @click="step=6">Диалоги</a></li>
                        <li><a class="dropdown-item" href="#bot-users" @click="step=3">Пользователи</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form
        class="pb-5 mb-5"
        v-on:submit.prevent="addBot">
        <div v-if="step===0">
            <div class="row" v-if="templates.length>0&&bot==null">
                <div class="col-12">
                    <div class="card border-success mb-3 mt-3">
                        <div class="card-body">
                            <label class="form-label" id="bot-level-2">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Ваш бот будет 1 в 1 как в шаблоне!<br>Потом можно исправить названия кнопок
                                            в меню.
                                        </div>
                                    </template>
                                </Popper>
                                Выберите шаблон!
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                            </label>
                            <select class="form-control"
                                    aria-label="Шаблон бота"
                                    v-model="botForm.selected_bot_template_id"
                                    aria-describedby="bot-level-2" required>
                                <option :value="bot.id"
                                        v-for="(bot, index) in templates">
                                    {{ bot.template_description || bot.bot_domain || 'Не указано' }}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-12">

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox"
                               :checked="botForm.is_template"
                               v-model="botForm.is_template" id="bot-is-template">
                        <label class="form-check-label" for="bot-is-template">
                            Сделать шаблоном
                        </label>
                    </div>

                </div>

                <div
                    v-if="botForm.is_template"
                    class="col-md-12 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-template-description">
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Если вы создаете шаблон, а не реального бота
                                    </div>
                                </template>
                            </Popper>
                            Название шаблона бота
                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        </label>
                        <input type="text" class="form-control"
                               placeholder="Название \ описание шаблона"
                               aria-label="Описание шаблона"
                               v-model="botForm.template_description"
                               maxlength="255"
                               aria-describedby="bot-template-description" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>Для создания бота в Телеграм воспользуйтесь <a
                        href="https://telegra.ph/Sozdanie-telegram-bota-06-12" target="_blank">инструкцией</a></p>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-domain">
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Строго взять из BotFather! ТО что при создании с окончанием на "bot"</div>
                                </template>
                            </Popper>
                            Доменное имя бота из BotFather
                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        </label>
                        <input type="text" class="form-control"
                               placeholder="Имя бота"
                               aria-label="Имя бота"
                               v-model="botForm.bot_domain"
                               maxlength="255"
                               aria-describedby="bot-domain" required>
                        <p v-if="botForm.bot_domain">Проверить работу бота <a :href="'https://t.me/'+botForm.bot_domain"
                                                                              target="_blank">@{{
                                botForm.bot_domain
                            }}</a>
                        </p>
                    </div>
                </div>


                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between" id="bot-token">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Взять из BotFater при создании бота! Длинная нечитаемая подсвеченная
                                            строка!
                                        </div>
                                    </template>
                                </Popper>
                                Токен бота
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                            </div>


                            <a href="https://t.me/botfather" target="_blank">Создать нового бота в ТГ</a>
                        </label>
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

                <div
                    class="col-md-6 col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label d-flex justify-content-between" id="bot-order-channel">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Ввести адрес ссылки на канал в форму после добавления тоукена
                                            </div>
                                        </template>
                                    </Popper>
                                    Канал для заказов (id)
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                </div>
                            </label>

                            <TelegramChannelHelper
                                :token="botForm.bot_token"
                                :param="'order_channel'"
                                v-on:callback="addTextTo"
                            />
                        </div>
                        <input type="text" class="form-control"
                               placeholder="id канала"
                               aria-label="id канала"
                               v-model="botForm.order_channel"
                               maxlength="255"
                               aria-describedby="bot-order-channel">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" id="bot-main-channel">Канал для постов (id,рекламный)</label>

                            <TelegramChannelHelper
                                :token="botForm.bot_token"
                                :param="'main_channel'"
                                v-on:callback="addTextTo"
                            />
                        </div>
                        <input type="text" class="form-control"
                               placeholder="id канала"
                               aria-label="id канала"
                               v-model="botForm.main_channel"
                               maxlength="255"
                               aria-describedby="bot-main-channel">
                    </div>
                </div>

            </div>
            <div class="row" v-if="botForm.bot_token">


                <div class="col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" id="bot-description">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Отобразится пользователю при первом запуске</div>
                                    </template>
                                </Popper>
                                Приветственное сообщение
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                                <small class="text-gray-400 ml-3" style="font-size:10px;"
                                       v-if="botForm.welcome_message">
                                    Длина текста {{ botForm.welcome_message.length }}</small>
                            </label>

                            <TextHelper
                                :param="'welcome_message'"
                                v-on:callback="addTextTo"
                            />
                        </div>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое приветствие при запуске бота"
                                  aria-label="Текстовое приветствие при запуске бота"
                                  v-model="botForm.welcome_message"
                                  aria-describedby="bot-description" required>
                    </textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" id="bot-description">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Для меню "О Боте"</div>
                                    </template>
                                </Popper>
                                Описание бота
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

                                <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="botForm.description">
                                    Длина текста {{ botForm.description.length }}</small>
                            </label>

                            <TextHelper
                                :param="'description'"
                                v-on:callback="addTextTo"
                            />
                        </div>

                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое описание бота"
                                  aria-label="Текстовое описание бота"
                                  v-model="botForm.description"
                                  aria-describedby="bot-description" required>
                    </textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" id="bot-maintenance-message">Сообщение для режима тех.
                                работ
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

                                <small class="text-gray-400 ml-3" style="font-size:10px;"
                                       v-if="botForm.maintenance_message">
                                    Длина текста {{ botForm.maintenance_message.length }}</small>
                            </label>
                            <TextHelper
                                :param="'maintenance_message'"
                                v-on:callback="addTextTo"
                            />
                        </div>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое сообщение"
                                  aria-label="Текстовое сообщение"
                                  v-model="botForm.maintenance_message"
                                  maxlength="255"
                                  aria-describedby="bot-maintenance-message" required>
                    </textarea>
                    </div>
                </div>


                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-balance">
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Начальная сумма денег на счету у конкретного бота</div>
                                </template>
                            </Popper>
                            Баланс бота, руб
                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        </label>
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
                        <label class="form-label" id="bot-tax-per-day">
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Сумма списания денег за сутки работы бота (тариф)</div>
                                </template>
                            </Popper>
                            Списание за сутки, руб
                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        </label>
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
                        <label class="form-label" id="bot-level-1">
                            Уровень 1 CashBack, %
                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        </label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_1"
                               max="50"
                               min="0"
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
                               max="50"
                               min="0"
                               aria-describedby="bot-level-2">
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="bot-level-3">Уровень 3 CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_3"
                               max="50"
                               min="0"
                               aria-describedby="bot-level-3">
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
                                <div class="col-5">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                               placeholder="Название ссылки"
                                               aria-label="Название ссылки"
                                               maxlength="255"
                                               v-model="botForm.social_links[index].title"
                                               :aria-describedby="'bot-social-link-'+index" required>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                               placeholder="Ссылка на соц.сеть"
                                               aria-label="Ссылка на соц.сеть"
                                               maxlength="255"
                                               v-model="botForm.social_links[index].url"
                                               :aria-describedby="'bot-social-link-'+index" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button
                                        type="button"
                                        @click="removeItem('social_links', index)"
                                        class="btn btn-outline-danger w-100"><i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button
                                        type="button"
                                        @click="addSocialLinks()"
                                        class="btn btn-outline-success w-100">Добавить еще ссылку
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <h6>Аватар для бота
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </h6>

                    <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                        <label for="bot-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            <span>+</span>
                            <input type="file" id="bot-photos" multiple accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in botForm.photos"
                             v-if="botForm.photos">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto(index)"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-else>
                            <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+botForm.image">
                            <div class="remove">
                                <a @click="removePhoto()"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <div class="row">
                <div class="col-12">
                    <button
                        :disabled="!botForm.bot_token"
                        type="submit" class="btn btn-success w-100 p-3">
                        <span v-if="!bot">Добавить бота</span>
                        <span v-else>Обновить бота</span>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="step===5">
            <ImageMenu
                v-if="bot&&!load"
                :bot="bot"
            />
        </div>


        <div v-if="step===1">
            <BotMenuList
                :keyboards="botForm.keyboards"
                v-if="botForm.keyboards"
                v-on:edit="editBtnScript"
                v-on:update="requestUpdateKeyboards"
                v-on:remove="removeKeyboard"/>
        </div>

        <div v-if="step===6">
            <BotDialogGroupList
                :bot="bot"
                v-if="!load"/>
        </div>

        <div v-if="step===2">
            <BotSlugList
                v-if="botForm.slugs&&!load"
                :slugs="botForm.slugs"
                :bot="bot"
                v-on:add="addSlug"
                v-on:remove="removeSlug"
                v-on:duplicate="duplicateSlug"/>
        </div>

        <div v-if="step===3">
            <BotUserList
                v-if="bot&&!load"
                :bot-id="bot.id"/>
        </div>

        <div class="row" v-if="step===4">
            <div class="col-12 col-md-8" v-if="bot&&!load">
                <Page
                    v-if="!loadPage"
                    :page="page"
                    :bot="bot"
                    v-on:callback="pageCallback"/>
            </div>

            <div class="col-12 col-md-4" v-if="!load&&bot">
                <PagesList
                    v-if="!loadPageList"
                    :bot-id="bot.id"
                    :editor="true"
                    v-on:callback="pageListCallback"/>

            </div>
        </div>


    </form>

    <div class="modal fade"

         id="open-add-script" tabindex="-1" aria-labelledby="open-add-script-label" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="open-construct-label">Добавление скрипта к кнопке</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotSlugList
                        v-on:add="addSlug"
                        v-if="command!=null&&!load"
                        :command="command"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ["company", "bot", "editor"],
    data() {
        return {
            page: null,
            step: 0,
            templates: [],
            load: false,
            loadPage:false,
            loadPageList:false,
            removedSlugs: [],
            removedKeyboards: [],

            command: null,

            botForm: {
                is_template: false,
                template_description: null,
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                main_channel: null,
                balance: null,
                tax_per_day: null,
                welcome_message: null,
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

                pages: [],

                keyboards: [],
            },
        }
    },
    watch: {
        'botForm.selected_bot_template_id': function (oVal, nVal) {
            if (this.botForm.selected_bot_template_id != null) {
                this.loadMenusByBotTemplate(this.botForm.selected_bot_template_id)
                this.loadSlugsByBotTemplate(this.botForm.selected_bot_template_id)
                this.loadPagesByBotTemplate(this.botForm.selected_bot_template_id)
            }
        }
    },
    mounted() {
        this.loadBotTemplates()

        if (this.bot)
            this.$nextTick(() => {
                this.loadMenusByBotTemplate(this.bot.id)
                this.loadSlugsByBotTemplate(this.bot.id)
                this.loadPagesByBotTemplate(this.bot.id)

                this.botForm = {
                    id: this.bot.id || null,
                    is_template: this.bot.is_template || false,
                    template_description: this.bot.template_description || null,
                    bot_domain: this.bot.bot_domain || null,
                    bot_token: this.bot.bot_token || null,
                    bot_token_dev: this.bot.bot_token_dev || null,
                    order_channel: this.bot.order_channel || null,
                    main_channel: this.bot.main_channel || null,
                    balance: this.bot.balance || null,
                    tax_per_day: this.bot.tax_per_day || null,

                    image: this.bot.image || null,

                    description: this.bot.description || null,

                    info_link: this.bot.info_link || null,

                    social_links: this.bot.social_links || [],

                    maintenance_message: this.bot.maintenance_message || null,
                    welcome_message: this.bot.welcome_message || null,

                    level_1: this.bot.level_1 || 10,
                    level_2: this.bot.level_2 || 0,
                    level_3: this.bot.level_3 || 0,

                    photos: this.bot.photos || [],
                }

            })
    },
    methods: {
        addTextTo(object = {param: null, text: null}) {
            this.botForm[object.param] = object.text;

        },
        duplicateSlug(index) {
            const slug = JSON.stringify(this.botForm.slugs[index])
            this.botForm.slugs.splice(index, 0, JSON.parse(slug))

            this.$notify({
                title: "Конструктор ботов",
                text: "Скрипт успешно продублирован!",
                type: 'success'
            });
        },
        requestUpdateKeyboards() {
            this.loadMenusByBotTemplate(this.bot.id)
        },
        editBtnScript(edit) {
            this.command = edit.command
            this.load = true

            this.$nextTick(() => {
                this.load = false
            })
        },
        removeKeyboard(index) {
            if (this.bot)
                this.removedKeyboards.push(this.botForm.keyboards[index].id);

            this.botForm.keyboards.splice(index, 1)

            let data = new FormData();
            Object.keys(this.botForm)
                .forEach(key => {
                    const item = this.botForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            if (this.removedKeyboards.length > 0)
                data.append("removed_keyboards", JSON.stringify(this.removedKeyboards))


            this.$store.dispatch("updateBot", {
                botForm: data
            }).then((response) => {

            }).catch(err => {

            })


        },
        addSlug(item) {
            this.botForm.slugs.push({
                id: null,
                bot_id: null,
                command: item.command,
                comment: item.comment,
                slug: item.slug
            })

            this.load = true
            this.$nextTick(() => {
                this.load = false
            })

            let btns = document.querySelectorAll(`button[data-bs-dismiss="modal"]`)

            btns.forEach(btn => {
                btn.click();
            })

            this.$notify({
                title: "Конструктор ботов",
                text: "Команда успешно связана со скриптом ",
                type: 'success'
            });

            this.command = null
        },
        removeSlug(index) {
            if (this.bot)
                this.removedSlugs.push(this.botForm.slugs[index].id);

            this.botForm.slugs.splice(index, 1)
        },

        loadMenusByBotTemplate(botId) {
            this.$store.dispatch("loadBotKeyboards", {
                botId: botId
            }).then((resp) => {
                this.botForm.keyboards = resp
            })
        },
        loadSlugsByBotTemplate(botId) {
            this.$store.dispatch("loadBotSlugs", {
                botId: botId
            }).then((resp) => {
                this.botForm.slugs = resp
            })
        },
        loadPagesByBotTemplate(botId) {
            this.$store.dispatch("loadBotPages", {
                botId: botId
            }).then((resp) => {
                this.botForm.pages = resp
            })
        },
        loadBotTemplates() {
            this.$store.dispatch("loadTemplates").then((resp) => {
                this.templates = resp.data

            })
        },
        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.botForm.image = null
            for (let i = 0; i < files.length; i++)
                this.botForm.photos.push(files[i])
        },
        addItem(name) {
            this.botForm[name].push("")
        },
        addSocialLinks() {
            this.botForm.social_links.push({
                title: null,
                url: null
            })
        },
        removeItem(name, index) {
            this.botForm[name].splice(index, 1)
        },
        removePhoto(index) {
            if (index)
                this.botForm.photos.splice(index, 1)
            else
                this.botForm.image = null
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

            if (this.bot) {
                if (this.removedSlugs.length > 0)
                    data.append("removed_slugs", JSON.stringify(this.removedSlugs))

                if (this.removedKeyboards.length > 0)
                    data.append("removed_keyboards", JSON.stringify(this.removedKeyboards))
            }

            if (this.company)
                data.append("company_id", this.company.id)

            for (let i = 0; i < this.botForm.photos.length; i++)
                data.append('images[]', this.botForm.photos[i]);

            data.delete("photos")

            this.$store.dispatch((this.bot == null ? "createBot" : "updateBot"), {
                botForm: data
            }).then((response) => {
                this.$emit("callback", response.data)
                this.$notify({
                    title: "Конструктор ботов",
                    text: (this.bot == null ? "Бот успешно создан!" : "Бот успешно обновлен!"),
                    type: 'success'
                });

                this.botForm = {
                    is_template: false,
                    template_description: null,
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

                    pages: [],

                    keyboards: [],
                }
            }).catch(err => {

            })


        },

        pageListCallback(page) {
            this.loadPage = true
            this.page = page
            this.$nextTick(() => {
                this.loadPage = false

            });
        },
        pageCallback(page) {
            this.loadPageList = true
            this.$nextTick(() => {
                this.loadPageList = false
            });
        }
    }
}
</script>
<style lang="scss">

.img-preview, .photo-loader {
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 42px;
    background: white;
    border-radius: 10px;
    border: 1px lightgray solid;
    position: relative;
}

.img-preview img, .photo-loader img {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
}

.img-preview .remove {
    display: none;
    position: absolute;
    z-index: 2;

    a {
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }
}

.img-preview:hover .remove {
    display: flex;
}

.fixed-footer {

    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    min-height: 70px;
    z-index: 990;
    padding: 0px;
    box-sizing: border-box;

    border: none;
    background: transparent;

    & .container {
        background: white;
        border: 1px #e3e3e3 solid;
        padding: 10px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px 0px;
        border-radius: 10px;
    }
}

.bot-sub-menu {
    position: sticky;
    top: 55px;
    background: white;
    z-index: 1000;
}

.bot-footer-menu {
    position: sticky;
    bottom: 10px;
    background: white;
    z-index: 990;
}

.custom-group-dropdown-btn {
    border-radius: 0px 5px 5px 0px !important;
    border-left: none !important;
}
</style>

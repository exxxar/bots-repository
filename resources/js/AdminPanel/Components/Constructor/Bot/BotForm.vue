<script setup>
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";
import TextHelper from "@/AdminPanel/Components/Constructor/Helpers/TextHelper.vue";
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";

import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue"
import ImageMenu from "@/AdminPanel/Components/Constructor/Deprecated/ImageMenu.vue";
import BotDialogGroupList from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import Shop from "@/AdminPanel/Components/Constructor/Shop/Shop.vue";
import AmoForm from "@/AdminPanel/Components/Constructor/Amo/AmoForm.vue";
import Mail from "@/AdminPanel/Components/Constructor/Mail/Mail.vue";
import BotFields from "@/AdminPanel/Components/Constructor/Bot/BotFields.vue";
import BotMediaTable from "@/AdminPanel/Components/Constructor/BotMediaTable.vue";
</script>
<template>
    <div class="row" v-if="company">
        <div class="col-12">
            <h6>Создаем бот к компании {{ company.title || 'Не установлен' }}</h6>
        </div>
    </div>
    <div class="row mb-3 bot-sub-menu" v-if="editor" style="background: transparent;">
        <div class="col-12">
            <div class="btn-group" role="group" aria-label="Basic outlined example" style="background: white;">
                <button type="button"
                        v-bind:class="{'btn-info text-white':step===0}"
                        @click="setStep(0)"
                        class="btn btn-outline-info"><i class="fa-solid fa-info mr-1"></i> Информация о боте
                </button>

                <button type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        v-bind:class="{'btn-info text-white':step===4}"
                        @click="setStep(4)"
                        class="btn btn-outline-info"><i class="fa-solid fa-file mr-2"></i> Страницы
                </button>


                <button type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        v-bind:class="{'btn-info text-white':step===10}"
                        @click="setStep(10)"
                        class="btn btn-outline-info"><i class="fa-solid fa-code mr-2"></i> Настраиваемые поля
                </button>

                <div class="dropdown">
                    <button
                        type="button"
                        :disabled="botForm.selected_bot_template_id===null"
                        class="btn btn-outline-info dropdown-toggle custom-group-dropdown-btn" href="#" role="button"
                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                        <li><a class="dropdown-item" href="#bot-menu-template" @click="setStep(1)"><i
                            class="fa-solid fa-keyboard mr-2"></i>Все клавиатуры в боте</a></li>
                        <li><a class="dropdown-item" href="#bot-slugs" @click="setStep(2)"><i
                            class="fa-solid fa-scroll mr-2"></i>Все скрипты в боте</a></li>
                        <li><a class="dropdown-item" href="#bot-dialogs" @click="setStep(6)"><i
                            class="fa-solid fa-comment-dots mr-2"></i>Все диалоги в боте</a></li>
                        <li><a class="dropdown-item" href="#bot-users" @click="setStep(3)"><i
                            class="fa-solid fa-users mr-2"></i>Все пользователи в боте</a></li>
                        <li><a class="dropdown-item" href="#bot-news" @click="setStep(9)"><i
                            class="fa-regular fa-newspaper mr-2"></i> Новостной канал</a></li>
                        <li><a class="dropdown-item" href="#bot-amo" @click="setStep(7)"><i
                            class="fa-solid fa-list-check mr-2"></i> AMO CRM</a></li>
                        <li><a class="dropdown-item" href="#bot-shop" @click="setStep(8)"><i
                            class="fa-brands fa-shopify mr-2"></i> Магазин</a></li>
                        <li><a class="dropdown-item" href="#bot-media" @click="setStep(11)"><i
                            class="fa-brands fa-shopify mr-2"></i> Медиа файлы бота</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form
        class="pb-5 mb-5"
        v-if="step===0"
        v-on:submit.prevent="addBot">
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
                            <span class="badge rounded-pill text-bg-warning m-0">желательно</span>
                        </label>
                        <select class="form-control"
                                aria-label="Шаблон бота"
                                v-model="botForm.selected_bot_template_id"
                                aria-describedby="bot-level-2">
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
                           :value="botForm.is_template"
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
                                <div>Если Вы создаете шаблон, а не реального бота
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

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Настройка параметров бота в BotFather</h6>


                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center"
                                   id="bot-title">
                                              <span>
                                                  Название бота
                                                  <small class="text-secondary" v-if="botForm.title!=null">Длина текста {{
                                                          botForm.title.length
                                                      }}/64</small>
                                              </span>

                                <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                            </label>

                            <input type="text" class="form-control"
                                   placeholder="Текст названия"
                                   aria-label="Текст названия"
                                   v-model="botForm.title"
                                   maxlength="64"
                                   aria-describedby="bot-title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center"
                                   id="bot-short-description">
                                              <span>
                                                  Короткое описание бота
                                                  <small class="text-secondary" v-if="botForm.short_description!=null">Длина текста {{
                                                          botForm.title.length
                                                      }}/120</small>
                                              </span>

                                <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                            </label>

                            <textarea class="form-control"
                                      placeholder="Короткий текст описания бота"
                                      aria-label="Короткий текст описания бота"
                                      v-model="botForm.short_description"
                                      maxlength="120"
                                      aria-describedby="bot-short-description">
                                </textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between align-items-center"
                                   id="bot-long-description">
                                              <span>
                                                  Длинное описание бота
                                                  <small class="text-secondary" v-if="botForm.long_description!=null">Длина текста {{
                                                          botForm.title.length
                                                      }}/512</small>
                                              </span>

                                <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                            </label>

                            <textarea class="form-control"
                                      placeholder="Длинный текст описания бота"
                                      aria-label="Длинный текст описания бота"
                                      v-model="botForm.long_description"
                                      maxlength="512"
                                      aria-describedby="bot-long-description">
                                </textarea>
                        </div>


                        <div class="row"
                             :key="'commands-'+index"
                             v-for="(item, index) in botForm.commands">
                            <div class="col-5">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="Название команды"
                                           aria-label="Название команды"
                                           maxlength="255"
                                           v-model="botForm.commands[index].command"
                                           :aria-describedby="'bot-command-'+index" required>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="Описание команды"
                                           aria-label="Описание команды"
                                           maxlength="255"
                                           v-model="botForm.commands[index].description"
                                           :aria-describedby="'bot-command-description-'+index" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeCommands(index)"
                                    class="btn btn-outline-danger w-100"><i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    @click="addCommands()"
                                    class="btn btn-outline-success w-100">Добавить еще команду
                                </button>
                            </div>
                        </div>

                    </div>
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
                    <small><a
                        @click="getChatLink(botForm.order_channel)"
                        href="javascript:void(0)">Узнать ссылку</a>(будет отправлена в бота)</small>
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
                    <small><a
                        @click="getChatLink(botForm.main_channel)"
                        href="javascript:void(0)">Узнать ссылку</a>(будет отправлена в бота)</small>
                </div>
            </div>

            <div class="col-12 mb-2" v-if="botForm.order_channel">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_threads"
                           type="checkbox"
                           id="need-payments">
                    <label class="form-check-label" for="need-payments">
                        Необходимо добавить рассылку по топикам для канала заказов (он же системный канал)
                    </label>
                </div>

            </div>

            <div class="col-12 mb-2" v-if="need_threads && botForm.order_channel">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <p>Для того, чтоб узнать идентификатор топика в группе впишите в чат "Мой id"</p>

                    <a class="btn btn-outline-info"
                       @click="createBotTopics"
                       href="javascript:void(0)">
                        <i class="fa-solid fa-paperclip mr-2"></i>Создать топики автоматически
                    </a>
                </div>

                <ul class="list-group">
                    <li v-for="(thread, index) in botForm.message_threads" class="list-group-item">
                        <p class="mb-0">{{ thread.title }} ({{ thread.key }})</p>
                        <input type="number" class="form-control"
                               min="0"
                               max="10000"
                               placeholder="Идентификатор топика"
                               v-model="botForm.message_threads[index].value">
                    </li>
                </ul>
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
                           min="0"
                           aria-describedby="bot-level-3">
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="mb-3">
                    <label class="form-label" id="cashback-fired-period">Период сгорания CashBack</label>
                    <select class="form-control" v-model="botForm.cashback_fire_period" id="cashback-fired-period">
                        <option :value="item.value" v-for="item in cashback_fire_periods">
                            {{ item.title || 'Не указано' }}
                        </option>
                    </select>
                </div>
                <div class="mb-3" v-if="botForm.cashback_fire_period>0">
                    <label class="form-label" id="cashback-fired-level">Уровень сгорания CashBack, %</label>
                    <input type="number" class="form-control"
                           placeholder="%"
                           aria-label="уровень сгорания CashBack"
                           v-model="botForm.cashback_fire_percent"
                           min="0"
                           max="100"
                           aria-describedby="cashback-fired-level">
                </div>
            </div>

            <div class="mb-2">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_cashback_config"
                           type="checkbox"
                           id="need-cashback-config">
                    <label class="form-check-label" for="need-cashback-config">
                        Необходимо настроить CashBack по категориям
                    </label>
                </div>

            </div>

            <div class="mb-2" v-if="need_cashback_config">
                <h6>Настройка категорий CashBack-а</h6>


                <div class="d-flex justify-content-between mb-2 flex-wrap"
                     :key="'social-link'+index"
                     v-for="(item, index) in botForm.cashback_config">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small>Название категории</small>

                        <button
                            type="button"
                            @click="removeCashBackConfig(index)"
                            class="btn btn-link text-danger"><i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control mb-2 w-100"
                           placeholder="Название категории"
                           aria-label="Название категории"
                           maxlength="255"
                           v-model="botForm.cashback_config[index].title"
                           :aria-describedby="'bot-cashback-config-'+index" required>


                </div>
                <button
                    type="button"
                    @click="addCashBackConfig()"
                    class="btn mb-2 rounded-sm text-uppercase btn-outline-info w-100">
                    Добавить еще категорию
                </button>
                <div class="divider divider-small my-3 bg-highlight "></div>
            </div>


            <div class="col-md-12 col-12 mb-2">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-check-label" for="warning-rules">
                                <i class="fa-solid fa-triangle-exclamation text-danger"></i> Правила критических
                                оповещений
                            </label>
                            <select class="form-control"
                                    v-model="selected_warning"
                                    @change="addWarning"
                                    id="warning-rules">
                                <option :value="null">Не выбрано</option>
                                <option :value="item" v-for="item in filteredWarnings">
                                    {{ item.title }}
                                </option>
                            </select>

                        </div>

                        <div class="card my-2 p-2" v-for="(warn, index) in botForm.warnings">

                            <div class="row">
                                <div class="col-md-4 d-flex align-items-center">
                                    <p class="m-0">{{ getWarning(warn.rule_key).title || 'Не найдено' }}</p>
                                </div>
                                <div class="col-md-2">

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               v-model="botForm.warnings[index].is_active"
                                               type="checkbox"
                                               :id="'warning-is-active-'+index">
                                        <label class="form-check-label" :for="'warning-is-active-'+index">
                                            <span v-if="botForm.warnings[index].is_active">Вкл</span>
                                            <span v-else>Выкл</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">

                                    <input type="number" class="form-control"
                                           placeholder="Значение"
                                           v-model="botForm.warnings[index].rule_value"
                                           min="0"
                                           aria-describedby="bot-level-3">
                                </div>
                                <div class="col-md-1 d-flex justify-content-center">
                                    <button
                                        @click="removeWarning(index)"
                                        type="button" class="btn btn-outline-danger"><i
                                        class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 mb-2">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_payments"
                           type="checkbox"
                           id="need-payments">
                    <label class="form-check-label" for="need-payments">
                        Необходимо подключить платежную систему
                    </label>
                </div>

            </div>

            <div class="col-md-12 col-12" v-if="need_payments">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox"
                           :value="botForm.auto_cashback_on_payments"
                           v-model="botForm.auto_cashback_on_payments"
                           id="bot-auto-cashback-on-payments">
                    <label class="form-check-label" for="bot-auto-cashback-on-payments">
                        Начислять CashBack автоматически после успешной оплаты
                    </label>
                </div>

                <div class="mb-3">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Если в боте планируется оплата, то необходимо через BotFather привязать нужную
                                платежную систему и указать в данном поле полученный токен
                            </div>
                        </template>
                    </Popper>
                    <label class="form-label" id="payment_provider_token">Токен платежной системы
                        <a href="https://t.me/botfather" target="_blank">Подключить</a>
                    </label>


                    <input type="text" class="form-control"
                           placeholder="Токен"
                           aria-label="Токен"
                           v-model="botForm.payment_provider_token"
                           aria-describedby="payment_provider_token">
                </div>


            </div>

            <div class="col-12 mb-2">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_shop"
                           type="checkbox"
                           id="need-shop">
                    <label class="form-check-label" for="need-shop">
                        Необходимо интегрировать магазин в бота
                    </label>
                </div>

            </div>

            <div class="col-md-12 col-12" v-if="need_shop">
                <div class="mb-3">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                            </div>
                        </template>
                    </Popper>
                    <label class="form-label" id="bot-vk_shop_link">Ссылка на группу ВК с товарами
                        <a href="https://vk.com/groups?w=groups_create" target="_blank">Создать</a>
                    </label>


                    <input type="url" class="form-control"
                           placeholder="Ссылка на группу ВК"
                           aria-label="ссылка на группу ВК"
                           v-model="botForm.vk_shop_link"
                           aria-describedby="vk_shop_link">
                </div>
            </div>

            <div class="col-md-12 col-12">
                <div class="mb-3">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Ссылка на внешний сервис обработки данных
                            </div>
                        </template>
                    </Popper>
                    <label class="form-label" id="callback_link">Ссылка на внешний сервис обработки данных
                    </label>


                    <input type="url" class="form-control"
                           placeholder="Ссылка на внешний сервис"
                           aria-label="ссылка на внешний сервис"
                           v-model="botForm.callback_link"
                           aria-describedby="callback_link">
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
    </form>

    <div v-if="step===7" class="pb-5 mb-5">
        <AmoForm
            :data="botForm.amo"
            v-if="!load"
        />
    </div>

    <div v-if="step===11" class="pb-5 mb-5">
        <BotMediaTable
            v-if="!load"
        />
    </div>


    <div v-if="step===8" class="pb-5 mb-5">
        <Shop v-if="!load"/>
    </div>

    <div v-if="step===10" class="pb-5 mb-5">
        <BotFields v-if="!load"/>
    </div>

    <div v-if="step===1" class="pb-5 mb-5">
        <KeyboardList
            :select-mode="false"
            v-if="!load"/>
    </div>

    <div v-if="step===6" class="pb-5 mb-5">
        <BotDialogGroupList
            v-if="!load"/>
    </div>

    <div v-if="step===2" class="pb-5 mb-5">
        <BotSlugList
            v-if="!load"
        />
    </div>

    <div v-if="step===3" class="pb-5 mb-5">
        <BotUserList
            v-if="!load"/>
    </div>

    <div class="row pb-5 mb-5" v-if="step===4">
        <div class="col-12 col-md-8" v-if="!load">
            <Page
                v-if="!loadPage"
                :page="page"
                v-on:callback="pageCallback"/>
        </div>

        <div class="col-12 col-md-4" v-if="!load">
            <PagesList
                :editor="true"
                v-on:callback="pageListCallback"/>

        </div>
    </div>

    <div v-if="step===9" class="pb-5 mb-5">
        <Mail/>
    </div>


</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["company", "bot", "editor"],
    data() {
        return {

            selected_warning: null,
            page: null,
            step: 0,
            templates: [],
            load: false,
            loadPage: false,
            needPageListUpdate: false,
            need_threads: false,
            need_cashback_config: false,
            need_payments: false,
            need_shop: false,
            command: null,
            warnings: [
                {
                    title: "Сумма чека больше чем",
                    key: "bill_sum_more_then"
                },
                {
                    title: "Сумма начисления кэшбэка больше чем",
                    key: "cashback_up_sum_more_then"
                },
                {
                    title: "Сумма списания кэшбэка больше чем",
                    key: "cashback_down_sum_more_then"
                }
            ],
            cashback_fire_periods: [
                {
                    title: 'Не сгорает',
                    value: 0,
                },
                {
                    title: '7 дней',
                    value: 7,
                },
                {
                    title: '15 дней',
                    value: 15,
                },
                {
                    title: '30 дней',
                    value: 30,
                },
                {
                    title: '60 дней',
                    value: 60,
                },
                {
                    title: '60 дней',
                    value: 90,
                },
                {
                    title: '120 дней',
                    value: 120,
                },
                {
                    title: '180 дней',
                    value: 180,
                },
                {
                    title: '360 дней',
                    value: 360,
                }
            ],

            botForm: {
                title: null,
                short_description: null,
                long_description: null,

                is_template: false,
                auto_cashback_on_payments: false,
                template_description: null,
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                message_threads: null,
                cashback_config: null,
                main_channel: null,
                vk_shop_link: null,
                callback_link: null,
                balance: null,
                tax_per_day: null,
                welcome_message: null,
                image: null,
                cashback_fire_percent: 0,
                cashback_fire_period: 0,
                description: null,
                info_link: null,
                social_links: [],
                maintenance_message: null,
                payment_provider_token: null,
                level_1: 10,
                level_2: 0,
                level_3: 0,
                photos: [],
                commands: null,
                selected_bot_template_id: null,
                pages: [],
                amo: null,
                warnings: []
            },
        }
    },
    watch: {
        'need_threads': function (oVal, nVal) {
            let threads = [
                {
                    title: 'Отзывы',
                    key: 'reviews',
                    value: null,
                },
                {
                    title: 'Начисление cashback',
                    key: 'cashback',
                    value: null,
                },
                {
                    title: 'Вопросы',
                    key: 'questions',
                    value: null,
                },
                {
                    title: 'Конкурсы',
                    key: 'actions',
                    value: null,
                },
                {
                    title: 'Заказы',
                    key: 'orders',
                    value: null,
                },
                {
                    title: 'Вывод средств',
                    key: 'ask-money',
                    value: null,
                }
            ];

            if (this.need_threads && !this.botForm.message_threads) {
                this.botForm.message_threads = threads
            }

            if (this.need_threads && this.botForm.message_threads) {
                threads.forEach(item => {
                    let index = this.botForm.message_threads.findIndex(sub => sub.key === item.key)

                    if (index === -1)
                        this.botForm.message_threads.push(item)
                })
            }
        },
        'need_payments': function (oVal, nVal) {
            if (!this.need_payments) {
                this.botForm.auto_cashback_on_payments = false
            }
        },
        'botForm.selected_bot_template_id': function (oVal, nVal) {
            if (this.botForm.selected_bot_template_id != null) {
                this.loadSlugsByBotTemplate(this.botForm.selected_bot_template_id)
                this.loadPagesByBotTemplate(this.botForm.selected_bot_template_id)
            }
        }
    },
    computed: {
        ...mapGetters(['getSlugs']),
        filteredWarnings() {
            if (this.botForm.warnings.length === 0)
                return this.warnings;

            return this.warnings.filter(item => {
                return !(this.botForm.warnings.findIndex(sub => sub.rule_key === item.key) >= 0)
            })
        }
    },
    mounted() {
        this.loadBotTemplates()

        if (this.bot)
            this.$nextTick(() => {
                // this.loadSlugsByBotTemplate(this.bot.id)
                //this.loadPagesByBotTemplate(this.bot.id)

                this.botForm = {
                    id: this.bot.id || null,

                    title: this.bot.title || null,
                    short_description: this.bot.short_description || null,
                    long_description: this.bot.long_description || null,

                    is_template: this.bot.is_template || false,
                    auto_cashback_on_payments: this.bot.auto_cashback_on_payments || false,
                    template_description: this.bot.template_description || null,
                    bot_domain: this.bot.bot_domain || null,
                    bot_token: this.bot.bot_token || null,
                    bot_token_dev: this.bot.bot_token_dev || null,
                    order_channel: this.bot.order_channel || null,
                    message_threads: this.bot.message_threads || null,
                    cashback_config: this.bot.cashback_config || null,
                    main_channel: this.bot.main_channel || null,
                    balance: this.bot.balance || null,
                    tax_per_day: this.bot.tax_per_day || null,
                    vk_shop_link: this.bot.vk_shop_link || null,
                    callback_link: this.bot.callback_link || null,
                    cashback_fire_percent: this.bot.cashback_fire_percent || 0,
                    cashback_fire_period: this.bot.cashback_fire_period || 0,
                    image: this.bot.image || null,
                    commands: this.bot.commands || null,

                    description: this.bot.description || null,

                    info_link: this.bot.info_link || null,

                    social_links: this.bot.social_links || [],

                    maintenance_message: this.bot.maintenance_message || null,
                    welcome_message: this.bot.welcome_message || null,
                    payment_provider_token: this.bot.payment_provider_token || null,

                    level_1: this.bot.level_1,
                    level_2: this.bot.level_2,
                    level_3: this.bot.level_3,

                    photos: this.bot.photos || [],
                    warnings: this.bot.warnings || [],

                    amo: this.bot.amo || null,
                }

                if (this.botForm.commands == null) {
                    this.botForm.commands = [
                        {
                            command: "/start",
                            description: "начни с этой команды"
                        },
                        {
                            command: "/admins",
                            description: "доступные администраторы в системе"
                        },
                        {
                            command: "/help",
                            description: "как использовать систему"
                        },
                        {
                            command: "/about",
                            description: "о CashMan"
                        }
                    ]
                }

                if (this.botForm.message_threads)
                    this.need_threads = true

                if (this.botForm.payment_provider_token)
                    this.need_payments = true


                if (this.botForm.cashback_config)
                    this.need_cashback_config = true


                this.setStep(localStorage.getItem("cashman_set_botform_step_index") || 0)
            })
    },
    methods: {
        createBotTopics(){
            this.$store.dispatch("createBotTopics", {
                dataObject: {
                    topics: this.botForm.message_threads,
                    bot_id: this.bot.id
                },
            }).then((resp) => {
                this.botForm.message_threads = resp.data
            })
        },
        getChatLink(chatId) {
            this.$store.dispatch("loadChatInfo", {
                dataObject: {
                    chat_id: chatId,
                    bot_id: this.bot.id
                },
            }).then((resp) => {
                console.log("chat info", resp)
            })
        },
        setStep(index) {
            this.step = parseInt(index)
            localStorage.setItem("cashman_set_botform_step_index", index)
        },
        addTextTo(object = {param: null, text: null}) {
            this.botForm[object.param] = object.text;

        },
        removeCommands(index) {
            this.botForm.commands.splice(index, 1)
        },
        addCommands() {
            if (!this.botForm.commands)
                this.botForm.commands = [];

            this.botForm.commands.push({
                command: null,
                description: null
            })
        }
        , loadSlugsByBotTemplate(botId) {

            this.load = true

            this.$store.dispatch("loadSlugs", {
                dataObject: {
                    botId: botId
                },
                size: 1000,
            }).then((resp) => {

                this.botForm.slugs = this.getSlugs

                this.$nextTick(() => {
                    this.load = false

                });
            })
        }
        ,
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

                if (this.bot == null)
                    this.botForm = {
                        title: null,
                        short_description: null,
                        long_description: null,
                        is_template: false,
                        auto_cashback_on_payments: false,
                        template_description: null,
                        bot_domain: null,
                        bot_token: null,
                        bot_token_dev: null,
                        order_channel: null,
                        message_threads: null,
                        main_channel: null,
                        balance: null,
                        tax_per_day: null,
                        callback_link: null,
                        cashback_fire_percent: 0,
                        cashback_fire_period: 0,
                        image: null,

                        description: null,

                        info_link: null,

                        social_links: [],

                        maintenance_message: null,
                        payment_provider_token: null,

                        level_1: 10,
                        level_2: 0,
                        level_3: 0,

                        photos: [],
                        warnings: [],

                        selected_bot_template_id: null,

                        pages: [],


                    }
            }).catch(err => {

            })


        }
        ,

        removeCashBackConfig(index) {
            this.botForm.cashback_config.splice(index, 1)
        }
        ,
        addCashBackConfig() {

            this.botForm.cashback_config = this.botForm.cashback_config == null ? [] : this.botForm.cashback_config;

            this.botForm.cashback_config.push({
                title: null,
            })

        }
        ,

        pageListCallback(page) {
            this.loadPage = true
            this.page = page
            this.$nextTick(() => {
                this.loadPage = false

            });
        }
        ,
        getWarning(key) {
            let item = this.warnings.find(item => item.key === key)


            return (!item) ? {
                title: 'Не найдено'
            } : item;

        }
        ,
        removeWarning(index) {
            this.botForm.warnings.splice(index, 1)
        }
        ,
        addWarning() {

            const item = this.selected_warning

            this.botForm.warnings.push({
                rule_key: item.key,
                rule_value: 0,
                is_active: true,
            })

            this.selected_warning = null

        }
        ,
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
.popper {
    background: #06135f !important;
    color: white !important;
    padding: 10px !important;
    border-radius: 10px !important;
}


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

<script setup>

</script>
<template>

    <div class="card card-style bg-1"
         style="height: 330px;">
        <div class="card-center" v-if="bot">
            <div class="w-100 d-flex justify-content-center p-3">
                <img
                    class="object-cover" style="width:100px; border-radius:50%;"
                    v-lazy="'/images-by-company-id/'+bot.company_id+'/'+bot.image" alt="">
            </div>

            <h2 class="color-white font-700 text-center mb-2">
                {{ bot.bot_domain || 'Нет домена' }}
            </h2>
            <p class="color-white text-center opacity-60 mt-n1 my-1">
                <a href="#" class="color-white"><i class="fa-solid fa-sack-dollar"></i> Баланс {{ bot.balance || '0' }}
                    ₽</a>
            </p>
            <p class="color-white text-center opacity-60 mt-n1 my-1">
                <i class="fa-solid fa-hand-holding-dollar"></i> Тариф {{ bot.tax_per_day || '0' }} ₽/день
            </p>

            <p class="color-white text-center opacity-60 mt-n1 my-1">
                <i class="fa-regular fa-calendar-days"></i> Дней {{ bot.balance / bot.tax_per_day }} осталось
            </p>

            <div class="w-100 d-flex justify-content-center py-4">

                <div class="custom-control ios-switch">
                    <input type="checkbox"
                           @change="switchStatus"
                           v-model="botForm.is_active"
                           class="ios-input" id="toggle-id-1">
                    <label class="custom-control-label" for="toggle-id-1"></label>
                </div>
                <span class="ml-5 text-white" v-if="botForm.is_active">Вкл</span>
                <span class="ml-5 text-white" v-else>Выкл</span>
            </div>


        </div>
        <div class="card-center" v-else>
            <p>Загружаем данные бота</p>
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border color-orange-dark" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>

    <form
        v-on:submit.prevent="addBot"
        class="card card-style">
        <div class="content" v-if="bot">
            <div class="mb-0">
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between  align-items-center mt-2" id="bot-domain">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Строго взять из BotFather! ТО что при создании с окончанием на "bot"</div>
                                </template>
                            </Popper>
                            Доменное имя бота из BotFather
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>

                    </label>
                    <input type="text" class="form-control"
                           placeholder="Имя бота"
                           aria-label="Имя бота"
                           v-model="botForm.bot_domain"
                           maxlength="255"
                           aria-describedby="bot-domain" required>
                    <p v-if="botForm.bot_domain">Проверить работу бота <a
                        href="javascript:void(0)"
                        @click="openUrl('https://t.me/'+botForm.bot_domain)">@{{
                            botForm.bot_domain
                        }}</a>
                    </p>
                </div>

                <div class="mb-2">
                    <label
                        class="form-label d-flex justify-content-between mt-2 d-flex align-items-center flex-wrap"
                        id="bot-token">

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
                        </div>

                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>

                        <a href="javascript:void(0)"
                           @click="openUrl('https://t.me/botfather')"
                           class="w-100"
                           target="_blank">Создать нового бота в ТГ</a>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Токен"
                           aria-label="Токен"
                           v-model="botForm.bot_token"
                           maxlength="255"
                           aria-describedby="bot-token" required>
                </div>


                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="bot-token-dev">Токен бота
                        (для тестирования)</label>
                    <input type="text" class="form-control"
                           placeholder="Токен"
                           aria-label="Токен"
                           v-model="botForm.bot_token_dev"
                           maxlength="255"
                           aria-describedby="bot-token-dev">
                </div>


                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <label
                            class="form-label d-flex justify-content-between mt-2 d-flex  align-items-center w-100"
                            id="bot-order-channel">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Ввести адрес ссылки на канал в форму после добавления тоукена
                                        </div>
                                    </template>
                                </Popper>
                                Канал для заказов (id)
                                <span @click="requestChannelId('order_channel')"><i
                                    class="fa-brands fa-telegram ml-2 color-blue2-dark"></i></span>
                            </div>
                            <Popper>
                                <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                <template #content>
                                    <div>Нужно
                                    </div>
                                </template>
                            </Popper>


                        </label>


                    </div>
                    <input type="text" class="form-control"
                           placeholder="id канала"
                           aria-label="id канала"
                           v-model="botForm.order_channel"
                           maxlength="255"
                           aria-describedby="bot-order-channel">
                </div>


                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <label class="form-label d-flex justify-content-between mt-2" id="bot-main-channel">Канал
                            для постов (id,рекламный)
                            <span @click="requestChannelId('main_channel')"><i
                                class="fa-brands fa-telegram ml-2 color-blue2-dark"></i></span>
                        </label>

                    </div>
                    <input type="text" class="form-control"
                           placeholder="id канала"
                           aria-label="id канала"
                           v-model="botForm.main_channel"
                           maxlength="255"
                           aria-describedby="bot-main-channel">
                </div>


                <div class="mb-2">
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

                <div class="mb-2" v-if="need_threads">
                    <p class="mb-2 font-italic">Для того, чтоб узнать идентификатор топика в группе впишите в чат <strong>"Мой id"</strong></p>
                    <ul class="list-group">
                        <li v-for="(thread, index) in botForm.message_threads" class="list-group-item">
                            <p class="mb-0">{{ thread.title }} ({{thread.key}})</p>
                            <input type="text" class="form-control"
                                   placeholder="Идентификатор топика"
                                   v-model="botForm.message_threads[index].value">
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mb-0" v-if="botForm.bot_token">


                <div class="mb-2">
                    <div class="d-flex justify-content-between flex-wrap">
                        <label
                            class="form-label d-flex justify-content-between  align-items-center mb-0  flex-wrap w-100"
                            id="bot-description">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Отобразится пользователю при первом запуске</div>
                                    </template>
                                </Popper>
                                Приветственное сообщение

                            </div>
                            <Popper>
                                <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                <template #content>
                                    <div>Нужно
                                    </div>
                                </template>
                            </Popper>


                        </label>

                        <small class="text-gray-400 w-100" style="font-size:10px;"
                               v-if="botForm.welcome_message">
                            Длина текста {{ botForm.welcome_message.length }}</small>
                    </div>
                    <textarea class="form-control font-12"
                              placeholder="Текстовое приветствие при запуске бота"
                              aria-label="Текстовое приветствие при запуске бота"
                              v-model="botForm.welcome_message"
                              style="min-height:200px;"
                              aria-describedby="bot-description" required>
                    </textarea>
                </div>


                <div class="mb-2">
                    <div class="d-flex justify-content-between flex-wrap">
                        <label class="form-label d-flex justify-content-between  align-items-center w-100 mb-0"
                               id="bot-description">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Для меню "О Боте"</div>
                                    </template>
                                </Popper>
                                Описание бота
                            </div>
                            <Popper>
                                <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                <template #content>
                                    <div>Нужно
                                    </div>
                                </template>
                            </Popper>


                        </label>
                        <small class="text-gray-400 w-100" style="font-size:10px;"
                               v-if="botForm.description">
                            Длина текста {{ botForm.description.length }} / 255</small>

                    </div>

                    <textarea class="form-control  font-12"
                              placeholder="Текстовое описание бота"
                              aria-label="Текстовое описание бота"
                              v-model="botForm.description"
                              style="min-height:200px;"
                              aria-describedby="bot-description" required>
                    </textarea>
                </div>


                <div class="mb-2">

                    <div class="d-flex justify-content-between flex-wrap">
                        <label class="form-label d-flex justify-content-between align-items-center w-100 mb-0"
                               id="bot-maintenance-message">

                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Для меню "О Боте"</div>
                                    </template>
                                </Popper>
                                Режим тех. работ
                            </div>
                            <Popper>
                                <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                <template #content>
                                    <div>Нужно
                                    </div>
                                </template>
                            </Popper>


                        </label>
                        <small class="text-gray-400 w-100" style="font-size:10px;"
                               v-if="botForm.maintenance_message">
                            Длина текста {{ botForm.maintenance_message.length }} / 255 </small>
                    </div>
                    <textarea class="form-control font-12"
                              placeholder="Текстовое сообщение"
                              aria-label="Текстовое сообщение"
                              v-model="botForm.maintenance_message"
                              maxlength="255"
                              style="min-height:200px;"
                              aria-describedby="bot-maintenance-message" required>
                    </textarea>

                </div>


                <div class="mb-2">

                    <label class="form-label d-flex justify-content-between  align-items-center mt-2" id="bot-level-1">
                        Уровень 1 CashBack, %
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>

                    </label>
                    <input type="number" class="form-control"
                           placeholder="%"
                           aria-label="уровень CashBack"
                           v-model="botForm.level_1"
                           max="50"
                           min="0"
                           aria-describedby="bot-level-1" required>

                </div>

                <div class="mb-2">

                    <label class="form-label d-flex justify-content-between mt-2" id="bot-level-2">Уровень 2
                        CashBack, %</label>
                    <input type="number" class="form-control"
                           placeholder="%"
                           aria-label="уровень CashBack"
                           v-model="botForm.level_2"
                           max="50"
                           min="0"
                           aria-describedby="bot-level-2">

                </div>

                <div class="mb-2">

                    <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">Уровень 3
                        CashBack, %</label>
                    <input type="number" class="form-control"
                           placeholder="%"
                           aria-label="уровень CashBack"
                           v-model="botForm.level_3"
                           max="50"
                           min="0"
                           aria-describedby="bot-level-3">

                </div>


                <div class="mb-2">
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

                    <div class="card" v-for="(warn, index) in botForm.warnings">

                        <p class="m-0">{{ getWarning(warn.rule_key).title || 'Не найдено' }}</p>

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
                        <input type="number" class="form-control mb-2"
                               placeholder="Значение"
                               v-model="botForm.warnings[index].rule_value"
                               min="0"
                               aria-describedby="bot-level-3">
                        <button
                            @click="removeWarning(index)"
                            type="button" class="btn btn-outline-danger"><i
                            class="fa-solid fa-trash-can"></i>
                        </button>

                        <div class="divider divider-small my-3 bg-highlight "></div>

                    </div>
                </div>


                <div class="mb-2">
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

                <div class=" mb-2" v-if="need_payments">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               :value="botForm.auto_cashback_on_payments"
                               v-model="botForm.auto_cashback_on_payments"
                               id="bot-auto-cashback-on-payments">
                        <label class="form-check-label" for="bot-auto-cashback-on-payments">
                            Начислять CashBack автоматически после успешной оплаты
                        </label>
                    </div>

                    <div class="mb-2">

                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Если в боте планируется оплата, то необходимо через BotFather привязать
                                            нужную
                                            платежную систему и указать в данном поле полученный токен
                                        </div>
                                    </template>
                                </Popper>
                                Токен платежной системы
                            </div>
                            <a href="https://t.me/botfather" target="_blank">Подключить</a>
                        </label>


                        <input type="text" class="form-control"
                               placeholder="Токен"
                               aria-label="уровень CashBack"
                               v-model="botForm.payment_provider_token"
                               aria-describedby="bot-level-3">
                    </div>
                </div>

                <div class=" mb-2">
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

                <div class=" mb-2" v-if="need_shop">


                    <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                                    </div>
                                </template>
                            </Popper>
                            ВК-группа
                        </div>

                        <a href="https://vk.com/groups?w=groups_create" target="_blank">Создать</a>
                    </label>


                    <input type="url" class="form-control"
                           placeholder="Ссылка на группу ВК"
                           aria-label="ссылка на группу ВК"
                           v-model="botForm.vk_shop_link"
                           aria-describedby="vk_shop_link">

                </div>


                <div class=" mb-2">


                    <label class="form-label d-flex justify-content-between mt-2" id="bot-callback-url">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Ссылка на внешний сервис для обратной связи
                                    </div>
                                </template>
                            </Popper>
                            ВК-группа
                        </div>


                    </label>


                    <input type="url" class="form-control"
                           placeholder="Ссылка на внешний сервис"
                           aria-label="Ссылка на внешний сервис"
                           v-model="botForm.callback_link"
                           aria-describedby="callback_link">

                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>

                <div class="mb-2">

                    <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">

                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                                    </div>
                                </template>
                            </Popper>
                            Информационная ссылка
                        </div>

                        <a target="_blank"
                           href="https://telegra.ph">Создать</a>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Ссылка ресурс telegraph"
                           aria-label="Ссылка ресурс telegraph"
                           maxlength="255"
                           v-model="botForm.info_link"
                           :aria-describedby="'bot-info-link'">

                </div>

                <div class="divider divider-small my-3  bg-highlight"></div>
                <h6>Ссылки на соц. сети</h6>


                <div class="d-flex justify-content-between mb-2 flex-wrap"
                     :key="'social-link'+index"
                     v-for="(item, index) in botForm.social_links">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <small>Ссылка</small>

                        <button
                            type="button"
                            @click="removeItem('social_links', index)"
                            class="btn btn-link text-danger"><i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control mb-2 w-100"
                           placeholder="Название ссылки"
                           aria-label="Название ссылки"
                           maxlength="255"
                           v-model="botForm.social_links[index].title"
                           :aria-describedby="'bot-social-link-'+index" required>

                    <input type="text" class="form-control  mb-2 "
                           placeholder="Ссылка на соц.сеть"
                           aria-label="Ссылка на соц.сеть"
                           maxlength="255"
                           v-model="botForm.social_links[index].url"/>


                </div>
                <button
                    type="button"
                    @click="addSocialLinks()"
                    class="btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                    Добавить еще ссылку
                </button>
                <div class="divider divider-small my-3  bg-highlight"></div>
                <h6 class="d-flex justify-content-between  align-items-center">Аватар для бота
                    <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>

                </h6>
                <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
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


                <button
                    :disabled="!botForm.bot_token"
                    type="submit"
                    class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                    Сохранить настройки
                </button>

            </div>
        </div>
        <div class="content" v-else>
            <p>Загружаем данные бота</p>
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border color-orange-dark" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
            </div>
        </div>
    </form>

</template>
<script>

export default {
    data() {
        return {
            selected_warning:null,
            load: false,
            need_payments: false,
            need_shop: false,
            bot: null,
            need_threads:false,
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
            botForm: {
                is_template: false,
                auto_cashback_on_payments: false,
                template_description: null,
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                message_threads: null,
                main_channel: null,
                vk_shop_link: null,
                callback_link: null,
                balance: null,
                tax_per_day: null,
                welcome_message: null,
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
                selected_bot_template_id: null,
                pages: [],
                warnings: [],
                amo: null,
                is_active: false,
            },
        }
    },
    computed:{
        filteredWarnings() {
            if (this.botForm.warnings.length === 0)
                return this.warnings;

            return this.warnings.filter(item => {
                return !(this.botForm.warnings.findIndex(sub => sub.rule_key === item.key) >= 0)
            })
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
            if (this.need_threads && !this.botForm.message_threads ) {
                this.botForm.message_threads = threads
            }

            if (this.need_threads && this.botForm.message_threads){
                threads.forEach(item=>{
                    let index = this.botForm.message_threads.findIndex(sub=>sub.key === item.key)

                    if (index===-1)
                        this.botForm.message_threads.push(item)
                })
            }
        },
        'need_payments': function (oVal, nVal) {
            if (!this.need_payments) {
                this.botForm.auto_cashback_on_payments = false
            }
        },
    },


    mounted() {

        this.loadBotAdminConfig();

        window.addEventListener("select-telegram-channel-id", (e) => {
            this.botForm[e.detail.param] = e.detail.channel
        });


    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

                this.botForm = {
                    id: this.bot.id || null,
                    is_template: this.bot.is_template || false,
                    auto_cashback_on_payments: this.bot.auto_cashback_on_payments || false,
                    template_description: this.bot.template_description || null,
                    bot_domain: this.bot.bot_domain || null,
                    bot_token: this.bot.bot_token || null,
                    bot_token_dev: this.bot.bot_token_dev || null,
                    order_channel: this.bot.order_channel || null,
                    message_threads: this.bot.message_threads || null,

                    main_channel: this.bot.main_channel || null,
                    balance: this.bot.balance || null,
                    tax_per_day: this.bot.tax_per_day || null,
                    vk_shop_link: this.bot.vk_shop_link || null,
                    callback_link: this.bot.callback_link || null,

                    image: this.bot.image || null,

                    description: this.bot.description || null,

                    info_link: this.bot.info_link || null,

                    social_links: this.bot.social_links || [],

                    maintenance_message: this.bot.maintenance_message || null,
                    welcome_message: this.bot.welcome_message || null,
                    payment_provider_token: this.bot.payment_provider_token || null,

                    level_1: this.bot.level_1 || 10,
                    level_2: this.bot.level_2 || 0,
                    level_3: this.bot.level_3 || 0,

                    warnings: this.bot.warnings || [],

                    photos: this.bot.photos || [],

                    amo: this.bot.amo || null,
                    is_active: this.bot.is_active || false,
                }

                if (this.botForm.payment_provider_token)
                    this.need_payments = true

                if (this.botForm.vk_shop_link)
                    this.need_shop = true
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
        switchStatus() {
            this.$store.dispatch("switchBotStatus").then(() => {
                this.$botNotification.success(
                    "Конструктор ботов", "Статус бота успешно изменен!"
                );
            })
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

                this.$botNotification.success(
                    "Конструктор ботов",
                    (this.bot == null ? "Бот успешно создан!" : "Бот успешно обновлен!")
                );

                if (this.bot == null)
                    this.botForm = {
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

                        image: null,

                        description: null,

                        info_link: null,

                        social_links: [],
                        warnings: [],
                        maintenance_message: null,
                        payment_provider_token: null,

                        level_1: 10,
                        level_2: 0,
                        level_3: 0,

                        photos: [],

                        selected_bot_template_id: null,

                        pages: [],
                        is_active: false,

                    }
            }).catch(err => {

            })


        },

        getWarning(key) {
            let item = this.warnings.find(item => item.key === key)


            return (!item) ? {
                title: 'Не найдено'
            } : item;

        },
        removeWarning(index) {
            this.botForm.warnings.splice(index, 1)
        },
        addWarning() {

            const item = this.selected_warning

            this.botForm.warnings.push({
                rule_key: item.key,
                rule_value: 0,
                is_active: true,
            })

            this.selected_warning = null

        },
        requestChannelId(param) {
            this.$botPages.telegramChannelHelper(param);
        },
        openUrl(url) {
            this.tg.openLink(url)
        },
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

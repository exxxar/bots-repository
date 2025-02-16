<script setup>
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
/*import TextHelper from "@/AdminPanel/Components/Constructor/Helpers/TextHelper.vue";*/
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import BotSearchModal from "@/AdminPanel/Components/Constructor/Bot/BotSearchModal.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugSimpleTableList.vue";

</script>
<template>
    <form
        class=" mb-5"
        :ref="'botForm'"
        v-on:submit.prevent="addBot">
        <section class="h-100 gradient-form" v-if="!isValidTelegramToken">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="row g-0">
                                <!-- Левая часть с приветствием -->
                                <div class="col-lg-6 d-flex align-items-center bg-primary">
                                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">

                                        <div class="d-flex justify-content-center mb-3">
                                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                                        </div>

                                        <h4 class="mb-4">Добро пожаловать в раздел создания бота!</h4>
                                        <p class="small mb-2">
                                            Здесь вы можете легко управлять своими Telegram-ботами: создавать новых,
                                            настраивать команды, анализировать статистику и многое другое. Войдите в
                                            систему, чтобы получить доступ ко всем возможностям платформы. Если у вас
                                            ещё нет аккаунта, зарегистрируйтесь — это займёт всего несколько минут.
                                        </p>

                                        <p class="small mb-2 fw-bold">
                                            Для начала рекомендуем ознакомится с обучением!
                                        </p>
                                        <a href="https://telegra.ph/Sozdanie-telegram-bota-02-02" target="_blank"
                                           class="btn w-100 btn-outline-light text-center p-3 mb-2"
                                        >
                                            <i class="fas fa-graduation-cap"></i> Обучение
                                        </a>

                                        <p class="small mb-2">
                                            Начните прямо сейчас и сделайте своих ботов ещё умнее и полезнее! 🚀
                                        </p>
                                        <a
                                            href="https://t.me/botfather" target="_blank"
                                            class=" btn w-100 btn-outline-light text-center p-3 mb-2">
                                            <i class="fab fa-telegram"></i> Создать бота в Телеграм
                                        </a>


                                    </div>

                                </div>

                                <!-- Правая часть с формой входа -->
                                <div class="col-lg-6">


                                    <div class="card-body p-md-5 mx-md-4">
                                        <div class="text-center">
                                            <h1 class="text-primary">NextIT</h1>
                                            <h4 class="mt-1 mb-2 pb-1">Современные решения для бизнеса</h4>
                                        </div>

                                        <div class="alert alert-warning"
                                             v-if="!isValidTelegramToken && botForm.bot_token?.length>0">
                                            Токен не действительный!
                                        </div>
                                        <p class="mb-2">Перед добавлением токена выберите <span
                                            class="fw-bold text-primary">тип бота</span>
                                        </p>

                                        <div class="row">
                                            <!-- Карточка 1: Чистый бот -->
                                            <div class="col-md-6 mb-2">
                                                <div
                                                    @click="botForm.bot_type=0"
                                                    v-bind:class="{'border-primary':botForm.bot_type === 0}"
                                                    class="card cursor-pointer" style="height:120px;">
                                                    <div
                                                        v-bind:class="{'text-primary':botForm.bot_type === 0}"
                                                        class="card-body d-flex flex-column justify-content-center align-items-center  p-3 ">
                                                        <i class="fas fa-robot mb-3" style="font-size:20px;"></i>
                                                        <h6 style="font-size:14px;text-align:center;">Чистый бот<br>(без
                                                            шаблона)</h6>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-6 mb-2" v-for="(item, index) in bot_types">
                                                <div class="card cursor-pointer"
                                                     @click="botForm.bot_type=item.id"
                                                     v-bind:class="{'border-primary':botForm.bot_type === item.id}"
                                                     style="height:120px;">
                                                    <div
                                                        v-bind:class="{'text-primary':botForm.bot_type === item.id}"
                                                        class="card-body d-flex flex-column justify-content-center align-items-center  p-3">
                                                        <template v-if="item.icon"><span v-html="item.icon"></span>
                                                        </template>
                                                        <h6 style="font-size:14px;text-align:center;">
                                                            {{ item.title }}</h6>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <p class="mb-2"><em><small>Для начала создания бота добавьте токен телеграм
                                            бота</small></em></p>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control"
                                                   placeholder="Токен"
                                                   aria-label="Токен"
                                                   v-model="botForm.bot_token"
                                                   maxlength="255"
                                                   minlength="40"
                                                   @invalid="alert('Вы не ввели токен бота!')"
                                                   aria-describedby="bot-token" required>
                                            <label class="form-label"
                                                   id="bot-token">
                                                Токен бота
                                            </label>
                                        </div>

                                        <p class="mb-2"><em><small>Или выберите существующего бота для
                                            редактирования</small></em></p>
                                        <BotSearchModal
                                            :custom-class="'btn btn-primary p-3 w-100 dropdown-toggle'"
                                            v-if="!load"
                                            v-on:select-bot="selectBot"
                                            :bot="bot"></BotSearchModal>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <template v-if="isValidTelegramToken">
            <div class="row">
                <div class="col-md-8 order-2 mb-2 order-md-1 col-12">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs justify-content-center">
                                <li class="nav-item" @click="tab=0">
                                    <a class="nav-link"
                                       v-bind:class="{'active':tab===0}"
                                       href="javascript:void(0)">Базовые настройки</a>
                                </li>
                                <li class="nav-item" @click="tab=1">
                                    <a class="nav-link"
                                       v-bind:class="{'active':tab===1}"
                                       href="javascript:void(0)">Кэшбэк&Финансы</a>
                                </li>
                                <li class="nav-item" v-if="botForm.id!=null" @click="tab=2">
                                    <a class="nav-link"
                                       v-bind:class="{'active':tab===2}"
                                       href="javascript:void(0)">Обратная связь</a>
                                </li>
                                <li class="nav-item" v-if="botForm.id==null">
                                    <a class="nav-link text-secondary"
                                       href="javascript:void(0)"><i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                        Обратная
                                        связь</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row py-3" v-show="tab===0">

                        <div class="col-md-12 col-12">

                            <div class="input-group mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control"
                                           placeholder="Имя бота"
                                           aria-label="Имя бота"
                                           :disabled="true"
                                           name='bot_domain'
                                           @invalid="alert('Вы не ввели доменное имя бота!', 0)"
                                           v-model="botForm.bot_domain"
                                           maxlength="255"
                                           aria-describedby="bot-domain" required>
                                    <label class="form-label" id="bot-domain">
                                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Доменное имя
                                        бота
                                        (загружается автоматически)
                                    </label>
                                </div>
                                <span class="input-group-text">
                                    <img
                                        v-if="load_photo"
                                        style="width:40px;"
                                        class="object-fit-cover"
                                        v-lazy="'/images/companies/'+botForm.bot_domain+'/logo.jpg'" alt="">
                                    <img
                                        v-else
                                        style="width:40px;"
                                        class="object-fit-cover"
                                        v-lazy="'/images/icon.png'" alt="">
                                </span>
                            </div>


                        </div>


                        <div class="col-md-12 col-12" v-if="profile.is_admin||false">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox"
                                       :value="botForm.is_template||false"
                                       v-model="botForm.is_template" id="bot-is-template">
                                <label class="form-check-label" for="bot-is-template">
                                    Сделать шаблоном
                                </label>
                            </div>

                        </div>

                        <div class="col-md-12 col-12"
                             v-if="botForm.is_template">
                            <div class="form-floating mb-3">

                                <input type="text" class="form-control"
                                       placeholder="Название \ описание шаблона"
                                       aria-label="Описание шаблона"
                                       v-model="botForm.template_description"
                                       maxlength="255"
                                       @invalid="alert('Вы не ввели название шаблона!')"
                                       aria-describedby="bot-template-description" required>

                                <label class="form-label" id="bot-template-description">
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Название шаблона
                                    бота
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Настройка параметров бота в BotFather</h6>


                                    <div class="form-floating mb-3">

                                        <input type="text" class="form-control"
                                               placeholder="Текст названия"
                                               aria-label="Текст названия"
                                               v-model="botForm.title"
                                               maxlength="64"
                                               aria-describedby="bot-title">

                                        <label class="form-label d-flex justify-content-between align-items-center"
                                               id="bot-title">
                                              <span v-if="botForm.title">
                                                  Название бота
                                                  <small class="text-secondary" v-if="botForm.title.length>0">Длина текста {{
                                                          botForm.title.length
                                                      }}/64</small>
                                              </span>

                                            <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                                        </label>

                                    </div>


                                    <div class="alert alert-info" role="alert">
                                        Данное описание видно в момент, когда пользователь делится ссылкой на бота, а
                                        также
                                        при
                                        нажатии на иконку бота.
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control"
                                                  placeholder="Описание в шапке бота"
                                                  aria-label="Описание в шапке бота"
                                                  v-model="botForm.short_description"
                                                  maxlength="105"
                                                  style="min-height:100px;"
                                                  aria-describedby="bot-short-description">
                                        </textarea>

                                        <label class="form-label"
                                               id="bot-short-description">
                                              <span>
                                                  Описание в шапке бота
                                                  <small class="text-secondary"
                                                         v-if="botForm.short_description.length>0">Длина текста {{
                                                          botForm.short_description.length
                                                      }}/105</small>
                                              </span>
                                        </label>
                                    </div>

                                    <div class="alert alert-info" role="alert">
                                        Данное описание видно при первом запуске бота, оно должно содержать информацию о
                                        возможностях бота:
                                        <ul class="m-0 pl-2">
                                            <li>- система лояльности</li>
                                            <li>- колесо фортуны</li>
                                            <li>- интернет-магазин</li>
                                            <li>- реферальная система</li>
                                        </ul>
                                        и т.д.
                                    </div>

                                    <div class="mb-3 form-floating">

                                        <textarea class="form-control"
                                                  placeholder="Описание возможностей бота"
                                                  aria-label="Описание возможностей бота"
                                                  v-model="botForm.long_description"
                                                  maxlength="505"
                                                  style="min-height:200px;"
                                                  aria-describedby="bot-long-description"></textarea>

                                        <label class="form-label"
                                               id="bot-long-description">
                                              <span>
                                                 Описание возможностей бота
                                                  <small class="text-secondary"
                                                         v-if="botForm.long_description.length>0">Длина текста {{
                                                          botForm.long_description.length
                                                      }}/505</small>
                                              </span>
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       v-model="needMenuBtn" id="needMenuBtn">
                                                <label class="form-check-label" for="needMenuBtn">
                                                    Нужна кнопка меню
                                                </label>
                                            </div>
                                        </div>


                                        <template v-if="needMenuBtn">

                                            <div class="col-12">

                                                <div class="input-group mb-3">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control"
                                                               placeholder="Текст кнопки меню"
                                                               aria-label="Текст кнопки меню"
                                                               maxlength="255"
                                                               @invalid="alert('Текст кнопки меню!', 0)"
                                                               v-model="botForm.menu.text"
                                                               required>
                                                        <label for="">Текст кнопки меню</label>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="url" class="form-control"
                                                               placeholder="Адрес перехода URL"
                                                               aria-label="Адрес перехода URL"
                                                               maxlength="255"
                                                               @invalid="alert('Вы не ввели адрес меню!', 0)"
                                                               v-model="botForm.menu.url"
                                                               required>
                                                        <label for="">URL-адрес перехода</label>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click="openSelectScriptModal"
                                                        class="input-group-text px-2 text-primary" id="basic-addon1">
                                                        <i class="fa-solid fa-link"></i>
                                                    </button>
                                                </div>


                                            </div>


                                        </template>

                                    </div>

                                    <h6 class="fw-bold mb-2">Пользовательские команды </h6>
                                    <div class="row"
                                         :key="'commands-'+index"
                                         v-for="(item, index) in botForm.commands">
                                        <div class="col-12" v-if="botForm.commands[index].command==='/adminmenu'">
                                            <div class="alert alert-primary" role="alert">
                                                <strong>Внимание!</strong> Отображать пользователю команду <strong
                                                class="text-danger">/adminmenu</strong> плохая идея. Команда доступна
                                                только
                                                администраторам системы, а обычный пользователь будет видеть ошибку. Это
                                                создаст
                                                негативное восприятие от работы сервиса.
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="input-group mb-3">

                                                <div class="form-floating">
                                                    <input type="text" class="form-control"
                                                           placeholder="Название команды"
                                                           aria-label="Название команды"
                                                           maxlength="255"
                                                           @invalid="alert('Вы не ввели название команды',0)"
                                                           v-model="botForm.commands[index].command"
                                                           :aria-describedby="'bot-command-'+index" required>
                                                    <label for="">Команда</label>
                                                </div>

                                                <div class="form-floating">
                                                    <input type="text" class="form-control"
                                                           placeholder="Описание команды"
                                                           aria-label="Описание команды"
                                                           maxlength="255"
                                                           @invalid="alert('Вы не ввели описание команды!', 0)"
                                                           v-model="botForm.commands[index].description"
                                                           :aria-describedby="'bot-command-description-'+index"
                                                           required>
                                                    <label for="">Описание команды</label>
                                                </div>
                                                <button class="input-group-text text-danger"
                                                        @click="removeCommands(index)"><i
                                                    class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button
                                                type="button"
                                                @click="addCommands()"
                                                class="btn btn-link  text-primary text-decoration-none p-0">
                                                <i class="fa-regular fa-square-plus"></i>
                                                Добавить еще команду
                                            </button>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       v-model="showCode" id="showCode">
                                                <label class="form-check-label" for="showCode">
                                                    Отобразить код
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-3" v-if="showCode">
                                            <label class="form-label" id="bot-domain">JSON-код клавиатуры</label>
                                            <Vue3JsonEditor
                                                v-if="loadCommandEditor"
                                                :mode="'code'"
                                                v-model="botForm.commands"
                                                :show-btns="false"
                                                :expandedOnStart="true"
                                                @json-change="onJsonChange"
                                            />
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row py-3" v-show="tab===1">

                        <div class="col-md-4 col-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control"
                                       placeholder="%"
                                       aria-label="уровень CashBack"
                                       v-model="botForm.level_1"
                                       min="0"
                                       @invalid="alert('Вы не ввели значение кэшбэка 1 уровня бота!', 1)"
                                       aria-describedby="bot-level-1" required>
                                <label class="form-label" id="bot-level-1">
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Уровень 1 CashBack,
                                    %
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control"
                                       placeholder="%"
                                       aria-label="уровень CashBack"
                                       v-model="botForm.level_2"
                                       min="0"
                                       aria-describedby="bot-level-2">
                                <label class="form-label" id="bot-level-2">Уровень 2 CashBack, %</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-floating mb-3">

                                <input type="number" class="form-control"
                                       placeholder="%"
                                       aria-label="уровень CashBack"
                                       v-model="botForm.level_3"
                                       min="0"
                                       aria-describedby="bot-level-3">
                                <label class="form-label" id="bot-level-3">Уровень 3 CashBack, %</label>
                            </div>
                        </div>


                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="need_cashback_fired"
                                       type="checkbox"
                                       id="need-cashback-fired">
                                <label class="form-check-label" for="need-cashback-fired">
                                    Необходимо настроить период сгорания CashBack
                                </label>
                            </div>

                        </div>
                        <div class="col-md-12 col-12" v-if="need_cashback_fired">
                            <div class="form-floating mb-3">

                                <select class="form-control" v-model="botForm.cashback_fire_period"
                                        id="cashback-fired-period">
                                    <option :value="item.value" v-for="item in cashback_fire_periods">
                                        {{ item.title || 'Не указано' }}
                                    </option>
                                </select>
                                <label class="form-label" id="cashback-fired-period">Период сгорания CashBack</label>
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

                        <div class="col-md-12 col-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control"
                                       placeholder="%"
                                       aria-label="процент для списания CashBack при покупке"
                                       v-model="botForm.max_cashback_use_percent"
                                       min="0"
                                       max="100"
                                       aria-describedby="cashback-max_cashback_use_percent">
                                <label class="form-label" id="cashback-max_cashback_use_percent">
                                    Автоматическое списание CashBack при покупке, % от цены товара</label>
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
                            <div class="form-check mb-3 ml-3">
                                <input class="form-check-input" type="checkbox"
                                       :value="botForm.auto_cashback_on_payments"
                                       v-model="botForm.auto_cashback_on_payments"
                                       id="bot-auto-cashback-on-payments">
                                <label class="form-check-label" for="bot-auto-cashback-on-payments">
                                    Начислять CashBack автоматически после успешной оплаты
                                </label>
                            </div>

                            <div class="input-group mb-3">
                                <div class="form-floating">

                                    <input type="text" class="form-control"
                                           id="payment_provider_token-input"
                                           placeholder="Токен"
                                           aria-label="Токен"
                                           v-model="botForm.payment_provider_token"
                                           aria-describedby="payment_provider_token">
                                    <label class="form-label" id="payment_provider_token">Токен платежной
                                        системы</label>

                                </div>
                                <a class="input-group-text" href="https://t.me/botfather" target="_blank">Подключить</a>
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

                            <div class="input-group mb-3">
                                <div class="form-floating">
                                    <input type="url" class="form-control"
                                           placeholder="Ссылка на группу ВК"
                                           aria-label="ссылка на группу ВК"
                                           v-model="botForm.vk_shop_link"
                                           aria-describedby="vk_shop_link">
                                    <label class="form-label" id="bot-vk_shop_link">Ссылка на группу ВК с
                                        товарами</label>

                                </div>
                                <a class="input-group-text" href="https://vk.com/groups?w=groups_create"
                                   target="_blank">Создать</a>
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
                            <div class="alert alert-light" role="alert">
                                Категории CashBack - это возможность разделить накопления и траты CashBack
                                пользователями бота
                                на
                                указанные цели, например, кофейня может создать категории: на кофе, на десерты - и
                                начислять
                                баллы
                                за купленный кофе отдельно от баллов за купленный десерт
                            </div>

                            <div class="d-flex justify-content-between mb-2 flex-wrap"
                                 :key="'social-link'+index"
                                 v-for="(item, index) in botForm.cashback_config">


                                <div class="input-group mb-2 w-100">
                                    <div class="form-floating ">
                                        <input type="text" class="form-control "
                                               placeholder="Название категории"
                                               aria-label="Название категории"
                                               maxlength="255"
                                               @invalid="alert('Вы не ввели название категории!', 1)"
                                               v-model="botForm.cashback_config[index].title"
                                               :aria-describedby="'bot-cashback-config-'+index" required>
                                        <label for="">Название категории</label>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeCashBackConfig(index)"
                                        class="input-group-text text-danger"
                                    ><i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>


                            </div>

                            <button
                                type="button"
                                @click="addCashBackConfig()"
                                class="btn mb-2 rounded-sm btn-outline-primary p-3 w-100">
                                Добавить категорию
                            </button>
                            <div class="divider divider-small my-3 bg-highlight "></div>
                        </div>
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="need_cashback_rules"
                                       type="checkbox"
                                       id="need-cashback-rules">
                                <label class="form-check-label" for="need-cashback-rules">
                                    Необходимо настроить оповещения под CashBack
                                </label>
                            </div>

                        </div>
                        <div class="col-md-12 col-12 mb-2" v-if="need_cashback_rules">
                            <div class="form-floating mb-2">

                                <select class="form-control"
                                        v-model="selected_warning"
                                        @change="addWarning"
                                        id="warning-rules">
                                    <option :value="null">Не выбрано</option>
                                    <option :value="item" v-for="item in filteredWarnings">
                                        {{ item.title }}
                                    </option>
                                </select>
                                <label class="form-check-label" for="warning-rules">
                                    <i class="fa-solid fa-triangle-exclamation text-danger"></i> Правила критических
                                    оповещений
                                </label>
                            </div>

                            <template v-for="(warn, index) in botForm.warnings">


                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   v-model="botForm.warnings[index].is_active"
                                                   type="checkbox"
                                                   :id="'warning-is-active-'+index">
                                            <label class="form-check-label"
                                                   style="font-size:12px;"
                                                   :for="'warning-is-active-'+index">
                                                <span v-if="botForm.warnings[index].is_active">Включено</span>
                                                <span v-else>Выключено</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-floating">
                                        <input type="number" class="form-control"
                                               placeholder="Значение"
                                               v-model="botForm.warnings[index].rule_value"
                                               min="0"
                                               aria-describedby="bot-level-3">
                                        <label for="">{{ getWarning(warn.rule_key).title || 'Не найдено' }}</label>
                                    </div>
                                    <button
                                        @click="removeWarning(index)"
                                        type="button"
                                        class="input-group-text text-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>

                            </template>
                        </div>
                    </div>
                    <div class="row py-3" v-show="tab===2">
                        <div class="col-12">
                            <div class="alert alert-light" role="alert">
                                <strong>Внимание!</strong> Пропишите сообщение "Мой id" в группе и выполните указания
                                бота
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-floating mb-3">

                                <input type="text" class="form-control"
                                       placeholder="id канала"
                                       aria-label="id канала"
                                       disabled="true"
                                       v-model="botForm.order_channel"
                                       maxlength="255"
                                       aria-describedby="bot-order-channel">
                                <label class="form-label" id="bot-order-channel">
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                    Канал для заказов (id)
                                </label>
                            </div>


                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control"
                                       placeholder="id канала"
                                       aria-label="id канала"
                                       v-model="botForm.main_channel"
                                       maxlength="255"
                                       disabled="true"
                                       aria-describedby="bot-main-channel">
                                <label class="form-label" id="bot-main-channel">Канал для постов (id,рекламный)</label>
                            </div>
                        </div>

                        <div class="col-12 mb-2" v-if="botForm.order_channel">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="need_threads"
                                       type="checkbox"
                                       id="need-topics-mailing">
                                <label class="form-check-label" for="need-topics-mailing">
                                    Необходимо добавить рассылку по топикам для канала заказов (он же системный канал)
                                </label>
                            </div>

                        </div>
                        <div class="col-12 mb-2" v-if="need_threads && botForm.order_channel">

                            <div class="alert alert-light">
                                Для автоматического создания топиков и заполнения информации по ним выполните следующие
                                действий:
                                <ul class="m-0 p-0">
                                    <li>1.Создайте группу</li>
                                    <li>2.Добавьте в группу вашего бота</li>
                                    <li>3.Выдайте все административные права боту</li>
                                    <li>4.Переведите группу в режим работы с темами в настройках группы (<strong
                                        class="fw-bold text-primary">переключатель "Темы"</strong>)
                                    </li>
                                    <li>5.Пропишите сообщение <strong class="fw-bold text-primary">"Мой id"</strong> в
                                        группе и выполните указания бота
                                    </li>
                                </ul>


                            </div>
                            <!--
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <p>Для того, чтоб узнать идентификатор топика в группе впишите в чат "Мой id"</p>

                                                            <button class="btn btn-outline-info"
                                                                    @click="createBotTopics"
                                                                    :disabled="!can_create_topics"
                                                                    type="button">
                                                                <i class="fa-solid fa-paperclip mr-2"></i>Создать топики автоматически
                                                            </button>
                                                        </div>
                            -->


                            <div class="row row-cols-3">
                                <div class="col" v-for="(thread, index) in botForm.message_threads">
                                    <div class="form-floating mb-2">
                                        <input type="number" class="form-control"
                                               min="0"
                                               max="10000"
                                               placeholder="Идентификатор топика"
                                               v-model="botForm.message_threads[index].value">
                                        <label for="">{{ thread.title }} ({{ thread.key }})</label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-2 mt-3">Сообщения</h6>
                            <div class="mb-3 form-floating">
                                <textarea type="text" class="form-control"
                                          placeholder="Текстовое сообщение"
                                          aria-label="Текстовое сообщение"
                                          v-model="botForm.maintenance_message"
                                          maxlength="255"
                                          style="min-height:200px;"
                                          @invalid="alert('Вы не ввели сообщение для технических работ бота!', 3)"
                                          aria-describedby="bot-maintenance-message" required></textarea>

                                <label class="form-label" id="bot-maintenance-message">
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Сообщение для
                                    режима тех.работ
                                    <small class="text-gray-400 ml-3" style="font-size:10px;"
                                           v-if="botForm.maintenance_message">
                                        Длина текста {{ botForm.maintenance_message.length }}</small>
                                </label>
                            </div>

                        </div>

                    </div>

                    <div class="row py-3" v-show="tab===4">
                        <div class="col-12">
                            <div class="alert alert-primary" role="alert">
                                Внешний сервис находится в эксперементальном режиме. Он нужен для связывания команд
                                (страниц)
                                бота с
                                программой Клиента (если есть такая необходимость).
                                Если на странице бота выбрано "внешнее управление", то все запросы будут переадесрованы
                                на
                                указанную
                                ниже ссылку.
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="form-floating mb-3">


                                <input type="url" class="form-control"
                                       placeholder="Ссылка на внешний сервис"
                                       aria-label="ссылка на внешний сервис"
                                       v-model="botForm.callback_link"
                                       aria-describedby="callback_link">
                                <label class="form-label" id="callback_link">Ссылка на внешний сервис обработки данных
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 order-1 mb-2 order-md-2 col-12">

                    <div class="card mb-2 border-light">
                        <div class="card-body">

                            <div class="form-floating mb-2">

                                <input type="text" class="form-control"
                                       placeholder="Токен"
                                       aria-label="Токен"
                                       v-model="botForm.bot_token"
                                       maxlength="255"
                                       minlength="40"
                                       @invalid="alert('Вы не ввели токен бота!')"
                                       aria-describedby="bot-token" required>
                                <label class="form-label" id="bot-token">
                                    Токен бота
                                </label>
                            </div>

                            <button type="submit"
                                    id="save-bot-btn"
                                    @click="messages = []"
                                    :disabled="!botForm.bot_token||spent_time_counter>0"
                                    title="Сохранение бота"
                                    class="btn btn-primary min-menu-btn w-100">
                                <template v-if="spent_time_counter>0">
                                    Осталось подождать {{ spent_time_counter }} сек.
                                </template>
                                <template v-else>
                                    <span v-if="!bot">Добавить бота</span>
                                    <span v-else><i class="fa-regular fa-floppy-disk"></i> Сохранить изменения</span>
                                </template>
                            </button>
                        </div>


                    </div>

                    <template v-if="profile.is_admin||false">


                        <div class="input-group mb-2">
                            <div class="form-floating">
                                <select class="form-select"
                                        required
                                        :disabled="servers.length===0"
                                        v-model="botForm.server"
                                        id="floatingSelect" aria-label="Floating label select example">
                                    <option
                                        :disabled="server.disabled"
                                        :value="server.key" v-for="server in servers">
                                        {{ server.title || 'без названия' }}
                                        ({{ server.current_count || 0 }} из {{ server.max_bot_limit || 0 }})
                                    </option>
                                </select>
                                <label for="floatingSelect"> <span
                                    class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                    Выбор сервера размещения</label>
                            </div>
                            <button
                                @click="updateWebHook"
                                :disabled="bot==null"
                                class="input-group-text text-danger">
                                <i class="fa-solid fa-right-left"></i>
                            </button>
                        </div>


                    </template>
                    <div class="card border-light mb-2">
                        <div class="card-body">
                            <p v-if="botForm.bot_domain">Проверить работу бота</p>

                            <a
                                class="btn btn-success w-100 p-3 mb-3"
                                :href="'https://t.me/'+botForm.bot_domain"
                                target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> {{
                                    botForm.bot_domain
                                }}</a>

                            <button
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#bot-balance-payment"
                                v-if="profile.is_manager"
                                class="btn btn-primary p-3 w-100 mb-3">Пополнить баланс бота
                            </button>

                            <template v-if="profile.is_admin">
                                <div class="col-12">
                                    <div class="alert alert-primary" role="alert">
                                        <strong>Внимание!</strong> Внесите сумму, которую вам дал Клиент! Эта сумма
                                        будет балансом
                                        Клиента
                                        для работы бота! Укажите тариф бота - это сумма, которую система будет списывать
                                        с клиента
                                        каждый
                                        день пока баланс бота не будет равен 0.
                                        <br>
                                        <strong>Внимание!</strong> Вы можете начислить какую-то небольшую сумму для того
                                        чтобы
                                        клиент
                                        протестировал работу бота. При достижении нулевого баланса клиент будет оповещен
                                        об этом и
                                        должен
                                        будет пополнить счёт бота!
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="mb-3 form-floating">
                                        <input type="number" class="form-control"
                                               placeholder="Баланс"
                                               aria-label="Баланс"
                                               v-model="botForm.balance"
                                               min="0"
                                               @invalid="alert('Вы не ввели сумму баланса бота!', 1)"
                                               aria-describedby="bot-balance" required>
                                        <label class="form-label" id="bot-balance">
                                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Баланс
                                            бота, руб
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="mb-3 form-floating">

                                        <input type="number" class="form-control"
                                               placeholder="Списание"
                                               aria-label="Списание"
                                               v-model="botForm.tax_per_day"
                                               min="0"
                                               @invalid="alert('Вы не ввели сумму списания бота!', 1)"
                                               aria-describedby="bot-tax-per-day" required>
                                        <label class="form-label" id="bot-tax-per-day">
                                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span> Списание за
                                            сутки, руб
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="card border-light">
                        <div class="card-body">
                            <div class="col-md-12 col-12">

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox"
                                           :value="need_company_select"
                                           v-model="need_company_select" id="bot-select-company">
                                    <label class="form-check-label" for="bot-select-company">
                                        Выбрать клиента из списка
                                    </label>
                                </div>
                                <p class="alert alert-danger"
                                   @click="need_company_select=true"
                                   v-if="botForm.company_id==null">Внимание! Вы не выбрали клиента!</p>
                                <p class="card alert alert-success cursor-pointer"
                                   @click="need_company_select=true"
                                   v-else>
                                    Выбран клиент <span v-if="company" class="font-bold">#{{ company.id }} {{
                                        company.title
                                    }}</span>
                                </p>
                            </div>
                            <div class="col-md-12 col-12" v-if="need_company_select">s

                                <div
                                    class="w-100 p-2"
                                    style="overflow-y:scroll;max-height:300px;">
                                    <CompanyList
                                        v-if="!load"
                                        :is-simple="true"
                                        :selected="botForm.company_id"
                                        v-on:callback="companyListCallback"/>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div
                        v-if="messages.length>0"
                        v-for="(message, index) in messages"
                        class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Внимание!</strong> {{ message || 'Ошибка' }}
                        <button type="button" class="btn-close"
                                @click="removeMessage(index)"></button>
                    </div>

                </div>

            </div>


        </template>

    </form>


    <!-- Modal -->
    <div class="modal fade" id="bot-balance-payment" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form
                v-on:submit.prevent="submitBotBalancePayment"
                class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Пополнение баланса бота</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template v-if="bot">
                        <div class="alert alert-light mb-2">
                            <p class="mb-2">Общий баланс: <strong
                                class="text-primary fw-bold">{{ profile.manager.balance || 0 }} ₽</strong></p>
                            <p class="mb-2">Тариф в боте <strong class="text-primary fw-bold">{{ bot.tax_per_day }}
                                ₽\день</strong></p>
                            <p class="mb-2">Баланс в боте <strong class="text-primary fw-bold">{{ bot.balance }}
                                ₽</strong></p>
                            <p class="mb-2"><strong class="fw-bold">Важно!</strong> Будьте внимательны при указании
                                суммы пополнения, отменить данную операцию будет невозможно!</p>
                        </div>
                        <div class="d-flex flex-wrap mb-2" v-if="this.profile.manager.balance>0">
                            <button type="button"
                                    @click="selectPaymentVariant(1)"
                                    class="btn btn-light text-primary">1 месяц
                            </button>
                            <button type="button"
                                    @click="selectPaymentVariant(3)"
                                    class="btn btn-light text-primary">3 месяца
                            </button>
                            <button type="button"
                                    @click="selectPaymentVariant(6)"
                                    class="btn btn-light text-primary">6 месяцев
                            </button>
                            <button type="button"
                                    @click="selectPaymentVariant(12)"
                                    class="btn btn-light text-primary">1 год
                            </button>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number"
                                   min="1"
                                   v-model="balanceForm.amount"
                                   :max="profile.manager.balance || 0 "
                                   class="form-control" id="bot-balance" placeholder="name@example.com">
                            <label for="bot-balance">Вносимая сумма, ₽</label>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit"
                            :disabled="spent_time_counter>0"
                            class="btn btn-primary">
                        <template v-if="spent_time_counter>0">
                            Осталось подождать {{ spent_time_counter }} сек.
                        </template>
                        <template v-else>
                            Пополнить баланс
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="select-script-for-menu" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Выбор скрипта</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template v-if="select_script_step===0">
                        <BotSlugList v-on:select="selectVariant($event)"/>
                    </template>

                    <template v-if="select_script_step===1">
                        <div class="alert alert-light">
                            Внимание! Необходимо выбрать тип скрипта в меню
                        </div>
                        <div class="row row-cols-3">
                            <div
                                v-for="item in script_types"
                                class="col mb-2">
                                <div class="card" @click="selectVariantStep2(item)">
                                    <div class="card-body text-center cursor-pointer text-primary fw-bold">
                                        {{ item.title }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
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
import {Vue3JsonEditor} from 'vue3-json-editor'

export default {
    components: {
        Vue3JsonEditor
    },
    props: ["bot"],
    data() {
        return {
            balanceForm: {
                amount: 0,
            },
            load_photo: true,
            loadCommandEditor: true,
            select_script_modal: null,
            select_script_step: 0,
            script_types: [
                {
                    title: "Магазин",
                    uri: '/menu',
                },
                {
                    title: "Отзывы",
                    uri: '/feedback',
                },
                {
                    title: "Колесо фортуны",
                    uri: '/wheel',
                },
                {
                    title: "Профиль",
                    uri: '/profile',
                },
                {
                    title: "Заказы",
                    uri: '/orders',
                },
                {
                    title: "Ввод промокода",
                    uri: '/new-promo-code',
                },
                {
                    title: "Контакты",
                    uri: '/contacts',
                },
                {
                    title: "Корзина",
                    uri: '/cart',
                },
            ],
            showCode: false,
            tab: 0,
            bot_types: [],
            spent_time_counter: 0,
            can_create: true,
            can_create_topics: true,
            messages: [],
            servers: [],
            need_company_select: false,
            selected_warning: null,
            page: null,
            step: 0,
            templates: [],
            load: false,
            loadPage: false,
            needPageListUpdate: false,
            need_threads: false,
            need_company: false,
            need_cashback_config: false,
            need_cashback_rules: false,
            need_cashback_fired: false,
            need_payments: false,
            need_shop: false,
            command: null,
            company: null,
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
            needMenuBtn: false,
            botForm: {
                title: 'Название бота',
                short_description: 'Описание в шапке бота',
                long_description: 'Описание при первом запуске бота',
                bot_type: 0,
                is_template: false,
                auto_cashback_on_payments: false,
                template_description: null,
                bot_domain: null,
                bot_token: null,
                company_id: null,
                creator_id: null,
                bot_token_dev: null,
                order_channel: null,
                message_threads: null,
                cashback_config: null,
                server: "main",
                main_channel: null,
                vk_shop_link: null,
                callback_link: null,
                balance: 70,
                tax_per_day: 10,
                welcome_message: "Приветствую!",
                image: null,
                cashback_fire_percent: 0,
                cashback_fire_period: 0,
                max_cashback_use_percent: 0,
                description: "Это ваш бот! Вот такое вот описание у него.",
                info_link: null,
                social_links: [],
                maintenance_message: "Технические работы",
                payment_provider_token: null,
                level_1: 10,
                level_2: 0,
                level_3: 0,
                commands: null,
                menu: {
                    text: "Меню",
                    url: null
                },
                selected_bot_template_id: null,
                pages: [],
                amo: null,
                warnings: []
            },
        }
    },
    watch: {
        'isValidTelegramToken': {
            handler(val) {
                if (this.profile.is_admin && (this.servers || []).length === 0)
                    this.loadCurrentServers()
            },
            deep: true
        },
        'botForm.bot_token': {
            handler(val) {
                if (this.isValidTelegramToken)
                    this.getMe()
            },
            deep: true
        },

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
                },
                {
                    title: 'Доставка',
                    key: 'delivery',
                    value: null,
                },
                {
                    title: 'Ответы',
                    key: 'response',
                    value: null,
                },
                {
                    title: 'Обратная связь',
                    key: 'callback',
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

    },
    computed: {
        ...mapGetters(['getSlugs', 'getCurrentCompany']),
        profile() {
            return window.profile
        },
        isValidTelegramToken() {
            if (!this.botForm.bot_token)
                return false

            const tokenRegex = /^[0-9]{8,10}:[a-zA-Z0-9_-]{35}$/;
            return tokenRegex.test(this.botForm.bot_token);
        },
        filteredWarnings() {
            if (this.botForm.warnings.length === 0)
                return this.warnings;

            return this.warnings.filter(item => {
                return !(this.botForm.warnings.findIndex(sub => sub.rule_key === item.key) >= 0)
            })
        }
    },
    mounted() {

        this.getActualBotTypes()
        this.select_script_modal = new bootstrap.Modal('#select-script-for-menu', {})
        //this.loadCurrentCompany()
        if (localStorage.getItem("cashman_admin_bot_creator_counter") != null) {
            let time = localStorage.getItem("cashman_admin_bot_creator_counter")
            this.startTimer(time === "null" || time == null ? 0 : time)
        }

        window.addEventListener('store_current_company-change-event', (event) => {
            this.company = this.getCurrentCompany
        });


        window.addEventListener('add-payment-system-event', (event) => {
            this.tab = 1
            this.need_payments = true

            this.$nextTick(() => {
                let paymentTokenInput = document.querySelector("#payment_provider_token-input")
                paymentTokenInput.classList = "form-control border-danger";
                paymentTokenInput.focus()

                setTimeout(() => {
                    let paymentTokenInput = document.querySelector("#payment_provider_token-input")
                    paymentTokenInput.classList = "form-control";
                }, 5000)
            })

        });

        if (this.bot)
            this.$nextTick(() => {
                this.botForm = {
                    id: this.bot.id || null,

                    title: this.bot.title || '',
                    short_description: this.bot.short_description || '',
                    long_description: this.bot.long_description || '',

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
                    server: this.bot.server || "main",
                    tax_per_day: this.bot.tax_per_day || null,
                    vk_shop_link: this.bot.vk_shop_link || null,
                    callback_link: this.bot.callback_link || null,
                    cashback_fire_percent: this.bot.cashback_fire_percent || 0,
                    cashback_fire_period: this.bot.cashback_fire_period || 0,
                    max_cashback_use_percent: this.bot.max_cashback_use_percent || 0,
                    image: this.bot.image || null,

                    description: this.bot.description || null,
                    creator_id: this.bot.creator_id || null,

                    info_link: this.bot.info_link || null,

                    social_links: this.bot.social_links || [],

                    maintenance_message: this.bot.maintenance_message || null,
                    welcome_message: this.bot.welcome_message || null,
                    payment_provider_token: this.bot.payment_provider_token || null,

                    level_1: this.bot.level_1,
                    level_2: this.bot.level_2,
                    level_3: this.bot.level_3,
                    company_id: this.bot.company_id,
                    commands: this.bot.commands || null,
                    warnings: this.bot.warnings || [],

                    amo: this.bot.amo || null,
                    menu: {
                        text: "Меню",
                        url: null
                    },
                }

                if (!Array.isArray(this.botForm.commands))
                    this.botForm.commands = []

                this.botForm.menu = this.bot.menu || {
                    text: "Меню",
                    url: null
                }

                if (this.botForm.menu.url !== null)
                    this.needMenuBtn = true

                if (this.botForm.commands == null)
                    this.autoAddCommands();

                if (this.botForm.message_threads)
                    this.need_threads = true

                if (this.botForm.payment_provider_token)
                    this.need_payments = true

                if ((this.botForm.cashback_config || []).length > 0)
                    this.need_cashback_config = true

                if (this.botForm.warnings.length > 0)
                    this.need_cashback_rules = true

                if (this.botForm.cashback_fire_period > 0)
                    this.need_cashback_fired = true

                if (this.bot.company)
                    this.company = this.bot.company
                //   this.setStep(localStorage.getItem("cashman_set_botform_step_index") || 0)
            })
        else {
            if (this.botForm.commands == null)
                this.autoAddCommands();
        }


    },
    methods: {
        selectPaymentVariant(month) {
            this.balanceForm.amount = Math.min(this.bot.tax_per_day * 31 * month, this.profile.manager.balance)
        },
        submitBotBalancePayment() {
            this.startTimer();

            let data = new FormData();

            Object.keys(this.balanceForm)
                .forEach(key => {
                    const item = this.balanceForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append("bot_domain", this.bot.bot_domain)

            this.$store.dispatch("setBotBalance", {
                form: data
            })
                .then((response) => {
                    this.balanceForm.amount = 0
                    this.$notify({
                        title: "Пополнение баланса",
                        text: "Отлично! Баланс бота успешно пополнен",
                        type: 'success'
                    });
                    window.location.reload()
                }).catch(() => {
                this.$notify({
                    title: "Пополнение баланса",
                    text: "Ошибка пополнения баланса бота",
                    type: 'error'
                });
            })
        },
        getActualBotTypes() {
            this.$store.dispatch("getActualBotTypes").then((response) => {
                this.bot_types = response || []
            })
        },
        openSelectScriptModal() {
            this.select_script_step = 0
            this.select_script_modal.show()
        },
        selectBot(bot) {
            this.$emit("callback")
        },
        autoAddCommands() {

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

        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_admin_bot_creator_counter", this.spent_time_counter)
                }, 1000
            )
        },
        alert(msg, tab = null) {
            if (tab != null)
                this.tab = tab
            this.messages.push(msg)
        },
        removeMessage(index) {
            this.messages.splice(index, 1)
        },
        getMe() {
            let token = this.botForm.bot_token || ''
            if (token.length < 40)
                return;

            this.$store.dispatch("getMe", {
                bot_token: token,
            }).then((resp) => {
                let username = resp.username || null
                let title = resp.first_name || null
                if (username)
                    this.botForm.bot_domain = username
                if (title)
                    this.botForm.title = title

                this.load_photo = false
                this.$nextTick(() => {
                    this.load_photo = true
                })
            })
        },
        addTextTo(object = {param: null, text: null}) {
            this.botForm[object.param] = object.text;

        },
        removeCommands(index) {
            this.botForm.commands.splice(index, 1)

            this.loadCommandEditor = false
            this.$nextTick(() => {
                this.loadCommandEditor = true
            })
        },
        addCommands() {
            if (!this.botForm.commands)
                this.botForm.commands = [];

            this.botForm.commands.push({
                command: null,
                description: null
            })
            this.loadCommandEditor = false
            this.$nextTick(() => {
                this.loadCommandEditor = true
            })
        },
        loadCurrentServers() {
            this.$store.dispatch("loadCurrentServers").then((resp) => {
                this.servers = resp
            })
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
        updateWebHook() {
            this.$store.dispatch("updateBotWebhook", {
                dataObject: {
                    bot_id: this.bot.id,
                    bot_server: this.botForm.server || null
                }
            }).catch(error => {
                this.alert(error.response.data.message)
            })
        },
        addBot() {

            this.startTimer();

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
            /* else {
                 this.alert('Вы не выбрали клиента', 0)
             }*/


            this.$store.dispatch((this.bot == null ? "createBot" : "updateBot"), {
                botForm: data
            })
                .then((response) => {

                    let bot = response.data

                    this.$emit("callback", bot)


                    this.$notify({
                        title: "Конструктор ботов",
                        text: (this.bot == null ? "Бот успешно создан!" : "Бот успешно обновлен!"),
                        type: 'success'
                    });

                    this.$store.dispatch("updateBotWebhook", {
                        dataObject: {
                            bot_id: bot.id
                        }
                    }).catch(error => {
                        this.alert(error.response.data.message)
                    })

                    if (this.bot == null)
                        this.botForm = {
                            title: '',
                            server: 'main',
                            short_description: '',
                            long_description: '',
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
                            max_cashback_use_percent: 0,
                            image: null,

                            description: null,

                            info_link: null,

                            social_links: [],

                            maintenance_message: null,
                            payment_provider_token: null,

                            level_1: 10,
                            level_2: 0,
                            level_3: 0,


                            warnings: [],

                            selected_bot_template_id: null,

                            pages: [],


                        }

                })
                .catch(error => {
                    this.$notify({
                        title: "Конструктор ботов",
                        text: error.response.data.message,
                        type: 'error'
                    });
                })


        },
        onJsonChange(value) {
            this.botForm.commands = value
        },
        removeCashBackConfig(index) {
            this.botForm.cashback_config.splice(index, 1)
        },
        addCashBackConfig() {

            this.botForm.cashback_config = this.botForm.cashback_config == null ? [] : this.botForm.cashback_config;

            this.botForm.cashback_config.push({
                title: null,
            })

        },
        getWarning(key) {
            let item = this.warnings.find(item => item.key === key)


            return (!item) ? {
                title: 'Не найдено'
            } : item;

        }
        ,
        removeWarning(index) {
            this.botForm.warnings.splice(index, 1)
        },
        /* loadCurrentCompany(company = null) {
             this.$store.dispatch("updateCurrentCompany", {
                 company: company
             }).then(() => {
                 this.company = this.getCurrentCompany
             })
         },*/
        companyListCallback(company) {
            this.load = true
            this.company = company
            this.botForm.company_id = company.id
            this.need_company_select = false
            //this.loadCurrentCompany(company)
            this.$nextTick(() => {
                this.load = false
            })

        },
        selectVariant(item) {
            this.select_script_step++
            this.botForm.menu.text = item.title || 'Меню'
            this.botForm.menu.url = (import.meta.env.VITE_ASSET_URL || '')
                + '/bot-client/simple/' + this.bot.bot_domain + '?slug=' + item.id + '#/s'


        },
        selectVariantStep2(item) {
            this.botForm.menu.url += item.uri
            this.select_script_modal.hide()
            this.select_script_step = 0
            this.$notify({
                title: "Конструктор ботов",
                text: "Скрипт успешно выбран",
                type: 'success'
            });
        },
        addWarning() {

            const item = this.selected_warning

            this.botForm.warnings.push({
                rule_key: item.key,
                rule_value: 0,
                is_active: true,
            })

            this.selected_warning = null

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

.gradient-custom-2 {
    background: #fccb90;
    background: -webkit-linear-gradient(to right, #2489ee, #364cd8, #1a97c5, #1a1c9a);
    background: linear-gradient(to right, #2489ee, #364cd8, #021f64, #1a1c9a);
}

.logo {
    font-size: 64px;
    font-weight: lighter;
    background: linear-gradient(90deg, #007BFF, #00C6FF);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-family: Arial, sans-serif;
}


</style>

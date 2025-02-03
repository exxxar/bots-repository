<script setup>
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
/*import TextHelper from "@/AdminPanel/Components/Constructor/Helpers/TextHelper.vue";*/
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import BotSearchModal from "@/AdminPanel/Components/Constructor/Bot/BotSearchModal.vue";
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

                                        <h4 class="mb-4">Добро пожваловать в раздел создания бота!</h4>
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
                                        <p class="mb-2">Вы сразу можете выбрать один из доступных типов бота
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

                                            <!-- Карточка 2: Бот для кафетериев / ресторанного дела -->
                                            <div class="col-md-6 mb-2">
                                                <div class="card cursor-pointer"
                                                     @click="botForm.bot_type=1"
                                                     v-bind:class="{'border-primary':botForm.bot_type === 1}"
                                                     style="height:120px;">
                                                    <div
                                                        v-bind:class="{'text-primary':botForm.bot_type === 1}"
                                                        class="card-body d-flex flex-column justify-content-center align-items-center  p-3">
                                                        <i class="fas fa-utensils mb-2" style="font-size:20px;"></i>
                                                        <h6 style="font-size:14px;text-align:center;">Бот для кафетериев<br>и
                                                            ресторанного дела</h6>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Карточка 3: Бот для салона красоты -->
                                            <div class="col-md-6 mb-2">
                                                <div class="card cursor-pointer"
                                                     @click="botForm.bot_type=2"
                                                     v-bind:class="{'border-primary':botForm.bot_type === 2}"
                                                     style="height:120px;">
                                                    <div
                                                        v-bind:class="{'text-primary':botForm.bot_type === 2}"
                                                        class="card-body d-flex flex-column justify-content-center align-items-center  p-3">
                                                        <i class="fas fa-spa mb-2" style="font-size:20px;"></i>
                                                        <h6 style="font-size:14px;text-align:center;">Бот для салонов
                                                            красоты</h6>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <div class="card cursor-pointer"
                                                     @click="botForm.bot_type=3"
                                                     v-bind:class="{'border-primary':botForm.bot_type === 3}"
                                                     style="height:120px;">
                                                    <div
                                                        v-bind:class="{'text-primary':botForm.bot_type === 3}"
                                                        class="card-body d-flex flex-column justify-content-center align-items-center  p-3">
                                                        <i class="fas fa-comments mb-2" style="font-size:20px;"></i>
                                                        <h6 style="font-size:14px;text-align:center;">Диалоговый
                                                            бот</h6>
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
                <div class="col-md-6 col-12">
                    <div class="card mb-3 border-primary">
                        <div class="card-body">

                            <label class="form-label" id="bot-token">
                                Токен бота
                            </label>
                            <input type="text" class="form-control"
                                   placeholder="Токен"
                                   aria-label="Токен"
                                   v-model="botForm.bot_token"
                                   maxlength="255"
                                   minlength="40"
                                   @invalid="alert('Вы не ввели токен бота!')"
                                   aria-describedby="bot-token" required>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12" v-if="profile.is_admin||false">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7 col-12">
                                    <div class="form-floating mb-2">
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
                                        <label for="floatingSelect">Выбор сервера размещения</label>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12">
                                    <button type="button"
                                            @click="updateWebHook"
                                            :disabled="bot==null"
                                            class="btn btn-outline-danger p-3 w-100">Сменить сервер вручную
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"
                 style="position:sticky;top:60px;z-index:100;">
                <div

                    class="col-12 col-md-12 d-flex justify-content-end">
                    <button type="submit"
                            id="save-bot-btn"
                            @click="messages = []"
                            :disabled="!botForm.bot_token||!can_create"
                            title="Сохранение бота"
                            class="btn btn-primary min-menu-btn">
                        <span v-if="!bot">Добавить бота</span>
                        <span v-else><i class="fa-regular fa-floppy-disk"></i> Обновить бота</span>
                        <span class="ml-2" v-if="!can_create">{{ spent_time_counter }} сек.</span>
                    </button>
                </div>
            </div>
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
                               href="javascript:void(0)"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Обратная
                                связь</a>
                        </li>
                        <li class="nav-item" @click="tab=3">
                            <a class="nav-link"
                               v-bind:class="{'active':tab===3}"
                               href="javascript:void(0)">Сообщения</a>
                        </li>
                        <li class="nav-item" @click="tab=4">
                            <a class="nav-link"
                               v-bind:class="{'active':tab===4}"
                               href="javascript:void(0)">Другие настройки</a>
                        </li>
                        <li class="nav-item" @click="tab=5">
                            <a class="nav-link text-danger"
                               v-bind:class="{'active':tab===5}"
                               href="javascript:void(0)"><i class="fa-solid fa-skull-crossbones mr-1"></i> Устаревшие
                                настройки</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row py-3" v-show="tab===0">

                <div class="col-8">
                    <div class="row">
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
                        <div class="col-md-12 col-12" v-if="need_company_select">

                            <CompanyList
                                v-if="!load"
                                :selected="botForm.company_id"
                                v-on:callback="companyListCallback"/>

                        </div>
                        <div class="col-md-12 col-12">
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
                                       @invalid="alert('Вы не ввели название шаблона!')"
                                       aria-describedby="bot-template-description" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Строго взять из BotFather! ТО что при создании с окончанием на "bot"</div>
                                        </template>
                                    </Popper>
                                    Доменное имя бота (загружается автоматически)
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Имя бота"
                                       aria-label="Имя бота"
                                       :disabled="true"
                                       name='bot_domain'
                                       @invalid="alert('Вы не ввели доменное имя бота!', 0)"
                                       v-model="botForm.bot_domain"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>

                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Настройка параметров бота в BotFather</h6>


                                    <div class="mb-3">
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
                                                  Описание в шапке бота
                                                  <small class="text-secondary"
                                                         v-if="botForm.short_description.length>0">Длина текста {{
                                                          botForm.short_description.length
                                                      }}/105</small>
                                              </span>

                                            <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                                        </label>

                                        <div class="alert alert-info" role="alert">
                                            Данное описание видно в момент, когда пользователь делится ссылкой на бота, а также
                                            при
                                            нажатии на иконку бота.
                                        </div>

                                        <textarea class="form-control"
                                                  placeholder="Описание в шапке бота"
                                                  aria-label="Описание в шапке бота"
                                                  v-model="botForm.short_description"
                                                  maxlength="105"
                                                  aria-describedby="bot-short-description">
                                </textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between align-items-center"
                                               id="bot-long-description">
                                              <span>
                                                 Описание возможностей бота
                                                  <small class="text-secondary"
                                                         v-if="botForm.long_description.length>0">Длина текста {{
                                                          botForm.long_description.length
                                                      }}/505</small>
                                              </span>

                                            <!--                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                                        </label>

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

                                        <textarea class="form-control"
                                                  placeholder="Описание возможностей бота"
                                                  aria-label="Описание возможностей бота"
                                                  v-model="botForm.long_description"
                                                  maxlength="505"
                                                  aria-describedby="bot-long-description">
                                </textarea>
                                    </div>


                                    <div class="row"
                                         :key="'commands-'+index"
                                         v-for="(item, index) in botForm.commands">
                                        <div class="col-12" v-if="botForm.commands[index].command==='/adminmenu'">
                                            <div class="alert alert-primary" role="alert">
                                                <strong>Внимание!</strong> Отображать пользователю команду <strong
                                                class="text-danger">/adminmenu</strong> плохая идея. Команда доступна только
                                                администраторам системы, а обычный пользователь будет видеть ошибку. Это создаст
                                                негативное восприятие от работы сервиса.
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="mb-3">
                                                <input type="text" class="form-control"
                                                       placeholder="Название команды"
                                                       aria-label="Название команды"
                                                       maxlength="255"
                                                       @invalid="alert('Вы не ввели название команды',0)"
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
                                                       @invalid="alert('Вы не ввели описание команды!', 0)"
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
                                                class="btn btn-outline-success w-100">
                                                <span v-if="(botForm.commands||[]).length>0"> Добавить еще команду</span>
                                                <span v-else> Добавить системное меню</span>

                                            </button>
                                        </div>
                                    </div>

                                    <div class="row"
                                    >
                                        <div class="col-12 my-2">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       v-model="showCode" id="showCode">
                                                <label class="form-check-label" for="showCode">
                                                    Отобразить код
                                                </label>
                                            </div>
                                        </div>
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

                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-2">Кнопка меню</p>
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
                                            <div class="col-12" >
                                <span
                                    v-for="item in menu_variants"
                                    @click="selectVariant(item)"
                                    class="badge bg-primary mb-2 btn">
                                    {{ item.title || 'Выбрать' }}
                                </span>
                                            </div>
                                            <div class="col-6" >
                                                <input type="text" class="form-control"
                                                       placeholder="Текст кнопки меню"
                                                       aria-label="Текст кнопки меню"
                                                       maxlength="255"
                                                       @invalid="alert('Текст кнопки меню!', 0)"
                                                       v-model="botForm.menu.text"
                                                       required>
                                            </div>

                                            <div class="col-6">
                                                <input type="url" class="form-control"
                                                       placeholder="Адрес перехода URL"
                                                       aria-label="Адрес перехода URL"
                                                       maxlength="255"
                                                       @invalid="alert('Вы не ввели адрес меню!', 0)"
                                                       v-model="botForm.menu.url"
                                                       required>
                                            </div>
                                        </template>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <p v-if="botForm.bot_domain">Проверить работу бота</p>

                            <a
                                class="btn btn-success w-100 p-3 mb-3"
                                :href="'https://t.me/'+botForm.bot_domain"
                               target="_blank">@{{
                                    botForm.bot_domain
                                }}</a>

                            <button class="btn btn-primary p-3 w-100 mb-3">Пополнить баланс бота</button>

                            <template v-if="profile.is_admin">
                                <div class="col-12">
                                    <div class="alert alert-primary" role="alert">
                                        <strong>Внимание!</strong> Внесите сумму, которую вам дал Клиент! Эта сумма будет балансом
                                        Клиента
                                        для работы бота! Укажите тариф бота - это сумма, которую система будет списывать с клиента
                                        каждый
                                        день пока баланс бота не будет равен 0.
                                        <br>
                                        <strong>Внимание!</strong> Вы можете начислить какую-то небольшую сумму для того чтобы
                                        клиент
                                        протестировал работу бота. При достижении нулевого баланса клиент будет оповещен об этом и
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
                                            <Popper>
                                                <i class="fa-regular fa-circle-question mr-1"></i>
                                                <template #content>
                                                    <div>Начальная сумма денег на счету у конкретного бота</div>
                                                </template>
                                            </Popper>
                                            Баланс бота, руб
                                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
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
                                            <Popper>
                                                <i class="fa-regular fa-circle-question mr-1"></i>
                                                <template #content>
                                                    <div>Сумма списания денег за сутки работы бота (тариф)</div>
                                                </template>
                                            </Popper>
                                            Списание за сутки, руб
                                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row py-3" v-show="tab===1">

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
                               @invalid="alert('Вы не ввели значение кэшбэка 1 уровня бота!', 1)"
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

                <div class="col-md-12 col-12">
                    <div class="mb-3">
                        <label class="form-label" id="cashback-max_cashback_use_percent">
                            Автоматическое списание CashBack при покупке, % от цены товара</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="процент для списания CashBack при покупке"
                               v-model="botForm.max_cashback_use_percent"
                               min="0"
                               max="100"
                               aria-describedby="cashback-max_cashback_use_percent">
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
                               id="payment_provider_token-input"
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
                               @invalid="alert('Вы не ввели название категории!', 1)"
                               v-model="botForm.cashback_config[index].title"
                               :aria-describedby="'bot-cashback-config-'+index" required>


                    </div>
                    <div class="alert alert-primary" role="alert">
                        Категории CashBack - это возможность разделить накопления и траты CashBack пользователями бота
                        на
                        указанные цели, например, кофейня может создать категории: на кофе, на десерты - и начислять
                        баллы
                        за купленный кофе отдельно от баллов за купленный десерт
                    </div>
                    <button
                        type="button"
                        @click="addCashBackConfig()"
                        class="btn mb-2 rounded-sm btn-outline-info w-100">
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
            </div>
            <div class="row py-3" v-show="tab===2">
                <div class="col-12">
                    <div class="alert alert-primary" role="alert">
                        <strong>Внимание!</strong> Для того чтобы узнать ID канала сперва создайте Канал или Группу в
                        телеграм, добавьте в него вашего <strong>сохраненного бота</strong>,
                        назначьте бота администратором Канала или Группы, а только после этого впишите команду "Мой id"
                        в
                        бота.
                    </div>
                </div>
                <div class="col-md-6 col-12">
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
                <div class="col-12" v-if="botForm.order_channel">
                    <div class="alert alert-primary" role="alert">
                        <strong>Внимание!</strong> Топики работают только в Группах телеграм с включенным режимом
                        "Топики" в
                        настройках Группы! В каналах топиков нет.
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

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p>Для того, чтоб узнать идентификатор топика в группе впишите в чат "Мой id"</p>

                        <button class="btn btn-outline-info"
                                @click="createBotTopics"
                                :disabled="!can_create_topics"
                                type="button">
                            <i class="fa-solid fa-paperclip mr-2"></i>Создать топики автоматически
                        </button>
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
            <div class="row py-3" v-show="tab===3">

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
                            <!--                        <TextHelper
                                                        :param="'maintenance_message'"
                                                        v-on:callback="addTextTo"
                                                    />-->
                        </div>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое сообщение"
                                  aria-label="Текстовое сообщение"
                                  v-model="botForm.maintenance_message"
                                  maxlength="255"
                                  @invalid="alert('Вы не ввели сообщение для технических работ бота!', 3)"
                                  aria-describedby="bot-maintenance-message" required>
                    </textarea>
                    </div>
                </div>
            </div>
            <div class="row py-3" v-show="tab===4">
                <div class="col-12">
                    <div class="alert alert-primary" role="alert">
                        Системная иконка нужна только для красивого отображения и узнаваемости бота на Landing-е
                        системы.
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <h6>Системная иконка бота
                        <span class="badge rounded-pill text-bg-warning m-0">желательно</span>
                    </h6>

                    <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
                        <label for="bot-photos" style="margin-right: 10px;"
                               class="photo-loader ml-2 bg-primary text-white shadow-md" v-if="botForm.photos">
                            <span>+</span>
                            <input type="file" id="bot-photos" accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in botForm.photos"
                             v-if="botForm.photos">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto()"><i class="fa-regular fa-trash-can"></i></a>
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
                <div class="col-12">
                    <div class="alert alert-primary" role="alert">
                        Внешний сервис находится в эксперементальном режиме. Он нужен для связывания команд (страниц)
                        бота с
                        программой Клиента (если есть такая необходимость).
                        Если на странице бота выбрано "внешнее управление", то все запросы будут переадесрованы на
                        указанную
                        ниже ссылку.
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

            </div>
            <div class="row py-3" v-show="tab===5">

                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                        Внимание! Данный блок настроек больше не поддерживается и со временем эти настройки будут
                        удалены из
                        системы.
                    </div>
                </div>

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

                            <!--                        <TextHelper
                                                        :param="'welcome_message'"
                                                        v-on:callback="addTextTo"
                                                    />-->
                        </div>
                        <textarea type="text" class="form-control"
                                  style="min-height:400px;"
                                  placeholder="Текстовое приветствие при запуске бота"
                                  aria-label="Текстовое приветствие при запуске бота"
                                  v-model="botForm.welcome_message"
                                  @invalid="alert('Вы не ввели приветственное сообщение!', 3)"
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

                            <!--                        <TextHelper
                                                        :param="'description'"
                                                        v-on:callback="addTextTo"
                                                    />-->
                        </div>

                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое описание бота"
                                  aria-label="Текстовое описание бота"
                                  v-model="botForm.description"
                                  @invalid="alert('Вы не ввели описание бота!',3)"
                                  aria-describedby="bot-description" required>
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
                                <div class="col-5">
                                    <div class="mb-3">
                                        <input type="text" class="form-control"
                                               placeholder="Название ссылки"
                                               aria-label="Название ссылки"
                                               maxlength="255"
                                               @invalid="alert('Вы не ввели название ссылки на соц. сеть!',4)"
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
                                               @invalid="alert('Вы не ввели ссылку на соц. сеть!',4)"
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
            loadCommandEditor: true,

            menu_variants: [
                {
                    title: 'Открыть меню',
                    url: '/bot-client/simple/{0}?slug={скрипт_id}#/s/menu'
                }
            ],
            showCode: false,
            tab: 0,
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
                photos: [],
                commands: null,
                menu: {
                    text: null,
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


        //this.loadCurrentCompany()
        if (localStorage.getItem("cashman_admin_bot_creator_counter") != null) {
            this.can_create = false;
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
                    photos: this.bot.photos || [],
                    warnings: this.bot.warnings || [],

                    amo: this.bot.amo || null,
                    menu: {
                        text: null,
                        url: null
                    },
                }

                if (!Array.isArray(this.botForm.commands))
                    this.botForm.commands = []

                this.botForm.menu = this.bot.menu || {
                    text: null,
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
                        this.can_create = true
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
        createBotTopics() {
            this.can_create_topics = false
            this.$store.dispatch("createBotTopics", {
                dataObject: {
                    topics: this.botForm.message_threads,
                    bot_id: this.bot.id
                },
            }).then((resp) => {
                this.botForm.message_threads = resp.data

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Топики успешно созданы!",
                    type: 'success'
                });
            }).catch(() => {
                this.can_create_topics = true

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка создания топиков",
                    type: 'error'
                });
            })
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
        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        onChangePhotos(e) {
            const files = e.target.files

            this.botForm.image = null
            for (let i = 0; i < files.length; i++)
                this.botForm.photos = [files[i]]
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
        removePhoto() {
            this.botForm.photos = []
            this.botForm.image = null
        },
        updateWebHook() {
            this.$store.dispatch("updateBotWebhook", {
                dataObject: {
                    bot_id: this.bot.id,
                    server: this.botForm.server
                }
            }).catch(error => {
                this.alert(error.response.data.message)
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
            /* else {
                 this.alert('Вы не выбрали клиента', 0)
             }*/

            for (let i = 0; i < this.botForm.photos.length; i++)
                data.append('images[]', this.botForm.photos[i]);

            data.delete("photos")

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

                    this.startTimer();
                    this.can_create = false

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

                            photos: [],
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
            String.format = function () {
                let s = arguments[0];
                for (let i = 0; i < arguments.length - 1; i++) {
                    let reg = new RegExp("\\{" + i + "\\}", "gm");
                    s = s.replace(reg, arguments[i + 1]);
                }
                return s;
            }

            this.botForm.menu.text = item.title || 'Меню'
            this.botForm.menu.url = (import.meta.env.VITE_ASSET_URL || '') + String.format(item.url, this.bot.bot_domain || 'домен_бота');
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

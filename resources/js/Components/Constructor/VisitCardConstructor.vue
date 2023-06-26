<template>

        <div class="row d-flex justify-content-center">

            <div class="col-12 col-md-8 mb-2">
                <!--Шаг 0-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===0">
                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">
                    <h2>Создадим Вашего бота вместе!</h2>
                    <h6>И так давайте же начнем!</h6>
                    <button class="btn mt-3 btn-primary rounded-pill px-3"
                            @click="step++">
                        Поехали создавать
                    </button>
                </div>
                <!--Шаг 1-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===1">
<!--                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">-->
                    <h2>Приветственное сообщение!</h2>
                    <p class="mt-2 mb-2"><span class="badge bg-white text-primary p-2 mr-2"><i
                        class="fa-regular fa-bell"></i></span>И так, первым делом бот должен быть общительным и вежливым! Вам необходимо добавить привественное сообщение. <strong>Напишите небольшой текст</strong> привествия и добавьте 2
                        фото (одно к приветствию, второе на аватар).</p>
                    <div class="form-floating mb-2 w-100">
                                                <textarea class="form-control"
                                                          style="min-height:150px;"
                                                          placeholder="Leave a comment here"
                                                          id="floatingInputValue" required></textarea>
                        <label for="floatingInputValue" class="text-primary">Напишите текст привествия в боте! </label>
                    </div>
                    <h6 class="mt-3 mb-3">Добавим изображения</h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Фото \ Ссылка</label>
                    </div>
                    <div
                        class="d-flex mt-3">
                        <button
                            style="min-height: 100px; min-width:100px;"
                            class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">

                            <i class="fa-regular fa-image"></i>
                            <span>Аватарка</span>
                        </button>

                        <button
                            style="min-height: 100px; min-width:100px;"
                            class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column">
                            <i class="fa-regular fa-image"></i>
                            <span>В профиль</span>
                        </button>

                    </div>

                    <button class="btn mt-3 bg-primary text-white rounded-pill px-3 shadow"
                            @click="step++">
                        Следующий шаг
                    </button>
                </div>
                <!--Шаг 2-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===2">
<!--                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">-->
                    <h2>Персональная информация</h2>
                    <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                        class="fa-regular fa-bell"></i></span> Напишите подробную информацию о
                        себе + фото</p>
                    <div class="form-floating mb-2 w-100">
                        <input
                            type="text"
                            class="form-control"
                            id="floatingTextarea"/>
                        <label for="floatingTextarea" class="text-primary">Ваше Ф.И.О.</label>
                    </div>
                    <div class="form-floating mb-2 w-100">
                        <input
                            type="text"
                            v-mask="'+7(###)###-##-##'"
                            class="form-control"
                            id="floatingTextarea"/>
                        <label for="floatingTextarea"  class="text-primary">Ваш личный номер телефона для связи менеджера</label>
                    </div>
                    <div class="form-floating mb-2 w-100">
                                        <textarea
                                            style="min-height:150px;"
                                            class="form-control"
                                            placeholder="Leave a comment here"
                                            id="floatingTextarea"></textarea>
                        <label for="floatingTextarea"  class="text-primary">Информация о Вас:</label>
                    </div>

                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <h6 class="mb-3 mt-3">Выберите своё персональное фото </h6>
                        <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Фото \ Ссылка</label>
                        </div>
                        <button
                            style="min-height: 100px; min-width:100px;"
                            class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                            <i class="fa-regular fa-image"></i>
                            <span>Фото</span>
                        </button>
                    </div>
                    <div>
                        <button class="btn mt-3 btn-light rounded-pill px-3 mr-2 shadow"
                                @click="step--">
                            Назад
                        </button>
                        <button class="btn mt-3 btn-primary rounded-pill px-3 shadow"
                                @click="step++">
                            Следующий шаг
                        </button>
                    </div>
                </div>
                <!--Шаг 3-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===3">
<!--                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">-->
                    <h2>Информация о бизнесе</h2>
                    <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                        class="fa-regular fa-bell"></i></span>  Напишите подробную информацию о
                        Вашем бизнесе + фото.</p>
                    <div class="form-floating mb-2 w-100">
                        <input
                            type="text"
                            class="form-control"
                            id="floatingTextarea"/>
                        <label for="floatingTextarea" class="text-primary">Название вашей организации</label>
                    </div>
                    <div class="form-floating mb-2 w-100">
                                                <textarea
                                                    style="min-height:150px;"
                                                    class="form-control"
                                                    placeholder="Leave a comment here"
                                                    id="floatingTextarea"></textarea>
                        <label for="floatingTextarea" class="text-primary">Информация о бизнесе:</label>
                    </div>

                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <h6 class="mb-3 mt-3">Выберите фото для вашей визитки </h6>
                        <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Фото \ Ссылка</label>
                        </div>
                        <button
                            style="min-height: 100px; min-width:100px;"
                            class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                            <i class="fa-regular fa-image"></i>
                            <span>Фото</span>
                        </button>
                    </div>
                    <div>
                        <button class="btn mt-3 btn-primary   shadow rounded-pill px-3 mr-2"
                                @click="step--">
                            Назад
                        </button>
                        <button class="btn mt-3 btn-light shadow rounded-pill px-3"
                                @click="step++">
                            Следующий шаг
                        </button>
                    </div>
                </div>

                <!--Шаг 4-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===4">
<!--                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">-->
                    <h2>Контактная информация</h2>
                    <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                        class="fa-regular fa-bell"></i></span>  Здесь будут ссылки на все ваши контактные данные ( социальные сети,
                        месенджеры, новер телефона и сайт) + фото.</p>


                       <div class="mb-2 d-flex align-items-center justify-content-center flex-column">

                           <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                           <div class="form-check form-switch">
                               <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                               <label class="form-check-label" for="flexSwitchCheckDefault">Фото \ Ссылка</label>
                           </div>
                           <button
                               style="min-height: 100px; min-width:100px;"
                               class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2 mb-2">
                               <i class="fa-regular fa-image"></i>
                               <span>Фото</span>
                           </button>

                           <h6>А также добавьте контактную информацию, нажав на  <i class="fa-solid fa-square-plus"></i></h6>

                           <div class="dropdown mr-2">
                               <button
                                   style="min-height: 100px; min-width:100px;"
                                   class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column "
                                   type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   <i class="fa-solid fa-square-plus" style="font-size: 24px;"></i>
                                   Контакт
                               </button>
                               <ul class="dropdown-menu">
                                   <li v-for="item in contactTypes"><a
                                       @click="addContact(item)"
                                       class="dropdown-item"><i :class="item.icon"
                                                                class="mr-1"></i>{{
                                           item.title || 'Не указан'
                                       }}</a>
                                   </li>

                               </ul>
                           </div>
                       </div>

                    <div class="row mb-2 w-100 d-flex justify-content-center">
                        <div class="col-12 col-md-6" v-for="(item, index) in form.contacts.links">
                            <div class="card mb-2 w-100 shadow">

                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="text"
                                               v-if="item.mask"
                                               :pattern="item.pattern"
                                               v-mask="item.mask"
                                               class="form-control"
                                               :id="'contact-input-'+index"
                                               v-model="form.contacts.links[index].value">
                                        <input type="text"
                                               v-else
                                               :pattern="item.pattern"
                                               class="form-control"
                                               :id="'contact-input-'+index"
                                               v-model="form.contacts.links[index].value">
                                        <label
                                            class="text-primary"
                                            :for="'contact-input-'+index">{{
                                                item.title || 'Без названия'
                                            }}</label>
                                    </div>

                                    <div class="form-floating mt-1">
                                        <textarea class="form-control"
                                               v-model="form.contacts.links[index].description"
                                               placeholder="Leave a comment here"
                                               :id="'contact-description-textarea-'+index">

                                        </textarea>
                                        <label
                                            class="text-primary"
                                            :for="'contact-description-textarea-'+index">Описание</label>
                                    </div>

                                    <div class="dropdown mt-1 ">
                                        <button class="btn btn-primary w-100" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis mr-1"></i>
                                        </button>
                                        <ul class="dropdown-menu w-100">
                                            <li><a class="dropdown-item"
                                                   @click="remove(index)"
                                            ><i
                                                class="fa-solid fa-trash-can mr-1"></i> Удалить</a></li>
                                            <li><a class="dropdown-item"
                                                   @click="duplicate(item)"
                                            ><i
                                                class="fa-solid fa-clone mr-1"></i> Дублировать</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn mt-3 btn-primary rounded-pill px-3 mr-2"
                                @click="step--">
                            Назад
                        </button>
                        <button class="btn mt-3 btn-light  rounded-pill px-3"
                                @click="step++">
                            Следующий шаг
                        </button>
                    </div>
                </div>

                <!--Шаг 5-->
                <div
                    class="w-100 d-flex justify-content-center align-items-center flex-column"
                    v-if="step===5">
<!--                    <img
                        style="width: 200px; height: 200px; object-fit:cover;"
                        src="/images/cashman.jpg" alt="">-->
                    <h2>Хочу себе в бота </h2>

                    <p>
                        <span v-if="form.functions.length>0">механик выбрано - {{ form.functions.length }} ед. </span>
                        <a class="underline cursor-pointer ml-2"
                          @click="form.functions = []"
                          v-if="form.functions.length>0"> <i
                        class="fa-solid fa-xmark mr-1 text-danger"></i>
                        </a></p>

                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-3 mb-2 position-relative " v-for="item in moreFunctions">
                            <button
                                v-if="item.description"
                                type="button" class="btn btn-link text-light function-info-modal-btn"
                                data-bs-toggle="modal"
                                :data-bs-target="'#info-modal-'+item.slug">
                                <i class="fa-regular fa-circle-question"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" :id="'info-modal-'+item.slug"

                                 tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-gradient-primary-to-secondary">

                                        <div class="modal-body">
                                            <p class="text-center" v-html="item.description"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <input type="checkbox"
                                   :disabled="!item.is_active"
                                   v-model="form.functions"
                                   :value="item.slug"
                                   class="btn-check function-check"
                                   :id="'btn-check-outlined'+item.slug" autocomplete="off">
                            <label
                                style="min-height: 100px;"
                                class="btn btn-outline-light shadow d-flex flex-column align-items-center justify-content-center"
                                :for="'btn-check-outlined'+item.slug">
                                <i :class="item.icon"></i>
                                <span class="small">{{ item.title }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="row" v-if="form.functions.length>0">
                        <div class="col-12">
                                            <span class="badge bg-light text-primary mr-2"
                                                  v-for="item in form.functions">{{ item }}</span>
                        </div>
                    </div>

                    <div>
                        <button class="btn mt-3 btn-primary rounded-pill px-3 mr-2"
                                @click="step--">
                            Назад
                        </button>
                        <button class="btn mt-3 btn-light rounded-pill px-3"
                                @click="step++">
                            Финиш!
                        </button>
                    </div>
                </div>
            </div>


        </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:["start"],
    data() {
        return {
            load: false,
            step: 0,
            form: {
                greeting: {
                    text: null,
                    avatar: null,
                    image: null
                },
                contacts: {
                    image: null,
                    links: []
                },
                selfInfo: {
                    text: null,
                    image: null
                },
                businessInfo: {
                    text: null,
                    image: null
                },
                functions: []
            },
            contactTypes: [
                {
                    title: 'Номер телефона',
                    mask: '+7(###)###-##-##',
                    pattern: null,
                    slug: 'phone-number',
                    icon: 'fa-solid fa-mobile-screen'
                },
                {
                    title: 'Эл.почта',
                    pattern: null,
                    mask: null,
                    slug: 'email',
                    icon: 'fa-solid fa-at'
                },
                {
                    title: 'Соц.сети',
                    pattern: null,
                    mask: null,
                    slug: 'social-link',
                    icon: 'fa-solid fa-square-share-nodes'
                },
                {
                    title: 'Сайт',
                    pattern: null,
                    mask: null,
                    slug: 'web-site-link',
                    icon: 'fa-solid fa-laptop'
                },
                {
                    title: 'Месенджер',
                    pattern: null,
                    mask: null,
                    slug: 'messenger-link',
                    icon: 'fa-regular fa-envelope'
                },
            ],
            moreFunctions: [
                {
                    title: 'КэшБек',
                    description: 'Например три уровня  7% , 3% и 1% + фото.',
                    slug: 'cashback',
                    icon: 'fa-solid fa-sack-dollar',
                    is_active: true
                },
                {
                    title: 'Агентский кабинет',
                    description: 'Желающие смогут стать Вашими агентами, рекомендовать Вас и получать вознаграждение.',
                    slug: 'agent-cabinet',
                    icon: 'fa-solid fa-house-laptop',
                    is_active: true
                },
                {
                    title: 'Бонус за рекомендации',
                    description: 'Приграси 5 друзе и получи подарок',
                    slug: 'referral-bonus',
                    icon: 'fa-solid fa-money-bill-trend-up',
                    is_active: true
                },
                {
                    title: 'Бонус за отзыв',
                    description: 'Оставь отзыв в яндексе и 2гис и получи подарок от нас.',
                    slug: 'review-bonus',
                    icon: 'fa-solid fa-comments-dollar',
                    is_active: false
                },
                {
                    title: 'Регистрация на мероприятия',
                    slug: 'event-form',
                    description: 'Вы можете регистрировать людей на мероприятия через нашего чат бата или же сообщать о своих ближайших мероприятиях',
                    icon: 'fa-solid fa-calendar-check',
                    is_active: true
                },
                {
                    title: 'Интеграция с каналами Telegram',
                    description: 'Мы может интегрировать ваш канал в наш бот',
                    slug: 'telegram-channel-integration',
                    icon: 'fa-brands fa-telegram',
                    is_active: false
                },
                {
                    title: 'Прикрепленные документы',
                    slug: 'attached-documents',
                    icon: 'fa-solid fa-folder-open',
                    description: 'Вы можете прикрепить документ в котором хотите рассказать (например) об обучении, о курсах и тд.',
                    is_active: true
                },
                {
                    title: 'Лид магнит',
                    slug: 'lead-magnet',
                    icon: 'fa-solid fa-magnet',
                    description: 'Лид магнит продумывается индивидуально',
                    is_active: true
                },
                {
                    title: 'Воронка продаж',
                    slug: 'sales-funnel',
                    description: 'Воронка продаж продумывается индивидуально',
                    icon: 'fa-solid fa-filter-circle-dollar',
                    is_active: true
                },
                {
                    title: 'Отзывы',
                    slug: 'reviews',
                    description: 'Можете добавить отзывы в бот чтобы людям не приходилось искать отзовы на других площадках ',
                    icon: 'fa-solid fa-magnifying-glass-dollar',
                    is_active: true
                },
                {
                    title: 'Задать вопрос',
                    slug: 'ask-a-question',
                    description: 'Люди смогут задавать вопрос прямо в бот, а Вы отвечать им уже лично',
                    icon: 'fa-solid fa-clipboard-question',
                    is_active: true
                },
                {
                    title: 'Получить онлайн консультацию',
                    slug: 'online-consultation',
                    description: 'Запись на консультацию',
                    icon: 'fa-solid fa-circle-info',
                    is_active: true
                },
                {
                    title: 'Локация',
                    slug: 'location',
                    description: 'Ваш адрес, если необходим вашему бизнесу ',
                    icon: 'fa-solid fa-map-location-dot',
                    is_active: true
                },
                {
                    title: 'Акции',
                    slug: 'promotions',
                    description: 'Вы сможете выкладывать акции и распродажи',
                    icon: 'fa-solid fa-percent',
                    is_active: true
                },

                {
                    title: 'Наши клиенты',
                    slug: 'our-clients',
                    description: 'Сюда Вы сможете выложить фото ваших гостей ',
                    icon: 'fa-solid fa-carrot',
                    is_active: true
                },
                {
                    title: 'Инвесторам',
                    slug: 'investors',
                    description: 'Предложения для инвестрора',
                    icon: 'fa-solid fa-comment-dollar',
                    is_active: true
                },
                {
                    title: 'Франшиза',
                    slug: 'franchise',
                    description: 'Информация о франшизе',
                    icon: 'fa-solid fa-earth-asia',
                    is_active: true
                },
                {
                    title: 'Стоимость услуг',
                    slug: 'cost-of-services',
                    description: 'Ваш прайс или меню',
                    icon: 'fa-solid fa-money-check-dollar',
                    is_active: true
                },
                {
                    title: 'Ваш магазин',
                    slug: 'custom-shop',
                    description: 'Ваш индивидуальный магазин внутри бота с возможностью оплаты онлайн',
                    icon: 'fa-brands fa-shopify',
                    is_active: true
                },
                {
                    title: 'Стоит купить или попробовать',
                    slug: 'buy-or-try',
                    description: 'Горящее предложение',
                    icon: 'fa-solid fa-basket-shopping',
                    is_active: true
                },
                {
                    title: 'Доставка',
                    slug: 'delivery',
                    description: 'Если вы отправляете доставки яндек или сдек и тд.',
                    icon: 'fa-solid fa-dolly',
                    is_active: true
                },
                {
                    title: 'Забронировать номер/столик',
                    slug: 'booking',
                    description: 'Человет сможет заброниравать в боте или же выйти на менеджера',
                    icon: 'fa-solid fa-utensils',
                    is_active: true
                },
                {
                    title: 'Атмосфера',
                    slug: 'atmosphere',
                    description: 'Фото Вашей атмосферы',
                    icon: 'fa-solid fa-trophy',
                    is_active: true
                },
                {
                    title: 'Курсы',
                    slug: 'courses',
                    description: 'Информация о курсах',
                    icon: 'fa-solid fa-graduation-cap',
                    is_active: true
                },
                {
                    title: 'Оплата в боте',
                    slug: 'payments',
                    description: 'Создавайте запросы на оплату непосредственно в боте',
                    icon: 'fa-regular fa-credit-card',
                    is_active: false
                },
                {
                    title: 'Колесо фортуны',
                    slug: 'wheel-of-fortune',
                    description: 'Розыгрыши призов в боте',
                    icon: 'fa-solid fa-dharmachakra',
                    is_active: false
                },
                {
                    title: 'Индивидуальная кнопка',
                    slug: 'individual-button',
                    description: 'Можете предложить свою кнопку',
                    icon: 'fa-solid fa-person-chalkboard',
                    is_active: true
                },
            ]
        }
    },
    computed: {
        ...mapGetters(['getCurrentCompany']),
    },
    mounted() {
        if (this.start)
            this.step = this.start
    },
    methods: {
        remove(index) {
            this.form.contacts.links.splice(index, 1)
        },
        duplicate(contact) {
            const tmpContact = {
                ...contact
            }
            this.form.contacts.links.push(tmpContact)
        },
        addContact(slug) {
            const tmpSlug = {
                ...slug,
                value: null,
                description: null,
            }
            this.form.contacts.links.push(tmpSlug)
        }
    }
}
</script>
<style lang="scss">
.function-check + label {
    & > i {
        font-size: 26px;
    }
}

.function-info-modal-btn {
    position: absolute;

    &:focus {
        outline: none;
    }
}
</style>

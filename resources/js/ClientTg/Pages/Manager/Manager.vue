<script setup>
import CallbackForm from "ClientTg@/Components/Shop/CallbackForm.vue";
import PlayerForm from "ClientTg@/Components/Shop/PlayerForm.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import ProjectInfoCard from "ClientTg@/Components/Shop/Helpers/ProjectInfoCard.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style p-3" v-if="!botUser.is_manager">
            <form
                v-on:submit.prevent="submit" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'/images/manager.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Приветствую Вас, <strong>Дорогой друг!</strong> Я хочу поздравить Вас и дать
                        возможность получать неограниченные заработка вместе с нашим сервисом! Для начала нам нужно с
                        Вами
                        познакомиться - это поможет нам понимать с кем мы становимся крепкими друзьями! Я задам Вам два
                        блока вопросов - по легче - вопросы общего плана и по сложнее - вопросы профессиональной сферы.</em>
                    </p>
                    <h6 class="text-center">Как мне к Вам обращаться?</h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Петров Петр Семенович"
                               aria-label="managerForm-name"
                               v-model="managerForm.name"
                               aria-describedby="managerForm-name" required>
                    </div>
                </div>


                <div class="col-12">
                    <h4 class="text-center my-3">
                        <i class="fa-solid fa-person-circle-question mr-2 color-green2-light"></i>
                        Общий блок вопросов
                    </h4>
                    <p class="mb-3" v-if="managerForm.name"><em>- Отлично, <strong>{{ managerForm.name }}</strong>! А
                        теперь,
                        чтобы Вы могли
                        пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                    </p>
                    <h6 class="text-center">Введите свой номер телефона</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="'+7(###)###-##-##'"
                               v-model="managerForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="managerForm-phone" aria-describedby="managerForm-phone" required>

                    </div>

                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Для своевременного оповещения Вас о наших нововведениях мы используем рассылку
                        через email. Вам необходимо указать его для удобства получения актуальной информации и отчетов о
                        Вашей работе и доходе.</em>
                    </p>
                    <h6 class="text-center">Введите свой email адрес</h6>
                    <div class="input-style input-style-2">
                        <input type="email" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.email"
                               placeholder="inbox@your-cashman.com"
                               aria-label="managerForm-phone" aria-describedby="managerForm-email" required>

                    </div>

                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Для связи с Вами нам также понадобятся ссылки на Ваши социальные сети!</em>
                    </p>
                    <h6 class="text-center">Ссылки на ваши соц. сети</h6>

                    <div class="input-style input-style-2 position-relative"
                         v-for="(item, index) in managerForm.social_links">
                        <input type="url" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.social_links[index]"
                               placeholder="Ссылка на соц. сеть"
                               :aria-label="'managerForm-social-links-'+index"
                               :aria-describedby="'managerForm-social-links-'+index" required>

                        <a href="javascript:void(0)"
                           v-if="index>0"
                           @click="remove('social_links', index)"
                           class="sub-btn">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                    </div>
                    <a
                        @click="add('social_links')"
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще ссылку</a>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пола?</em></p>
                    <h6 class="text-center">Вы мужчина или женщина?</h6>

                    <div class="row mb-0">
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight':managerForm.sex}"
                                @click="managerForm.sex = true"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Мужчина</span>
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight ':!managerForm.sex}"
                                @click="managerForm.sex = false"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s  shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Женщина</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Для того, чтобы я мог поздравлять Вас с днем рождения и сделать Вам приятно, мне
                        нужно знать, когда он у Вас</em></p>
                    <h6 class="text-center">Введите свой день рождения</h6>
                    <div class="input-style input-style-2">
                        <input type="date" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.birthday"
                               aria-label="managerForm-birthday" aria-describedby="managerForm-birthday" required>
                    </div>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно
                        знать
                        город Вашего проживания\работы.</em></p>
                    <h6 class="text-center">Какой у Вас город?</h6>
                    <div class="input-style input-style-2">
                        <input type="text"
                               v-model="managerForm.city"
                               list="datalistCityOptions"
                               class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Краснодар"
                               aria-label="managerForm-city" aria-describedby="managerForm-city" required>
                        <datalist id="datalistCityOptions">
                            <option value="Краснодар"/>
                            <option value="Ростов-на-Дону"/>
                            <option value="Таганрог"/>
                            <option value="Донецк"/>
                            <option value="Москва"/>
                        </datalist>
                    </div>
                </div>


                <div class="col-12">
                    <h4 class="text-center my-3">
                        <i class="fa-solid fa-clipboard-question mr-2 color-green2-light"></i>
                        Профессиональный блок вопросов
                    </h4>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Полученное высшее образование</h6>
                    <p class="mb-0 text-center"><em>Если еще нет высшего образования, впишите "нет"</em></p>
                    <div class="input-style input-style-2 position-relative"
                         v-for="(item, index) in managerForm.educations">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.educations[index]"
                               placeholder="Название ВУЗа, специализация, уровень образования"
                               :aria-label="'managerForm-educations-'+index"
                               :aria-describedby="'managerForm-educations-'+index" required>

                        <a href="javascript:void(0)"
                           v-if="index>0"
                           @click="remove('educations', index)"
                           class="sub-btn">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                    </div>
                    <a
                        @click="add('educations')"
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще высшее образование</a>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Ваши сильные стороны</h6>

                    <div class="input-style input-style-2 position-relative"
                         v-for="(item, index) in managerForm.strengths">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.strengths[index]"
                               placeholder="Ваше сильное качество"
                               :aria-label="'managerForm-strengths-'+index"
                               :aria-describedby="'managerForm-strengths-'+index" required>

                        <a href="javascript:void(0)"
                           v-if="index>0"
                           @click="remove('strengths', index)"
                           class="sub-btn">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                    </div>
                    <a
                        @click="add('strengths')"
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще сильные стороны</a>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Слабые стороны есть у всех! Нам нужно понимать в чем Вас стоит прокачать и в
                        каких проблемных для Вас местах оказать помощь и т.д.</em></p>
                    <h6 class="text-center">Ваши слабые стороны</h6>

                    <div class="input-style input-style-2 position-relative"
                         v-for="(item, index) in managerForm.weaknesses">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.weaknesses[index]"
                               placeholder="Ваша слабая сторона"
                               :aria-label="'managerForm-weaknesses-'+index"
                               :aria-describedby="'managerForm-weaknesses-'+index" required>

                        <a href="javascript:void(0)"
                           v-if="index>0"
                           @click="remove('weaknesses', index)"
                           class="sub-btn">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                    </div>
                    <a
                        @click="add('weaknesses')"
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще слабые стороны</a>
                </div>


                <div class="col-12">
                    <p class="mb-3"><em>А теперь о ваших навыка - впишите название навыка и укажите % владения навыком
                        при помощи ползунка.</em></p>
                    <h6 class="text-center">Ваши слабые стороны</h6>

                    <div class="mb-2" v-for="(item, index) in managerForm.skills">
                        <div class="input-style input-style-2 position-relative">
                            <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                                   v-model="managerForm.skills[index].title"
                                   placeholder="Название навыка"
                                   :aria-label="'managerForm-skills-'+index"
                                   :aria-describedby="'managerForm-skills-'+index" required>

                            <a href="javascript:void(0)"
                               v-if="index>0"
                               @click="remove('skills', index)"
                               class="sub-btn">
                                <i class="fa-regular fa-square-minus"></i>
                            </a>
                        </div>

                        <div class="range-slider bottom-15 range-slider-icons">
                            <i class="fa-range-icon-1 color-theme">0</i>
                            <i class="fa-range-icon-2 color-theme">100</i>
                            <p class="mb-0 text-center"><span
                                v-if="managerForm.skills[index].title">{{ managerForm.skills[index].title }} прокачан на </span>{{ managerForm.skills[index].value }}%
                            </p>

                            <input class="ios-slider"
                                   max="100"
                                   v-model="managerForm.skills[index].value"
                                   type="range">
                        </div>
                    </div>

                    <a
                        @click="add('skills')"
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще навык</a>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Напишите о себе любую информацию, которая может иметь для нас значение </em></p>
                    <h6 class="text-center">Дополнительная информация</h6>
                    <div class="input-style input-style-2">
                        <textarea type="text" class="form-control text-left font-14 p-3 rounded-s border-theme"
                                  v-model="managerForm.info"
                                  placeholder="Дополнительная информация"
                                  style="min-height:200px;"
                                  aria-label="managerForm-referral" aria-describedby="managerForm-info">
                        </textarea>
                    </div>
                </div>


                <div class="divider divider-small my-3 bg-highlight "></div>

                <div class="col-12">
                    <p class="mb-3"><em>Для того чтоб вы и ваш друг получали больше бонусов воспользуйтесь
                        реферальной программой и введите реферальный код от вашего друга!</em></p>
                    <h6 class="text-center">Введите ссылку на вашего друга</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.referral"
                               v-mask="'#######-#######-#######'"
                               placeholder="0000000-0000000-0000000"
                               aria-label="managerForm-referral" aria-describedby="managerForm-referral" required>
                    </div>
                </div>


                <div class="divider divider-small my-3 bg-highlight "></div>

                <div class="col-12">
                    <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                        использования и дайте свое согласие на их принятие.</em></p>

                    <p>Перед отправкой данных нужно ознакомиться с <a
                        href="#">политикой конфиденциальности</a>.</p>

                    <div class="d-flex mb-3">
                        <div class="pt-1">
                            <h5 data-activate="toggle-id-1" class="font-500 font-13">
                                <span v-if="!managerForm.sex">С правилами озакномилась</span>
                                <span v-if="managerForm.sex">С правилами ознакомлен</span>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch">
                                <input
                                    v-model="confirm"
                                    type="checkbox" class="ios-input" id="toggle-id-1">
                                <label class="custom-control-label" for="toggle-id-1"></label>
                            </div>
                        </div>
                    </div>


                    <button type="submit"
                            :disabled="!confirm||load"
                            class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Отправить анкету
                    </button>

                </div>
            </form>

        </div>


        <div class="card card-style p-3" v-if="botUser.is_manager">
            <h6>Поздравляем! Вы являетесь нашим официальным Менеджером! </h6>

            <a href="javascript:void(0)"
               v-if="botUser.manager.verified_at!=null"
               class="chip chip-small bg-gray1-dark">
                <i class="fa fa-check bg-green1-dark"></i>
                <strong class="color-black font-400">Учетная запись менеджера активна</strong>
            </a>


            <a href="#" class="chip chip-small bg-gray1-dark" v-else>
                <i class="fa fa-times bg-red2-dark"></i>
                <strong class="color-black font-400">Учетная запись менеджера не активна</strong>
            </a>

            <p class="mb-0">Имя: {{ botUser.name || 'Не указано' }}</p>
            <p class="mb-0">Телефон: {{ botUser.phone || 'Не указано' }}</p>
            <p class="mb-0">Город: {{ botUser.city || 'Не указано' }}</p>
            <p class="mb-0">Дата рождения: {{ botUser.birthday || 'Не указано' }}</p>
            <p class="mb-0">Ваш баланс: {{ botUser.manager.balance || 0 }} руб</p>
            <p class="mb-0">Пол: {{ botUser.sex ? 'Мужской' : 'Женский' }}</p>
            <p class="mb-0">Колл-во слотов под клиентов: {{botUser.manager.max_company_slot_count || 0}}</p>
            <p class="mb-3">Колл-во слотов под ботов у клиента: {{botUser.manager.max_bot_slot_count || 0}}</p>

            <h6>Вам доступны следующие возможности:</h6>
            <ul>
                <li>Регистрация клиента</li>
                <li>Создание ботов любой конфигурации и сложности</li>
                <li>Выставление счетов на оплату и прием платеже за обслуживание</li>
                <li>Реферальная программа</li>
                <li>Профессиональное обучение</li>
                <li>Использование ресурсов компании для привлечения клиентов: маркетологи, дизайнеры, smm-специалисты,
                    программисты
                </li>
                <li>Тех.поддержка по системе 24\7</li>


            </ul>
            <h6> Ваши бонусы:</h6>
            <ul>
                <li>Оплата за регистрацию клиента (после его оплаты)</li>
                <li>Начисление персональной скидки (сгораемой и несгораемой)</li>
                <li>Получение бонусных доходов с реферальной программы 1, 2 и 3 уровня: ваши друзья работают, а вы
                    получаете доход. Доход ограничен лишь числом друзей.
                </li>
            </ul>

            <ReturnToBot class="mb-2"/>
        </div>

    </div>


    <ProjectInfoCard/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {

            load: false,
            confirm: false,
            step: 0,
            botUser: null,
            managerForm: {
                name: null,
                phone: null,
                email: null,

                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,
                referral: null,
                strengths: [""],
                weaknesses: [""],
                educations: [""],
                social_links: [""],
                skills: [
                    {
                        title: null,
                        value: 50,
                    }
                ],
                info: null,

                //навыки, сильные и слабые стороны, краткое био

            }
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

            this.managerForm.name = this.botUser.name || this.botUser.fio_from_telegram || null
            this.managerForm.phone = this.botUser.phone || null
            this.managerForm.email = this.botUser.email || null
            this.managerForm.birthday = this.botUser.birthday || null
            this.managerForm.city = this.botUser.city || null
            this.managerForm.country = this.botUser.country || null
            this.managerForm.address = this.botUser.address || null
            this.managerForm.sex = this.botUser.sex || true
        }

    },
    mounted() {

    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        nextStep() {
            this.step++;
        },
        remove(section, index) {
            this.managerForm[section].splice(index, 1)
        },
        add(section) {
            if (section !== "skills")
                this.managerForm[section].push('')
            else
                this.managerForm[section].push({
                    title: null,
                    value: 50,
                })
        },
        submit() {
            this.loading = true;

            let data = new FormData();
            Object.keys(this.managerForm)
                .forEach(key => {
                    const item = this.managerForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            this.$store.dispatch("saveManager",
                data
            ).then((resp) => {
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}

.input-style-2 a {
    position: absolute;
    right: 12px;
    top: 12px;
    font-size: 16px;
}
</style>

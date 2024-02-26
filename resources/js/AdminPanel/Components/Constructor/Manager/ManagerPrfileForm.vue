<script setup>
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";
import ManagerPackageList from "@/AdminPanel/Components/Constructor/Manager/ManagerPackageList.vue";
import ManagerSlotList from "@/AdminPanel/Components/Constructor/Manager/ManagerSlotList.vue";
import ManagerStudiesList from "@/AdminPanel/Components/Constructor/Manager/ManagerStudiesList.vue";
import PromoCodeActivateForm from "@/AdminPanel/Components/Constructor/PromoCodes/PromoCodeActivateForm.vue";
</script>

<template>


    <div class="container shadow-lg border  mt-1 mb-1" v-if="botUser">
        <div class="row">
            <div class="col-md-3 border-right bg-primary" v-if="botUser.manager">
                <div
                    style="position:sticky;top:0px;"
                    class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <p class="text-white" v-if="botUser.manager.verified_at!=null">
                        Учетная запись менеджера <strong class="text-uppercase">активирована!</strong>
                    </p>
                    <p class="text-white" v-else>
                        Учетная запись менеджера<!-- <strong class="text-uppercase">не активна!</strong>-->
                    </p>

                    <img
                        class="rounded-circle mt-5 mb-5" style="width:150px; height:150px; object-fit:cover; border:1px white solid;"
                        v-lazy="botUser.manager.image?botUser.manager.image:'../images/manager.png'">


                    <p class="mb-0 text-light">Приветствуем, <strong class="text-white">{{
                            botUser.name || 'Не указано'
                        }}</strong>!</p>
                    <!--                    <p class="mb-0">Телефон: <strong class="text-white">{{ botUser.phone || 'Не указано' }}</strong></p>-->
                    <!--
                                        <p class="mb-0">Ваш баланс: <strong class="text-white">{{ botUser.manager.balance || 0 }}
                                            руб</strong></p>-->
                    <!--                    <p class="mb-0">Слотов под клиентов: <strong
                                            class="text-white">{{ botUser.manager.max_company_slot_count || 0 }}</strong></p>-->
                    <p class="mb-3 my-3"><a href="#"
                                            data-bs-toggle="modal" data-bs-target="#activate-promo-code"
                                            class="btn btn-link text-white">У вас <strong
                        class="text-white">{{
                            botUser.manager.max_bot_slot_count || 0
                        }}</strong> свободных слотов</a></p>
                    <a @click="tab++"
                       v-if="tab===0"
                       class="btn btn-outline-light p-3 rounded-5 w-100 mb-2"><i class="fa-solid fa-user-pen mr-2"></i>Профиль</a>
                    <a @click="tab=0"
                       v-if="tab>0"
                       class="btn btn-outline-light p-3 rounded-5 w-100 mb-2">Главный экран</a>

                    <a
                        @click="tab=10"
                        class="btn btn-info p-3 rounded-5 w-100 mb-2"><i class="fa-solid fa-graduation-cap mr-2"></i>Обучение</a>

                    <a
                        @click="tab=11"
                        class="btn btn-info p-3 rounded-5 w-100 mb-2"><i class="fa-solid fa-robot mr-2"></i>Мои боты</a>

<!--                    <a href="/bot-page"
                       target="_blank"
                       v-if="botUser.manager"
                       class="text-white">
                        Перейти к редактированию ботов
                    </a>-->

<!--                    <a href="/договор_аренды_по.docx" target="_blank" class="text-white">
                        Образец договора для клиента
                    </a>-->
                </div>
            </div>

            <div class="col-md-9"
                 v-bind:class="{'col-md-12':!botUser.manager}"
                 v-if="tab===0">
                <div class="row">
                    <div class="col-12 mt-3" v-if="!botUser.manager">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3>Для начал заведите профиль</h3>
                                <h5 class="card-title">Редактирование профиля менеджера</h5>
                                <p class="card-text">Внесите информацию о себе и получите более расширенный доступ к
                                    системе</p>
                                <a @click="tab++" class="btn btn-outline-primary p-3">Перейти к разделу</a>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 my-3" v-if="botUser.manager">

                        <BotForm v-if="!load"
                                 v-on:callback="prepareCurrentBot"
                        />
                    </div>


                </div>
            </div>
            <div class="col-md-9"
                 v-bind:class="{'col-md-12':!botUser.manager}"
                 v-if="tab>0&&tab<10">
                <div class="row mt-2">
                    <div class="col-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link"
                                   @click="tab=1"
                                   v-bind:class="{'active':tab===1}"
                                   aria-current="page" href="#">
                                    Профиль
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled"
                                   v-bind:class="{'active':tab===2}"
                                   href="#" @click="tab=2">
                                    Документы
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled"
                                   v-bind:class="{'active':tab===3}"
                                   @click="tab=3"
                                   href="#">
                                    Клиенты & Боты
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled"
                                   @click="tab=4"
                                   v-bind:class="{'active':tab===4}"
                                   href="#"
                                >
                                    Навыки и умения
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled"
                                   @click="tab=5"
                                   v-bind:class="{'active':tab===5}"
                                   href="#"
                                >
                                    Статистика
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" v-if="tab===1">
                    <form
                        v-on:submit.prevent="submitManager" class="row mb-0">
                        <div
                            class="col-md-6 border-right">
                            <div class="p-3 py-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">Настройки профиля менеджера</h4>
                                </div>


                                <div class="col-12">
                                    <label>Укажите Ваше Ф.И.О</label>
                                    <input type="text" class="form-control font-14 p-3"
                                           placeholder="Петров Петр Семенович"
                                           aria-label="managerForm-name"
                                           v-model="managerForm.name"
                                           aria-describedby="managerForm-name" required>
                                </div>


                                <div class="col-12 mt-3">
                                    <label>Введите свой номер телефона</label>
                                    <input type="text"
                                           class="form-control font-14 p-3 rounded-s border-theme"
                                           v-mask="'+7(###)###-##-##'"
                                           v-model="managerForm.phone"
                                           placeholder="+7(000)000-00-00"
                                           aria-label="managerForm-phone" aria-describedby="managerForm-phone"
                                           required>

                                </div>

                                <div class="col-12">
                                    <label>Введите свой email адрес</label>
                                    <input type="email"
                                           class="form-control font-14 p-3 rounded-s border-theme"
                                           v-model="managerForm.email"
                                           placeholder="inbox@your-cashman.com"
                                           aria-label="managerForm-phone" aria-describedby="managerForm-email"
                                           required>
                                </div>


                                <div class="col-12">
                                    <label>Укажите город вашего проживания</label>
                                    <div class="input-style input-style-2">
                                        <input type="text"
                                               v-model="managerForm.city"
                                               list="datalistCityOptions"
                                               class="form-control font-14 p-3 rounded-s border-theme"
                                               placeholder="Город проживания"
                                               aria-label="managerForm-city" aria-describedby="managerForm-city"
                                               required>
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
                                    <p class="mb-3"><em>Напишите о себе любую информацию, которая может иметь для нас
                                        значение </em></p>
                                    <h6>Дополнительная информация</h6>
                                    <div class="input-style input-style-2">
                        <textarea type="text" class="form-control text-left font-14 p-3 rounded-s border-theme"
                                  v-model="managerForm.info"
                                  placeholder="Дополнительная информация"
                                  style="min-height:200px;"
                                  aria-label="managerForm-referral" aria-describedby="managerForm-info">
                        </textarea>
                                    </div>
                                </div>

                                <div class="col-12" v-if="!botUser.manager">
                                    <p class="mb-3"><em>Для того чтоб вы и ваш друг получали больше бонусов
                                        воспользуйтесь
                                        реферальной программой и введите реферальный код от вашего друга!</em></p>
                                    <h6 class="text-center">Введите реферальный код вашего друга</h6>
                                    <div class="input-style input-style-2">
                                        <input type="text"
                                               class="form-control text-center font-14 p-3 rounded-s border-theme"
                                               v-model="managerForm.referral"
                                               placeholder="Реферальный код"
                                               aria-label="managerForm-referral"
                                               aria-describedby="managerForm-referral">
                                    </div>
                                </div>


                                <div class="col-12">
                                    <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте
                                        условия
                                        использования и дайте свое согласие на их принятие.</em></p>

                                    <p>Перед отправкой данных нужно ознакомиться с <a
                                        href="#">политикой конфиденциальности</a>.</p>

                                    <div class="d-flex mb-3">
                                        <div class="pt-1">
                                            <p data-activate="toggle-id-1" class="font-500 font-13">
                                                <span v-if="!managerForm.sex">С правилами ознакомилась</span>
                                                <span v-if="managerForm.sex">С правилами ознакомлен</span>
                                            </p>
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
                                            class="btn btn-primary mb-2  text-uppercase  w-100">
                                        Отправить анкету
                                    </button>

                                </div>

                            </div>
                        </div>
                        <div
                            class="col-md-6 border-right">
                            <div class="my-2">
                                <label class="mb-3">Загрузи своё персональное фото, мы же должны знать в лицо наших
                                    сотрудников</label>
                                <div class="d-flex justify-content-center flex-wrap ">
                                    <label for="bot-photos" style="margin-right: 10px;"
                                           class="photo-loader ml-2 text-center">
                                        <span class="p-3"><i class="fa-solid fa-image"></i></span>
                                        <input type="file" id="bot-photos" accept="image/*"
                                               @change="onChangePhotos"
                                               style="display:none;"/>

                                    </label>
                                </div>


                                <div class="d-flex justify-content-center flex-wrap mt-2" v-if="managerForm.image">
                                    <div class="img-preview">
                                        <img v-lazy="managerForm.image">
                                        <div class="remove">
                                            <a @click="removePhoto('image')" class="cursor-pointer"><i
                                                class="fa-regular fa-trash-can"></i> удалить фото</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center flex-wrap mt-2"
                                     v-if="photo">

                                    <div class="img-preview"
                                         style="margin-right: 10px;">
                                        <img v-lazy="getPhoto(photo).imageUrl">
                                        <div class="remove">
                                            <a @click="removePhoto('photo')" class="cursor-pointer"><i
                                                class="fa-regular fa-trash-can"></i> удалить фото</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="mb-2">

                                <label>Укажите ваш пол</label>

                                <div class="row mb-0">
                                    <div class="col-6 p-1">
                                        <div
                                            v-bind:class="{'btn-primary text-white':managerForm.sex}"
                                            @click="managerForm.sex = true"
                                            class="btn btn-outline-secondary w-100  d-flex justify-content-center align-items-center ">
                                            <i class="fa-solid fa-mars font-28 mr-2"></i>
                                            <span class="text-center text-uppercase my-2">Мужчина</span>
                                        </div>
                                    </div>
                                    <div class="col-6 p-1">
                                        <div
                                            v-bind:class="{'btn-primary text-white':!managerForm.sex}"
                                            @click="managerForm.sex = false"
                                            class="btn btn-outline-secondary w-100 d-flex justify-content-center align-items-center ">
                                            <i class="fa-solid fa-mars font-28 mr-2"></i>
                                            <span class="text-center text-uppercase my-2">Женщина</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Ссылки на ваши соц. сети</label>

                                <div class="input-group position-relative mb-2"
                                     v-for="(item, index) in managerForm.social_links">
                                    <input type="url"
                                           class="form-control  font-14 p-3 rounded-s border-theme"
                                           v-model="managerForm.social_links[index]"
                                           placeholder="Ссылка на соц. сеть"
                                           :aria-label="'managerForm-social-links-'+index"
                                           :aria-describedby="'managerForm-social-links-'+index">
                                    <span class="input-group-text bg-primary"
                                          v-if="index>0">
                                            <a href="javascript:void(0)"

                                               @click="remove('social_links', index)"
                                               class="text-white">
                                                <i class="fa-regular fa-square-minus"></i>
                                            </a>
                                         </span>
                                </div>
                                <a
                                    @click="add('social_links')"
                                    class="d-block w-100 py-3 text-center"
                                    href="javascript:void(0)">Добавить еще ссылку</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-if="tab===2"></div>
                <div class="row" v-if="tab===3"></div>
                <div class="row" v-if="tab===4">


                    <div class="col-12">
                        <label>Введите дату своего рождения</label>
                        <div class="input-style input-style-2">
                            <input type="date"
                                   class="form-control font-14 p-3 rounded-s border-theme"
                                   v-model="managerForm.birthday"
                                   aria-label="managerForm-birthday" aria-describedby="managerForm-birthday"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6" v-bind:class="{'col-md-6':!botUser.manager}">

                        <div class="row mt-5">
                            <div class="col-12">
                                <label>Полученное высшее образование</label>
                                <p class="mb-0 small"><em>Если еще нет высшего образования, впишите <strong
                                    class="text-primary">"нет"</strong></em></p>
                                <div class="input-group position-relative mb-2"
                                     v-for="(item, index) in managerForm.educations">
                                    <input type="text"
                                           class="form-control font-14 p-3"
                                           v-model="managerForm.educations[index]"
                                           placeholder="Название ВУЗа, специализация, уровень образования"
                                           :aria-label="'managerForm-educations-'+index"
                                           :aria-describedby="'managerForm-educations-'+index" required>

                                    <span class="input-group-text bg-primary "
                                          v-if="index>0"
                                          :id="'managerForm-educations-'+index">
                             <a href="javascript:void(0)"

                                @click="remove('educations', index)"
                                class=" text-white">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                        </span>

                                </div>
                                <a
                                    @click="add('educations')"
                                    class="d-block w-100"
                                    href="javascript:void(0)"><i class="fa-solid fa-plus mr-2"></i>Добавить еще высшее
                                    образование</a>
                            </div>

                            <div class="col-12 mt-3">
                                <label>Ваши сильные стороны</label>

                                <div class="input-group position-relative mb-2"
                                     v-for="(item, index) in managerForm.strengths">
                                    <input type="text"
                                           class="form-control font-14 p-3"
                                           v-model="managerForm.strengths[index]"
                                           placeholder="Ваше сильное качество"
                                           :aria-label="'managerForm-strengths-'+index"
                                           :aria-describedby="'managerForm-strengths-'+index" required>

                                    <span class="input-group-text bg-primary "
                                          v-if="index>0"
                                          :id="'managerForm-strengths-'+index">
                             <a href="javascript:void(0)"
                                @click="remove('strengths', index)"
                                class="text-white">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                        </span>

                                </div>
                                <a
                                    @click="add('strengths')"
                                    class="d-block w-100"
                                    href="javascript:void(0)"><i class="fa-solid fa-plus mr-2"></i>Добавить еще сильные
                                    стороны</a>
                            </div>

                            <div class="col-12 mt-3">
                                <label>Ваши слабые стороны</label>

                                <div class="input-group position-relative mb-2"
                                     v-for="(item, index) in managerForm.weaknesses">
                                    <input type="text"
                                           class="form-control font-14 p-3"
                                           v-model="managerForm.weaknesses[index]"
                                           placeholder="Ваша слабая сторона"
                                           :aria-label="'managerForm-weaknesses-'+index"
                                           :aria-describedby="'managerForm-weaknesses-'+index" required>
                                    <span class="input-group-text bg-primary "
                                          v-if="index>0"
                                          :id="'managerForm-weaknesses-'+index">
                        <a href="javascript:void(0)"
                           v-if="index>0"
                           @click="remove('weaknesses', index)"
                           class="text-white">
                            <i class="fa-regular fa-square-minus"></i>
                        </a>
                        </span>
                                </div>
                                <a
                                    @click="add('weaknesses')"
                                    class="d-block w-100"
                                    href="javascript:void(0)"><i class="fa-solid fa-plus mr-2"></i> Добавить еще слабые
                                    стороны</a>
                            </div>


                            <div class="col-12 mt-3">
                                <label>Ваши профессиональные навыки</label>

                                <div class="mb-2" v-for="(item, index) in managerForm.skills">
                                    <div class="input-group position-relative ">
                                        <input type="text"
                                               class="form-control font-14 p-3"
                                               v-model="managerForm.skills[index].title"
                                               placeholder="Название навыка"
                                               :aria-label="'managerForm-skills-'+index"
                                               :aria-describedby="'managerForm-skills-'+index" required>
                                        <span class="input-group-text bg-primary "
                                              v-if="index>0"
                                              :id="'managerForm-skills-'+index">
                            <a href="javascript:void(0)"
                               v-if="index>0"
                               @click="remove('skills', index)"
                               class="text-white">
                                <i class="fa-regular fa-square-minus"></i>
                            </a>
                            </span>
                                    </div>

                                    <div class="range-slider bottom-15 range-slider-icons">
                                        <p class="my-2 text-center font-bold"><span
                                            v-if="managerForm.skills[index].title">{{
                                                managerForm.skills[index].title
                                            }} прокачан на </span>{{ managerForm.skills[index].value }}%
                                        </p>

                                        <input class=" form-range w-100"
                                               max="100"
                                               v-model="managerForm.skills[index].value"
                                               type="range">
                                    </div>
                                </div>

                                <a
                                    @click="add('skills')"
                                    class="d-block w-100"
                                    href="javascript:void(0)"><i class="fa-solid fa-plus mr-2"></i> Добавить еще
                                    навык</a>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="row" v-if="tab===5"></div>
            </div>

            <div class="col-md-9"
                 v-bind:class="{'col-md-12':!botUser.manager}"
                 v-if="tab===10">

                <ManagerStudiesList></ManagerStudiesList>

            </div>

            <div class="col-md-9"
                 v-bind:class="{'col-md-12':!botUser.manager}"
                 v-if="tab===11">
                <ManagerSlotList v-on:callback="callback"></ManagerSlotList>
            </div>

            <div class="col-md-9"
                 v-bind:class="{'col-md-12':!botUser.manager}"
                 v-if="tab===12">
                <ManagerPackageList :bot="bot"></ManagerPackageList>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="activate-promo-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="text-center">Активируйте ваш ключ и получите дополнительные слоты</h6>
                    <PromoCodeActivateForm :bot="bot"></PromoCodeActivateForm>
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

export default {
    props: ["bot"],
    data() {
        return {
            tab: 0,
            load: false,
            confirm: false,
            step: 0,
            botUser: null,
            isEdit: false,
            messages: [],
            photo: null,
            botUserForm: {
                id: null,
                is_vip: false,
                is_admin: false,
                is_work: false,
                is_manager: false,
                is_deliveryman: false,
                user_in_location: false,
                name: null,
                phone: null,
                email: null,
                birthday: null,
                age: null,
                city: null,
                country: null,
                address: null,
                sex: null,
                is_blocked: null,
                blocked_message: null,
            },
            managerForm: {
                name: null,
                phone: null,
                email: null,
                image: null,
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
            this.prepareManager()
        }

    },
    mounted() {
        this.prepareManager()
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        getSelf() {
            return window.profile
        },
        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        callback(index) {
            this.tab = index
        },
        prepareManager() {
            this.botUser = this.getSelf


            this.managerForm.name = this.botUser.name || this.botUser.fio_from_telegram || null
            this.managerForm.phone = this.botUser.phone || null
            this.managerForm.email = this.botUser.email || null
            this.managerForm.birthday = this.botUser.birthday || null
            this.managerForm.city = this.botUser.city || null
            this.managerForm.country = this.botUser.country || null
            this.managerForm.address = this.botUser.address || null
            this.managerForm.sex = this.botUser.sex || true


            if (this.botUser.manager) {
                this.managerForm.id = this.botUser.manager.id || null
                this.managerForm.social_links = this.botUser.manager.social_links || [""]
                this.managerForm.skills = this.botUser.manager.skills || [{
                    title: null,
                    value: 50,
                }]
                this.managerForm.weaknesses = this.botUser.manager.weaknesses || [""]
                this.managerForm.educations = this.botUser.manager.educations || [""]
                this.managerForm.strengths = this.botUser.manager.strengths || [""]
                this.managerForm.image = this.botUser.manager.image || null
            }
            this.botUserForm.id = this.botUser.id
            this.botUserForm.is_vip = this.botUser.is_vip
            this.botUserForm.is_admin = this.botUser.is_admin
            this.botUserForm.is_work = this.botUser.is_work
            this.botUserForm.is_manager = this.botUser.is_manager
            this.botUserForm.is_deliveryman = this.botUser.is_deliveryman
            this.botUserForm.user_in_location = this.botUser.user_in_location
            this.botUserForm.name = this.botUser.name || this.botUser.username || this.botUser.id
            this.botUserForm.phone = this.botUser.phone
            this.botUserForm.email = this.botUser.email
            this.botUserForm.birthday = this.botUser.birthday || null
            this.botUserForm.city = this.botUser.city || null
            this.botUserForm.country = this.botUser.country || null
            this.botUserForm.address = this.botUser.address || null
            this.botUserForm.sex = this.botUser.sex || false
            this.botUserForm.is_blocked = this.botUser.blocked_at != null
            this.botUserForm.blocked_message = this.botUser.blocked_message || null
        },
        alert(msg) {
            this.messages.push(msg)
        },
        removeMessage(index) {
            this.messages.splice(index, 1)
        },
        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        removePhoto(type = 'photo') {
            if (type === 'photo')
                this.photo = null

            if (type === 'image')
                this.managerForm.image = null
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
        },
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
        submitBotUser() {
            this.$store.dispatch('updateBotUser', {
                botUserForm: this.botUserForm
            }).then(() => {
                this.isEdit = false

                this.messages = []
                this.botUserForm = {
                    id: null,
                    is_vip: false,
                    is_admin: false,
                    is_work: false,
                    is_manager: false,
                    is_deliveryman: false,
                    user_in_location: false,
                    name: null,
                    phone: null,
                    email: null,
                    birthday: null,
                    age: null,
                    city: null,
                    country: null,
                    address: null,
                    sex: null,
                    is_blocked: false
                }

                this.$emit("update")
                this.$notify("Редактирование данных: Данные успешно обновлены!")
            }).catch(() => {
                this.$notify("Редактирование данных: Ошибка обновления данных")
            })
        },
        submitManager() {
            this.loading = true;

            const photo = this.photo || null

            let data = new FormData();

            if (photo) {
                data.append('images[]', photo);
            }

            Object.keys(this.managerForm)
                .forEach(key => {
                    const item = this.managerForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            data.append('bot_id', this.bot.id);
            data.append('bot_user_id', this.botUser.id);

            this.$store.dispatch("saveManager",
                data
            ).then((resp) => {
                window.location.reload()
                this.$notify("Редактирование данных: Данные успешно обновлены!")
            }).catch(() => {
                this.loading = false
            })
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        prepareCurrentBot(bot) {
            this.loadCurrentBot(bot)
            localStorage.setItem("cashman_set_botform_step_index", 0)
            localStorage.setItem("cashman_set_botpage_step_index", 2)
            window.location.href = '/bot-page'
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

<script setup>

</script>
<template>

    <div class="card mb-2" v-if="botUser">
        <div class="card-body" v-if="!isManagerVerified">
            <form
                v-on:submit.prevent="submitManager" class="row mb-0">
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
                    <p class="mb-3">Загрузи свой персональное фото, мы же должны знать в лицо наших сотрудников</p>
                    <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
                        <label for="bot-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            <span>+</span>
                            <input type="file" id="bot-photos" accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>


                        <div class="mb-2 img-preview" v-if="managerForm.image" style="margin-right: 10px;">
                            <img v-lazy="getPhoto(managerForm.image).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto()"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-12">
                    <h4 class="text-center my-5 text-primary">
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

                    <div class="input-group position-relative mb-2"
                         v-for="(item, index) in managerForm.social_links">
                        <input type="url" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.social_links[index]"
                               placeholder="Ссылка на соц. сеть"
                               :aria-label="'managerForm-social-links-'+index"
                               :aria-describedby="'managerForm-social-links-'+index" required>
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

                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пола?</em></p>
                    <h6 class="text-center">Вы мужчина или женщина?</h6>

                    <div class="row mb-0">
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'btn-primary text-white':managerForm.sex}"
                                @click="managerForm.sex = true"
                                class="btn btn-outline-secondary w-100 p-2 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Мужчина</span>
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'btn-primary text-white':!managerForm.sex}"
                                @click="managerForm.sex = false"
                                class="btn btn-outline-secondary w-100 p-2 d-flex justify-content-between flex-column align-items-center ">
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
                               placeholder="Город проживания"
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
                    <h4 class="text-center my-5 text-primary">
                        Профессиональный блок вопросов
                    </h4>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Полученное высшее образование</h6>
                    <p class="mb-0 text-center"><em>Если еще нет высшего образования, впишите <strong
                        class="text-primary">"нет"</strong></em></p>
                    <div class="input-group position-relative mb-2"
                         v-for="(item, index) in managerForm.educations">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
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
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще высшее образование</a>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Ваши сильные стороны</h6>

                    <div class="input-group position-relative mb-2"
                         v-for="(item, index) in managerForm.strengths">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
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
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще сильные стороны</a>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Слабые стороны есть у всех! Нам нужно понимать в чем Вас стоит прокачать и в
                        каких проблемных для Вас местах оказать помощь и т.д.</em></p>
                    <h6 class="text-center">Ваши слабые стороны</h6>

                    <div class="input-group position-relative mb-2"
                         v-for="(item, index) in managerForm.weaknesses">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
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
                        class="d-block w-100 py-3 text-center"
                        href="javascript:void(0)">Добавить еще слабые стороны</a>
                </div>


                <div class="col-12">
                    <p class="mb-3"><em>А теперь о ваших навыка - впишите название навыка и укажите % владения навыком
                        при помощи ползунка.</em></p>
                    <h6 class="text-center">Ваши профессиональные навыки</h6>

                    <div class="mb-2" v-for="(item, index) in managerForm.skills">
                        <div class="input-group position-relative ">
                            <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
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

                <div class="col-12">
                    <p class="mb-3"><em>Для того чтоб вы и ваш друг получали больше бонусов воспользуйтесь
                        реферальной программой и введите реферальный код от вашего друга!</em></p>
                    <h6 class="text-center">Введите реферальный код вашего друга</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="managerForm.referral"
                               placeholder="Реферальный код"
                               aria-label="managerForm-referral" aria-describedby="managerForm-referral">
                    </div>
                </div>


                <div class="col-12">
                    <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте условия
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
            </form>
        </div>
        <div class="card-body" v-else>

            <p class="mb-0">Ваш баланс: <strong class="text-primary">{{ botUser.manager.balance || 0 }} ₽</strong></p>
            <p class="mb-0">Колл-во слотов под клиентов: <strong class="text-primary">{{ botUser.manager.max_company_slot_count || 0 }} ед.</strong></p>
            <p class="mb-3">Колл-во слотов под ботов у клиента: <strong class="text-primary">{{ botUser.manager.max_bot_slot_count || 0 }} ед.</strong></p>

            <a href="/manager-page" class="btn btn-outline-primary w-100 mb-3">Перейти в ваш профиль</a>
            <h6 class="text-center my-3"> Ваши возможности</h6>
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">Регистрация клиента</li>
                <li class="list-group-item">Создание ботов любой конфигурации и сложности</li>
                <li class="list-group-item">Выставление счетов на оплату и прием платеже за обслуживание</li>
                <li class="list-group-item">Реферальная программа</li>
                <li class="list-group-item">Профессиональное обучение</li>
                <li class="list-group-item">Использование ресурсов компании для привлечения клиентов: маркетологи,
                    дизайнеры,
                    smm-специалисты,
                    программисты
                </li>
                <li class="list-group-item">Тех.поддержка по системе</li>


            </ol>
            <h6 class="text-center my-3"> Ваши бонусы</h6>
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">Оплата за регистрацию клиента (после его оплаты)</li>
                <li class="list-group-item">Начисление персональной скидки (сгораемой и несгораемой)</li>
                <li class="list-group-item">Получение бонусных доходов с реферальной программы 1, 2 и 3 уровня: ваши
                    друзья работают, а вы
                    получаете доход. Доход ограничен лишь числом друзей.
                </li>
            </ol>

        </div>
    </div>
<!--
    <div class="card" v-if="isManagerVerified">

        <div class="card-body" v-if="botUser">
            <a href="javascript:void(0)"
               @click="isEdit=!isEdit"
               class="btn btn-info mb-2  font-900 w-100">
                <span v-if="!isEdit"><i class="fa-solid fa-user-pen mr-2"></i> Редактировать данные о себе</span>
                <span v-else><i class="fa-solid fa-chevron-up mr-2"></i> Завершить редактирование</span>
            </a>

            <table class="table table-borderless  rounded-sm shadow-l"
                   v-if="!isEdit"
                   style="overflow: hidden;">
                <thead>
                <tr class="bg-gray1-dark">
                    <th scope="col" class="color-theme">Ключ</th>
                    <th scope="col" class="color-theme">Значение</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">ID в системе</th>
                    <td class="font-weight-bold">{{ botUser.id || 'Не указано' }}</td>

                </tr>

                <tr>
                    <th scope="row">Дата регистрации</th>
                    <td class="font-weight-bold">{{ $filters.current(botUser.created_at) }}</td>

                </tr>

                <tr>
                    <th scope="row">Телеграм ID</th>
                    <td class="font-weight-bold">{{ botUser.telegram_chat_id || 'Не указано' }}</td>

                </tr>
                <tr v-bind:class="{'bg-red1-light':botUser.name==null}">
                    <th scope="row">Имя</th>
                    <td class="font-weight-bold">{{ botUser.name || 'Не указано' }}</td>

                </tr>
                <tr>
                    <th scope="row">Имя из телеграмма</th>
                    <td class="font-weight-bold">{{ botUser.fio_from_telegram || 'Не указано' }}</td>

                </tr>

                <tr v-bind:class="{'bg-red1-light':botUser.phone==null}">
                    <th scope="row">Номер телефона</th>
                    <td class="font-weight-bold">{{ botUser.phone || 'Не указано' }}</td>

                </tr>
                <tr v-bind:class="{'bg-red1-light':botUser.age==null}">
                    <th scope="row">Возраст</th>
                    <td class="font-weight-bold">{{ botUser.age || 'Не указано' }}</td>

                </tr>
                <tr>
                    <th scope="row">Пол</th>
                    <td class="font-weight-bold">{{ botUser.sex ? 'Мужчина' : 'Женщина' }}</td>

                </tr>

                <tr v-bind:class="{'bg-red1-light':botUser.country==null}">
                    <th scope="row">Страна</th>
                    <td class="font-weight-bold">{{ botUser.country || 'Не указано' }}</td>

                </tr>

                <tr v-bind:class="{'bg-red1-light':botUser.city==null}">
                    <th scope="row">Город</th>
                    <td class="font-weight-bold">{{ botUser.city || 'Не указано' }}</td>

                </tr>

                <tr v-bind:class="{'bg-red1-light':botUser.address==null}">
                    <th scope="row">Адрес</th>
                    <td class="font-weight-bold">{{ botUser.address || 'Не указано' }}</td>

                </tr>

                <tr>
                    <th scope="row">VIP</th>
                    <td class="font-weight-bold">{{ botUser.is_vip ? 'Да' : 'Нет' }}</td>

                </tr>

                <tr>
                    <th scope="row">Менеджер</th>
                    <td class="font-weight-bold">{{ botUser.is_manager ? 'Да' : 'Нет' }}</td>

                </tr>
                <tr>
                    <th scope="row">Доставщик</th>
                    <td class="font-weight-bold">{{ botUser.is_deliveryman ? 'Да' : 'Нет' }}</td>

                </tr>
                <tr>
                    <th scope="row">На работе</th>
                    <td class="font-weight-bold">{{ botUser.is_work ? 'Да' : 'Нет' }}</td>

                </tr>

                <tr>
                    <th scope="row">В заведении</th>
                    <td class="font-weight-bold">{{ botUser.user_in_location ? 'Да' : 'Нет' }}</td>

                </tr>

                <tr v-if="botUser.blocked_at">
                    <th scope="row">Пользователь заблокирован</th>
                    <td class="font-weight-bold">
                        <p class="mb-0"> {{ $filters.current(botUser.blocked_at) }}</p>
                        <p class="mb-0"><em>{{ botUser.blocked_message ?? 'Без сообщения' }}</em></p>
                    </td>
                </tr>

                </tbody>
            </table>
            <form v-on:submit.prevent="submitBotUser" v-if="isEdit">

                <div class="form-group mb-2">
                    <label for="">Ф.И.О пользователя</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.name"
                           @invalid="alert('Вы не указали имя!')"
                           placeholder="Иванов Иван Иванович" required>
                </div>


                <div class="form-group mb-2">
                    <label for="">Телефон</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.phone"
                           v-mask="'+7(###)###-##-##'"
                           @invalid="alert('Вы не указали телефон!')"
                           placeholder="+7(XXX) XXX-XX-XX" required>
                </div>

                <div class="form-group mb-2">
                    <label for="">Почта</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.email"
                           placeholder="example@gmail.com">
                </div>

                <div class="form-group mb-2">
                    <label for="">Адрес</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.address"
                           placeholder="ул. Петрова, 123, кв 45">
                </div>

                <div class="form-group mb-2">
                    <label for="">Дата рождения</label>
                    <input type="date" class="form-control"
                           v-model="botUserForm.birthday"
                           placeholder="12/01/2023">
                </div>

                <div class="form-group mb-2">
                    <label for="">Страна</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.country"
                           placeholder="Россия">
                </div>


                <div class="form-group mb-2">
                    <label for="">Город</label>
                    <input type="text" class="form-control"
                           v-model="botUserForm.city"
                           placeholder="Краснодар">
                </div>


                <div class="row mb-0">
                    <p class="col-12 mb-0">VIP</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_vip = true"
                            v-bind:class="{'bg-success text-white':botUserForm.is_vip,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_vip}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-check mr-1"></i> Да
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_vip = false"
                            v-bind:class="{'bg-success text-white':!botUserForm.is_vip,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_vip}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-xmark mr-1"></i> Нет
                        </button>
                    </div>
                </div>

                <div class="row mb-0">
                    <p class="col-12 mb-0">Доставщик</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_deliveryman = true"
                            v-bind:class="{'bg-success  text-white':botUserForm.is_deliveryman,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_deliveryman}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-check mr-1"></i> Да
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_deliveryman = false"
                            v-bind:class="{'bg-success  text-white':!botUserForm.is_deliveryman,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_deliveryman}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-xmark mr-1"></i> Нет
                        </button>
                    </div>
                </div>

                <div class="row mb-0">
                    <p class="col-12 mb-0">Менеджер</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_manager = true"
                            v-bind:class="{'bg-success  text-white':botUserForm.is_manager,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_manager}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-check mr-1"></i> Да
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_manager = false"
                            v-bind:class="{'bg-success  text-white':!botUserForm.is_manager,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_manager}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-xmark mr-1"></i> Нет
                        </button>
                    </div>
                </div>

                <div class="row mb-0">
                    <p class="col-12 mb-0">Работает</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_work = true"
                            v-bind:class="{'bg-success  text-white':botUserForm.is_work,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_work}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-check mr-1"></i> Да
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.is_work = false"
                            v-bind:class="{'bg-success  text-white':!botUserForm.is_work,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_work}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-xmark mr-1"></i> Нет
                        </button>
                    </div>
                </div>

                <div class="row mb-0">
                    <p class="col-12 mb-0">В заведении</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.user_in_location = true"
                            v-bind:class="{'bg-success  text-white':botUserForm.user_in_location,'bg-theme border-green1-dark color-green1-dark':!botUserForm.user_in_location}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-check mr-1"></i> Да
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.user_in_location = false"
                            v-bind:class="{'bg-success  text-white':!botUserForm.user_in_location,'bg-theme border-green1-dark color-green1-dark':botUserForm.user_in_location}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-xmark mr-1"></i> Нет
                        </button>
                    </div>
                </div>


                <div class="row mb-0">
                    <p class="col-12 mb-0">Пол</p>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.sex = true"
                            v-bind:class="{'bg-success  text-white':botUserForm.sex,'bg-theme border-green1-dark color-green1-dark':!botUserForm.sex}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-mars mr-1"></i> Муж
                        </button>
                    </div>
                    <div class="col-6">
                        <button
                            type="button"
                            @click="botUserForm.sex = false"
                            v-bind:class="{'bg-success  text-white':!botUserForm.sex,'bg-theme border-green1-dark color-green1-dark':botUserForm.sex}"
                            class="w-100 btn btn-outline-secondary mb-3 rounded-sm text-uppercase font-900">
                            <i class="fa-solid fa-venus mr-1"></i> Жен
                        </button>
                    </div>
                </div>

                <div v-if="messages.length>0"
                     v-for="(message, index) in messages"
                     class="alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                    <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                    <button type="button"
                            @click="removeMessage(index)"
                            class="close color-white opacity-60 font-16">×
                    </button>
                </div>

                <button type="submit"
                        class="btn btn-primary mt-2 mb-1 rounded-s text-uppercase font-900 shadow-s bg-green2-dark w-100">
                    Сохранить
                </button>
                <div class="divider divider-small my-3 bg-highlight "></div>
            </form>
        </div>
    </div>
-->

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            isEdit: false,
            messages: [],
            load: false,
            confirm: false,
            step: 0,
            botUser: null,

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
        },
        isManagerVerified() {

            if (!this.botUser)
                return false;

            if (!this.botUser.manager)
                return false;

           /* if (!this.botUser.manager.verified_at)
                return false*/

            return true
        }
    },
    methods: {
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
                this.managerForm.social_links = this.botUser.manager.social_links || [""]
                this.managerForm.skills = this.botUser.manager.skills || [{
                    title: null,
                    value: 50,
                }]
                this.managerForm.weaknesses = this.botUser.manager.weaknesses || [""]
                this.managerForm.educations = this.botUser.manager.educations || [""]
                this.managerForm.strengths = this.botUser.manager.strengths || [""]
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
        removePhoto(index) {

            this.managerForm.image = null
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.managerForm.image = files[0]
            console.log(files)
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
                this.$botNotification.notification("Редактирование данных", "Данные успешно обновлены!")
            }).catch(() => {
                this.$botNotification.warning("Редактирование данных", "Ошибка обновления данных")
            })
        },
        submitManager() {
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

            data.set('image', this.managerForm.image);
            data.append('bot_id', this.bot.id);
            data.append('bot_user_id', this.botUser.id);

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

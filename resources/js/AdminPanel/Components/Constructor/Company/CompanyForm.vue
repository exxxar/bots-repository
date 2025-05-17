<script setup>
import Location from "@/AdminPanel/Components/Constructor/Location/Location.vue";
</script>

<template>
    <div class="row mb-2" v-if="need_reset&&!editor">
        <div class="col-12">
            <button
                type="button"
                @click="resetForm"
                class="btn btn-outline-danger">Новый клиент \ очистка формы
            </button>
        </div>
    </div>

    <div class="row mb-2" v-if="editor">
        <div class="col-12">


            <div class="btn-group w-100" role="group" aria-label="Basic outlined example">

                <button type="button"

                        v-bind:class="{'btn-primary border-white text-white':step===0}"
                        @click="step=0"
                        class="btn btn-outline-primary">Информация о компании
                </button>
                <button type="button"
                        v-bind:class="{'btn-primary border-white text-white':step===1}"
                        @click="step=1"
                        class="btn btn-outline-primary">Информация о расположении
                </button>
                <!--                <div class="dropdown">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary dropdown-toggle custom-group-dropdown-btn" href="#" role="button"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-bars"></i>
                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item" href="#bot-image-menu" @click="step=5">Меню заведения</a></li>
                                        <li><a class="dropdown-item" href="#bot-menu-template" @click="step=1">Меню бота</a></li>
                                        <li><a class="dropdown-item" href="#bot-slugs" @click="step=2">Скрипты</a></li>
                                        <li><a class="dropdown-item" href="#bot-users" @click="step=3">Пользователи</a></li>
                                    </ul>
                                </div>-->
            </div>
        </div>
    </div>

    <form v-on:submit.prevent="submitForm" v-if="step===0">
        <div class="row">
<!--            <div class="col-12 mb-3">

                <div class="form-floating">
                    <select class="form-select"
                            v-model="companyForm.business_form"
                            id="company-business-form-categories"
                            aria-label="Выберите тип организационно-правовой формы предприятия">
                        <option selected>Выберите один из типов</option>
                        <option :value="form.id" v-for="(form, index) in business_form_categories">
                            {{ form.title || 'Не указано' }}
                        </option>
                    </select>
                    <label for="company-business-form-categories">Вид организационно-правовой формы предприятия</label>
                </div>

            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="company-title">
                    <Popper content="Тип налогообложения вашей компании">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Тип налогообложения

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <select type="text" class="form-control"
                        aria-label="Выберите налогообложения"
                        v-model="companyForm.vat_code"
                        aria-describedby="company-vat-code" required>
                    <option :value="code.id" v-for="(code, index) in vat_codes">
                        {{ code.title || 'Не указано' }}
                    </option>
                </select>
            </div>
            <div class="col-12" v-if="companyForm.business_form===0||companyForm.business_form===1">
                <div class="col-12">
                    <h4>Общие реквизиты</h4>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="company-full-name"
                               placeholder="Общество с ограниченной ответственностью «Удача»">
                        <label for="floatingInput">ФИО</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                            <textarea
                                class="form-control"
                                id="company-full-name"
                                placeholder="Общество с ограниченной ответственностью «Удача»">
                            </textarea>
                        <label for="floatingInput">Паспортные данные</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="company-full-name"
                               placeholder="Общество с ограниченной ответственностью «Удача»">
                        <label for="floatingInput">Регистрационный адрес</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="company-full-name"
                               placeholder="Общество с ограниченной ответственностью «Удача»">
                        <label for="floatingInput">Номер ИНН</label>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="companyForm.business_form===2">
                <div class="row">
                    <div class="col-12">
                        <h4>Общие реквизиты</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ФИО собственника бизнеса</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Регистрационный адрес</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Номер ИНН</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ОГРНИП</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ЕГРИП</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="companyForm.business_form>=3">
                <div class="row">
                    <div class="col-12">
                        <h4>Общие реквизиты</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Полное наименование компании</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-short-name" placeholder="ООО «Удача»">
                            <label for="floatingInput">Сокращенное наименование компании</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Иванов Иван Иванович">
                            <label for="floatingInput">Генеральный директор компании</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-law-address"
                                   placeholder="629001, Россия, Ямало-Ненецкий АО, г. Салехард, ул. Северная, 63, оф. 6">
                            <label for="floatingInput">Юридический адрес компании</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-phisic-address"
                                   placeholder="629001, Россия, Ямало-Ненецкий АО, г. Салехард, ул. Северная, 63, оф. 6">
                            <label for="floatingInput">Фактический (почтовы) адрес компании</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="company-inn" placeholder="1234567890">
                            <label for="floatingInput">ИНН</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="company-kpp" placeholder="4561234789">
                            <label for="floatingInput">КПП</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-okved" placeholder="22.21">
                            <label for="floatingInput">ОКВЭД</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-okved" placeholder="22.21">
                            <label for="floatingInput">ОКПО</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-okved" placeholder="22.21">
                            <label for="floatingInput">ОКАТО</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ОГРН</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ЕГРЮЛ</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <h4>Банковские реквизиты</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Номер счета в банке</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text"
                                   maxlength="9"
                                   minlength="9"
                                   class="form-control"
                                   id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Банковский идентификационный код — БИК</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <textarea
                                class="form-control"
                                id="company-full-name"
                                placeholder="Общество с ограниченной ответственностью «Удача»">
                            </textarea>
                            <label for="floatingInput">Полное наименование банка, включая номер отделения или
                                дополнительного офиса и город</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   maxlength="20"
                                   id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Корреспондентский счет</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text"
                                   minlength="10"
                                   maxlength="12"
                                   class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">ИНН банка</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">КПП банка</label>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="companyForm.business_form===0||companyForm.business_form===1">
                        или же номер карты для самозанятных и физлиц {{ companyForm.law_params || '-' }}
                        <div class="form-floating mb-3">
                            <input type="text"
                                   minlength="16"
                                   maxlength="19"
                                   v-model="companyForm.law_params['bank_card']"
                                   class="form-control" id="company-full-name"
                                   placeholder="Общество с ограниченной ответственностью «Удача»">
                            <label for="floatingInput">Номер карты</label>
                        </div>
                    </div>
                </div>
            </div>-->

            <div class="col-12">
                <h4>Системное</h4>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="company-title">
                    <Popper content="Название вашей компании">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название компании

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="companyForm.title"
                       maxlength="255"
                       aria-describedby="company-title" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="company-title">
                    <Popper content="Тип налогообложения вашей компании">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Тип налогообложения

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <select type="text" class="form-control"
                        aria-label="Выберите налогообложения"
                        v-model="companyForm.vat_code"
                        aria-describedby="company-vat-code" required>
                    <option :value="code.id" v-for="(code, index) in vat_codes">
                        {{ code.title || 'Не указано' }}
                    </option>
                </select>
            </div>


            <div class="col-12 mb-3">
                <label class="form-label" id="company-title">
                    <Popper content="Тип налогообложения вашей компании">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Вид организационно-правовой формы предприятия

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <select type="text" class="form-control"
                        aria-label="Выберите тип организационно-правовой формы предприятия"
                        v-model="companyForm.business_form"
                        aria-describedby="company-business-form-categories" required>
                    <option :value="form.id" v-for="(form, index) in business_form_categories">
                        {{ form.title || 'Не указано' }}
                    </option>
                </select>
            </div>


            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-slug">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Название компании на АНГЛИЙСКОМ<br>
                                    без пробелов! можно использовать _<br>
                                    Должно быть уникальным! Не отображается пользователю.
                                </div>
                            </template>
                        </Popper>
                        Название компании латиницей (домен компании)
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Мнемоническое имя"
                           aria-label="Мнемоническое имя"
                           pattern="^[a-zA-Z][a-zA-Z0-9-_]{1,40}$"
                           v-model="companyForm.slug"
                           maxlength="255"
                           aria-describedby="company-slug" required>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label " id="company-description">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Добавится в раздел "О Нас"</div>
                            </template>
                        </Popper>
                        Описание компании
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        <small class="text-gray-400 ml-3" style="font-size:10px;"
                               v-if="companyForm.description">
                            Длина текста {{ companyForm.description.length }}</small>
                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание компании"
                              aria-label="Описание компании"
                              v-model="companyForm.description"
                              aria-describedby="company-description" required>
                                    </textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-address">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Где находится главное заведение компании!<br>Можно не указывать, т.к. есть
                                    еще
                                    "Локации"
                                </div>
                            </template>
                        </Popper>
                        Основной адрес компании</label>
                    <input type="text" class="form-control"
                           placeholder="Адрес"
                           aria-label="Адрес"
                           maxlength="255"
                           v-model="companyForm.address"
                           aria-describedby="company-address">
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-email">Основная почта компании</label>
                    <input type="email" class="form-control"
                           placeholder="Почтовый адрес"
                           aria-label="Почтовый адрес"
                           maxlength="255"
                           v-model="companyForm.email"
                           aria-describedby="company-email">
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-manager">Менеджер компании</label>
                    <input type="text" class="form-control"
                           placeholder="Имя менеджера"
                           aria-label="Имя менеджера"
                           v-model="companyForm.manager"
                           maxlength="255"
                           aria-describedby="company-manager">
                </div>
            </div>

            <div class="col-12 ">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Логотип компании <span
                            class="badge rounded-pill text-bg-warning m-0">Желательно</span></h6>
                    </div>
                    <div class="card-body d-flex justify-content-start">

                        <label for="photos" style="margin-right: 10px;" class="photo-loader text-primary ml-2">
                            +
                            <input type="file" id="photos"
                                   accept="image/*" @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>
                        <div class="mb-2 img-preview"
                             style="margin-right: 10px;"
                             v-if="photo">
                            <img v-lazy="getPhoto().imageUrl">
                            <div class="remove">
                                <a @click="photo=null"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>


                        <div class="mb-2 img-preview"
                             style="margin-right: 10px;"
                             v-if="companyForm.image">
                            <img v-lazy="'/images/'+companyForm.slug+'/'+companyForm.image">
                            <div class="remove">
                                <a @click="removeCompanyImage"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Телефонные номера</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h6>Телефонный номер</h6>
                            </div>

                        </div>
                        <div class="row"
                             :key="'phone'+index"
                             v-for="(item, index) in companyForm.phones">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           v-mask="'+7(###)###-##-##'"
                                           placeholder="+7(000)000-00-00"
                                           aria-label="Номер телефона"
                                           maxlength="255"
                                           v-model="companyForm.phones[index]"
                                           :aria-describedby="'company-phone-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('phones', index)"
                                    class="btn btn-outline-danger w-100"><i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    @click="addItem('phones')"
                                    class="btn btn-outline-success w-100">Добавить еще номер
                                </button>
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
                             :key="'link'+index"
                             v-for="(item, index) in companyForm.links">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="Ссылка на соц.сеть"
                                           aria-label="Ссылка на соц.сеть"
                                           maxlength="255"
                                           v-model="companyForm.links[index]"
                                           :aria-describedby="'company-link-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('links', index)"
                                    class="btn btn-outline-danger w-100"><i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    @click="addItem('links')"
                                    class="btn btn-outline-success w-100">Добавить еще ссылку
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 ">

                <div class="card mb-3">
                    <div class="card-header">
                        <h6>График работы</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h6>День недели </h6>
                                <a class="btn btn-link" @click="schedulePlaceholder">Заполнить\очистить</a>
                            </div>

                        </div>
                        <div class="row"
                             :key="'link'+index"
                             v-for="(item, index) in companyForm.schedule">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="День недели и время работы"
                                           aria-label="День недели и время работы"
                                           maxlength="255"
                                           v-model="companyForm.schedule[index]"
                                           :aria-describedby="'company-schedule-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('schedule', index)"
                                    class="btn btn-outline-danger w-100"><i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    :disabled="companyForm.schedule.length===7"
                                    @click="addItem('schedule')"
                                    class="btn btn-outline-success w-100">Добавить еще время работы
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="companyForm.id===null">Создать компанию</span>
                    <span v-else>Обновить компанию</span>
                </button>
            </div>
        </div>
    </form>

    <div v-if="step===1">
        <Location v-if="company&&!load"
                  :company="company"
        />
    </div>


</template>

<script>
export default {
    props: ["company", "editor"],
    data() {
        return {
            step: 0,
            load: false,
            photo: null,
            removedImage: null,
            need_reset: false,
            vat_codes: [
                {
                    id: 1,
                    title: 'Общая система налогообложения'
                },
                {
                    id: 2,
                    title: 'Упрощенная (УСН, доходы)'
                },
                {
                    id: 3,
                    title: 'Упрощенная (УСН, доходы минус расходы)'
                },
                {
                    id: 4,
                    title: 'Единый налог на вмененный доход (ЕНВД)'
                },
                {
                    id: 5,
                    title: 'Единый сельскохозяйственный налог (ЕСН)'
                },
                {
                    id: 6,
                    title: 'Патентная система налогообложения'
                }
            ],
            /*    law_params_types: [
                    {
                        id: 1,
                        title: "ИНН",
                    },
                    {
                        id: 2,
                        title: "ОГРН",
                    },
                    {
                        id: 3,
                        title: "Юридический адрес",
                    },
                    {
                        id: 3,
                        title: "Фактический адрес",
                    },
                    {
                        id: 3,
                        title: "Генеральный директор",
                    },
                    {
                        id: 4,
                        title: "Номер ОКВЭД",
                    },
                    {
                        id: 5,
                        title: "Банковский \ расчётный счёт",
                    },
                    {
                        id: 6,
                        title: "Номер банковской карты",
                    },
                    {
                        id: 7,
                        title: "Корреспондентский счет"

                    },
                    {
                        id: 8,
                        title: "БИК"

                    },
                    {
                        id: 9,
                        title: "Наименование банка"

                    },
                    {
                        id: 10,
                        title: "Адрес банка"

                    },
                    {
                        id: 11,
                        title: "Паспортные данные",
                        description: "серия, номер, кем выдан, дата выдачи"
                    },
                    {
                        id: 12,
                        title: "Паспортные данные",
                    },

                ],*/
            business_form_categories: [
                {
                    id: 0,
                    title: "Физическое-лицо",
                },
                {
                    id: 1,
                    title: "Самозанятый",
                },

                {
                    id: 2,
                    title: "Индивидуальный предприниматель (ИП)",
                },
                {
                    id: 3,
                    title: "Общество с ограниченной ответственностью (ООО)",
                },
                {
                    id: 4,
                    title: "Акционерное общество (АО)",
                },
                {
                    id: 5,
                    title: "Полное товарищество (ПТ)",
                },
                {
                    id: 6,
                    title: "Товарищество на вере (ТНВ)",
                },
                {
                    id: 7,
                    title: "Производственный кооператив (ПК)",
                },
                {
                    id: 8,
                    title: "Государственное унитарное предприятие (ГУП)",
                },
                {
                    id: 9,
                    title: "Муниципальное унитарное предприятие (МУП)"
                },


            ],
            companyForm: {
                id: null,
                title: null,
                slug: null,
                description: null,
                address: null,
                business_form: null,
                phones: [""],
                links: [""],
                law_params: {

                },
                email: null,
                vat_code: 1,
                schedule: [],
                manager: null,
            }
        }
    },
    watch: {
        companyForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {
        this.companyForm.law_params['bank_card'] = '1234';
        this.companyForm.law_params['phisical_adress'] = '1234';
        if (this.company)
            this.$nextTick(() => {
                this.companyForm = {
                    id: this.company.id || null,
                    title: this.company.title || null,
                    slug: this.company.slug || null,
                    image: this.company.image || null,
                    description: this.company.description || null,
                    address: this.company.address || null,
                    phones: this.company.phones || [""],
                    links: this.company.links || [""],
                    email: this.company.email || null,
                    vat_code: this.company.vat_code || 1,
                    schedule: this.company.schedule || [],
                    manager: this.company.manager || null,
                }

            })

    },
    methods: {
        resetForm() {
            this.photo = null
            this.removedImage = null
            this.companyForm = {
                id: null,
                title: null,
                slug: null,
                description: null,
                address: null,
                phones: [""],
                links: [""],
                email: null,
                schedule: [],
                manager: null,
                vat_code: 1,
            }
            this.$nextTick(() => {
                this.need_reset = false
            })

        },
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
            this.companyForm.image = null
        },

        schedulePlaceholder() {
            if (this.companyForm.schedule.length > 0) {
                this.companyForm.schedule = []
            } else {
                this.companyForm.schedule = [
                    "Понедельник - с 8:00 до 20:00",
                    "Вторник - с 8:00 до 20:00",
                    "Среда - с 8:00 до 20:00",
                    "Четверг - с 8:00 до 20:00",
                    "Пятница - с 8:00 до 20:00",
                    "Суббота - с 8:00 до 20:00",
                    "Воскресенье - выходной",
                ]
            }
        },
        removeCompanyImage() {
            this.removedImage = this.companyForm.image
            this.companyForm.image = null
        },
        addItem(name) {
            this.companyForm[name].push("")
        },
        removeItem(name, index) {
            this.companyForm[name].splice(index, 1)
        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.companyForm)
                .forEach(key => {
                    const item = this.companyForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('company_logo', this.photo);

            if (this.removedImage != null)
                data.append('removed_image', this.removedImage);

            this.$store.dispatch(this.companyForm.id === null ?
                    "createCompany" :
                    "updateCompany",
                {
                    companyForm: data
                }).then((response) => {
                this.$emit("callback", response.data)

                this.$notify("Компания успешно создана");
            }).catch(err => {
            })

        },

    }
}
</script>

<style>

.img-preview,
.photo-loader {
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

.img-preview img,
.photo-loader img {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lime {
    color: lime;
}

.img-preview .remove {
    display: none;
}

.img-preview:hover .remove {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 10px;
    cursor: pointer;
}

.img-preview:hover .remove a {
    font-size: 12px;
    color: white;
}
</style>

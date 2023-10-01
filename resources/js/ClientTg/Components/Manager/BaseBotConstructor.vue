<script setup>
import BotList from "@/ClientTg/Components/Manager/Bots/BotList.vue";
import CompanyList from "@/ClientTg/Components/Manager/Clients/CompanyList.vue";
</script>
<template>
    <div class="card card-style" v-if="step===0">
        <div class="content mb-0">
            <h2>Создание клиента</h2>
            <h6>Так-с, создаем нового клиента или используем уже созданного клиента?</h6>
            <button
                @click="step=1"
                class="w-100 btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme">
                Создать нового клиента
            </button>

            <div class="divider divider-small my-3 bg-highlight "></div>

            <h6>Выбрать клиента из списка</h6>
            <CompanyList v-on:callback="selectCompany">

            </CompanyList>

        </div>
    </div>


    <div class="card card-style" v-if="step===1">
        <div class="content mb-0">

            <form
                v-on:submit.prevent="nextStep"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Персональная информация</h2>
                <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span> Напишите подробную информацию о
                    клиенте и его фото</p>
                <div class="form-floating mb-2 w-100">
                    <label for="form-selfInfo-name" class="text-primary">Ф.И.О. клиента</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Иванов И.И."
                        v-model="form.selfInfo.name"
                        @invalid="alert('Вы не добавили Ф.И.О.')"
                        id="form-selfInfo-name" required/>

                </div>
                <div class="form-floating mb-2 w-100">
                    <label for="form-selfInfo-phone" class="text-primary">Личный номер телефона клиента для связи
                        менеджера</label>
                    <input
                        type="text"
                        v-model="form.selfInfo.phone"
                        v-mask="'+7(###)###-##-##'"
                        placeholder="+7(000)000-00-00"
                        class="form-control"
                        @invalid="alert('Вы не добавили номер телефона')"
                        id="form-selfInfo-phone" required/>

                </div>
                <div class="form-floating mb-2 w-100">
                    <label for="form-selfInfo-text" class="text-primary">Информация о клиенте:</label>
                    <textarea
                        style="min-height:150px;"
                        v-model="form.selfInfo.text"
                        class="form-control font-12"
                        @invalid="alert('Вы не добавили информацию о клиенте')"
                        placeholder="Немного деталей о Вас"
                        id="form-selfInfo-text" required></textarea>

                </div>

                <p>Вы можете пропустить этап добавления фотографий в данном конструкторе и добавить их позже. По
                    умолчанию будут добавлены красивые фотографии с CashMan-ом вместо реальных фотографий.</p>

                <div class="d-flex w-100">
                    <div class="pt-1">
                        <h5 data-activate="toggle-id-2" class="font-500 font-13">
                            <strong v-if="form.need_all_photo">Добавить все фото сразу</strong>
                            <strong v-if="!form.need_all_photo">Пропустить фото</strong>
                        </h5>
                    </div>
                    <div class="ml-auto mr-4 pr-2">
                        <div class="custom-control ios-switch ios-switch-icon">
                            <input type="checkbox"
                                   v-model="form.need_all_photo"
                                   class="ios-input" id="toggle-id-2">
                            <label class="custom-control-label" for="toggle-id-2"></label>
                            <i class="fa fa-check font-11 color-white"></i>
                            <i class="fa fa-times font-11 color-white"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center flex-column" v-if="form.need_all_photo">
                    <h6 class="mb-3 mt-3">Выберите персональное фото клиента</h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>

                    <div class="d-flex w-100">
                        <div class="pt-1">
                            <h5 data-activate="toggle-selfInfo-need-photo" class="font-500 font-13">
                                <strong v-if="form.selfInfo.need_photo">Выбрать фото</strong>
                                <strong v-if="!form.selfInfo.need_photo">Указать ссылку на фото</strong>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch ios-switch-icon">
                                <input type="checkbox"
                                       v-model="form.selfInfo.need_photo"
                                       class="ios-input" id="toggle-selfInfo-need-photo">
                                <label class="custom-control-label" for="toggle-selfInfo-need-photo"></label>
                                <i class="fa fa-check font-11 color-white"></i>
                                <i class="fa fa-times font-11 color-white"></i>
                            </div>
                        </div>
                    </div>


                    <label style="height: 100px; width:100px;"
                           :for="'self-image-photo'"
                           v-if="form.selfInfo.need_photo"
                           class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.selfInfo.image"
                              class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.selfInfo.image).imageUrl" style="width:100%;object-fit: cover;"
                                 alt="">
                        </span>
                        <input type="file"
                               :id="'self-image-photo'"
                               accept="image/*"
                               @invalid="alert('Не выбрано фото!')"
                               @change="onChangePhotos($event,'selfInfo','image')"
                               style="display:none;" required/>
                    </label>


                    <div class="form-floating w-100" v-if="!form.selfInfo.need_photo">
                        <label for="form-selfInfo-photo-url" class="text-black">Фото</label>
                        <input type="url" class="form-control" i
                               @invalid="alert('Добавьте ссылку на фотографию!')"
                               id="form-selfInfo-photo-url"
                               placeholder="Ссылка на фото" required>

                    </div>


                </div>

                <div class="mt-3 w-100">
                    <div v-if="messages.length>0"
                         v-for="(message, index) in messages"
                         class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                        <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                        <button type="button"
                                @click="removeMessage(index)"
                                class="close color-white opacity-60 font-16">×
                        </button>
                    </div>
                </div>


                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>
            <!--Шаг 3-->

        </div>
    </div>

    <div class="card card-style" v-if="step===2">
        <div class="content mb-0">
            <form
                v-on:submit.prevent="nextStep"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Информация о бизнесе</h2>
                <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span> Напишите подробную информацию о
                    бизнесе клиента и добавьте логотип компании. Он будет использоваться для общедоступной информации о
                    клиенте.</p>
                <div class="form-floating mb-2 w-100">
                    <label for="form-business-info-name" class="text-primary">Название организации клиента (имя
                        компании)</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="ООО 'Рога и Копыта'"
                        v-model="form.businessInfo.name"
                        @invalid="alert('Вы не добавили название компании!')"
                        id="form-business-info-name" required/>

                </div>
                <div class="form-floating mb-2 w-100">
                    <label for="form-business-info-text" class="text-primary">Информация о бизнесе:</label>
                    <textarea
                        style="min-height:150px;"
                        class="form-control  font-12"
                        v-model="form.businessInfo.text"
                        placeholder="Главное начать..."
                        @invalid="alert('Вы не добавили описание компании')"
                        id="form-business-info-text" required></textarea>

                </div>

                <div class="d-flex justify-content-center align-items-center flex-column" v-if="form.need_all_photo">
                    <h6 class="mb-3 mt-3">Выберите фото для визитки с информацией о бизнесе </h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>

                    <div class="d-flex w-100">
                        <div class="pt-1">
                            <h5 data-activate="toggle-businessInfo-need-photo" class="font-500 font-13">
                                <strong v-if="form.businessInfo.need_photo">Выбрать фото</strong>
                                <strong v-if="!form.businessInfo.need_photo">Указать ссылку на фото</strong>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch ios-switch-icon">
                                <input type="checkbox"
                                       v-model="form.businessInfo.need_photo"
                                       class="ios-input" id="toggle-businessInfo-need-photo">
                                <label class="custom-control-label" for="toggle-businessInfo-need-photo"></label>
                                <i class="fa fa-check font-11 color-white"></i>
                                <i class="fa fa-times font-11 color-white"></i>
                            </div>
                        </div>
                    </div>


                    <label style="height: 100px; width:100px;"
                           :for="'businessInfo-image-photo'"
                           v-if="form.businessInfo.need_photo"
                           class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.businessInfo.image"
                              class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.businessInfo.image).imageUrl"
                                 style="width:100%;object-fit: cover;" alt="">
                        </span>
                        <input type="file"

                               :id="'businessInfo-image-photo'"
                               accept="image/*"
                               @invalid="alert('Вы не добавили логотип компании')"
                               @change="onChangePhotos($event,'businessInfo','image')"
                               style="display:none;" required/>
                    </label>


                    <div class="form-floating w-100" v-if="!form.businessInfo.need_photo">
                        <label for="floatingInputGroup1" class="text-black">Фото</label>
                        <input type="url" class="form-control"
                               @invalid="alert('Вы не добавили ссылку на фотографию компании')"
                               id="form-businessInfo-photo"
                               placeholder="Ссылка на фото компании" required>

                    </div>
                </div>

                <div v-if="messages.length>0"
                     v-for="(message, index) in messages"
                     class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                    <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                    <button type="button"
                            @click="removeMessage(index)"
                            class="close color-white opacity-60 font-16">×
                    </button>
                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>

            <!--Шаг 4-->

        </div>
    </div>

    <div class="card card-style" v-if="step===3">
        <div class="content mb-0">


            <form
                v-on:submit.prevent="nextStep"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Контактная информация</h2>
                <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span> Здесь будут ссылки на все контактные данные компании (
                    социальные
                    сети, месенджеры, номер телефона и сайт) + фотография, которая будет отображаться в разделе
                    контактов.</p>


                <div class="mb-2 d-flex align-items-center justify-content-center flex-column"
                     v-if="form.need_all_photo">

                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>

                    <div class="d-flex w-100">
                        <div class="pt-1">
                            <h5 data-activate="toggle-contacts-need-photo" class="font-500 font-13">
                                <strong v-if="form.contacts.need_photo">Выбрать фото</strong>
                                <strong v-if="!form.contacts.need_photo">Указать ссылку на фото</strong>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch ios-switch-icon">
                                <input type="checkbox"
                                       v-model="form.contacts.need_photo"
                                       class="ios-input" id="toggle-contacts-need-photo">
                                <label class="custom-control-label" for="toggle-contacts-need-photo"></label>
                                <i class="fa fa-check font-11 color-white"></i>
                                <i class="fa fa-times font-11 color-white"></i>
                            </div>
                        </div>
                    </div>


                    <label style="height: 100px; width:100px;"
                           :for="'contacts-image-photo-1'"
                           v-if="form.contacts.need_photo"
                           class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.contacts.image"
                              class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.contacts.image).imageUrl" style="width:100%;object-fit: cover;"
                                 alt="">
                        </span>
                        <input type="file"
                               :id="'contacts-image-photo-1'"
                               accept="image/*"
                               @invalid="alert('Вы не добавили фотографию к контактам')"
                               @change="onChangePhotos($event,'contacts','image')"
                               style="display:none;" required/>
                    </label>


                    <div class="form-floating w-100" v-if="!form.contacts.need_photo">
                        <label for="form-contacts-photo" class="text-black">Фото</label>
                        <input type="url" class="form-control"
                               @invalid="alert('Вы не добавили ссылку на фотографию к контактам')"
                               id="form-contacts-photo"
                               placeholder="Ссылка на фото контакта" required>

                    </div>


                    <!--                    <div class="mb-2 row">

                                            <div class="col-6 " v-for="item in contactTypes">
                                                <a href="javascript:void(0)"
                                                   @click="addContact(item)"
                                                   style="min-height:100px;"
                                                   class="w-100 d-flex flex-column justify-content-center btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"><i
                                                    :class="item.icon"
                                                    class="mr-1 "></i>{{
                                                        item.title || 'Не указан'
                                                    }}
                                                </a>
                                            </div>


                                        </div>-->
                </div>


                <div class="card bg-20 m-0 content rounded-m shadow mb-2">
                    <div class="card-body">
                        <h6 class="color-white">А также добавьте контактную информацию, нажав на <i
                            class="fa-solid fa-square-plus"></i></h6>


                        <button type="button"
                                class="w-100  btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue1-dark color-blue1-dark bg-theme"
                                @click="addContact"><i
                            class="fa-solid fa-square-plus mr-1"></i> Добавить
                        </button>

                        <div class="card card-style ml-0 mr-0 mb-3 bg-white"
                             v-for="(item, index) in form.contacts.links">
                            <div class="content">

                                <select
                                    v-model="form.contacts.links[index].slug"
                                    class="form-control w-100 mb-2">
                                    <option
                                        :value="ct.slug"
                                        v-for="ct in contactTypes">
                                        {{ ct.title || 'Не указан' }}
                                    </option>
                                </select>

                                <div class="form-floating w-100">
                                    <label
                                        class="text-primary"
                                        :for="'contact-input-'+index">
                                        {{ getContactsType(form.contacts.links[index].slug).title || 'Не указано' }}
                                    </label>
                                    <input type="text"
                                           v-if="getContactsType(form.contacts.links[index].slug).mask||null"
                                           :pattern="getContactsType(form.contacts.links[index].slug).pattern"
                                           v-mask="getContactsType(form.contacts.links[index].slug).mask"
                                           class="form-control"
                                           placeholder="Введите данные"
                                           @invalid="alert('Вы не ввели данные')"
                                           :id="'contact-input-'+index"
                                           v-model="form.contacts.links[index].value" required>
                                    <input type="text"
                                           v-else
                                           :pattern="getContactsType(item.slug).pattern"
                                           class="form-control w-100"
                                           :id="'contact-input-'+index"
                                           @invalid="alert('Вы не ввели данные')"
                                           v-model="form.contacts.links[index].value" required>

                                </div>

                                <div class="form-floating mt-1 ">
                                    <label
                                        class="text-primary"
                                        :for="'contact-description-textarea-'+index">Описание</label>
                                    <textarea class="form-control font-12"
                                              v-model="form.contacts.links[index].description"
                                              @invalid="alert('Вы не ввели описание контакта')"
                                              placeholder="Поясните пользователям к чему относится данный контакт"
                                              :id="'contact-description-textarea-'+index" required>

                                        </textarea>

                                </div>


                                <div class="d-flex justify-content-center mt-3">
                                    <a href="javascript:void(0)"
                                       class="btn mr-2 btn-border btn-m btn-full rounded-s text-uppercase font-900 border-red2-dark color-red2-dark bg-theme"
                                       @click="remove(index)"><i class="fa-solid fa-trash-can"></i> </a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-border btn-m btn-full rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"

                                       @click="duplicate(item)"><i
                                        class="fa-solid fa-clone"></i> </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-overlay bg-blue2-dark opacity-95 rounded-m shadow-l"></div>
                    <div class="card-overlay dark-mode-tint rounded-m shadow-l"></div>
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

                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>

            <!--Шаг 5-->

        </div>
    </div>

    <div class="card card-style" v-if="step===4">
        <div class="content mb-0">
            <form
                v-on:submit.prevent="nextStep"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <h2>Создадим непосредственно бота</h2>
                <h6><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span>Первым делом необходимо имя бота (на русском - для
                    пользователей, на английском - как ссылка на вашего бота)</h6>

                <div class="form-floating mb-2 w-100">
                    <label for="floatingTextarea" class="text-primary">Название бота (на русском)</label>

                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="50"
                        v-model="form.name"
                        placeholder="Например, Шаурма-Бот"
                        @invalid="alert('Неверное название бота!')"
                        id="floatingTextarea" required/>
                </div>


                <div class="border-green1-dark pl-3
                border-left border-top-0 border-bottom-0 border-right-0 mb-2">
                    <p class="mb-1">
                        <i class="fa-solid fa-hand-point-right mr-2 text-primary"></i> Ссылка должна быть длинной (от 7
                        символов) и, по возможности, уникальной.
                    </p>
                    <p class="mb-1">
                        <i class="fa-solid fa-hand-point-right mr-2 text-primary"></i>Ссылка должна заканчиваться словом
                        "bot"
                    </p>
                    <p class="mb-1">
                        <i class="fa-solid fa-hand-point-right mr-2 text-primary"></i>Ссылка должна состоть только из
                        английских символов и символов нижнего подчеркивания
                    </p>
                    <p class="mb-1">
                        <i class="fa-solid fa-hand-point-right mr-2 text-primary"></i>В ссылке нельзя ставить пробелы
                    </p>
                    <strong>Рекомендации для коллег</strong>
                </div>


                <div class="d-flex w-100">
                    <div class="pt-1">
                        <h5 data-activate="toggle-id-2" class="font-500 font-13">
                            <strong v-if="!form.has_name">Сгенерировать временное имя</strong>
                            <strong v-if="form.has_name">У меня есть имя</strong>
                        </h5>
                    </div>
                    <div class="ml-auto mr-4 pr-2">
                        <div class="custom-control ios-switch ios-switch-icon">
                            <input type="checkbox"
                                   v-model="form.has_name"
                                   class="ios-input" id="toggle-id-2">
                            <label class="custom-control-label" for="toggle-id-2"></label>
                            <i class="fa fa-check font-11 color-white"></i>
                            <i class="fa fa-times font-11 color-white"></i>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-2 w-100" v-if="form.has_name">
                    <label for="form-bot-domain"
                           class="text-primary">Доменное имя бота на английском</label>
                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="50"
                        placeholder="test_ivan_bot"
                        v-model="form.botDomain"
                        @invalid="alert('Неверное значение имени бота!')"
                        pattern="[_A-Za-z0-9]{5,50}(bot)"
                        id="form-bot-domain" required/>
                </div>

                <div class="border-green1-dark pl-3
                border-left border-top-0 border-bottom-0 border-right-0 mb-2">

                    <p class="mb-1">Если у Вас есть <a href="https://t.me/botFather" class="text-primary">Token</a>, то
                        Вы сразу же сможете протестировать своего бота на практике. Если же Token-а нет, то вам помогут
                        его создать наши менеджеры.</p>
                    <div class="d-flex">
                        <div class="pt-1">
                            <h5 data-activate="toggle-id-2" class="font-500 font-13">
                                <strong v-if="form.have_token">У меня есть Token бота</strong>
                                <strong v-if="!form.have_token">У меня нет Token бота</strong>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch ios-switch-icon">
                                <input type="checkbox"
                                       v-model="form.have_token"
                                       class="ios-input" id="toggle-id-2">
                                <label class="custom-control-label" for="toggle-id-2"></label>
                                <i class="fa fa-check font-11 color-white"></i>
                                <i class="fa fa-times font-11 color-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-2 w-100" v-if="form.has_token">
                    <label for="floatingTextarea"
                           class="text-primary">Телеграм token бота</label>
                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="100"
                        placeholder="Значение тоукена"
                        @invalid="alert('Неверное значение тоукена')"
                        v-model="form.token"
                        id="floatingTextarea" required/>

                </div>

                <div v-if="messages.length>0"
                     v-for="(message, index) in messages"
                     class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                    <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                    <button type="button"
                            @click="removeMessage(index)"
                            class="close color-white opacity-60 font-16">×
                    </button>
                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>
            <!--Шаг 1-->


        </div>
    </div>

    <div class="card card-style" v-if="step===5">
        <div class="content mb-0">
            <form
                v-on:submit="nextStep"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Приветственное сообщение!</h2>

                <p class="mt-2 mb-2 w-100s"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span>И так,бот должен быть общительным и вежливым!
                    Вам необходимо добавить приветственное сообщение. <strong>Напишите небольшой текст</strong>
                    приветствия и добавьте 2
                    фото (одно к приветствию, второе на аватар).</p>
                <div class="form-floating mb-2 w-100">
                    <label for="floatingInputValue" class="text-primary">Напишите текст приветствия в боте! </label>
                    <textarea class="form-control font-12"
                              style="min-height:150px;"
                              @invalid="alert('Незаполненно базовое приветствие')"
                              v-model="form.greeting.text"
                              placeholder="Начните с чего-то хорошего... клиенты это любят!)"
                              id="floatingInputValue" required></textarea>

                </div>

                <div v-if="form.need_all_photo">
                    <h6 class="mt-3 mb-3">Добавим изображения</h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>

                    <div class="d-flex w-100">
                        <div class="pt-1">
                            <h5 data-activate="toggle-greeting-need-photo" class="font-500 font-13">
                                <strong v-if="form.greeting.need_photo">Выбрать фото</strong>
                                <strong v-if="!form.greeting.need_photo">Указать ссылку на фото</strong>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch ios-switch-icon">
                                <input type="checkbox"
                                       v-model="form.greeting.need_photo"
                                       class="ios-input" id="toggle-greeting-need-photo">
                                <label class="custom-control-label" for="toggle-greeting-need-photo"></label>
                                <i class="fa fa-check font-11 color-white"></i>
                                <i class="fa fa-times font-11 color-white"></i>
                            </div>
                        </div>
                    </div>


                    <div
                        v-if="form.greeting.need_photo"
                        class="d-flex mt-3">

                        <label style="height: 100px; width:100px;"
                               for="greeting-avatar"
                               class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w d-flex justify-content-center align-items-center flex-column mr-2">

                            <span v-if="!form.greeting.avatar"
                                  class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Аватарка</span>
                            </span>

                            <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.greeting.avatar).imageUrl" style="width:100%;object-fit: cover;"
                                 alt="">
                        </span>

                            <input type="file"
                                   id="greeting-avatar"
                                   accept="image/*"
                                   @invalid="alert('Не выбрала фотография аватара!')"
                                   @change="onChangePhotos($event,'greeting','avatar')"
                                   style="display:none;" required/>
                        </label>

                        <label style="height: 100px; width:100px;"
                               for="greeting-profile"
                               class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.greeting.profile"
                              class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Профиль</span>
                            </span>

                            <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.greeting.profile).imageUrl" style="width:100%;object-fit: cover;"
                                 alt="">
                        </span>
                            <input type="file"
                                   id="greeting-profile"
                                   accept="image/*"
                                   @invalid="alert('Требуется фотография для профиля!')"
                                   @change="onChangePhotos($event,'greeting','profile')"
                                   style="display:none;" required/>
                        </label>


                    </div>

                    <div
                        v-if="!form.greeting.need_photo"
                        class="mt-3 w-100">

                        <div class="form-floating w-100">
                            <label for="form-greeting-photo-url" class="text-black">Аватар</label>
                            <input type="url" class="form-control"
                                   @invalid="alert('Требуется url фото для аватара!')"
                                   id="form-greeting-photo-url"
                                   placeholder="Ссылка на аватар" required>

                        </div>

                        <div class="form-floating w-100">
                            <label for="form-greeting-profile-photo-url" class="text-black">В профиль</label>
                            <input type="url"
                                   class="form-control"
                                   @invalid="alert('Требуется url фото для профиля!')"
                                   id="form-greeting-profile-photo-url"
                                   placeholder="Ссылка на фото профиля" required>
                        </div>

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


                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>


            </form>
            <!--Шаг 2-->
        </div>
    </div>

    <div class="card card-style" v-if="step===6">
        <div class="content mb-0">
            <div class="d-flex w-100">
                <div class="pt-1">
                    <h5 data-activate="toggle-need-create-bot" class="font-500 font-13">
                        <strong v-if="!form.need_create_bot">Выбрать из готовых ботов</strong>
                        <strong v-if="form.need_create_bot">Создать самостоятельно</strong>
                    </h5>
                </div>
                <div class="ml-auto mr-4 pr-2">
                    <div class="custom-control ios-switch ios-switch-icon">
                        <input type="checkbox"
                               v-model="form.need_create_bot"
                               class="ios-input" id="toggle-need-create-bot">
                        <label class="custom-control-label" for="toggle-need-create-bot"></label>
                        <i class="fa fa-check font-11 color-white"></i>
                        <i class="fa fa-times font-11 color-white"></i>
                    </div>
                </div>
            </div>

            <form v-if="!form.need_create_bot">
                <BotList
                    :selected-bot-id="form.selected_bot_id"
                    v-on:callback="selectBot"/>

                <div v-if="messages.length>0"
                     v-for="(message, index) in messages"
                     class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                    <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                    <button type="button"
                            @click="removeMessage(index)"
                            class="close color-white opacity-60 font-16">×
                    </button>
                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>
                <button
                    type="button"
                    @click="submitBot"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-green2-dark w-100">
                    Создать демон и перейти к оформлению
                </button>
                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>
            <form
                v-if="form.need_create_bot"
                v-on:submit.prevent="submitStep5"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Хочу себе в бота </h2>

                <p>
                    <span v-if="form.functions.length>0">механик выбрано - {{ form.functions.length }} ед. </span>
                    <a href="javascript:void(0)" class="underline cursor-pointer ml-2"
                       @click="form.functions = []"
                       v-if="form.functions.length>0"> <i
                        class="fa-solid fa-xmark mr-1 text-danger"></i>
                    </a></p>

                <div class="row mb-2">
                    <div class="col-6 col-md-4 col-lg-3 mb-2 position-relative " v-for="item in moreFunctions">

                        <div class="relative">
                            <input type="checkbox"
                                   :disabled="!item.is_active"
                                   v-model="form.functions"
                                   :value="item.slug"
                                   class="btn-check function-check d-none"
                                   :id="'btn-check-outlined'+item.slug">
                            <label

                                style="min-height: 100px;"
                                v-bind:class="{'border-green1-dark color-green1-dark':form.functions.indexOf(item.slug)!==-1, 'border-gray1-dark color-gray1-dark':form.functions.indexOf(item.slug)===-1}"
                                class=" btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900  d-flex flex-column align-items-center justify-content-center  bg-theme"
                                :for="'btn-check-outlined'+item.slug">
                                <i :class="item.icon"></i>
                                <span class="small">{{ item.title }}</span>
                            </label>
                            <a href="javascript:void(0)"
                               style="position: absolute;top: 2px;left: 23px;color: #E91E63;"
                               @click="displayDescription(item.description)"><i class="fa-regular fa-lightbulb"></i></a>
                        </div>

                    </div>
                </div>

                <div v-if="form.functions.length>0">
                   <span class="badge bg-light text-primary mr-2"
                         v-for="item in form.functions">{{ item }}</span>
                </div>

                <div v-if="form.functions.indexOf('individual-button')>=0">

                    <p class="alert border-success  m-0 my-2">Опишите ваши индивидуальные потребности и наши
                        разработчики неприменно реализуют
                        данную фичу
                        для Вас!</p>


                    <div class="form-floating mt-1">
                        <label
                            class="text-primary"
                            for="individual-description-textarea">Описание фичи</label>
                        <textarea class="form-control font-12"
                                  v-model="form.individual"
                                  style="min-height:200px;"
                                  @invalid="alert('Вы не ввели описание фичи!')"
                                  placeholder="Напишите задуманное..."
                                  id="individual-description-textarea" required>
                        </textarea>

                    </div>

                </div>

                <div v-if="messages.length>0"
                     v-for="(message, index) in messages"
                     class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                    <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                    <button type="button"
                            @click="removeMessage(index)"
                            class="close color-white opacity-60 font-16">×
                    </button>
                </div>

                <div class="divider divider-small my-3 bg-highlight "></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Следующий шаг
                </button>

                <button
                    type="button"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                    @click="prevStep">
                    Назад
                </button>
            </form>

            <!-- Шаг 6 -->

        </div>
    </div>

    <div class="card card-style" v-if="step===7">
        <div class="content mb-0">
            <h6>Добавьте нужные страницы в бота:</h6>

            <form v-on:submit.prevent="addPage">
                <div class="form-floating mb-2 w-100">
                    <label for="pageForm-title" class="text-primary">Название страницы</label>

                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="50"
                        v-model="pageForm.title"
                        placeholder="Например, Главное меню"
                        id="pageForm-title" required/>


                </div>


                <button
                    type="submit"
                    class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                    Добавить страницу
                </button>
            </form>
            <div class="divider divider-small my-3 bg-highlight "></div>
            <h6>Созданные страницы <small v-if="form.pages.length>0">(всего {{ form.pages.length }} стр.)</small></h6>

            <div
                v-for="(page, index) in form.pages"
                class="d-flex align-items-center btn w-100 btn-border mb-2 rounded-sm text-left font-900 border-blue1-dark color-blue1-dark bg-theme">

                <p
                    class="w-100 p-2 font-12"
                    v-if="form.pages[index].isStatic">
                    {{ form.pages[index].title }}
                </p>
                <input
                    class="w-100 p-2"
                    style="border:none;"
                    v-else
                    v-model="form.pages[index].title"
                    type="text">
                <a href="javascript:void(0)"
                   @click="removePage(index)"
                   v-if="!form.pages[index].isStatic"
                   class="btn btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-red1-light">
                    <i class="fa-solid fa-trash-can"></i>
                </a>


            </div>

            <div v-if="messages.length>0"
                 v-for="(message, index) in messages"
                 class="mb-2 alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

                <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
                <button type="button"
                        @click="removeMessage(index)"
                        class="close color-white opacity-60 font-16">×
                </button>
            </div>

            <div class="divider divider-small my-3 bg-highlight "></div>
            <p class="mb-2">Так-с, финальный этап! Сейчас мы создадим демо и сможем перейти к завершению создания бота!</p>
            <button
                type="button"
                @click="submitBot"
                class="btn btn-m btn-full mb-2 rounded-l text-uppercase font-900 shadow-s bg-green2-dark w-100">
                Создать демо
            </button>

            <button
                type="button"
                class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                @click="prevStep">
                Назад
            </button>
        </div>
    </div>

    <div class="card card-style" v-if="step===8">
        <div class="content mb-0">

            <div
                class="w-100 d-flex justify-content-center align-items-center flex-column"
            >
                <h2>Демонстрация работы бота</h2>
                <h6><a href="#">{{ form.botDomain }}</a></h6>
                <p class="mb-0"><small>{{ form.name || 'Не указано имя' }}</small></p>

                <div class="mobile mb-2 p-0">
                    <iframe
                        style="border:none;"
                        :src="'/web/'+form.botDomain"></iframe>
                </div>

                <div
                    v-if="!form.token"
                    class="alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">
                    <span><i class="fa-solid fa-triangle-exclamation"></i></span>
                    <strong>Тоукен не указан!</strong>
                    <button type="button" class="close color-white opacity-60 font-16" data-dismiss="alert"
                            aria-label="Close">×
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="card card-style" v-if="step===8">
        <div class="content mb-0">
            <h2 class="text-center mt-3 mb-0">Оформление заказа</h2>
            <h6 class="text-center mb-3">Выберите тариф для подключения</h6>

            <div class="pricing-4 rounded-m shadow-m bg-theme">
                <h1 class="pricing-title text-center bg-green2-dark text-uppercase">Пробный</h1>
                <h3 class="pricing-value text-center bg-green1-light color-white font-40">3999<sup>руб</sup></h3>
                <h2 class="pricing-subtitle text-center bg-green1-light font-16">Разовый на 1 месяц</h2>
                <ul class="pricing-list text-center">
                    <li>1 бот</li>
                    <li>до 20 страниц</li>
                    <li>до 10 любых модулей</li>
                    <li>1 месяц тех. поддержки</li>
                </ul>
                <a href="#" class="btn btn-s bg-green2-dark btn-center-l text-uppercase rounded-s font-900">Выбрать</a>
                <p class="text-center color-gray-dark small-text font-10 uppercase mt-2 mb-0 pb-0">+0.01% персональной
                    скидки менеджера</p>
            </div>


            <div class="pricing-4 rounded-m shadow-m bg-theme">
                <h1 class="pricing-title text-center bg-blue2-dark text-uppercase">Базовый</h1>
                <h3 class="pricing-value text-center bg-blue2-light color-white font-40">6999<sup>руб</sup></h3>
                <h2 class="pricing-subtitle text-center bg-blue2-light font-16">+2500руб\месяц</h2>
                <ul class="pricing-list text-center">
                    <li>до 3х ботов</li>
                    <li>до 20 страниц в боте</li>
                    <li>жо 20 модулей в боте</li>
                    <li>неограниченно тех.поддержка</li>
                    <li>интеграция с CRM</li>
                    <li>до 2х пользовательских модулей индивидуальной разработки</li>
                </ul>
                <a href="#" class="btn btn-s bg-blue2-dark btn-center-l text-uppercase rounded-s font-900">Выбрать</a>
                <p class="text-center color-gray-dark small-text font-10 uppercase mt-2 mb-0 pb-0">+0.05% персональной
                    скидки менеджера</p>
            </div>

            <div class="pricing-4 rounded-m shadow-m bg-theme">
                <h1 class="pricing-title text-center bg-red2-dark text-uppercase">V.I.P.</h1>
                <h3 class="pricing-value text-center bg-red2-light color-white font-40">26999<sup>руб</sup></h3>
                <h2 class="pricing-subtitle text-center bg-red2-light font-16">+6 500руб\месяц</h2>
                <ul class="pricing-list text-center">
                    <li>неограниченное число ботов</li>
                    <li>неограниченно страниц</li>
                    <li>неограниченно модулей</li>
                    <li>неограниченно тех.поддержка</li>
                    <li>интеграция с CRM</li>
                    <li>пользовательская индивидуальная разработка</li>
                </ul>
                <a href="#" class="btn btn-s bg-red2-dark btn-center-l text-uppercase rounded-s font-900">Выбрать</a>
                <p class="text-center color-gray-dark small-text font-10 uppercase mt-2 mb-0 pb-0">+0.5% персональной
                    скидки менеджера</p>
            </div>

            <button
                type="button"
                class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                @click="prevStep">
                Назад
            </button>

            <button
                type="button"
                class="w-100 btn btn-border btn-m btn-full mb-3 rounded-l text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme"
                @click="start">
                В начало
            </button>
        </div>
    </div>

</template>
<script>

export default {
    props: ["start"],
    data() {
        return {
            messages: [],
            load: false,
            step: 0,
            pageForm: {
                title: null
            },

            form: {
                name: null,
                botDomain: "isushibot",
                has_token: false,
                has_name: false,
                need_all_photo: true,
                token: null,
                need_create_bot: true,
                selected_bot_id: null,
                selected_company_id: null,

                greeting: {
                    text: null,
                    avatar: null,
                    profile: null,
                    need_photo: true
                },
                contacts: {
                    need_photo: true,
                    image: null,
                    links: []
                },
                selfInfo: {
                    name: null,
                    phone: null,
                    text: null,
                    image: null,
                    need_photo: true
                },
                businessInfo: {
                    name: null,
                    text: null,
                    image: null,
                    need_photo: true
                },
                functions: ['cashback', 'friends-system', 'ask-a-question', 'location', 'wheel-of-fortune'],
                pages: [
                    {
                        title: '/start',
                        comment: 'Начни с этого момента',
                        isStatic: true
                    },
                    {
                        title: '/help',
                        comment: 'Страница помощи',
                        isStatic: true
                    },
                    {
                        title: '/about',
                        comment: 'Страница описания бота',
                        isStatic: true
                    }
                ],
                individual: null
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
                    title: 'Адресс',
                    pattern: null,
                    mask: null,
                    slug: 'address',
                    icon: 'fa-solid fa-location-dot'
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
                /* {
                     title: 'Агентский кабинет',
                     description: 'Желающие смогут стать Вашими агентами, рекомендовать Вас и получать вознаграждение.',
                     slug: 'agent-cabinet',
                     icon: 'fa-solid fa-house-laptop',
                     is_active: true
                 },*/
                {
                    title: 'Бонус за рекомендации',
                    description: 'Приграси 5 друзе и получи подарок',
                    slug: 'referral-bonus',
                    icon: 'fa-solid fa-money-bill-trend-up',
                    is_active: true
                },
                {
                    title: 'AMO CRM',
                    description: 'Интеграция с AMO CRM',
                    slug: 'amo-crm',
                    icon: 'fa-solid fa-money-bill-trend-up',
                    is_active: true
                },
                {
                    title: 'Система друзей',
                    description: 'Реферальная система друзей',
                    slug: 'friends-system',
                    icon: 'fa-solid fa-people-pulling',
                    is_active: true
                },
                /*   {
                       title: 'Бонус за отзыв',
                       description: 'Оставь отзыв в яндексе и 2гис и получи подарок от нас.',
                       slug: 'review-bonus',
                       icon: 'fa-solid fa-comments-dollar',
                       is_active: false
                   },*/
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
                    is_active: true
                },
                /*  {
                      title: 'Прикрепленные документы',
                      slug: 'attached-documents',
                      icon: 'fa-solid fa-folder-open',
                      description: 'Вы можете прикрепить документ в котором хотите рассказать (например) об обучении, о курсах и тд.',
                      is_active: true
                  },*/
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
                /*   {
                       title: 'Франшиза',
                       slug: 'franchise',
                       description: 'Информация о франшизе',
                       icon: 'fa-solid fa-earth-asia',
                       is_active: true
                   },*/
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
                /* {
                     title: 'Стоит купить или попробовать',
                     slug: 'buy-or-try',
                     description: 'Горящее предложение',
                     icon: 'fa-solid fa-basket-shopping',
                     is_active: true
                 },*/
                /*  {
                      title: 'Доставка',
                      slug: 'delivery',
                      description: 'Если вы отправляете доставки яндек или сдек и тд.',
                      icon: 'fa-solid fa-dolly',
                      is_active: true
                  },*/
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
                    is_active: true
                },
                {
                    title: 'Колесо фортуны',
                    slug: 'wheel-of-fortune',
                    description: 'Розыгрыши призов в боте',
                    icon: 'fa-solid fa-dharmachakra',
                    is_active: true
                },
                {
                    title: 'Квест в Instagram',
                    slug: 'insta-quest',
                    description: 'Розыгрыши призов в боте c отсылкой на инстаграм',
                    icon: 'fa-brands fa-instagram',
                    is_active: true
                },
                {
                    title: 'Карточка накоплений',
                    slug: 'coffee-card',
                    description: 'Система накопления бонусов, например чашек кофе',
                    icon: 'fa-solid fa-mug-hot',
                    is_active: true
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
    computed: {},
    watch: {
        'form.need_create_bot': {
            handler: function (newValue) {
                this.form.selected_bot_id = null
                this.form.functions = []
            },
            deep: true
        }
    },

    mounted() {
        if (this.start)
            this.step = this.start
    },
    methods: {
        start(){
            this.step = 0
            this.messages = []

            window.scrollTo({
                top: 50,
                behavior: "smooth"
            })
        },
        prevStep() {

            this.step--;
            this.messages = []

            window.scrollTo({
                top: 50,
                behavior: "smooth"
            })
        },
        nextStep() {

            this.step++;

            this.messages = []

            window.scrollTo({
                top: 50,
                behavior: "smooth"
            })
        },
        alert(msg) {
            this.messages.push(msg)
        },
        removeMessage(index) {
            this.messages.splice(index, 1)
        },
        selectCompany(company) {
            this.form.selected_company_id = company.id
            this.$botNotification.notification("Информация", "Вы успешно выбрали за основу данного клиента!")
            this.step = 4
        },
        selectBot(bot) {
            this.form.selected_bot_id = bot.id

            this.$botNotification.notification("Информация", "Вы успешно выбрали за основу данного бота!")

        },
        removePage(index) {
            this.form.pages.splice(index, 1)
        },
        addPage() {
            const form = this.pageForm
            this.form.pages.push({
                title: form.title,
                isStatic: false,
            })
            this.pageForm.title = null;
        },
        displayDescription(description) {
            this.$botNotification.notification("Информация", description)
        },
        getContactsType(slug) {
            return this.contactTypes.find(item => item.slug === slug) || null
        },
        submitStep5() {
            this.form.functions.forEach(item => {
                let func = this.moreFunctions.find(f => f.slug === item)
                let index = this.form.pages.find(page => page.title === func.title) || null
                if (!index)
                    this.form.pages.push({
                        title: func.title,
                        isStatic: false,
                    })
            })
            this.step++;
        },
        submitBot() {

            let data = new FormData();
            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.form.greeting.need_photo) {
                data.append('greeting_image_avatar', this.form.greeting.avatar);
                data.append('greeting_image_profile', this.form.greeting.profile);
            }

            if (this.form.contacts.need_photo) {
                data.append('contacts_image', this.form.contacts.image);
            }

            if (this.form.selfInfo.need_photo) {
                data.append('self_info_image', this.form.selfInfo.image);
            }

            if (this.form.businessInfo.need_photo) {
                data.append('business_info_image', this.form.businessInfo.image);
            }

            this.$store.dispatch("createBotLazy", {
                botForm: data
            }).then((response) => {

                this.step++;

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Бот успешно создан!",
                    type: 'success'
                });


            }).catch(err => {
                this.step++;
            })
        },
        remove(index) {
            this.form.contacts.links.splice(index, 1)
        },
        duplicate(contact) {
            const tmpContact = {
                ...contact
            }
            this.form.contacts.links.push(tmpContact)
        },
        addContact() {
            const tmpSlug = {
                slug: 'phone-number',
                value: null,
                description: null,
            }
            this.form.contacts.links.push(tmpSlug)
        },
        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        onChangePhotos(e, section, param) {
            const files = e.target.files
            this.form[section][param] = files[0]
        },
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

.mobile {
    min-width: 300px;
    height: 500px;
    border-radius: 10px;
    border: 2px white solid;
    padding: 10px;
    position: relative;

    iframe {
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }
}

.custom-alert-text {
    padding-right: 26px;
    margin: 0;
    color: white;
    line-height: 130%;
}
</style>

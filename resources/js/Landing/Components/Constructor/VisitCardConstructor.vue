<template>

    <div class="row d-flex justify-content-center">

        <div class="col-12 col-md-8 mb-2">
            <!--Шаг 0-->
            <form
                v-on:submit.prevent="step++"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
                v-if="step===0">
                <h2>Создадим Вашего бота вместе!</h2>
                <h6>Первым делом необходимо имя бота (на русском - для пользователей, на английском - как ссылка на
                    вашего бота)</h6>

                <div class="form-floating mb-2 w-100">
                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="50"
                        v-model="form.name"
                        id="floatingTextarea" required/>
                    <label for="floatingTextarea" class="text-primary">Название вашего бота (на русском)</label>
                </div>

                <div class="alert border-light alert-dismissible fade show w-100" role="alert">
                    <ul>
                        <li> Ссылка должна быть длинной (от 7 символов) и, по возможности, уникальной.</li>
                        <li> Ссылка должна заканчиваться словом "bot"</li>
                        <li> Ссылка должна состоть только из английских символов и символов нижнего подчеркивания</li>
                        <li> В ссылке нельзя ставить пробелы</li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="form-floating mb-2 w-100">
                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="50"
                        v-model="form.botDomain"
                        pattern="[_A-Za-z0-9]{5,50}(bot)"
                        id="floatingTextarea" required/>
                    <label for="floatingTextarea"
                           class="text-primary">Доменное имя Вашего бота на английском</label>
                </div>

                <div class="alert border-light alert-dismissible fade show w-100" role="alert">
                    <p>Если у Вас есть <a href="https://t.me/botFather" class="text-light">Token</a>, то Вы сразу же сможете протестировать своего бота на парктике. Если же Token-а нет, то вам помогут его создать наши менеджеры.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="form.have_token"
                               type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">
                            <strong v-if="!form.have_token">У меня есть Token бота</strong>
                            <strong v-if="form.have_token">У меня нет Token бота</strong>
                        </label>
                    </div>
                </div>

                <div class="form-floating mb-2 w-100" v-if="form.have_token">
                    <input
                        type="text"
                        class="form-control"
                        minlength="5"
                        maxlength="100"
                        v-model="form.token"
                        id="floatingTextarea" required/>
                    <label for="floatingTextarea"
                           class="text-primary">Телеграм token бота</label>
                </div>

                <button
                    type="submit"
                    class="btn mt-3 bg-primary text-white rounded-pill px-3 shadow">
                    Следующий шаг
                </button>
            </form>
            <!--Шаг 1-->
            <form
                v-on:submit="step++"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
                v-if="step===1">
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Приветственное сообщение!</h2>
                <p class="mt-2 mb-2"><span class="badge bg-white text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span>И так, первым делом бот должен быть общительным и вежливым!
                    Вам необходимо добавить привественное сообщение. <strong>Напишите небольшой текст</strong>
                    привествия и добавьте 2
                    фото (одно к приветствию, второе на аватар).</p>
                <div class="form-floating mb-2 w-100">
                                                <textarea class="form-control"
                                                          style="min-height:150px;"
                                                          v-model="form.greeting.text"
                                                          placeholder="Leave a comment here"
                                                          id="floatingInputValue" required></textarea>
                    <label for="floatingInputValue" class="text-primary">Напишите текст привествия в боте! </label>
                </div>
                <h6 class="mt-3 mb-3">Добавим изображения</h6>
                <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           v-model="form.greeting.need_photo"
                           type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">
                        <strong v-if="form.greeting.need_photo">Фото</strong>
                        <strong v-if="!form.greeting.need_photo">Ссылка</strong>
                    </label>
                </div>
                <div
                    v-if="form.greeting.need_photo"
                    class="d-flex mt-3">

                    <label style="height: 100px; width:100px;"
                           :for="'greeting-avatar'"
                           class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">

                            <span v-if="!form.greeting.avatar" class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Аватарка</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.greeting.avatar).imageUrl" style="width:100%;object-fit: cover;" alt="">
                        </span>

                        <input type="file"
                               name="greeting-avatar"
                               :id="'greeting-avatar'"
                               accept="image/*"
                               @change="onChangePhotos($event,'greeting','avatar')"
                               style="display:none;" required/>
                    </label>

                    <label style="height: 100px; width:100px;"
                           :for="'greeting-profile'"
                           class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.greeting.profile" class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Профиль</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.greeting.profile).imageUrl" style="width:100%;object-fit: cover;" alt="">
                        </span>
                        <input type="file"
                               :id="'greeting-profile'"
                               name="greeting-profile"
                               accept="image/*"
                               @change="onChangePhotos($event,'greeting','profile')"
                               style="display:none;" required/>
                    </label>


                </div>

                <div
                    v-if="!form.greeting.need_photo"
                    class="d-flex mt-3 flex-wrap">

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-globe"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1" placeholder="Username">
                            <label for="floatingInputGroup1" class="text-black">Аватар</label>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-globe"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1" placeholder="Username">
                            <label for="floatingInputGroup1" class="text-black">В профиль</label>
                        </div>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="btn mt-3 btn-light rounded-pill px-3 mr-2 shadow"
                        @click="step--">
                        Назад
                    </button>
                    <button class="btn mt-3 bg-primary text-white rounded-pill px-3 shadow"
                            type="submit">
                        Следующий шаг
                    </button>
                </div>

            </form>
            <!--Шаг 2-->
            <form
                v-on:submit.prevent="step++"
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
                        v-model="form.selfInfo.name"
                        id="floatingTextarea" required/>
                    <label for="floatingTextarea" class="text-primary">Ваше Ф.И.О.</label>
                </div>
                <div class="form-floating mb-2 w-100">
                    <input
                        type="text"
                        v-model="form.selfInfo.phone"
                        v-mask="'+7(###)###-##-##'"
                        class="form-control"
                        id="floatingTextarea" required/>
                    <label for="floatingTextarea" class="text-primary">Ваш личный номер телефона для связи
                        менеджера</label>
                </div>
                <div class="form-floating mb-2 w-100">
                                        <textarea
                                            style="min-height:150px;"
                                            v-model="form.selfInfo.text"
                                            class="form-control"
                                            placeholder="Leave a comment here"
                                            id="floatingTextarea" required></textarea>
                    <label for="floatingTextarea" class="text-primary">Информация о Вас:</label>
                </div>

                <div class="d-flex justify-content-center align-items-center flex-column">
                    <h6 class="mb-3 mt-3">Выберите своё персональное фото </h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="form.selfInfo.need_photo"
                               type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">
                            <strong v-if="form.selfInfo.need_photo">Фото</strong>
                            <strong v-if="!form.selfInfo.need_photo">Ссылка</strong>
                        </label>

                    </div>

                    <label style="height: 100px; width:100px;"
                           :for="'self-image-photo'"
                           v-if="form.selfInfo.need_photo"
                           class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.selfInfo.image" class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.selfInfo.image).imageUrl" style="width:100%;object-fit: cover;" alt="">
                        </span>
                        <input type="file"
                               name="self-image-photo"
                               :id="'self-image-photo'"
                               accept="image/*"
                               @change="onChangePhotos($event,'selfInfo','image')"
                               style="display:none;" required/>
                    </label>

                    <div
                        v-if="!form.selfInfo.need_photo"
                        class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-globe"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1"
                                   placeholder="Username" required>
                            <label for="floatingInputGroup1" class="text-black">Фото</label>
                        </div>
                    </div>

                </div>

                <p class="m-0" v-if="!form.selfInfo.image">Добавтье фотографию!</p>
                <div>
                    <button
                        type="button"
                        class="btn mt-3 btn-light rounded-pill px-3 mr-2 shadow"
                        @click="step--">
                        Назад
                    </button>
                    <button
                        class="btn mt-3 btn-primary rounded-pill px-3 shadow"
                        type="submit">
                        Следующий шаг
                    </button>
                </div>
            </form>
            <!--Шаг 3-->
            <form
                v-on:submit.prevent="step++"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
                v-if="step===3">
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Информация о бизнесе</h2>
                <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span> Напишите подробную информацию о
                    Вашем бизнесе + фото.</p>
                <div class="form-floating mb-2 w-100">
                    <input
                        type="text"
                        class="form-control"
                        v-model="form.businessInfo.name"
                        id="floatingTextarea"/>
                    <label for="floatingTextarea" class="text-primary">Название вашей организации</label>
                </div>
                <div class="form-floating mb-2 w-100">
                                                <textarea
                                                    style="min-height:150px;"
                                                    class="form-control"
                                                    v-model="form.businessInfo.text"
                                                    placeholder="Leave a comment here"
                                                    id="floatingTextarea" required></textarea>
                    <label for="floatingTextarea" class="text-primary">Информация о бизнесе:</label>
                </div>

                <div class="d-flex justify-content-center align-items-center flex-column">
                    <h6 class="mb-3 mt-3">Выберите фото для вашей визитки </h6>
                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="form.businessInfo.need_photo"
                               type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">
                            <strong v-if="form.businessInfo.need_photo">Фото</strong>
                            <strong v-if="!form.businessInfo.need_photo">Ссылка</strong>
                        </label>
                    </div>

                    <label style="height: 100px; width:100px;"
                           :for="'businessInfo-image-photo'"
                           v-if="form.businessInfo.need_photo"
                           class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.businessInfo.image" class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.businessInfo.image).imageUrl" style="width:100%;object-fit: cover;" alt="">
                        </span>
                        <input type="file"
                               name="businessInfo-image-photo"
                               :id="'businessInfo-image-photo'"
                               accept="image/*"
                               @change="onChangePhotos($event,'businessInfo','image')"
                               style="display:none;" required/>
                    </label>

                    <div
                        v-if="!form.businessInfo.need_photo"
                        class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-globe"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1"
                                   placeholder="Username" required>
                            <label for="floatingInputGroup1" class="text-black">Фото</label>
                        </div>
                    </div>
                </div>
                <div>
                    <button
                        type="button"
                        class="btn mt-3 btn-primary   shadow rounded-pill px-3 mr-2"
                        @click="step--">
                        Назад
                    </button>
                    <button
                        type="submit"
                        class="btn mt-3 btn-light shadow rounded-pill px-3">
                        Следующий шаг
                    </button>
                </div>
            </form>

            <!--Шаг 4-->
            <form
                v-on:submit.prevent="step++"
                class="w-100 d-flex justify-content-center align-items-center flex-column"
                v-if="step===4">
                <!--                    <img
                                        style="width: 200px; height: 200px; object-fit:cover;"
                                        src="/images/cashman.jpg" alt="">-->
                <h2>Контактная информация</h2>
                <p class="mt-2 mb-2"><span class="badge bg-light text-primary p-2 mr-2"><i
                    class="fa-regular fa-bell"></i></span> Здесь будут ссылки на все ваши контактные данные ( социальные
                    сети,
                    месенджеры, новер телефона и сайт) + фото.</p>


                <div class="mb-2 d-flex align-items-center justify-content-center flex-column">

                    <p>Вы можете выбрать фото или же указать ссылку на фото</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="form.contacts.need_photo"
                               type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">
                            <strong v-if="form.contacts.need_photo">Фото</strong>
                            <strong v-if="!form.contacts.need_photo">Ссылка</strong>
                        </label>
                    </div>


                    <label style="height: 100px; width:100px;"
                           :for="'contacts-image-photo'"
                           v-if="form.contacts.need_photo"
                           class="btn btn-outline-light shadow d-flex justify-content-center align-items-center flex-column mr-2">
                        <span v-if="!form.contacts.image" class="d-flex justify-content-center align-items-center flex-column">
                                  <i class="fa-regular fa-image"></i>
                                  <span>Фото</span>
                            </span>

                        <span style="overflow: hidden" v-else>
                            <img v-lazy="getPhoto(form.contacts.image).imageUrl" style="width:100%;object-fit: cover;" alt="">
                        </span>
                        <input type="file"
                               name="contacts-image-photo"
                               :id="'contacts-image-photo'"
                               accept="image/*"
                               @change="onChangePhotos($event,'contacts','image')"
                               style="display:none;" required/>
                    </label>

                    <div
                        v-if="!form.contacts.need_photo"
                        class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-globe"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInputGroup1"
                                   placeholder="Username" required>
                            <label for="floatingInputGroup1" class="text-black">Фото</label>
                        </div>
                    </div>

                    <h6>А также добавьте контактную информацию, нажав на <i class="fa-solid fa-square-plus"></i></h6>

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
                                           v-model="form.contacts.links[index].value" required>
                                    <input type="text"
                                           v-else
                                           :pattern="item.pattern"
                                           class="form-control"
                                           :id="'contact-input-'+index"
                                           v-model="form.contacts.links[index].value" required>
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
                                                  :id="'contact-description-textarea-'+index" required>

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
                    <button
                        type="button"
                        class="btn mt-3 btn-primary rounded-pill px-3 mr-2"
                        @click="step--">
                        Назад
                    </button>
                    <button class="btn mt-3 btn-light  rounded-pill px-3"
                            type="submit">
                        Следующий шаг
                    </button>
                </div>
            </form>

            <!--Шаг 5-->
            <form
                v-on:submit.prevent="submitBot"
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
                               :id="'btn-check-outlined'+item.slug">
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

                <div class="row" v-if="form.functions.indexOf('individual-button')>=0">
                    <div class="col-12">
                        <p>Опишите ваши индивидуальные потребности и наши разработчики неприменно реализуют данную фичу для Вас!</p>
                        <div class="form-floating mt-1">
                                        <textarea class="form-control"
                                                  v-model="form.individual"
                                                  placeholder="Leave a comment here"
                                                  id="individual-description-textarea" required>

                                        </textarea>
                            <label
                                class="text-primary"
                                for="individual-description-textarea">Описание фичи</label>
                        </div>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="btn mt-3 btn-primary rounded-pill px-3 mr-2"
                        @click="step--">
                        Назад
                    </button>
                    <button
                        type="submit"
                        class="btn mt-3 btn-light rounded-pill px-3">
                        Финиш!
                    </button>
                </div>
            </form>

            <!-- Шаг 6 -->
            <div
                class="w-100 d-flex justify-content-center align-items-center flex-column"
                v-if="step===6">
                <h2>Демонстрация работы бота</h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mobile">
                            <iframe :src="'/web/'+form.botDomain"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
        </div>


    </div>


</template>
<script>

export default {
    props: ["start"],
    data() {
        return {
            load: false,
            step: 0,
            form: {
                name: null,
                botDomain: 'vape888bot',
                have_token:false,
                token:null,
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
                functions: [],
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
                    title: 'Адресс расположения',
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

    },
    mounted() {
        if (this.start)
            this.step = this.start
    },
    methods: {
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
        addContact(slug) {
            const tmpSlug = {
                ...slug,
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
</style>

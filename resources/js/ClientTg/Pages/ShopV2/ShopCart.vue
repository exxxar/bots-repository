<script setup>
import ProductCardSimple from "@/ClientTg/Components/ShopV2/ProductCardSimple.vue";
</script>
<template>

    <div v-if="cartProducts.length>0">
        <div v-if="tab===0" class="container py-3">

            <div class="row">
                <div class="col-12">
                    <h4>Итого</h4>
                    <p>
                        Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки
                        рассчитывается отдельно
                        и
                        зависит от расстояния.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12" v-for="(item, index) in cartProducts">
                    <ProductCardSimple :item="item.product"/>
                </div>
            </div>
            <h6 class="opacity-75 mb-3 mt-2">Число персон</h6>
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-4">
                            <button
                                @click="decPersons"
                                type="button" class="btn p-2 w-100 bg-primary"><i
                                class="fa-solid fa-minus font-22"></i></button>
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <strong class="font-22">{{ deliveryForm.persons }}</strong>
                        </div>

                        <div class="col-4">
                            <button type="button"
                                    @click="incPersons"
                                    class="btn p-2 w-100 bg-primary"><i
                                class="fa-solid fa-plus font-22"></i></button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Товаров в корзине <strong>{{ cartTotalCount }} ед.</strong></h6>
                    <h6>Общая цена товаров <strong>{{ cartTotalPrice }}₽</strong></h6>
                    <h6>Приборы на <strong>{{ deliveryForm.persons }} чел.</strong></h6>
                </div>
            </div>
            <button
                v-if="cartTotalCount>0"
                @click="clearCart"
                class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid  fa-trash-can mr-2"></i><span class="color-white">Очистить корзину</span>
            </button>

        </div>
        <form
            id="basket"
            class="container py-3"
            v-on:submit.prevent="startCheckout" v-if="tab===1">
            <h6 class="opacity-75">Способы получения заказа</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   v-bind:class="{'active':!deliveryForm.need_pickup}"
                   @click="deliveryForm.need_pickup = false"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-truck mr-2"></i>Доставка
                </a>
                <a href="javascript:void(0)"
                   @click="deliveryForm.need_pickup = true"
                   v-bind:class="{'active':deliveryForm.need_pickup}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-brands fa-shopify mr-2"></i>Самовывоз</a>
                <a href="javascript:void(0)"
                   v-if="deliveryForm.need_pickup"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                    <label class="form-check-label" for="in-restaurant">
                        <i class="fa-solid fa-utensils mr-2"></i>
                        В заведении
                    </label>

                    <input class="form-check-input"
                           name="pickup-format"
                           v-model="deliveryForm.pick_up_type"
                           type="radio" value="0" id="in-restaurant">
                </a>
                <a href="javascript:void(0)"
                   v-if="deliveryForm.need_pickup"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                    <label class="form-check-label" for="pick-up-in-package">
                        <i class="fa-solid fa-person-walking-luggage mr-2"></i>
                        Забрать с собой
                    </label>

                    <input class="form-check-input"
                           type="radio" value="1"
                           v-model="deliveryForm.pick_up_type"
                           name="pickup-format"
                           id="pick-up-in-package">
                </a>
            </div>

            <h6 class="opacity-75">Способы оплаты</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   @click="deliveryForm.payment_type = 0"
                   v-if="settings.can_use_card"
                   v-bind:class="{'active':deliveryForm.payment_type === 0}"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-earth-americas mr-2"></i> Онлайн через бота
                </a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.payment_type === 1}"
                   v-if="deliveryForm.pick_up_type==0&&settings.can_use_cash"
                   @click="deliveryForm.payment_type = 1"

                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-credit-card mr-2"></i>Картой в заведении</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.payment_type === 2}"
                   @click="deliveryForm.payment_type = 2"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-solid fa-file-invoice mr-2"></i>Переводом</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.payment_type === 3}"
                   @click="deliveryForm.payment_type = 3"
                   v-if="settings.can_use_cash"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-money-bill-1 mr-2"></i> Наличными</a>
            </div>

            <h6 class="opacity-75">Бонусы <small>(нажми для использования)</small></h6>

            <div class="card my-3"
                 v-bind:class="{'text-bg-primary':deliveryForm.use_cashback}"
                 @click="deliveryForm.use_cashback=!deliveryForm.use_cashback">
                <div
                    class="card-body">
                    <p class="d-flex justify-content-between mb-0">
                        <span> Списать CashBack</span>
                        <strong>{{ cashbackLimit }}₽</strong>
                    </p>
                </div>
            </div>

            <h6 class="opacity-75 mb-3">Информация</h6>

            <div class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.name"
                       class="form-control" id="deliveryForm-name"
                       placeholder="Иванов Иван Иванович" required>
                <label for="deliveryForm-name">Ф.И.О.</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text"
                       v-mask="'+7(###)###-##-##'"
                       v-model="deliveryForm.phone"
                       class="form-control" id="deliveryForm-phone"
                       placeholder="+7(000)000-00-00" required>
                <label for="deliveryForm-phone">Номер телефона</label>
            </div>


            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.city"
                       class="form-control" id="deliveryForm-city"
                       placeholder="Ваш город" required>
                <label for="deliveryForm-city">Ваш город</label>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.street"
                       class="form-control" id="deliveryForm-street"
                       placeholder="Улица" required>
                <label for="deliveryForm-street">Улица</label>
            </div>


            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.building"
                       class="form-control" id="deliveryForm-building"
                       placeholder="Номер дома" required>
                <label for="deliveryForm-building">Номер дома</label>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.flat_number"
                       class="form-control" id="deliveryForm-flat-number"
                       placeholder="Номер квартиры">
                <label for="deliveryForm-flat-number">Номер квартиры</label>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.entrance_number"
                       class="form-control" id="deliveryForm-entrance-number"
                       placeholder="Номер подъезда">
                <label for="deliveryForm-entrance-number">Номер подъезда</label>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="form-floating mb-3">
                <input type="text"
                       v-model="deliveryForm.floor_number"
                       class="form-control" id="deliveryForm-floor-number"
                       placeholder="Номер этажа">
                <label for="deliveryForm-floor-number">Номер этажа</label>
            </div>


            <div class="form-floating">
            <textarea class="form-control"
                      v-model="deliveryForm.info"
                      style="height:200px;line-height:150%;"
                      placeholder="Информация" id="deliveryForm-info"></textarea>
                <label v-if="!deliveryForm.need_pickup" for="deliveryForm-info">Информация для доставщика</label>
                <label v-else for="deliveryForm-info">Информация для сотрудника</label>
            </div>

            <h6 class="opacity-75 mt-3">К какому времени приготовить?</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.when_ready}"
                   @click="deliveryForm.when_ready = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-stopwatch-20 mr-2"></i>По готовности
                </a>
                <a href="javascript:void(0)"
                   @click="deliveryForm.when_ready = false"
                   v-bind:class="{'active':!deliveryForm.when_ready}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-clock mr-2"></i>К указанному времени</a>

            </div>

            <div
                v-if="!deliveryForm.when_ready"
                class="form-floating">
                <input type="datetime-local"
                       v-model="deliveryForm.time"
                       class="form-control" id="deliveryForm-time" placeholder="Время доставки" required>
                <label for="deliveryForm-time">Время доставки</label>
            </div>

            <h6 class="opacity-75 mt-3" v-if="!deliveryForm.need_pickup">Ограничения по здоровью</h6>

            <div class="list-group my-3" v-if="!deliveryForm.need_pickup">

                <a href="javascript:void(0)"
                   @click="deliveryForm.has_disability = false"
                   v-bind:class="{'active':!deliveryForm.has_disability}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-heart mr-2"></i>Нет ограничений по здоровью</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.has_disability}"
                   @click="deliveryForm.has_disability = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-house-medical-flag mr-2"></i>Есть ограничения по здоровью
                </a>
            </div>


            <div class="list-group my-3" v-if="deliveryForm.has_disability&&!deliveryForm.need_pickup">
                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-1"> <i class="fa-solid fa-head-side-mask mr-2"></i> Болею</label>
                    <input type="checkbox"
                           value="болею"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-1">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-ear-deaf mr-2"></i> Плохо слышит \ говорит</label>
                    <input type="checkbox"
                           value="плохо слышит или говорит"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-5">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-3"> <i class="fa-solid fa-glasses mr-2"></i> Слабовидящий</label>
                    <input type="checkbox"
                           value="слабовидящий"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-3">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-4"> <i class="fa-solid fa-wheelchair mr-2"></i> Ограничения мобильности</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="ограничения мобильности"
                           v-model="deliveryForm.disabilities" id="switch-4">
                </a>

            </div>

            <h6 class="opacity-75 mt-3">Сводка</h6>

            <div class="card my-3 ">
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Суммарно, ед. <strong>{{ cartTotalCount }}
                                шт.</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Цена <strong>{{ cartTotalPrice }}
                                <sup>.00</sup>₽</strong>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                                <strong v-if="deliveryForm.use_cashback">{{ cashbackLimit }} <sup>.00</sup>₽</strong>
                                <strong v-else>-</strong>
                            </p>
                        </li>

                        <li class="list-group-item" v-if="!deliveryForm.need_pickup">
                            <p class="mb-0 d-flex justify-content-between">Цена доставки
                                <strong v-if="deliveryForm.delivery_price>0">{{ deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong>
                                <strong v-else>от курьера</strong>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p v-if="!deliveryForm.use_cashback"
                               class="mb-0 d-flex justify-content-between">Итого, цена
                                <strong>{{ cartTotalPrice + deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong></p>
                            <p v-else
                               class="mb-0 d-flex justify-content-between">Итого, цена
                                <strong>{{ (cartTotalPrice - cashbackLimit) + deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong></p>
                        </li>
                    </ul>

                    <div v-if="deliveryForm.payment_type === 3&&settings.can_use_cash">
                        <p class="my-3 text-center">Мы можем подготовить для вас сдачу с:</p>
                        <div class="row row-cols-2 mb-0">
                            <div class="col" v-for="money in moneyVariants">
                                <button class="btn btn-outline-primary w-100 mb-2 rounded-5"
                                        type="button"
                                        @click="deliveryForm.money=money"
                                        v-bind:class="{'btn-primary text-white':deliveryForm.money===money}">{{
                                        money
                                    }}₽
                                </button>
                            </div>

                        </div>
                        <p class="mb-2"><em>или введите другую сумму...</em></p>

                        <div class="form-floating">
                            <input type="number"
                                   min="0"
                                   v-model="deliveryForm.money"
                                   class="form-control" id="deliveryForm-money" placeholder="С какой суммы нужна сдача">
                            <label for="deliveryForm-money">С какой суммы нужна сдача</label>
                        </div>

                    </div>
                </div>
            </div>


            <p v-if="settings.delivery_price_text" v-html="settings.delivery_price_text"></p>
            <p v-if="settings.min_price">Минимальная цена заказа {{ settings.min_price || 0 }} руб</p>

            <button
                v-if="cartTotalPrice <= settings.free_shipping_starts_from"
                @click="requestDeliveryPrice"
                class="btn btn-outline-light text-primary p-3 w-100 mb-2"
                :disabled="!canRequestDeliverPrice">
                <i class="fa-solid fa-map-location-dot mr-2"></i> Узнать цену доставки
            </button>

            <button
                v-if="(deliveryForm.payment_type!==2||settings.need_pay_after_call)&&tab===1"
                type="submit"
                :disabled="spent_time_counter>0||(!deliveryForm.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)"
                class="btn btn-primary p-3 w-100 mb-2">

                <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
                <i v-else class="fa-solid fa-hourglass  mr-2"></i>

                <span
                    v-if="spent_time_counter<=0"
                    class="color-white">Оформить</span>
                <span
                    v-else
                    class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
            </button>

            <!--            ||(!deliveryForm.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)-->
            <button
                v-if="deliveryForm.payment_type===2&&!settings.need_pay_after_call&&tab===1"
                type="button"
                @click="tab=3"
                :disabled="!canSubmitForm"
                class="btn btn-primary p-3 w-100">
                <i class="fa-solid fa-receipt mr-2"></i> Оплатить переводом
            </button>
        </form>
        <form v-if="tab===3"
              class="container py-3"
              v-on:submit.prevent="startCheckout">
            <h5 class="my-3 text-left"><i class="fa-regular fa-image mr-2"></i>Фотография чека</h5>

            <div class="alert alert-light mb-2 fw-bold"
                 v-if="settings.payment_info"
                 role="alert" v-html="settings.payment_info"></div>

            <h6 class="opacity-75 mt-3">Сводка</h6>

            <div class="card my-3 ">
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Суммарно, ед. <strong>{{ cartTotalCount }}
                                шт.</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Цена <strong>{{ cartTotalPrice }}
                                <sup>.00</sup>₽</strong>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                                <strong v-if="deliveryForm.use_cashback">{{ cashbackLimit }} <sup>.00</sup>₽</strong>
                                <strong v-else>-</strong>
                            </p>
                        </li>

                        <li class="list-group-item" v-if="!deliveryForm.need_pickup">
                            <p class="mb-0 d-flex justify-content-between">Цена доставки
                                <strong v-if="deliveryForm.delivery_price>0">{{ deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong>
                                <strong v-else>от курьера</strong>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p v-if="!deliveryForm.use_cashback"
                               class="mb-0 d-flex justify-content-between">Итого, цена
                                <strong>{{ cartTotalPrice + deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong></p>
                            <p v-else
                               class="mb-0 d-flex justify-content-between">Итого, цена
                                <strong>{{ (cartTotalPrice - cashbackLimit) + deliveryForm.delivery_price }}
                                    <sup>.00</sup>₽</strong></p>
                        </li>
                    </ul>

                    <div v-if="deliveryForm.payment_type === 3&&settings.can_use_cash">
                        <p class="my-3 text-center">Мы можем подготовить для вас сдачу с:</p>
                        <div class="row row-cols-2 mb-0">
                            <div class="col" v-for="money in moneyVariants">
                                <button class="btn btn-outline-primary w-100 mb-2 rounded-5"
                                        type="button"
                                        @click="deliveryForm.money=money"
                                        v-bind:class="{'btn-primary text-white':deliveryForm.money===money}">{{
                                        money
                                    }}₽
                                </button>
                            </div>

                        </div>
                        <p class="mb-2"><em>или введите другую сумму...</em></p>

                        <div class="form-floating">
                            <input type="number"
                                   min="0"
                                   v-model="deliveryForm.money"
                                   class="form-control" id="deliveryForm-money" placeholder="С какой суммы нужна сдача">
                            <label for="deliveryForm-money">С какой суммы нужна сдача</label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center flex-wrap w-100">
                <label
                    v-if="deliveryForm.image==null"
                    for="menu-photos"
                    class="photo-loader-bill d-flex flex-column justify-content-center align-items-center mb-2">
                    <i class="fa-regular fa-image my-2 text-primary" style="font-size:20px;"></i>
                    <span class="text-primary fw-bold">Выбрать фотографию чека</span>
                    <input type="file" id="menu-photos" accept="image/*"
                           @change="onChangePhotos"
                           style="display:none;"/>

                </label>
                <div class="mb-2 img-preview-bill"
                     v-if="deliveryForm.image!=null">
                    <img v-lazy="getPhoto(deliveryForm.image).imageUrl">
                    <div class="remove">
                        <a @click="removePhoto()"><i class="fa-solid fa-trash-can mr-2 text-primary"></i>Удалить</a>
                    </div>
                </div>

            </div>

            <div class="form-floating mb-2">
            <textarea class="form-control"
                      v-model="deliveryForm.image_info"
                      style="height:100px;line-height:150%;"
                      placeholder="Информация" id="deliveryForm-image_info"></textarea>
                <label for="deliveryForm-image_info">Текст к оплате <small>(не обязательно)</small></label>
            </div>

            <!--            ||(!deliveryForm.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)||deliveryForm.image==null-->
            <button
                type="submit"
                :disabled="!canSubmitForm"
                class="btn btn-primary p-3 w-100">

                <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
                <i v-else class="fa-solid fa-hourglass  mr-2"></i>

                <span
                    v-if="spent_time_counter<=0"
                    class="color-white">Оформить</span>
                <span
                    v-else
                    class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
            </button>
        </form>
    </div>

    <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
        <div class="d-flex justify-content-center flex-column align-items-center">
            <i class="fa-brands fa-shopify mb-3" style="font-size:36px;"></i>
            <p>Корзина пустая:(</p>
        </div>
    </div>
    <nav
        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0" style="border-radius:10px 10px 0px 0px;">
        <div v-if="cartProducts.length>0" class="w-100">
            <button type="button"
                    v-if="tab===0"
                    @click="tab=1"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">Оформление заказа
            </button>
            <button type="button"
                    v-if="tab>=1"
                    @click="tab=0"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">Корзина с товаром
            </button>
        </div>
        <div v-else class="w-100">
            <button type="button"
                    @click="goToCatalog"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">Вернуться в магазин
            </button>
        </div>
    </nav>
</template>
<script>


import baseJS from "@/ClientTg/modules/custom";
import {mapGetters} from "vuex";

export default {
    props: ["type"],
    data() {
        return {
            tab: 0,
            settings: {
                can_use_cash: true,
                can_use_card: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
                menu_list_type: 0,
                payment_info: 0,
                need_category_by_page: false,
                need_pay_after_call: false,
                free_shipping_starts_from: 0,
            },
            spent_time_counter: 0,
            is_requested: false,
            isCollapsed: true,
            search: null,
            products: [],
            paginate: null,
            categories: [],
            sending: false,
            min_price: null,
            max_price: null,
            need_request_delivery_price: true,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                city: null,
                street: null,
                building: null,
                flat_number: null,
                entrance_number: null,
                floor_number: null,
                info: null,
                need_pickup: false,
                pick_up_type: 1,
                has_disability: false,
                use_cashback: false,
                disabilities: [],
                money: null,
                cash: true,
                payment_type: 2,
                persons: 1,
                time: null,
                when_ready: true,// по готовности
                image: null,
                image_info: null,
                delivery_price: 0,
                distance: 0,
            },
        }
    },
    watch: {
        'tab': {
            handler: function (newValue) {
                window.scroll(0, 80);
            },
            deep: true
        },

        'deliveryForm.need_pickup': {
            handler: function (newValue) {
                if (this.deliveryForm.need_pickup) {
                    this.deliveryForm.delivery_price = 0
                    this.deliveryForm.distance = 0
                }
            },
            deep: true
        },
        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.deliveryForm.delivery_price = 0
                }
            },
            deep: true
        },
        'deliveryForm.cash': {
            handler: function (newValue) {
                if (!this.deliveryForm.cash)
                    this.deliveryForm.money = null
            },
            deep: true
        },

    },
    computed: {
        ...mapGetters(['getProducts', 'cartProducts', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),
        canRequestDeliverPrice() {
            return this.need_request_delivery_price && this.deliveryForm.city != null && this.deliveryForm.street != null && this.deliveryForm.building != null;
        },

        canSubmitForm() {


            return (this.spent_time_counter || 0) == 0
                && (!this.deliveryForm.use_cashback ?
                    this.cartTotalPrice >= this.settings.min_price :
                    this.cartTotalPrice - this.cashbackLimit > this.settings.min_price)
              && (this.tab == 3 ? this.deliveryForm.image != null : true)
        },

        canUseCashBack() {
            return this.getSelf.cashBack && this.settings.min_price_for_cashback < this.cartTotalPrice
        },
        getCurrentBot() {
            return window.currentBot
        },
        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack ? this.getSelf.cashBack.amount : 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.getCurrentBot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },
        filteredProducts() {

            if (!this.search)
                return this.products

            return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
        tg() {
            return window.Telegram.WebApp;
        },
        activeCategories() {
            let tmp = [];
            this.filteredProducts.forEach(item => {
                tmp.push(item.categories.map(o => o['id']))
            })
            return [...new Set(tmp.map(item => item[0])), ...new Set(tmp.map(item => item[1]))];
        }
    },

    mounted() {

        this.tg.BackButton.hide()

        if (localStorage.getItem("cashman_self_product_delivery_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_self_product_delivery_counter"))
        }

        //  this.selectProductTypeDisplay(this.settings.menu_list_type || 0)

        if (localStorage.getItem("cashman_self_product_type_display") != null) {
            this.product_type_display = parseInt(localStorage.getItem("cashman_self_product_type_display") || 0)
        }


        this.deliveryForm.name = localStorage.getItem("cashman_self_product_delivery_form_name") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_name") : null

        this.deliveryForm.phone = localStorage.getItem("cashman_self_product_delivery_form_phone") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_phone") : null

        this.deliveryForm.address = localStorage.getItem("cashman_self_product_delivery_form_address") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_address") : null

        this.deliveryForm.city = localStorage.getItem("cashman_self_product_delivery_form_city") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_city") : null

        this.deliveryForm.street = localStorage.getItem("cashman_self_product_delivery_form_street") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_street") : null

        this.deliveryForm.building = localStorage.getItem("cashman_self_product_delivery_form_building") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_building") : null

        this.deliveryForm.flat_number = localStorage.getItem("cashman_self_product_delivery_form_flat_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_flat_number") : null

        this.deliveryForm.entrance_number = localStorage.getItem("cashman_self_product_delivery_form_entrance_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_entrance_number") : null

        this.deliveryForm.disabilities = localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities") != null ?
            JSON.parse(localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities")) : []

        if (this.deliveryForm.disabilities.length > 0)
            this.deliveryForm.has_disability = true

        // this.clearCart();

        this.loadProducts()
        this.loadShopModuleData()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()


        window.addEventListener("scroll-to-basket", (event) => { // (1)
            this.tab = 2
            document.querySelector("#basket").scrollIntoView({
                behavior: 'smooth'
            });
        });


    },
    methods: {
        requestDeliveryPrice() {
            this.need_request_delivery_price = false

            this.$notify({
                title: "Корзина",
                text: "Мы начали процесс расчета цены доставки",
            })

            this.$store.dispatch("requestDeliveryPrice", {
                city: this.deliveryForm.city,
                street: this.deliveryForm.street,
                building: this.deliveryForm.building,
            }).then(resp => {
                console.log("resp", resp)
                this.deliveryForm.delivery_price = resp.price || 0
                this.deliveryForm.distance = resp.distance || 0

                this.need_request_delivery_price = true

                this.$notify({
                    title: "Корзина",
                    text: "Цена доставки успешно просчитана",
                    type: "success"
                })
            }).catch(() => {
                this.deliveryForm.delivery_price = 0
                this.deliveryForm.distance = 0
                this.need_request_delivery_price = true

                this.$notify({
                    title: "Корзина",
                    text: "Ошибка расчёта цены доставки",
                    type: "error"
                })
            })
        },
        onChangePhotos(e) {
            const file = e.target.files[0]
            this.deliveryForm.image = file
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.deliveryForm.image = null
        },
        goToCatalog() {
            this.$router.push({name: 'CatalogV2'})
        },

        decPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons > 1 ? this.deliveryForm.persons - 1 : this.deliveryForm.persons;
        },
        incPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons < 100 ? this.deliveryForm.persons + 1 : this.deliveryForm.persons;
        },
        selectProductTypeDisplay(type) {
            this.product_type_display = type
            localStorage.setItem("cashman_self_product_type_display", this.product_type_display)
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                }, 1000
            )
        },
        clearCart() {
            this.tab = 1
            this.$store.dispatch("clearCart").then(() => {
                this.$notify({
                    title: "Корзина",
                    text: "Корзина успешно очищена",
                    type: "success"
                })
            })

        },
        resetCategories() {
            this.categories = []
            this.loadProducts(0)
        },
        selectCategory(item) {

            if (!this.settings.need_category_by_page) {
                let index = this.categories.findIndex(category => category.id === item.id)
                if (index !== -1) {
                    this.categories.splice(index, 1)
                } else
                    this.categories.push(item)
            } else
                this.categories = [item]

            if (this.settings.need_category_by_page)
                this.tab = 1

            this.loadProducts(0)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
                    })
                })
            })
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search,
                    categories: this.categories.length > 0 ? this.categories.map(o => o['id']) : null,
                    min_price: this.min_price || null,
                    max_price: this.max_price || null
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject

            })
        },

        startCheckout() {

            localStorage.setItem("cashman_self_product_delivery_form_name", this.deliveryForm.name || '')
            localStorage.setItem("cashman_self_product_delivery_form_phone", this.deliveryForm.phone || '')
            localStorage.setItem("cashman_self_product_delivery_form_address", this.deliveryForm.address || '')
            localStorage.setItem("cashman_self_product_delivery_form_city", this.deliveryForm.city || '')
            localStorage.setItem("cashman_self_product_delivery_form_street", this.deliveryForm.street || '')
            localStorage.setItem("cashman_self_product_delivery_form_building", this.deliveryForm.building || '')
            localStorage.setItem("cashman_self_product_delivery_form_flat_number", this.deliveryForm.flat_number || '')

            localStorage.setItem("cashman_self_product_delivery_form_entrance_number", this.deliveryForm.entrance_number || '')
            if ((this.deliveryForm.disabilities || []).length > 0)
                localStorage.setItem("cashman_self_product_delivery_form_entrance_disabilities", JSON.stringify(this.deliveryForm.disabilities || []))
            else
                localStorage.removeItem("cashman_self_product_delivery_form_entrance_disabilities");


            if (this.is_requested) {
                this.$botNotification.warning("Упс!", `Сделать повторный заказ можно через <strong>${this.spent_time_counter} сек.</strong>`)
                return;
            }

            let data = new FormData();

            data.append("need_payment_link", this.deliveryForm.payment_type === 0)

            Object.keys(this.deliveryForm)
                .forEach(key => {
                    const item = this.deliveryForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.type)
                data.append("type", this.type)


            if (this.deliveryForm.payment_type === 0 && this.settings.can_use_card) {
                this.$store.dispatch("createCheckoutLink", {
                    deliveryForm: data

                }).then((resp) => {
                    this.tg.openInvoice(resp.result)
                })
                return;
            }

            if (typeof this.deliveryForm.image != "string") {
                data.append('photo', this.deliveryForm.image);
                data.delete("image")
            }

            this.sending = true
            this.$store.dispatch("startCheckout", {
                deliveryForm: data

            })
                .then((response) => {

                    this.deliveryForm = {
                        message: null,
                        name: null,
                        phone: null,
                    }

                    this.$notify({
                        title: "Доставка",
                        text: "Дальнейшая инструкция отправлена вам в бот!",
                        type: "success"
                    })

                    this.tab = 1
                    this.$store.dispatch("clearCart");
                    //this.clearCart();

                    this.tg.close();

                    this.sending = false
                }).catch(err => {
                this.sending = false

                this.$notify({
                    title: "Доставка",
                    text: "Ошибка! Обратитесь к администратору",
                    type: "error"
                })
            })

            this.startTimer();
            this.is_requested = true
        },
        loadActualProducts() {
            this.$store.dispatch("loadActualPriceInCart")
        },
        removeCategory(index) {
            this.categories.splice(index, 1)
        },
        addToCart(item) {
            this.$cart.add(item.product)
        },
    }
}
</script>
<style lang="scss">
.scrolled-list {
    width: 100%;
    overflow-x: auto;
}

.card-style {
    margin: 0px 5px 15px 5px !important;
}

.content {
    margin: 10px 10px 10px 10px !important;
}

.go-to-cart {
    position: fixed;
    bottom: 0px;
    width: 100%;
    z-index: 100;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
}


.photo-loader-bill {
    width: 100%;
    height: 300px;
    border-radius: 5px;
    border: 1px solid #dcdcdc;


    span {
        font-size: 20px;
        font-weight: normal !important;
    }
}

.img-preview-bill {
    width: 100%;
    height: 300px;
    border-radius: 5px;
    position: relative;

    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        border-radius: 5px;
    }

    .remove {
        position: absolute;
        z-index: 2;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.38);
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: white;
        font-size: 20px;
    }

}
</style>

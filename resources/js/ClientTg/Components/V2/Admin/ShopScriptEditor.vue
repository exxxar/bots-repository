<script setup>
import WheelOfFortuneShopVariant from "@/ClientTg/Components/V2/Games/WheelOfFortuneShopVariant.vue";
</script>
<template>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=0"
               v-bind:class="{'active fw-bold':tab===0}"
               aria-current="page"
               href="javascript:void(0)">Магазин</a>
        </li>
        <li class="nav-item">
            <a class="nav-link "
               v-bind:class="{'active fw-bold':tab===1}"
               @click="tab=1"
               href="javascript:void(0)">Колесо фортуны</a>
        </li>
    </ul>

    <form v-on:submit.prevent="submit">
        <div v-if="tab===0" class="py-3">

            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.is_disabled"
                       role="switch" id="script-settings-is_disabled">
                <label class="form-check-label" for="script-settings-is_disabled">Состояние магазина: <span
                    v-bind:class="{'text-primary fw-bold':!form.is_disabled}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':form.is_disabled}">выкл</span></label>
            </div>

            <p class="alert alert-light mb-2">Текст для клиентов, который отображается пользователю если магазин в
                данный момент выключен администратором</p>
            <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.disabled_text"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-disabled_text"></textarea>
                <label for="script-settings-disabled_text">Текст при выключении</label>
            </div>
            <p class="alert alert-light mb-2">Сумма, от которой будет доступен заказа в магазине</p>
            <div class="form-floating mb-2">
                <input type="number"
                       min="0"
                       v-model="form.min_price"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Минимальная сумма заказа</label>
            </div>
            <p class="alert alert-light mb-2">Далее настройка параметров доставки: базовая цена для расчёта стоимости
                доставки. Расчёт доставки идет по прямой от точки А к точке Б. Формула: базовая цена + N км * цена за
                км</p>
            <div class="form-floating mb-2">
                <input type="number"
                       min="0"
                       v-model="form.min_base_delivery_price"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Базовая цена доставки</label>
            </div>
            <p class="alert alert-light mb-2">Цена доставки за 1км пути</p>
            <div class="form-floating mb-2">
                <input type="number"
                       min="0"
                       v-model="form.price_per_km"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Цена за км пути</label>
            </div>
            <div class="form-floating mb-2">
                <input type="number"
                       min="0"
                       v-model="form.free_shipping_starts_from"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Бесплатная доставка от</label>
            </div>
            <p class="alert alert-light mb-2">Описание процесса доставки \ оплаты доставки. Текст размещен в корзине
                перед нажатием кнопки оформления заказа.</p>
            <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.delivery_price_text"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-disabled_text"></textarea>
                <label for="script-settings-disabled_text">Текст доставки</label>
            </div>

            <p class="alert alert-light mb-2">Расчет цены доставки происходит на основе координат. Укажите координаты
                вашего заведения из
                <a class="text-primary fw-bold" href="https://yandex.ru/maps/" target="_blank">Яндекс.Карты</a> -
                скопируйте и вставьте <span class="fw-bold text-primary">00.000000</span>, <span
                    class="fw-bold text-primary">00.000000</span> координаты в это поле.
            </p>
            <div class="form-floating mb-2">
                <input type="search"
                       min="0"
                       v-mask="['##.######, ##.######']"
                       v-model="form.shop_coords"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Координаты заведения</label>
            </div>

            <p class="alert alert-light mb-2">Платежная информация: как оплатить и дальнейшие инструкции</p>
            <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.payment_info"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-disabled_text"></textarea>
                <label for="script-settings-disabled_text">Текст оплаты</label>
            </div>
            <p class="alert alert-light mb-2">Данный флаг позволяет отключить прием платежа через скриншот. После
                оформления заказа клиент будет ждать звонка с инструкцией от оператора</p>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.need_pay_after_call"
                       role="switch" id="script-settings-need_pay_after_call">
                <label class="form-check-label" for="script-settings-need_pay_after_call">Режим оплаты после звонка:
                    <span v-bind:class="{'text-primary fw-bold':form.need_pay_after_call}">вкл</span> \ <span
                        v-bind:class="{'text-primary fw-bold':!form.need_pay_after_call}">выкл</span></label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.can_use_cash"
                       role="switch" id="script-settings-can_use_cash">
                <label class="form-check-label" for="script-settings-can_use_cash">Оплата наличными или переводом: <span
                    v-bind:class="{'text-primary fw-bold':form.can_use_cash}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.can_use_cash}">выкл</span></label>
            </div>
            <p class="alert alert-light mb-2">Оплата картой подразумевает использование платежного агрегатора и требует
                ввода тоукена платежной системы. Оплата через агрегатора облагается налогом.</p>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.can_use_card"
                       role="switch" id="script-settings-can_use_card">
                <label class="form-check-label" for="script-settings-can_use_card">Оплата картой: <span
                    v-bind:class="{'text-primary fw-bold':form.can_use_card}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.can_use_card}">выкл</span></label>
            </div>


        </div>

        <div v-if="tab===1" class="py-3">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.wheel_of_fortune.can_play"
                       role="switch" id="script-settings-is_disabled">
                <label class="form-check-label" for="script-settings-wheel-of-fortune-can_play">Состояние колеса
                    фортуны: <span v-bind:class="{'text-primary fw-bold':form.wheel_of_fortune.can_play}">вкл</span> \
                    <span v-bind:class="{'text-primary fw-bold':!form.wheel_of_fortune.can_play}">выкл</span></label>
            </div>
            <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.wheel_of_fortune.rules"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play"></textarea>
                <label for="script-settings-disabled_text">Правила колеса фортуны</label>
            </div>
            <p class="alert alert-light mb-2">Редактирование призов, описания, отметки места получения приза, цвет
                сектора и цвет текста. Максимальное число секторов <span class="fw-bold text-primary">10</span>, сейчас
                создано <span class="fw-bold text-primary">{{ (form.wheel_of_fortune.items || []).length }}</span>
                секторов. <strong class="fw-bold text-primary">Внимание!</strong> При удалении сектора идет пересчет его номера!</p>
            <div class="accordion accordion-flush" :id="'wheel_of_fortune'">
                <div class="accordion-item" v-for="(item, index) in form.wheel_of_fortune.items">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                :data-bs-target="'#wheel-sector-'+index" aria-expanded="false"
                                :aria-controls="'wheel-sector-'+index">
                            Сектор {{ item.value }} #{{ item.id }}
                        </button>
                    </h2>
                    <div :id="'wheel-sector-'+index" class="accordion-collapse collapse"
                         :data-bs-parent="'#wheel_of_fortune'">

                        <div class="form-floating my-2">
                            <input type="text"
                                   v-model="form.wheel_of_fortune.items[index].value"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Телеграм-эмодзи</label>
                        </div>

                        <div class="form-floating mb-2">
                             <textarea class="form-control"
                                       v-model="form.wheel_of_fortune.items[index].description"
                                       maxlength="4000"
                                       style="min-height:150px;"
                                       placeholder="Leave a comment here"
                                       :id="'script-settings-description-'+index" required>
                             </textarea>
                            <label :for="'script-settings-description-'+index">Описание приза</label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="color"
                                   v-model="form.wheel_of_fortune.items[index].bgColor"
                                   class="form-control" :id="'script-settings-bgColor-'+index"
                                   placeholder="name@example.com" required>
                            <label :for="'script-settings-bgColor-'+index">Цвет фона сектора</label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="color"
                                   v-model="form.wheel_of_fortune.items[index].color"
                                   class="form-control" :id="'script-settings-color-'+index"
                                   placeholder="name@example.com" required>
                            <label :for="'script-settings-color-'+index">Цвет шрифта сектора</label>
                        </div>

                        <p class="alert-light alert mb-2">Впишите или выберите где можно получить приз: <span
                            @click="attachMarkText(index, 'во время доставки')"
                            class="fw-bold text-primary cursor-pointer text-decoration-underline">во время доставки</span>
                            или <span
                                @click="attachMarkText(index, 'в заведении')"
                                class="fw-bold text-primary cursor-pointer text-decoration-underline">в заведении</span>.
                            Вы можете вписать сразу несколько
                            вариантов.</p>
                        <div class="form-floating my-2">
                            <input type="search"
                                   v-model="form.wheel_of_fortune.items[index].mark"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Где выдается приз</label>
                        </div>

                        <a href="javascript:void(0)"
                           @click="removeSector(index)"
                           class="btn btn-link w-100 text-center my-3">Удалить сектор #{{ item.id }}</a>
                    </div>
                </div>

            </div>
            <button
                type="button"
                :disabled="form.wheel_of_fortune.items.length===10"
                @click="addSector"
                class="btn btn-outline-primary w-100 p-3 mb-2">Добавить еще сектор
            </button>

            <div class="alert alert-light mb-2">
                <p>Демонстрация заполнения</p>
                <WheelOfFortuneShopVariant
                    v-if="loaded"
                    v-model="form.wheel_of_fortune.items"></WheelOfFortuneShopVariant>
            </div>
        </div>

        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>
</template>
<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            loaded: true,
            tab: 0,
            form: {
                shop_coords: null,
                yandex_geocoder: null,
                free_shipping_starts_from: 0,
                min_base_delivery_price: 0,
                price_per_km: 80,
                payment_info: null,
                main_text: null,
                delivery_price_text: null,
                min_price: 80,
                is_disabled: false,
                can_use_cash: true,
                can_use_card: true,
                need_pay_after_call: false,
                disabled_text: null,
                wheel_of_fortune: {
                    can_play: true,
                    rules: 'Колесо фортуны доступно 1 раз в сутки. В качестве приза вы можете выиграть 1 из предложенных призов и воспользоваться ими в заведении или на доставке:) Приятного отдыха!',
                    // short_description:'В данный момент розыгрыш недоступен',
                    items: [
                        {
                            id: 1,
                            value: "🍅",
                            bgColor: "#fac600",
                            color: "#ffffff",
                            mark: 'в заведении',
                            description: null,
                        },
                        {
                            id: 2,
                            value: "🍲",
                            bgColor: "#ffffff",
                            color: "#000000",
                            mark: 'в заведении & на доставке',
                            description: null,
                        },
                        {
                            id: 3,
                            value: "🍦",
                            bgColor: "#ff2e55",
                            color: "#ffffff",
                            description: null,
                            mark: 'на доставке',
                        },
                        {
                            id: 4,
                            value: "😍",
                            bgColor: "#a1043a",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении',
                        },
                        {
                            id: 5,
                            value: "☕",
                            bgColor: "#ffffff",
                            color: "#000000",
                            description: null,
                            mark: 'в заведении',
                        },
                        {
                            id: 6,
                            value: "🍕",
                            bgColor: "#c92729",
                            color: "#ffffff",
                            description: null,
                            mark: 'на доставке',
                        },
                        {
                            id: 7,
                            value: "📲",
                            bgColor: "#ffffff",
                            color: "#000000",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                        {
                            id: 8,
                            value: "📌",
                            bgColor: "#c92729",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                        {
                            id: 9,
                            value: "🚀",
                            bgColor: "#c92729",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                    ]
                }
            }
        }
    },
    watch: {
        'form.wheel_of_fortune.items': {
            handler: function (newValue) {
                this.loaded = false
                this.$nextTick(() => {
                    this.loaded = true
                })
            },
            deep: true
        }
    },
    methods: {
        removeSector(index) {
            this.form.wheel_of_fortune.items.splice(index, 1)

            let i = 1
            this.form.wheel_of_fortune.items.forEach(item=>{
                item.id =  i
                i++
            })
            this.$notify({
                title: "Редактор",
                text: "Сектор успешно удален! Идентификаторы секторов пересчитаны",
                type: "success"
            })
        },
        attachMarkText(index, text) {
            this.form.wheel_of_fortune.items[index].mark +=
                (this.form.wheel_of_fortune.items[index].mark || '').length === 0 ?
                    text : " & " + text
        },
        addSector() {
            if ((this.form.wheel_of_fortune.items || []).length === 0)
                this.form.wheel_of_fortune.items = []

            if (this.form.wheel_of_fortune.items.length < 10) {
                this.form.wheel_of_fortune.items.push({
                    id: this.form.wheel_of_fortune.items.length + 1,
                    value: this.form.wheel_of_fortune.items.length + 1,
                    bgColor: "#c92729",
                    color: "#ffffff",
                    description: null,
                    mark: 'в заведении & на доставке',
                })

                this.$notify({
                    title: "Редактор",
                    text: "Сектор успешно добавлен!",
                    type: "success"
                })
            } else {
                this.$notify({
                    title: "Редактор",
                    text: "Достигнут лимит секторов!",
                    type: "error"
                })
            }

        },
        submit() {

        }
    }
}
</script>

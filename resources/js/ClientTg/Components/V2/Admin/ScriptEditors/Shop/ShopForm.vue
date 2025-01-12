<script setup>
import SlugForm from "@/ClientTg/Components/V2/Admin/Slugs/SlugForm.vue";
</script>

<template>

    <div v-if="loaded_params">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.is_disabled"
                   role="switch" id="script-settings-is_disabled">
            <label class="form-check-label" for="script-settings-is_disabled">Состояние магазина: <span
                v-bind:class="{'text-primary fw-bold':!form.is_disabled}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':form.is_disabled}">выкл</span></label>
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.is_product_list"
                   role="switch" id="script-settings-is_product_list">
            <label class="form-check-label" for="script-settings-is_product_list">Отображение товаров: <span
                v-bind:class="{'text-primary fw-bold':!form.is_product_list}">плитка</span> \ <span
                v-bind:class="{'text-primary fw-bold':form.is_product_list}">список</span></label>
        </div>
        <p class="alert alert-light mb-2">
            Тип магазина влияет на отображение самого магазина и на корзину.
        </p>
        <div class="form-floating mb-2">
            <select class="form-select"
                    v-model="form.shop_display_type"
                    id="floatingSelect" aria-label="Floating label select example">
                <option :value="0">Продовольственный</option>
                <option :value="1">Бытовые товары</option>
                <option :value="2">Электронные товары</option>
            </select>
            <label for="floatingSelect">Тип магазина</label>
        </div>

        <p class="alert alert-light mb-2">Вы можете разрешить или запретить клиентам оставлять заказы вне рабочего
            времени вашего сервиса.
            Если переключатель выключен, то покупки будут доступны <span
                v-bind:class="{'text-primary fw-bold':!form.can_buy_after_closing}">согласно графика</span>
            работы вашего заведения, а если включен - <span
                v-bind:class="{'text-primary fw-bold':form.can_buy_after_closing}">всегда</span>.</p>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.can_buy_after_closing"
                   role="switch" id="script-settings-can_buy_after_closing">
            <label class="form-check-label" for="script-settings-can_buy_after_closing">Покупки после закрытия: <span
                v-bind:class="{'text-primary fw-bold':form.can_buy_after_closing}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.can_buy_after_closing}">выкл</span></label>
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

        <template v-if="form.shop_display_type===0">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.need_automatic_delivery_request"
                       role="switch" id="script-settings-need_automatic_delivery_request">
                <label class="form-check-label" for="script-settings-need_automatic_delivery_request">Автоматический расчет
                    цены доставки: <span
                        v-bind:class="{'text-primary fw-bold':form.need_automatic_delivery_request}">вкл</span> \ <span
                        v-bind:class="{'text-primary fw-bold':!form.need_automatic_delivery_request}">выкл</span></label>
            </div>

            <template v-if="form.need_automatic_delivery_request">

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

                <p class="alert alert-light mb-2">Расчет цены доставки происходит на основе координат. Укажите координаты
                    вашего заведения из
                    <a class="text-primary fw-bold" href="https://yandex.ru/maps/" target="_blank">Яндекс.Карты</a> -
                    скопируйте и вставьте <span class="fw-bold text-primary">00.000000</span>, <span
                        class="fw-bold text-primary">00.000000</span> координаты в это поле. Для работы
                    <a class="text-primary fw-bold" href="https://yandex.ru/maps-api/products/geocoder-api" target="_blank">Яндекс.Геокодер</a>
                    необходимо настроить ключ.
                </p>

                <div class="form-floating mb-2">
                    <input type="text"
                           v-model="form.yandex_geocoder"
                           required
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Токен от Яндекс.Геокодер</label>
                </div>

                <div class="form-floating mb-2">
                    <input type="text"
                           v-mask="['##.######, ##.######']"
                           v-model="form.shop_coords"
                           required
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Координаты заведения</label>
                </div>

            </template>
        </template>

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

        <div class="form-floating mb-2" v-if="form.can_use_card">
            <input type="text"
                   v-model="form.payment_token"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Тоукен платежной системы</label>
        </div>



        <div class="divider my-3">Секции</div>
        <p class="alert alert-light">
            Данный блок переключателей включает \ выключает отображение соответствующих секций в корзине.
        </p>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.need_promo_code"
                   role="switch" id="script-settings-need_promo_code">
            <label class="form-check-label" for="script-settings-need_promo_code">Промокод: <span
                v-bind:class="{'text-primary fw-bold':form.need_promo_code}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.need_promo_code}">выкл</span></label>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.need_bonuses_section"
                   role="switch" id="script-settings-need_bonuses_section">
            <label class="form-check-label" for="script-settings-need_bonuses_section">Оплата бонусами: <span
                v-bind:class="{'text-primary fw-bold':form.need_bonuses_section}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.need_bonuses_section}">выкл</span></label>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.need_prizes_from_wheel_of_fortune"
                   role="switch" id="script-settings-need_prizes_from_wheel_of_fortune">
            <label class="form-check-label" for="script-settings-need_prizes_from_wheel_of_fortune">Призы колеса
                фортуны: <span
                    v-bind:class="{'text-primary fw-bold':form.need_prizes_from_wheel_of_fortune}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.need_prizes_from_wheel_of_fortune}">выкл</span></label>
        </div>

        <template v-if="form.shop_display_type === 0">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.need_person_counter"
                       role="switch" id="script-settings-need_person_counter">
                <label class="form-check-label" for="script-settings-need_person_counter">Число персон: <span
                    v-bind:class="{'text-primary fw-bold':form.need_person_counter}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.need_person_counter}">выкл</span></label>
            </div>

            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.need_health_restrictions"
                       role="switch" id="script-settings-need_health_restrictions">
                <label class="form-check-label" for="script-settings-need_health_restrictions">Ограничения по здоровью:
                    <span
                        v-bind:class="{'text-primary fw-bold':form.need_health_restrictions}">вкл</span> \ <span
                        v-bind:class="{'text-primary fw-bold':!form.need_health_restrictions}">выкл</span></label>
            </div>

            <div class="form-check form-switch mb-2">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="form.need_table_list"
                       role="switch" id="script-settings-need_table_list">
                <label class="form-check-label" for="script-settings-need_table_list">Столики в заведении: <span
                    v-bind:class="{'text-primary fw-bold':form.need_table_list}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.need_table_list}">выкл</span></label>
            </div>

            <template v-if="form.need_table_list">
                <p class="alert alert-light mb-2">
                    Укажите максимальное число столиков в заведении
                </p>
                <div
                    class="form-floating mb-2">
                    <input type="number"
                           min="0"
                           max="200"
                           v-model="form.max_tables"
                           class="form-control" id="modelValue-table-number"
                           placeholder="Номер столика">
                    <label for="modelValue-table-number">Число столиков</label>
                </div>

                <a
                    :href="'/bot-client/'+bot.bot_domain+'/tables-qr?count='+form.max_tables+'&script-id='+scriptId"
                    target="_blank"
                    class="btn btn-info w-100"
                ><i class="fa-solid fa-qrcode"></i> Скачать QR-коды для столиков</a>
            </template>

        </template>

        <div class="divider my-3">Настройка СБП</div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.can_use_sbp"
                   role="switch" id="script-settings-need_use_sbp">
            <label class="form-check-label" for="script-settings-need_use_sbp">Использовать СБП для оплат: <span
                v-bind:class="{'text-primary fw-bold':form.can_use_sbp}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.can_use_sbp}">выкл</span></label>
        </div>

        <template v-if="form.can_use_sbp&&form.sbp">
            <p class="mb-2 alert alert-light">Внимание! На текущий момент для СБП используется только
                <a href="https://telegra.ph/Nastrojka-SBP-v-T-bank-12-28" target="_blank" class="text-primary fw-bold">Т-Банк</a>
            </p>
            <div
                class="form-floating mb-2">
                <input type="text"
                       required
                       v-model="form.sbp.tinkoff.terminal_key"
                       class="form-control" id="modelValue-tinkoff-terminal-key"
                       placeholder="Ключ терминала">
                <label for="modelValue-tinkoff-terminal-key">Ключ терминала</label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       required
                       v-model="form.sbp.tinkoff.terminal_password"
                       class="form-control" id="modelValue-tinkoff-terminal-password"
                       placeholder="Пароль терминала">
                <label for="modelValue-tinkoff-terminal-password">Пароль терминала</label>
            </div>

            <div class="form-floating mb-2">
                <select class="form-select"
                        required
                        v-model="form.sbp.tinkoff.tax"
                        id="floatingSelect" aria-label="Floating label select example">
                    <option :value="tax.tax" v-for="(tax, taxIndex) in tax_variants">{{ tax.title }}</option>
                </select>
                <label for="floatingSelect">Схема налогооблажения</label>
            </div>

            <div class="form-floating mb-2">
                <select class="form-select"
                        required
                        v-model="form.sbp.tinkoff.vat"
                        id="floatingSelect" aria-label="Floating label select example">
                    <option :value="vat.value" v-for="(vat, vatIndex) in vat_variants">{{ vat.title }}</option>
                </select>
                <label for="floatingSelect">% НДС</label>
            </div>

        </template>


        <div class="divider my-3">Настройка призов из розыгрыша</div>


        <p class="alert alert-light mb-2">
            Вам нужно выбрать скрипт колеса, призы которого будут автоматически вносится в заказ пользователем.
        </p>
        <h6>Доступные для выбора скрипты</h6>
        <ul class="list-group mb-2" v-if="(scripts||[]).length>0">
            <li
                @click="selectScript(null)"
                v-bind:class="{'bg-primary text-white':(form.selected_script_id||null)==null}"
                class="list-group-item d-flex justify-content-between">Не выбрано
            </li>
            <li
                @click="selectScript(item)"
                v-for="item in scripts"
                v-bind:class="{'bg-primary text-white':(form.selected_script_id||null)==item.id}"
                class="list-group-item d-flex justify-content-between">{{ item.command || '-' }} <small class="fw-bold">#{{
                    item.id
                }}</small>
            </li>

        </ul>

    </div>

</template>
<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            loaded_params: false,

            scripts: [],

            vat_variants: [

                {
                    value: 'none',
                    title: 'Нет',
                },
                {
                    value: 'vat0',
                    title: '0%',
                },
                {
                    value: 'vat10',
                    title: '10%',
                },
                {
                    value: 'vat18',
                    title: '18%',
                },
                {
                    value: 'vat20',
                    title: '20%',
                },
            ],
            tax_variants: [
                {
                    tax: 'osn',
                    title: 'общая',
                },
                {
                    tax: 'usn_income',
                    title: 'упрощенная (доходы)',
                },
                {
                    tax: 'usn_income_outcome',
                    title: 'упрощенная (доходы минус расходы)',
                },
                {
                    tax: 'patent',
                    title: 'патентная',
                },
                {
                    tax: 'envd',
                    title: 'единый налог на вмененный доход',
                },
                {
                    tax: 'esn',
                    title: 'единый сельскохозяйственный налог',
                },
                {
                    tax: 'self',
                    title: 'НПД',
                }
            ],
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
                can_buy_after_closing: false,
                can_use_cash: true,
                can_use_card: true,
                payment_token:null,
                need_pay_after_call: false,
                disabled_text: null,

                shop_display_type: 0,
                is_product_list: false,

                can_use_sbp: false,


                sbp: {
                    selected_sbp_bank: 'tinkoff',
                    tinkoff: {
                        terminal_key: null,
                        terminal_password: null,
                        tax: null,
                        vat: null,
                    },
                    sber: {}
                },

                max_tables: 0,
                need_table_list: false,

                need_promo_code: true,

                need_automatic_delivery_request: true,
                need_person_counter: true,
                need_bonuses_section: true,
                need_health_restrictions: true,
                need_prizes_from_wheel_of_fortune: true,
                selected_script_id: null,
            },
        }
    },
    computed: {
        bot() {
            return window.currentBot
        },
        scriptId(){
            return window.currentScript || null
        }
    },
    watch: {
        'form': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.form)
            },
            deep: true
        },
    },
    mounted() {

        this.loadWheelScriptVariants()

        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            this.loaded_params = true
        })
    },
    methods: {
        selectScript(item) {

            if (item == null) {
                this.form.selected_script_id = null
                return;
            }

            this.form.selected_script_id = null

            this.$nextTick(() => {
                this.form.selected_script_id = item.id
            })
        },

        loadWheelScriptVariants() {

            this.$store.dispatch("wheelOfFortuneLoadScriptVariants")
                .then((response) => {
                    this.scripts = response || []
                })
                .catch(err => {
                })
        },

    }
}
</script>

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

        <p class="alert alert-light mb-2">Вы можете разрешить или запретить клиентам оставлять заказы вне рабочего времени вашего сервиса.
            Если переключатель выключен, то покупки будут доступны <span v-bind:class="{'text-primary fw-bold':!form.can_buy_after_closing}">согласно графика</span>
            работы вашего заведения, а если включен - <span v-bind:class="{'text-primary fw-bold':form.can_buy_after_closing}">всегда</span>.</p>
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

</template>
<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            loaded_params: false,
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
                need_pay_after_call: false,
                disabled_text: null,
            },
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
        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            this.loaded_params = true
        })
    }
}
</script>

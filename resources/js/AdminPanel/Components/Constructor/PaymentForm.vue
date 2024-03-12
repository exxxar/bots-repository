
<template>
    <form class="payform-tinkoff"
          v-on:submit.prevent="submit"
          name="payform-tinkoff" id="payform-tinkoff">
        <input class="payform-tinkoff-row" type="hidden"
               v-model="paymentForm.terminalkey"
               name="terminalkey" >
        <input class="payform-tinkoff-row" type="hidden"
               v-model="paymentForm.frame"
               name="frame">
        <input class="payform-tinkoff-row" type="hidden"
               v-model="paymentForm.language"
               name="language">
        <input class="payform-tinkoff-row" type="hidden"
               v-model="paymentForm.receipt"
               name="receipt">
        <input class="payform-tinkoff-row" type="text"
               v-model="paymentForm.amount"
               placeholder="Сумма заказа" name="amount" required>
        <input class="payform-tinkoff-row" type="hidden"
               v-model="paymentForm.order"
               placeholder="Номер заказа" name="order">
        <input class="payform-tinkoff-row" type="text"
               v-model="paymentForm.description"
               placeholder="Описание заказа" name="description">
        <input class="payform-tinkoff-row" type="text"
               v-model="paymentForm.name"
               placeholder="ФИО плательщика" name="name">
        <input class="payform-tinkoff-row" type="email"
               v-model="paymentForm.email"
               placeholder="E-mail" name="email">
        <input class="payform-tinkoff-row" type="tel"
               v-model="paymentForm.phone"
               placeholder="Контактный телефон" name="phone">
        <input class="payform-tinkoff-row payform-tinkoff-btn" type="submit" value="Оплатить">
    </form>

</template>
<script>
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            bot: null,
            paymentForm: {
                terminalkey: "TinkoffBankTest",
                frame: false,
                language: "ru",
                description: null,
                amount: 0,
                order: null,
                receipt: null, //[]
                name: null,
                email: null,
                phone: null,
            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot()

        this.paymentForm.terminalkey = import.meta.env.VITE_TINKOFF_TERMINAL_KEY

        let recaptchaScript = document.createElement('script')
        recaptchaScript.setAttribute('src', 'https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js')
        document.head.appendChild(recaptchaScript)


    },
    methods: {
        submit() {

            const TPF = document.getElementById("payform-tinkoff");

            const {description, amount, email, phone, receipt} = TPF;

            if (receipt) {
                if (!email.value && !phone.value)
                    return alert("Поле E-mail или Phone не должно быть пустым");

                TPF.receipt.value = JSON.stringify({
                    "EmailCompany": "cashman.2024@mail.ru",
                    "Taxation": "patent",
                    "Items": [
                        {
                            "Name": description.value || "Оплата",
                            "Price": amount.value + '00',
                            "Quantity": 1.00,
                            "Amount": amount.value + '00',
                            "PaymentMethod": "full_prepayment",
                            "PaymentObject": "service",
                            "Tax": "none"
                        }
                    ]
                });
            }
            pay(TPF);
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },


    }
}
</script>
<style lang="scss">
.payform-tinkoff {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 2px auto;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    max-width: 250px;
}

.payform-tinkoff-row {
    margin: 2px;
    border-radius: 4px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    -webkit-transition: 0.3s;
    -o-transition: 0.3s;
    transition: 0.3s;
    border: 1px solid #DFE3F3;
    padding: 15px;
    outline: none;
    background-color: #DFE3F3;
    font-size: 15px;
}

.payform-tinkoff-row:focus {
    background-color: #FFFFFF;
    border: 1px solid #616871;
    border-radius: 4px;
}

.payform-tinkoff-btn {
    background-color: #FBC520;
    border: 1px solid #FBC520;
    color: #3C2C0B;
}

.payform-tinkoff-btn:hover {
    background-color: #FAB619;
    border: 1px solid #FAB619;
}
</style>

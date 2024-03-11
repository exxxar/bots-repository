<html lang="ru">
<head>

    <script defer src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

<style>.tinkoffPayRow {
        display: block;
        margin: 1%;
        width: 160px;
    }</style>




<div id="tinkoffWidgetContainer">

<form name="TinkoffPayForm">

    <input class="tinkoffPayRow" type="hidden" name="terminalkey" value=" "/>

    <input class="tinkoffPayRow" type="hidden" name="language" value="ru"/>

    <input class="tinkoffPayRow" type="text" placeholder="Сумма заказа" name="amount" value="111" required min="10.00"/>

    <input class="tinkoffPayRow" type="text" placeholder="Номер заказа" name="order"/>

    <input class="tinkoffPayRow" type="text" placeholder="Описание заказа" name="description"/>

    <input class="tinkoffPayRow" type="text" placeholder="ФИО плательщика" name="name"/>

    <input class="tinkoffPayRow" type="text" placeholder="E-mail" name="email"/>

    <input class="tinkoffPayRow" type="text" placeholder="Контактный телефон" name="phone"/>

    <input class="tinkoffPayRow" type="hidden" name="receipt" value="">

</form>

</div>

<script type="text/javascript">

    const terminalkey = document.forms.TinkoffPayForm.terminalkey;

    // Параметры для оплаты одного товара

    const widgetParameters = {

        container: "tinkoffWidgetContainer",

        terminalKey: terminalkey.value,

        paymentSystems: {

            TinkoffFps: {

                paymentInfo: function () {

                    return {

                        paymentData: document.forms.TinkoffPayForm,

                    };

                },

            },

        },

    };

    window.addEventListener("load", function () {

        initPayments(widgetParameters);

    });

    function tinkoffPayFunction(target) {

        let form = target.parentElement;

        let name = form.description.value || "Оплата";

        let amount = form.amount.value;

        let email = form.email.value;

        let phone = form.phone.value;

        if (amount && email && phone) {

            form.receipt.value = JSON.stringify({

                "Email": email,

                "Phone": phone,

                "EmailCompany": "mail@mail.com",

                "Taxation": "patent",

                "Items": [

                    {

                        "Name": name,

                        "Price": amount + '00',

                        "Quantity": 1.00,

                        "Amount": amount + '00',

                        "PaymentMethod": "full_prepayment",

                        "PaymentObject": "service",

                        "Tax": "none"

                    }

                ]

            });

            pay(form);

        } else alert("Не все обязательные поля заполнены")

        return false;

    }

</script>

</body>
</html>

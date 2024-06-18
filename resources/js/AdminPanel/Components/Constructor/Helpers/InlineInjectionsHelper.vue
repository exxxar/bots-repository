<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fa-brands fa-hire-a-helper"></i>
        </button>

        <div
            class="dropdown-menu item-with-text cursor-pointer text-muted p-2">

            <li><a class="dropdown-item" @click="expressionsSelect(item)"
                   v-for="item in expressions">{{item.description || '-'}}</a>
            </li>

        </div>
    </div>
</template>
<script>
export default {
    props: ["param"],
    data(){
        return {
            expressions:[
                {
                    expression:"{{userName}}",
                    description:'Имя пользователя'
                },

                {
                    expression:"{{level_1_percent}}",
                    description:'Процент кэшбэка 1 уровня'
                },

                {
                    expression:"{{level_2_percent}}",
                    description:'Процент кэшбэка 2 уровня'
                },

                {
                    expression:"{{level_3_percent}}",
                    description:'Процент кэшбэка 3 уровня'
                },

                {
                    expression:"{{cashback_fire_percent}}",
                    description:'Процент сжигания кэшбэка'
                },

                {
                    expression:"{{is_admin}}",
                    description:'Статус администратора'
                },

                {
                    expression:"{{is_work}}",
                    description:'Статус работника'
                },

                {
                    expression:"{{is_vip}}",
                    description:'Статус VIP'
                },

                {
                    expression:"{{is_manager}}",
                    description:'Статус менеджера'
                },

                {
                    expression:"{{is_deliveryman}}",
                    description:'Статус доставщика'
                },

                {
                    expression:"{{sex}}",
                    description:'Пол клиента'
                },
                {
                    expression:"{{cashback}}",
                    description:'Сумма КэшБэк пользователя'
                },
                {
                    expression:'{{telegramChatId}}',
                    description:'Идентификатор чата'
                },
                {
                    expression:'{{referralLink}}',
                    description:'Реферальная ссылка'
                },
                {
                    expression:'{{referralQr}}',
                    description:'Реферальный QR-код'
                },

            ]
        }
    },

    methods: {
        expressionsSelect(item) {
            this.$emit("callback", {
                param: this.param,
                text: item.expression
            })
        },
    }
}
</script>
<style lang="scss">
.item-with-text {
    max-height: 300px;
    max-width: 500px;
    min-width: 500px !important;
    overflow-y: auto;
}
</style>

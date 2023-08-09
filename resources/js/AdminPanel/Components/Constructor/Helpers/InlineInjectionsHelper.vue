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

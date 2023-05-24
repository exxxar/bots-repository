<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fa-regular fa-registered"></i>
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
                    expression:null,
                    description:'Нет проверки'
                },
                {
                    expression:'/^[a-z0-9]+$/i',
                    description:'Проверка набора из латинских букв и цифр'
                },
                {
                    expression:'/^[а-яё0-9]+$/iu',
                    description:'Проверка на кириллицу и цифры'
                },
                {
                    expression: '/^\d+$/',
                    description:'Проверка на число'
                },
                {
                    expression: '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/',
                    description:'Проверка Email'
                },
                {
                    expression: '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',
                    description:'Проверка номера телефона: +7(982)000-00-00'
                },
                {
                    expression: '/^(0[1-9]|[12][0-9]|3[01])[\.](0[1-9]|1[012])[\.](19|20)\d\d$/',
                    description:'Проверка даты по формату DD.MM.YYYY'
                },
                {
                    expression: '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/',
                    description:'Проверка даты по формату YYYY-MM-DD'
                },
                {
                    expression:  '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                    description:'Проверка доменного имени'
                }
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

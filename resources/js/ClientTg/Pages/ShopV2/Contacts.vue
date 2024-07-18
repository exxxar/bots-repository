<template>

    <div class="container py-3" v-if="bot">
        <div
            v-if="settings.yandex_map_link"
            class="d-flex" style="min-height:300px;">

            <div style="position:relative;overflow:hidden;">
                <iframe
                    :src="settings.yandex_map_link"
                    width="560" height="400" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe>
            </div>
        </div>
        <div class="alert alert-danger" role="alert" v-else>
            Вы не указали ссылку на карту а расположением вашего заведения
        </div>

    <h6 class="my-3" >{{bot.company.address||'Адрес вашего заведения'}}</h6>
    <h6 class="opacity-75 mb-3">Контактная информация</h6>

    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Телефон</span>
            <span class="text-primary fw-bold">7(949)000-00-00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Инста</span>
            <span class="text-primary fw-bold">@test_insta_profile</span>
        </li>
        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Вконтакте</span>
            <span class="text-primary fw-bold">https://vk.com/test_vk_public</span>
        </li>
        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Почта</span>
            <span class="text-primary fw-bold">test@gmail.com</span>
        </li>

    </ul>

    <h6 class="opacity-75 my-3">Прием заказов осуществляется</h6>
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Понедельник</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Вторник</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Среда</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Четверг</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Пятница</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Суббота</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>

        <li class="list-group-item d-flex justify-content-between"
            aria-current="true">
            <span>Воскресенье</span>
            <span class="text-primary fw-bold">10:00 - 21:59</span>
        </li>
    </ul>
    </div>
</template>
<script>

export default {
    data() {
        return {
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
                yandex_map_link: null,
            },

        }
    },
    computed:{
        tg() {
            return window.Telegram.WebApp;
        },
        bot(){
            return window.currentBot
        },
    },
    mounted() {
        this.loadShopModuleData()

        this.tg.BackButton.hide()
    },
    methods:{
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
                    })
                })
            })
        },

    }
}
</script>

<template>
    <div class="container g-2 my-3">
        <h6 class="opacity-75 mb-3">Доступные сервисы</h6>

        <button type="button"
                @click="goTo('ProfileV2')"
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-solid fa-user-tie mr-2"></i>
            Профиль
        </button>
        <button type="button"
                @click="goTo('CatalogV2')"
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-brands fa-shopify mr-2 "></i>
            Магазин
        </button>
        <button type="button"
                @click="goTo('ShopCartV2')"
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-solid fa-basket-shopping mr-2"></i>
            Корзина
        </button>
        <button type="button"
                disabled
                @click="goTo('OrdersV2')"
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-solid fa-clock-rotate-left mr-2"></i>
            История заказов
        </button>
        <button type="button"
                @click="goTo('CatalogV2')"
                disabled
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-solid fa-clover mr-2"></i>Колесо фортуны
        </button>
        <button type="button"
                disabled
                @click="goTo('ContactsV2')"
                class="btn btn-outline-primary w-100 p-3 mb-2">
            <i class="fa-regular fa-circle-question mr-2"></i>
            О нас
        </button>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {}
    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
        bot() {
            return window.currentBot
        },
    },
    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            if (this.$route.name === "MenuV2")
                this.tg.close()
            else
                this.$router.back()
        })
    },
    methods: {
        goTo(name) {
            this.$router.push({name: name})
        }
    }
}
</script>

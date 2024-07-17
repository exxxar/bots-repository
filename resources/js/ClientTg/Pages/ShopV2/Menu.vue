<template>
    <div class="container g-2 my-3">
        <h6 class="opacity-75 mb-3">Доступные сервисы</h6>

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
            <div class="col">
                <button type="button"
                        @click="goTo('ProfileV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/profile.png'" class="img-fluid" alt="">

                        <p class="my-2">Профиль</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('CatalogV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/shop.png'" class="img-fluid" alt="">

                        <p class="my-2">Магазин</p>
                    </div>

                </button>
            </div>

            <div class="col">

                <button type="button"
                        @click="goTo('ShopCartV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/cart.png'" class="img-fluid" alt="">

                        <p class="my-2">Корзина
                            <span class="badge bg-primary" v-if="cartTotalCount>0   ">{{ cartTotalCount }}</span>
                        </p>
                    </div>

                </button>

            </div>

            <div class="col">

                <button type="button"
                        @click="goTo('OrdersV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/history.png'" class="img-fluid" alt="">

                        <p class="my-2"> История заказов</p>
                    </div>

                </button>

            </div>

<!--            <div class="col">

                <button type="button"

                        @click="goTo('WheelOfFortuneV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/gift.png'" class="img-fluid" alt="">

                        <p class="my-2"> Колесо фортуны</p>
                    </div>

                </button>


            </div>-->

<!--            <div class="col">

                <button type="button"
                        @click="goTo('ContactsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/contacts.png'" class="img-fluid" alt="">

                        <p class="my-2">Наши контакты</p>
                    </div>

                </button>

            </div>-->
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {}
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),


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

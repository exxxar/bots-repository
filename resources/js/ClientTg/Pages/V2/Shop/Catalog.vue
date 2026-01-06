<script setup>
import ShopProductCatalog from "@/ClientTg/Components/V2/Shop/ShopProductCatalog.vue";
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";
</script>
<template>


    <ShopProductCatalog
        v-if="settings"
        :settings="settings">
        <template #navigation>
            <nav
                class="navbar navbar-expand-sm fixed-bottom p-2 bg-transparent border-0 "
                style="border-radius:10px 10px 0px 0px;z-index:999!important;">
                <div class="btn-group w-100" role="group" aria-label="Basic example">
                    <button type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu"
                            class="btn btn-primary p-2 d-flex flex-column justify-content-center">
                        <i class="fa-solid fa-bars"></i>
                        <span style="font-size:10px;">Меню</span>
                    </button>
                    <button
                        @click="goToBooking"
                        type="button" class="btn btn-primary p-2 d-flex flex-column justify-content-center">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span style="font-size:10px;">Бронь</span>
                    </button>
                    <button
                        @click="goToCart"
                        type="button" class="btn btn-primary p-2 d-flex flex-column justify-content-center">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span style="font-size:10px;">Корзина</span>
                        <span class="cart-marker" v-if="cartTotalCount>0">
                            {{ cartTotalPrice || 0 }}₽
                        </span>
                    </button>
                    <button
                        @click="loadFavProducts"
                        type="button" class="btn btn-primary p-2 d-flex flex-column justify-content-center">
                        <i class="fa-solid fa-heart"></i>
                        <span style="font-size:10px;">Избранное</span>
                        <span class="cart-marker"
                              style="padding:0px 5px;right:20px;"
                              v-if="favCount>0">
                            {{ favCount }}
                        </span>

                    </button>
                </div>

            </nav>
            <!--            <nav

                            class="navbar navbar-expand-sm fixed-bottom p-2 bg-transparent border-0"
                            style="border-radius:10px 10px 0px 0px;z-index:999!important;">
                            <button
                                v-if="canBay"
                                @click="goToCart"
                                style="box-shadow: 1px 1px 6px 0px #0000004a;"
                                class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-between ">

                        <span class="d-block" style="position:relative;"><i class="fa-solid fa-cart-shopping mr-2">
                        </i><sup class="bg-white text-primary sup-badge" v-if="cartTotalCount>0">{{ cartTotalCount }}</sup>Корзина </span>
                                <strong>{{ cartTotalPrice || 0 }}<sup class="font-10 opacity-50">.00</sup>₽</strong>
                            </button>
                            <p
                                v-else
                                style="box-shadow: 1px 1px 6px 0px #0000004a;"
                                data-bs-toggle="modal" data-bs-target="#schedule-list-display"
                                class="btn btn-secondary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-between "
                            >
                                В данный момент покупки недоступны
                            </p>
                        </nav>-->
        </template>
    </ShopProductCatalog>


    <!-- Modal -->
    <div class="modal fade" id="fav-product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Избранное</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div
                        v-if="favorites.length>0"
                        class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                        <div class="col"
                             v-for="(product, index) in favorites">

                            <ProductCard
                                :item="product"
                            />


                        </div>
                    </div>
                    <p class="alert alert-light" v-else>
                        Вы еще не добавили товары в избранное:(
                    </p>
                </div>

            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            settings: null,
            favorites: [],
        }
    },
    computed: {
        ...mapGetters([
            'inCart',
            'cartProducts',
            'cartTotalCount',
            'cartTotalPrice',
            'getSelf']),

        bot() {
            return window.currentBot
        },
        favCount(){
          return window.self.config.favorites?.length || 0
        },
        canBay() {
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true

            return (this.bot.company || {is_work: true}).is_work || (this.settings ? this.settings.can_buy_after_closing : true)
        },
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {

        this.tg.BackButton.show()

        if (this.bot.settings?.self_updated) {
            this.settings = this.bot.settings
        } else
            this.loadShopModuleData()

        this.tg.BackButton.onClick(() => {
            this.tg.close()
        })

        this.loadBasketData()

        if (!this.canBay) {
            const modalEl = document.querySelector('#schedule-list-display');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (modalInstance)
                modalInstance.show()
        }

    },
    methods: {
        goToBooking() {
            this.$router.push({name: 'TableBookingV2'});
        },
        goToCart() {
            this.$router.push({name: 'ShopCartV2'});
        },
        loadBasketData() {
            return this.$store.dispatch("loadProductsInBasket")
        },
        loadFavProducts() {

            const modalEl = document.querySelector('#fav-product-modal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);

            if (modalInstance)
                modalInstance.show()

            this.$store.dispatch("getFavList").then((resp) => {
                this.favorites = resp.data || []

            }).catch(() => {

            })
        },
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    if (!this.settings)
                        this.settings = {}

                    let data = resp.data
                    if (data)
                        Object.keys(data).forEach(item => {
                            if (item)
                                this.settings[item] = data[item]

                        })

                })
            })
        },

    }
}
</script>
<style lang="scss">
.cart-marker {
    position: absolute;
    top: 4px;
    right: 5px;
    /* height: 14px; */
    background: var(--bs-info);
    font-size: 10px;
    /* width: 100%; */
    padding: 0px 2px;
    border-radius: 3px;
}

</style>

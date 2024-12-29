<script setup>
import ProductCardSimple from "@/ClientTg/Components/V2/Shop/ProductCardSimple.vue";
import CollectionCardSimple from "@/ClientTg/Components/V2/Shop/CollectionCardSimple.vue";
import PromoCodeForm from "@/ClientTg/Components/V2/Shop/PromoCodeForm.vue";


</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <h4>Итого</h4>
                <p>
                    Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки
                    рассчитывается отдельно
                    и
                    зависит от расстояния.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12" v-for="(item, index) in cartProducts">
                <ProductCardSimple
                    v-if="(item.product||null)!=null"
                    :item="item.product"/>

                <CollectionCardSimple
                    v-if="(item.collection||null)!=null"
                    :params="item.params"
                    :item="item.collection"/>
            </div>
        </div>


        <template  v-if="settings.need_promo_code || false">
            <div class="form-check form-switch my-3">
                <input class="form-check-input"
                       type="checkbox"
                       v-model="display_promo_code"
                       role="switch" id="script-settings-display_promo_code">
                <label class="form-check-label" for="script-settings-display_promo_code">
                    У меня есть промокод
                </label>
            </div>

            <PromoCodeForm
                v-if="display_promo_code"
                v-on:callback="activateDiscount"></PromoCodeForm>
        </template>


        <template
            v-if="settings.need_prizes_from_wheel_of_fortune && filteredActionData.length>0">
            <h6 class="opacity-75 mb-2 mt-2 ">Выбор приза Колеса фортуны</h6>
            <Carousel v-bind="config">
                <Slide :key="'empty-prize'">
                    <div
                        @click="selectPrize(null)"
                        v-bind:class="{'border-primary':formData.action_prize==null}"
                        class="card mx-1 p-3 align-items-center" style="height:250px;border-color:#e9ecef;">
                        <img v-lazy="'/images/chest.png'" class="card-img-top  object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary">Приз не выбран</p>
                        </div>
                    </div>
                </Slide>
                <Slide v-for="slide in filteredActionData" :key="slide">
                    <div
                        @click="selectPrize(slide)"
                        v-bind:class="{'border-primary':(formData.action_prize?.prize||{description:null}).description == slide.description}"
                        class="card  mx-1 p-3 align-items-center" style="height:250px;border-color:#e9ecef;">
                        <img v-lazy="'/images/wheel.png'"
                             class="card-img-top object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary">{{ slide.description || '-' }}</p>
                        </div>
                    </div>
                </Slide>
                <template #addons>
                    <Navigation/>
                    <Pagination/>
                </template>
            </Carousel>
        </template>


        <template v-if="(settings.need_person_counter || false) && (settings.shop_display_type === 0)">
            <h6 class="opacity-75 mb-3 mt-2">Число персон</h6>
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-4">
                            <button
                                @click="decPersons"
                                type="button" class="btn p-2 w-100 btn-light text-dark"><i
                                class="fa-solid fa-minus font-22"></i></button>
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <strong
                                class="fw-bold"
                                style="font-size:16px;">{{ formData.persons }}</strong>
                        </div>

                        <div class="col-4">
                            <button type="button"
                                    @click="incPersons"
                                    class="btn p-2 w-100 btn-light text-dark"><i
                                class="fa-solid fa-plus font-22"></i></button>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <div class="card my-3">
            <div class="card-body">
                <h6>Товаров в корзине <strong class="fw-bold">{{ cartTotalCount }} ед.</strong></h6>
                <h6>Общая цена товаров <strong class="fw-bold">{{
                        cartTotalPrice - formData.promo.discount
                    }}₽</strong>
                    <strong
                        v-if="(formData.promo.discount||0)>0"
                        class="text-success fw-bold"> (-{{ formData.promo.discount }}₽)</strong>
                </h6>

                <template v-if="(settings.need_person_counter || false) && (settings.shop_display_type === 0)">
                    <h6>Приборы на <strong class="fw-bold">{{ formData.persons }} чел.</strong></h6>
                </template>

                <p class="fst-italic">
                    <span class="fw-bold text-primary">Внимание!</span> Скидка за промокод доступна только если
                    сумма заказа больше чем
                    <span class="fw-bold text-primary">{{ formData.promo.activate_price }}₽</span>
                </p>
                <h6 v-if="formData.promo.discount>0">Скидка за промокод <strong
                    class="fw-bold">{{ formData.promo.discount }} ₽</strong>

                </h6>
            </div>
        </div>
        <button
            v-if="cartTotalCount>0"
            @click="clearCart"
            class="btn btn-outline-primary p-3 w-100">
            <i class="fa-solid  fa-trash-can mr-2"></i><span class="color-white">Очистить корзину</span>
        </button>

    </div>
</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    props: ["settings", "formData"],
    components: {
        Carousel, Slide, Pagination, Navigation
    },
    computed: {
        ...mapGetters(['cartProducts', 'cartTotalCount', 'cartTotalPrice']),
        filteredActionData() {
            if (!this.action)
                return []
            return this.action?.data.filter(item => !item.taked_at)
        }
    },
    data() {
        return {
            config: {
                itemsToShow: 1.5
            },
            display_promo_code:false,
            action: null
        }
    },
    mounted() {

        this.loadClientNotUsedPrizes()

    },
    methods: {
        selectPrize(item) {

            if (item == null) {
                this.$emit("select-prize", null)
                return
            }

            this.$emit("select-prize", {
                prize: item,
                action_id: this.action.id
            })
        },
        loadClientNotUsedPrizes() {
            this.$store.dispatch("getClientNotUsedPrizesFromWheelOfFortune", {
                slug_id: this.settings.selected_script_id
            }).then((resp) => {
                this.action = resp.action
            })

        },
        activateDiscount(item) {
            this.$emit("discount", item)
        },
        decPersons() {
            this.$emit("person-dec")
        },
        incPersons() {
            this.$emit("person-inc")
        },
        clearCart() {
            this.$emit("change-tab", 1)
            this.$store.dispatch("clearCart").then(() => {
                this.$notify({
                    title: "Корзина",
                    text: "Корзина успешно очищена",
                    type: "success"
                })
            })

        },
    }
}
</script>

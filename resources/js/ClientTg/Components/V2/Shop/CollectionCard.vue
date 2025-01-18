<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
import ProductCollectionView from "@/ClientTg/Components/V2/Shop/ProductCollectionView.vue";
import CollectionCardSimple from "@/ClientTg/Components/V2/Shop/CollectionCardSimple.vue";
</script>
<template v-if="item">

    <div class="card  border-0 product-card">
        <div
            @click="showCollectionDetails"
            class="img-container">
            <img
                v-if="item.image"
                class="rounded-3"
                v-lazy="'/images-by-company-id/'+bot.company.id+'/'+item.image" alt="">

            <div class="controls">
                <div class="top d-flex justify-content-end w-100 align-items-center">
                    <div class="product-count w-100 p-2">
                        <span class="text-white fw-bold">
                            <i class="fa-solid fa-layer-group mr-1"></i> {{ (item.products || []).length }}</span>
                    </div>
                    <span
                        v-if="item.discount>0"
                        class="badge bg-primary mr-2 fw-bold">%</span>
                </div>

            </div>
        </div>

        <div class="card-body px-0">
            <p class="text-center mb-2" style="font-size: 12px;">{{ item.title.slice(0, 50) }} <span
                v-if="item.title.length>50">...</span></p>


            <div
                class="d-flex justify-content-between align-items-center px-2">

                <button type="button"
                        v-if="checkInCart===0"
                        v-bind:class="{'btn-secondary':!canProductAction}"
                        :disabled="item.in_stop_list_at!=null|| !canProductAction"
                        @click="incCollectionCart"
                        style="font-size:12px;"
                        class="btn btn-md btn-light w-100 rounded-3">
                    <template v-if="item.discount>0">
                        {{ discountPrice }}₽ <span style="text-decoration:line-through;">{{ currentPrice }}₽</span>
                    </template>
                    <template v-else>
                        {{ currentPrice }}₽
                    </template>
                </button>

                <button type="button"
                        v-else
                        @click="openVariantList"
                        v-bind:class="{'btn-secondary':!canProductAction}"
                        style="font-size:12px;"
                        class="btn btn-md btn-success w-100 rounded-3">
                    Все варианты <span class="badge bg-primary">{{cartCollections.length}}</span>
                </button>

<!--                <div class="btn-group w-100 rounded-3" v-if="checkInCart>0">
                    <button type="button"
                            @click="decCollectionCart"
                            style="border-radius:6px 0px 0px 6px;"
                            class="btn btn-sm btn-primary">-
                    </button>
                    <button type="button" class="btn btn-sm btn-primary ">{{ checkInCart }}</button>
                    <button type="button"
                            @click="incCollectionCart"
                            style="border-radius:0px 6px 6px 0px;"
                            class="btn btn-sm btn-primary">+
                    </button>
                </div>-->
            </div>


        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'collection-variant-modal-'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Варианты в корзине</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="row" v-if="cartCollections.length>0">
                        <div class="col-12" v-for="(item, index) in cartCollections">
                            <CollectionCardSimple
                                :params="item.params"
                                :item="item.collection"/>
                        </div>
                    </div>
                    <p v-else class="alert alert-light">
                        Ни одной коллекции не было добавлено в корзину!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'collection-modal-info'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Подборка</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-5 pb-5">
                    <ProductCollectionView
                        v-if="item"
                        :item="item"></ProductCollectionView>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["item"],
    data() {
        return {
            tab: 0,
            selected_image: null,
            loading_reviews: false,
            reviews: [],
            paginate: null,
            sending:false,
            is_online:true,
        }
    },
    computed: {
        ...mapGetters(['inCollectionCart','cartCollections']),
        uuid() {
            const data = uuidv4();
            return data
        },
        discountPrice() {
            return Math.round(this.currentPrice * (1 - ((this.item.discount === 0 ? 1 : this.item.discount) / 100)))
        },
        bot() {
            return window.currentBot
        },
        checkInCart() {
            return this.inCollectionCart(this.item.id, null)
        },
        currentPrice() {
            let price = 0

            this.item.products.forEach(item => {
                if (item.is_checked)
                    price += item.current_price || 0
            })

            return price
        },
        canProductAction(){
            return this.is_online && !this.sending
        }
    },
    mounted() {
        this.selected_image = (this.item.images || []).length > 0 ? this.item.images[0] : null

        window.addEventListener('online', () => {
            this.is_online = true
        });
        window.addEventListener('offline', () => {
            this.is_online = false
        });
    },
    methods: {
        openVariantList(){
            const myModal = new bootstrap.Modal(document.getElementById('collection-variant-modal-' + this.item.id), {})
            myModal.show()
        },
        showCollectionDetails() {
            const myModal = new bootstrap.Modal(document.getElementById('collection-modal-info' + this.item.id), {})
            myModal.show()
        },

        incCollectionCart() {
            this.item.current_price = this.discountPrice

            this.sending = true
            let incResult = this.checkInCart === 0 ?
                this.$store.dispatch("addCollectionToCart", this.item):
                this.$store.dispatch("incQuantity", this.item.id)

            incResult.then(() => {
                this.sending = false
                this.$notify({
                    title: "Добавление товара",
                    text: 'Товар успешно добавлен',
                    type: 'success'
                })
            }).catch(() => {
                this.sending = false
                this.$notify({
                    title: "Добавление товара",
                    text: 'Ошибка добавления товара!',
                    type: 'error'
                })
            })
        },
        decCollectionCart() {
            this.sending = true
            let decResult = this.checkInCart <= 1 ?
                this.$store.dispatch("removeCollectionFromCart", this.item.id):
                this.$store.dispatch("decQuantity", this.item.id)

            decResult.then(() => {
                this.sending = false
                this.$notify({
                    title: "Удаление товара",
                    text: 'Товар успешно удален',
                    type: 'success'
                })
            }).catch(() => {
                this.sending = false
                this.$notify({
                    title: "Удаление товара",
                    text: 'Ошибка удаления товара!',
                    type: 'error'
                })
            })
        },

    }
}
</script>
<style lang="scss">
.in-cart-count {
    padding: 4px;
    display: block;
    position: absolute;
    top: -10px;
    right: -10px;
    background: red;
    color: white;
    border-radius: 50%;
    width: 26px;
    height: 26px;
}

.product-card {
    min-height: 290px;

    img {
        object-fit: cover;
        /* height: 100%; */
        width: 100%;
        max-height: 190px;
        height: 190px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 7px;

        .card-text {
            text-align: center;
            font-weight: 900;
        }
    }
}

.shadow-bg {
    background: #0000005e;
    padding: 5px;
}

.img-container {
    position: relative;
    display: block;

    img {
        position: relative;
        z-index: 1;
    }

    .controls {
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
}

.product-count {
    span {
        background: #00000069;
        padding: 5px 6px;
        border-radius: 5px;
        font-size: 10px;
    }
}
</style>

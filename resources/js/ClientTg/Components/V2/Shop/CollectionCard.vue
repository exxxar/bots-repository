<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
import ProductCollectionView from "@/ClientTg/Components/V2/Shop/ProductCollectionView.vue";
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
                            <i class="fa-solid fa-layer-group mr-1"></i> {{ (item.products||[]).length}}</span>
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
                            v-if="inCart(item.id)===0"
                            @click="incCollectionCart"
                            style="font-size:12px;"
                            class="btn btn-md btn-light w-100 rounded-3">
                        <template v-if="item.discount>0">
                            {{ discountPrice }}₽ <span style="text-decoration:line-through;">{{ summaryPrice }}₽</span>
                        </template>
                        <template v-else>
                            {{ summaryPrice }}₽
                        </template>
                    </button>

                    <div class="btn-group w-100 rounded-3" v-if="inCart(item.id)>0">
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                @click="decCollectionCart"
                                style="border-radius:6px 0px 0px 6px;"
                                class="btn btn-sm btn-primary">-
                        </button>
                        <button type="button" class="btn btn-sm btn-primary ">{{ checkInCart }}</button>
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                @click="incCollectionCart"
                                style="border-radius:0px 6px 6px 0px;"
                                class="btn btn-sm btn-primary">+
                        </button>
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
            showCart: false,
            reviews: [],
            paginate: null,
        }
    },
    computed: {
        ...mapGetters(['inCart']),
        uuid() {
            const data = uuidv4();
            return data
        },
        discountPrice() {
            return Math.round(this.summaryPrice * (1 - ((this.item.discount === 0 ? 1 : this.item.discount) / 100)))
        },
        bot() {
            return window.currentBot
        },
        summaryPrice() {
            let sum = 0
            this.item.products.forEach(product => {
                if (product.is_checked)
                    sum += product.current_price || 0
            })

            return sum
        },
        checkInCart() {
            return this.inCart(this.item.id)
        },

    },
    mounted() {
        this.item.collection_id = this.item.id
        this.item.id = this.uuid

        this.selected_image = (this.item.images || []).length > 0 ? this.item.images[0] : null
    },
    methods: {

        showCollectionDetails() {
            const myModal = new bootstrap.Modal(document.getElementById('collection-modal-info' + this.item.id), {})
            myModal.show()
        },

        incCollectionCart() {
            this.item.current_price = this.discountPrice

            if (this.checkInCart === 0)
                this.$store.dispatch("addCollectionToCart", this.item)
            else
                this.$store.dispatch("incQuantity", this.item.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно добавлен',
                type: 'success'
            })
        },
        decCollectionCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeCollectionFromCart", this.item.id)
            else
                this.$store.dispatch("decQuantity", this.item.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно удален',
                type: 'success'
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

<script setup>
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";

</script>
<template>

    <template v-if="product">
        <img
            v-if="item.image"
            class="card-img-top"
            v-lazy="'/images-by-company-id/'+bot.company.id+'/'+product.image" alt="">


        <h5 class="card-title my-3 fw-bold">{{ product.title || '-' }}</h5>
        <p class="card-text">{{ product.description || '-' }}</p>
        <p class="card-text fw-bold text-center p-3 border-primary border text-primary">

            <template v-if="product.discount>0">
                {{ discountPrice }}₽ <span style="text-decoration:line-through;">{{ summaryPrice }}₽</span>
            </template>
            <template v-else>
                {{ summaryPrice }}₽
            </template>
            <small
                class="w-100 d-block text-center"
                v-if="product.products.length>0">За {{ product.products.length }} товаров</small>
        </p>


        <template
            v-if="preparedProducts.length>0"
            v-for="cat in preparedProducts">
            <h5 class="my-4 divider" :id="'collection-cat-'+cat.id">{{ cat.title || '-' }}</h5>

            <p v-if="cat.products.length>1" class="alert alert-light mb-2">
                В данной категории вы можете выбрать из доступных вариантов
            </p>
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                <div
                    class="col"
                    v-for="(product, index) in cat.products">

                    <ProductCard
                        :can-select="!((product.config||{choose_all_in_category:false}).choose_all_in_category)"
                        :collection-selected="product.is_checked"
                        :collection-mode="true"
                        :item="product"
                        v-on:select-in-collection="selectSubProduct"
                    />
                </div>

                <div
                    v-if="(product.config||{can_skip_categories:false}).can_skip_categories"
                    class="col">
                    <div
                        @click="cancelCategory(cat)"
                        class="card  border-0 product-card">
                        <div
                            class="img-container">
                            <img
                                class="rounded-3"
                                v-lazy="'/images/default-product-image.jpg'">
                        </div>

                        <div class="card-body px-0">
                            <p class="text-center mb-2" style="font-size: 12px;">Данная категория не интересует</p>

                            <div
                                class="d-flex justify-content-between align-items-center px-2">
                                <button type="button"
                                        style="font-size:12px;"
                                        class="btn btn-md w-100 rounded-2 mb-2 btn-outline-light text-black">
                                    Выбрать
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </template>

        <!--       <div
                    v-if="(product.products||[]).length>0"
                    class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                    <div class="col"
                         v-for="(product, index) in  (product.products||[])">

                        <ProductCard
                            :item="product"
                        />

                    </div>

                </div>-->

        <nav
            class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
            style="border-radius:10px 10px 0px 0px;">

            <div class="d-flex w-100 flex-column">

                <button type="button"
                        :disabled="summaryPrice===0"
                        v-if="inCart(product.id)===0"
                        @click="addCollectionToCart"
                        class="btn btn-sm btn-primary w-100 rounded-3 p-3">
                    Добавить
                    <template v-if="product.discount>0">
                        {{ discountPrice }}₽ <span style="text-decoration:line-through;">{{ summaryPrice }}₽</span>
                    </template>
                    <template v-else>
                        {{ summaryPrice }}₽
                    </template>
                </button>

                <div class="btn-group w-100" v-if="inCart(product.id)>0">
                    <button type="button"
                            :disabled="product.in_stop_list_at"
                            @click="decCollectionCart"
                            style="border-radius:8px 0 0 8px;"
                            class="btn btn-sm btn-primary p-3">-
                    </button>
                    <button type="button" class="btn btn-sm btn-primary">{{ checkInCart }}</button>
                    <button type="button"
                            :disabled="product.in_stop_list_at"
                            @click="incCollectionCart"
                            style="border-radius:0 8px 8px 0;"
                            class="btn btn-sm  btn-primary p-3">+
                    </button>
                </div>
            </div>
        </nav>
    </template>

</template>
<script>
import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["item"],
    data() {
        return {
            product:null,
        }
    },
    computed: {
        ...mapGetters(['inCart']),
        uuid() {
            const data = uuidv4();
            return data
        },
        checkInCart() {
            return this.inCart(this.product.id)
        },
        bot() {
            return window.currentBot
        },
        discountPrice() {
            return Math.round(this.summaryPrice * (1 - ((this.product.discount === 0 ? 1 : this.product.discount) / 100)))
        },
        summaryPrice() {
            let sum = 0
            this.product.products.forEach(product => {
                if (product.is_checked)
                    sum += product.current_price || 0
            })

            return sum
        },
        preparedProducts() {
            let categories = []

            let hasCategory = false
            this.product.products.forEach(product => {

                if ((product.categories || []).length > 0) {
                    let findIndex = categories.findIndex(item => item.title == product.categories[0].title)

                    if (findIndex === -1) {

                        categories.push({
                            id: product.categories[0].id,
                            title: product.categories[0].title,
                            products: [product]
                        })
                    } else
                        categories[findIndex].products.push(product)

                    hasCategory = true
                }
            })

            if (!hasCategory) {
                categories.push({
                    id: this.uuid,
                    title: "Без категории",
                    products: []
                })

                this.product.products.forEach(product => {
                    categories[0].products.push(product)
                });
            }


            categories.forEach(cat => {
                let index = 0
                cat.products.forEach(product => {
                    let config = this.product.config ? this.product.config : {
                        choose_all_in_category: false
                    }
                    product.is_checked = (config.choose_all_in_category || false) ? true : index === 0
                    index++
                })
            })

            return categories
        }
    },
    mounted() {

        this.$nextTick(()=>{
            const tmp = JSON.parse(JSON.stringify(this.item))
            this.product = tmp
            this.product.collection_id = tmp.id
            this.product.id = this.uuid
        })

    },
    methods: {
        incCollectionCart() {
            if (this.checkInCart === 0)
                this.addCollectionToCart()
            else
                this.$store.dispatch("incQuantity", this.product.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно добавлен',
                type: 'success'
            })
        },
        decCollectionCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeCollectionFromCart", this.product.id)
            else
                this.$store.dispatch("decQuantity", this.product.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно удален',
                type: 'success'
            })
        },
        addCollectionToCart() {


            this.$store.dispatch("addCollectionToCart", this.item).then(() => {
                this.$notify({
                    title: "Добавление товара",
                    text: 'Товар успешно добавлен',
                    type: 'success'
                })
            }).catch(() => {

            })
        },

        cancelCategory(cat) {
            let currentCategoryId = cat.id

            this.product.products.forEach(product => {
                if (product.categories[0].id === currentCategoryId) {
                    product.is_checked = false
                    this.$store.dispatch("removeProduct", product.id)
                }
            })

            if (this.checkInCart > 0)
                this.$store.dispatch("removeCollectionFromCart", this.product.id)

            this.$notify({
                title: "Подборки товара",
                text: 'Вы успешно исключили категорию товара из подборки',
                type: 'success'
            })
        },
        selectSubProduct(product) {
            let currentIndex = this.product.products.findIndex(item => product.id === product.id)
            let currentCategoryId = product.categories[0].id

            this.product.products.forEach(product => {
                if (product.categories[0].id === currentCategoryId) {
                    product.is_checked = false
                    this.$store.dispatch("removeProduct", product.id)
                }
            })

            this.product.products[currentIndex].is_checked = true

            if (this.checkInCart > 0)
                this.$store.dispatch("removeCollectionFromCart", this.product.id)
        }
    },

}
</script>

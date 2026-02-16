<script setup>
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>
<template>

    <div class="form-floating mb-2">

        <input type="number"
               v-model="extra_charge"
               class="form-control" placeholder="Поиск страницы">
        <label for="">Наценка, %</label>
    </div>

    <div class="form-check form-switch mb-2">
        <input class="form-check-input"
               v-model="need_product_config"
               type="checkbox" role="switch" id="need_product_config">
        <label class="form-check-label"
               for="need_product_config">Режим настройки отображения товаров</label>
    </div>

    <template v-if="partner&&categories.length>0">
        <div class="card mb-2" v-for="category in categories">
            <div class="card-header">
                {{category.title}}
            </div>
            <div class="card-body">
                <template v-if="category.products.length>0">
                    <ul class="list-group list-group-flush" >
                        <li
                            v-bind:class="{'bg-exclude':excludes.indexOf(product.id)!==-1}"
                            class="list-group-item"

                            v-for="(product, index) in category.products">

                            <p class="mb-0 d-flex justify-content-between align-items-center"> {{ product.title }}
                                <span class="badge bg-primary" v-if="extra_charge===0">{{ product.current_price || 0 }}₽</span>
                                <span class="badge bg-info" v-else>{{
                                        parseFloat(product.current_price) + parseFloat((product.current_price * extra_charge / 100).toFixed(2))
                                    }}₽</span>
                            </p>


                            <div class="row row-cols-1" v-if="need_product_config">
                                <div class="col">
                                    <div class="d-flex mt-2 mb-2 justify-content-center">
                                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                                            <input type="radio"
                                                   @change="changeStatus(product.id, 0)"
                                                   :checked="excludes.indexOf(product.id)===-1"
                                                   class="btn-check"
                                                   :name="'config-partner-product-'+product.id"
                                                   :id="'config-partner-product1-'+product.id" autocomplete="off">
                                            <label class="btn btn-outline-primary"
                                                   :for="'config-partner-product1-'+product.id">Отображать</label>

                                            <input type="radio"
                                                   @change="changeStatus(product.id, 1)"
                                                   :checked="excludes.indexOf(product.id)!==-1"
                                                   class="btn-check"
                                                   :name="'config-partner-product-'+product.id"
                                                   :id="'config-partner-product2-'+product.id" autocomplete="off">
                                            <label class="btn btn-outline-primary"
                                                   :for="'config-partner-product2-'+product.id">Не отображать</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </li>
                    </ul>
                    <template v-if="category.products_count > category.products.length">
                        <button
                            @click="loadMore(category.id, category.products.length)"
                            class="btn btn-outline-light text-primary  p-3 w-100 my-3" type="button">
                                            <span v-if="!load_content">Загрузить еще
                                            ({{ category.products_count - category.products.length }})</span>
                            <span v-else class="d-inline-flex align-items-center">
                                                Загружаем....
                                                    <span class="spinner-border" role="status">
                                                      <span class="visually-hidden">Loading...</span>
                                                    </span>
                                            </span>
                        </button>
                    </template>
                </template>
                <p class="alert alert-light mb-0" v-else>
                    Товар в категории нет
                </p>

            </div>
        </div>
    </template>

    <template    v-if="categories.length===0">
        <p
            class="alert alert-info mb-2">Товары и категории еще не загрузились.. Ожидаем...</p>
        <PreloaderV1></PreloaderV1>
    </template>

</template>
<script>
export default {
    props: ["partner"],
    data() {
        return {
            load_content: false,
            categories:[],
            extra_charge: 0,
            need_product_config: false,
        }
    },
    computed: {
        excludes() {
            return this.partner.config?.excludes || []
        }
    },
    mounted() {
        this.extra_charge = this.partner.extra_charge || 0
        this.loadProducts()
    },
    methods: {
        loadMore(catId, offset) {
            this.load_content = true
            return this.$store.dispatch("loadMoreProductsByCategory", {
                partner_id: this.partner?.bot_partner_id || null,
                category_id: catId,
                offset: offset,
            }).then((resp) => {

                let count = resp.length || 0
                this.load_content = false
                if (count === 0)
                {
                    this.categories.find(p => p.id === catId).products_count = offset
                    return
                }

                this.categories.find(p => p.id === catId).products.push(...resp)
            }).catch(() => {
                this.load_content = false
            })
        },
        loadProducts(page = 0) {
            this.load_content = false
            return this.$store.dispatch("loadProductsByCategory", {
                partner_id: this.partner.bot_partner_id || null,
            }).then((resp) => {

                this.load_content = true

                this.$nextTick(() => {
                    this.categories = resp.data
                    this.load_content = false

                })


            }).catch(() => {
                this.load_content = false

                this.$store.dispatch("clearCart").then(() => {
                    this.loadProducts()
                })
            })
        },
        changeStatus(productId, status) {
            let excludes = this.partner.config?.excludes || []

            let index = excludes.indexOf(productId)
            if (index === -1)
                excludes.push(productId)
            else
                excludes.splice(index, 1)

            this.partner.config.excludes = excludes

            this.$store.dispatch("changePartnerProductStatus", {
                product_id: productId,
                partner_id: this.partner.id,
                status: status
            }).then((resp) => {


            }).catch(() => {

            })
        },
    }
}
</script>
<style scoped>
.bg-exclude {
    background-color: #ff961d;
}
</style>

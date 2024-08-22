<template>
    <ul class="list-group">
        <li
            v-for="item in menus"
            @click="selectMenu(item)"
            v-bind:class="{'active':(this.selected_menu||{id:null}).id == item.id}"
            class="list-group-item"><span class="badge bg-primary mr-2">#{{ item.id }}</span>{{ item.name }}
        </li>

    </ul>
    <hr
        v-if="products.length>0"
        class="mt-3 mb-0 p-0">

    <template v-for="(category, catIndex) in products">
        <h6 class="my-2">{{ category.name }}</h6>

        <ul class="list-group">
            <li
                class="list-group-item d-flex justify-content-between align-items-center"
                v-for="(item, prodIndex) in category.items"
            >
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="products[catIndex].items[prodIndex].need_add"
                           type="checkbox" value="" :id="'product-'+item.id">
                    <label class="form-check-label" :for="'product-'+item.id">
                        {{ item.name || '-' }}
                    </label>
                </div>

                <i
                    @click="selectProduct(item)"
                    class="fa-solid fa-arrow-up-right-from-square"></i>
            </li>
        </ul>


    </template>

    <button
        v-if="products.length>0"
        style="z-index: 100;bottom:10px;"
        type="button" class="btn btn-primary w-100 p-3 my-3 position-sticky">Сохранить товары
    </button>

    <!-- Modal -->
    <div class="modal fade" id="product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="selected_product">
                    <h6>{{ selected_product.name || 'Нет имени' }}</h6>
                    <p>{{ selected_product.description || 'Нет описания' }}</p>

                    <template v-for="item in selected_product.itemSizes">
                        <img v-lazy="item.buttonImageUrl"
                             class="img-fluid"
                             alt="">

                        <p class="my-2">
                            Цена <span class="fw-bold" v-for="price in item.prices">{{ price.price || 0 }} ₽</span>
                        </p>
                    </template>


                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            selected_menu: null,
            selected_product: null,
            menus: [],
            categories: [],
            products: []
        }
    },
    mounted() {
        this.getMenus()
    },
    methods: {
        selectProduct(item) {
            this.selected_product = item
            const productModal = new bootstrap.Modal('#product-modal', {})
            productModal.show()
        },
        selectMenu(item) {
            this.selected_menu = item

            this.getProducts()
        },
        getMenus() {
            this.$store.dispatch("getIikoMenu")
                .then((response) => {
                    this.menus = response.menus || []
                }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка получения токена",
                    type: "error"
                });
            })
        },
        getProducts() {
            this.$store.dispatch("getIikoProducts", {
                menu_id: (this.selected_menu || {id: null}).id
            })
                .then((response) => {
                    this.products = response.products.itemCategories || []
                    this.categories = response.products.productCategories || []

                    this.products.forEach(cat => {
                        cat.items.forEach(prod => {
                            prod.need_add = true
                        })
                    })
                }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка получения токена",
                    type: "error"
                });
            })
        },
    }
}
</script>

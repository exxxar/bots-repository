<template>
    <div class="card rounded-l shadow-xl bg-18 mb-3 d-block"
         v-bind:style="{'background-image': 'url('+(item.images[0]||'')+')' }"
         style="height: 320px;margin: 20px;"
         data-card-height="320">
        <div class="card-top mt-3 mr-3">
            <button type="button"
                    @click="addToCart"
                    style="width:50px;height:50px;"
                    class="btn rounded-l shadow-xl bg-highlight color-white float-right"><i
                class="fa fa-shopping-cart"></i></button>
            <a href="javascript:void(0)" class="icon icon-s rounded-l shadow-xl bg-red2-dark color-white float-right ml-2 mr-2"><i
                class="fa fa-heart"></i></a>

        </div>
        <div class="card-bottom mb-3"  @click="goToProduct">
            <div class="content mb-0">
                <div class="d-flex">
                    <div>
                        <p
                            style="line-height: 130%;"
                            class="mb-n1 font-600  color-highlight d-flex flex-column"><span v-for="category in item.categories">{{category.title||'Без категории'}}</span></p>
                        <h1 class="font-700">{{ item.title || 'Нет заголовка' }}</h1>
                    </div>
                    <div class="ml-auto">
                        <h1>₽{{ currentPriceParts.left }}<sup
                            class="font-300 opacity-30">{{ currentPriceParts.right }}</sup></h1>
                        <span
                            class="badge bg-highlight color-white px-3 py-1 mt-n1 text-uppercase d-block"
                            v-if="oldPrice>0">Скидки</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-gradient-fade rounded-l"></div>
        <div class="card-overlay"></div>
    </div>
</template>
<script>
export default {
    props: ["item"],
    data() {
        return {
            showCart: false
        }
    },
    computed: {
        currentPriceParts() {
            return {
                right: "00" ,
                left:this.item.current_price
            }
        },
        currentPrice() {
            return this.item.current_price / 100
        },
        oldPrice() {
            return this.item.old_price / 100
        }
    },
    methods: {
        addToCart() {
            this.$cart.add(this.item)
        },
        goToProduct() {
            this.$router.push({name: 'product', params: {productId: this.item.id}})
        }
    }
}
</script>

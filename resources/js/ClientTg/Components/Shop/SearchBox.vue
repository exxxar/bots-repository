<template>
    <div class="content mb-4">
        <div class="search-box bg-theme rounded-m shadow-xl bottom-0">
            <i class="fa fa-search"></i>
            <input type="text" class="border-0"

                   v-model="search"
                   placeholder="Что вы хотели бы найти? (найдем всё)">
        </div>

        <!-- disabled-search-list -->
        <div class="search-results  card card-style mx-0 mt-3 px-2">
            <div class="list-group list-custom-large">
                <a href="#"
                   v-for="product in products"
                   data-filter-item data-filter-name="all demo smartphone apple iphone">
                    <i class="fab fa-apple color-gray2-dark"></i>
                    <span>{{product.title ||'Не указано'}}</span>
                    <strong>Works on iOS 10 and Higher</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>

            </div>
        </div>
    </div>
</template>
<script>
import baseJS from "@/ClientTg/modules/custom";
import {mapGetters} from "vuex";

export default {
    data(){
      return {
          search: null,
          products: null,
      }
    },
    watch:{
        search:function(){
           // this.loadProducts(0)
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject']),
    },
    methods: {
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search
                },
                page: page,
                size:20
            }).then(() => {
                this.products = this.getProducts
                baseJS.handler()
            })
        }
    }
}
</script>

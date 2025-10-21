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

    <ul class="list-group" v-if="partner">
        <li
            v-bind:class="{'bg-exclude':excludes.indexOf(product.id)!==-1}"
            class="list-group-item"

            v-for="(product, index) in partner.products">

            <p class="mb-0 d-flex justify-content-between align-items-center"> {{ product.title }}
                <span class="badge bg-primary" v-if="extra_charge===0">{{ product.current_price || 0 }}₽</span>
                <span class="badge bg-info" v-else>{{  parseFloat(product.current_price)+parseFloat((product.current_price*extra_charge / 100).toFixed(2)) }}₽</span>
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
</template>
<script>
export default {
    props: ["partner"],
    data() {
        return {
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
    },
    methods: {
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

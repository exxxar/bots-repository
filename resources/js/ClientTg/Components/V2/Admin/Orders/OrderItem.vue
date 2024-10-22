<script setup>
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
</script>

<template>
    <div
        @click="select(item)"
        style="font-weight:bold;"

        class="list-group-item d-flex justify-content-between p-3
                       align-items-start flex-column" aria-current="true">

        <p class="mb-2 d-flex justify-content-between w-100 align-items-center">
            <span class="badge bg-primary ">Заказ #{{ item.id }}</span>
            <span style="font-size:12px;"
                  v-if="item.is_cashback_crediting"><i class="fa-solid fa-check mr-2 text-success"></i> CashBack начислен</span>
            <span style="font-size:12px;"
                  v-else><i class="fa-solid fa-xmark mr-1 text-danger"></i> CashBack не начислен</span>
        </p>
        <p><i class="fa-solid fa-stopwatch mr-1 text-primary"></i> Время заказа {{ item.created_at }}

        </p>
        <ul v-if="(item.product_details||[]).length>0">
            <li v-for="product in  item.product_details[0].products">{{ product.title }}</li>
        </ul>

        <p class="mb-2">Цена заказа: <strong class="fw-bold"> {{ item.summary_price }} руб.</strong></p>

        <ReviewCard
            v-if="item.review"
            :is-admin="true"
            v-model="item.review"></ReviewCard>

        <button
            v-if="!item.is_cashback_crediting"
            type="button"
            :disabled="spent_time_counter>0"
            @click="addCashBack"
            class="btn btn-outline-success w-100 mt-2">
              <span
                  v-if="spent_time_counter<=0"
                  class="color-white">Начислить CashBack</span>
            <span
                v-else
                class="color-white">Осталось ждать {{ spent_time_counter || 0 }} сек.</span>
        </button>


        <!--
                <hr class="mb-0">
                <button type="button"
                        class="btn btn-outline-info w-100" data-bs-toggle="modal" :data-bs-target="'#order-details-'+item.id">
                    Детали заказа
                </button>

                &lt;!&ndash; Modal &ndash;&gt;
                <div class="modal fade" :id="'order-details-'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Детали заказа</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{item}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
        -->
    </div>


</template>
<script>
export default {
    props: ["item"],
    data() {
        return {
            spent_time_counter: 0,
        }
    },
    mounted() {
        if (localStorage.getItem("cashman_order_cashback_add_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_order_cashback_add_counter") || 0)
        }
    },
    methods: {
        addCashBack() {
            this.startTimer();
            this.$store.dispatch("addCashBackToOrder", {
                order_id: this.item.id
            }).then(() => {
                this.item.is_cashback_crediting = true
                this.$notify({
                    title: "Отзывы",
                    text: "Вы успешно начислили CashBack пользователю",
                    type: "success"
                })
            }).catch(() => {
                this.item.is_cashback_crediting = false
                this.$notify({
                    title: "Отзывы",
                    text: "Ошибка начисления CashBack",
                    type: "error"
                })
            })

        },
        select(item) {
            return this.$emit("select", item)
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_order_cashback_add_counter", this.spent_time_counter)
                }, 1000
            )
        },
    }
}
</script>

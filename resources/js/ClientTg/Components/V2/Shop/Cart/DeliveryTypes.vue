<template>
    <div class="list-group my-3" v-if="deliveryForm">
        <a href="javascript:void(0)"
           v-bind:class="{'active':!deliveryForm.need_pickup}"
           @click="deliveryForm.need_pickup = false"
           class="list-group-item list-group-item-action p-3" aria-current="true">
            <i class="fa-solid fa-truck mr-2"></i>
            <span class="px-2">Доставка</span>
        </a>
        <a href="javascript:void(0)"
           @click="deliveryForm.need_pickup = true"
           v-bind:class="{'active':deliveryForm.need_pickup}"
           class="list-group-item list-group-item-action p-3">
            <i class="fa-brands fa-shopify mr-2"></i>
            <span class="px-2">Самовывоз</span>
        </a>
        <a href="javascript:void(0)"
           v-if="deliveryForm.need_pickup"
           class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
            <label class="form-check-label" for="in-restaurant">
                <i class="fa-solid fa-utensils mr-2"></i>

                <span class="px-2">В заведении</span>
            </label>

            <input class="form-check-input"
                   name="pickup-format"
                   v-model="deliveryForm.pick_up_type"
                   type="radio" value="0" id="in-restaurant">
        </a>
        <a href="javascript:void(0)"
           v-if="deliveryForm.need_pickup"
           class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
            <label class="form-check-label" for="pick-up-in-package">
                <i class="fa-solid fa-person-walking-luggage mr-2"></i>

                <span class="px-2">Забрать с собой</span>
            </label>

            <input class="form-check-input"
                   type="radio" value="1"
                   v-model="deliveryForm.pick_up_type"
                   name="pickup-format"
                   id="pick-up-in-package">
        </a>
    </div>

</template>
<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            deliveryForm: null,
        }
    },
    watch: {
        'deliveryForm': {
            handler: function (newValue) {

                this.$emit("update:modelValue", this.deliveryForm)
            },
            deep: true
        },
        'modelValue': {
            handler: function (newValue) {
                this.deliveryForm = newValue
            },
            deep: true
        },
    },
    mounted() {
        this.deliveryForm = this.modelValue
    },
    computed: {

        bot() {
            return window.currentBot
        },
        settings() {
            return this.bot.settings
        },


    },
}
</script>

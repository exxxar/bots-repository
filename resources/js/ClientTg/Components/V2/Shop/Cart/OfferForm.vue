<template>
    <template v-if="bot.company.law_params?.offer_link">
        <div class="alert alert-light my-2 border-primary">
            <p class="mb-2">
                Нажимая кнопку, вы соглашаетесь с условиями
                <a :href="bot.company.law_params?.offer_link" class="fw-bold" target="_blank">договора оферты</a>,
                а также вы даете согласие на обработку персональных данных согласно
                <a href="https://www.consultant.ru/document/cons_doc_LAW_61801/" target="_blank">
                    Федеральному закону №152-ФЗ
                </a>
            </p>
            <div class="form-check form-switch">

                <input
                    v-model="offer_agreement"
                    class="form-check-input" type="checkbox" role="switch" id="offerSwitch" checked>
                <label class="form-check-label fw-bold" for="offerSwitch">Я соглашаюсь</label>
            </div>
        </div>
    </template>
    <template v-else>
        <div class="alert alert-light my-2 border-primary">
            <p class="mb-2">
                Нажимая кнопку, вы соглашаетесь с условиями договора оферты данной компании.

            </p>
            <div class="form-check form-switch">

                <input
                    v-model="offer_agreement"
                    class="form-check-input" type="checkbox" role="switch" id="offerSwitch" checked>
                <label class="form-check-label fw-bold" for="offerSwitch">Я соглашаюсь</label>
            </div>
        </div>
    </template>
</template>
<script>

export default {
    props: ["modelValue"],
    data() {
        return {
            offer_agreement: false,
        }
    },
    watch: {

        'modelValue': {
            handler: function (newValue) {
                this.offer_agreement = newValue
            },
            deep: true
        },
        'offer_agreement': {
            handler: function (newValue) {

                this.$emit("update:modelValue", this.offer_agreement)
            },
            deep: true
        },
    },
    mounted() {
        this.offer_agreement = this.modelValue || false
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

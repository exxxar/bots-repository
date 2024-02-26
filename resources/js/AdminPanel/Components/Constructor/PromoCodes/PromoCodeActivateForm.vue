<script setup>

</script>
<template>

    <form v-on:submit.prevent="submitForm" class="py-3">
        <div class="row">

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="promoCodeForm.code"
                           class="form-control" id="promo-code-field" placeholder="Ваш промокод" required>
                    <label for="promo-code-field">Промокод</label>
                </div>
            </div>
            <div class="col-12 mb-2" v-if="slots>0">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Внимание</strong> Вам зачислено {{slots}} слотов!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="col-12">
                <button
                    :disabled="(promoCodeForm.code||'').length===0"
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    Активировать промокод
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: ["bot"],
    data() {
        return {
            load: false,
            slots: 0,
            promoCodeForm: {

                code: null,
            }
        }
    },
    computed: {
        getSelf() {
            return window.profile
        }
    },

    mounted() {

    },
    methods: {

        submitForm() {
            let data = new FormData();
            Object.keys(this.promoCodeForm)
                .forEach(key => {
                    const item = this.promoCodeForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("promoCodeActivate",
                {
                    promoCodeForm: data
                }).then((response) => {

                this.slots = response.slots || 0
                this.promoCodeForm = {
                    code: null,

                }

                this.$emit("callback", response.data)
                this.$notify("Промокод успешно активирован");
            }).catch(err => {
                this.$notify("Ошибка активации промокода");
            })

        },

    }
}
</script>

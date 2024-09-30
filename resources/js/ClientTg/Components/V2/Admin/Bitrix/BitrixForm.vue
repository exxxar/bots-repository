<template>


    <form v-on:submit.prevent="submit">
        <div class="form-floating mb-3">
            <input type="text"
                   v-model="bitrixForm.url"
                   class="form-control" id="bitrixForm-url" placeholder="name@example.com">
            <label for="bitrixForm-url">URL для сбора данных</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input"
                   v-model="bitrixForm.is_active"
                   type="checkbox" role="switch" id="bitrixForm-is_active">
            <label class="form-check-label" for="bitrixForm-is_active">
                Сбор данных: <span v-bind:class="{'fw-bold text-primary':bitrixForm.is_active}">вкл</span> / <span v-bind:class="{'fw-bold text-primary':!bitrixForm.is_active}">выкл</span>
            </label>
        </div>

        <button
            :disabled="!bitrixForm.url"
            class="w-100 btn btn-primary p-3 my-3">Сохранить</button>
    </form>

</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'
import {mapGetters} from "vuex";

export default {
    props: ["data", "bot"],
    components: {
        Vue3JsonEditor
    },
    data() {
        return {
            load: false,
            bitrixForm: {
                id: null,
                url: null,
                is_active: false,
                bot_id: null,
                config: [],
            },
        }
    },

    mounted() {


        if (this.data)
            this.$nextTick(() => {
                this.bitrixForm.id = this.data.id || null
                this.bitrixForm.url = this.data.url || null
                this.bitrixForm.is_active = this.data.is_active || false
                this.bitrixForm.config = this.data.config || []
                this.bitrixForm.bot_id = this.data.bot_id || this.bot.id || null
            })


    },
    methods: {


        submit() {

            let data = new FormData();
            Object.keys(this.bitrixForm)
                .forEach(key => {
                    const item = this.bitrixForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("storeBitrix", {
                bitrixForm: data
            }).then((response) => {
                this.$notify({
                    title: 'Работа с Bitrix',
                    text: 'Данные CRM успешно сохранены',
                    type: "success"
                });

               this.$emit("callback")
            }).catch(err => {

            })


        }
    }
}
</script>

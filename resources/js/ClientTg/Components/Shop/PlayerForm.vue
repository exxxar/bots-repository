<template>

           <slot name="head"></slot>

            <form v-on:submit.prevent="submit">
<!--
                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa fa-user"></i>
                    <span class="color-highlight">Ф.И.О.</span>
                    <em>(нужно)</em>
                    <input class="form-control"
                           v-model="form.name"
                           type="text" placeholder="Иванов Иван Иванович" required>
                </div>

                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa-solid fa-phone"></i>
                    <span class="color-highlight">Телефон</span>
                    <em>(нужно)</em>
                    <input class="form-control"
                           type="text"
                           v-mask="'+7(###)###-##-##'"
                           v-model="form.phone"
                           placeholder="+7(000)000-00-00"
                           required>
                </div>
-->
                <button type="submit"
                        :disabled="sending"
                        class="btn btn-full btn-m shadow-l rounded-s text-uppercase
                         font-900 bg-green1-dark w-100">
                    Начать
                </button>


            </form>



</template>
<script>
import baseJS from '@/ClientTg/modules/custom.js'

export default {
    data(){
      return {
          sending:false,
          form:{
              name: null,
              phone: null,
          }
      }
    },
    computed:{
        self(){
            return window.self
        }
    },
    mounted() {
        baseJS.handler()

        this.form.name = this.self.fio_from_telegram || this.self.name
        this.form.phone = this.self.phone || null
    },
    methods:{
        submit(){
            this.$emit("callback", this.form)
        }
    }
}
</script>

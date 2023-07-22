<template>
    <div class="card card-style">
        <div class="content mb-0">
            <h3>Анкета игрока</h3>

            <p>
                Для участия в конкурсе и дальнейшего получения приза необходимо заполнить данную анкету! Укажите своё имя и номер телефона чтоб менеджер
                мог выдать Вам приз по итогу.
            </p>

            <form v-on:submit.prevent="submit">
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


                <button type="submit"
                        :disabled="sending"
                        class="btn btn-full btn-m shadow-l rounded-s text-uppercase font-900 bg-green1-dark my-4 w-100">
                    Начать игру
                </button>
            </form>


        </div>
    </div>
</template>
<script>
import baseJS from '@/modules/custom.js'

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
    mounted() {
        baseJS.handler()
    },
    methods:{
        submit(){
            this.$emit("callback", this.form)
        }
    }
}
</script>

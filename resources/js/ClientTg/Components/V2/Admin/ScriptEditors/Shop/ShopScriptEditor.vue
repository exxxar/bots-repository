<script setup>
import ShopWheelForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopWheelForm.vue";
import ShopForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopForm.vue";
</script>
<template>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=0"
               v-bind:class="{'active fw-bold':tab===0}"
               aria-current="page"
               href="javascript:void(0)">Магазин</a>
        </li>
        <li class="nav-item">
            <a class="nav-link "
               v-bind:class="{'active fw-bold':tab===1}"
               @click="tab=1"
               href="javascript:void(0)">Колесо фортуны</a>
        </li>
    </ul>

    <form v-on:submit.prevent="submit">
        <div v-if="tab===0" class="py-3">
            <ShopForm
                v-if="form"
                v-model="form"></ShopForm>
        </div>

        <div v-if="tab===1" class="py-3">
            <ShopWheelForm
                v-if="form"
                v-model="form"></ShopWheelForm>
        </div>

        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>
</template>
<script>
export default {
    props: ["modelValue","defaultType"],
    data() {
        return {
            tab: 0,
            form: null,
        }
    },
    watch: {
        'form': {
            handler: function (newValue) {
                this.$emit("updated:modelValue", this.form)
            },
            deep: true
        }
    },
    mounted() {

        this.$nextTick(() => {
            this.form = this.modelValue

            if (this.defaultType)
                this.tab = this.defaultType || 0
        })


    },
    methods: {

        /*  startTimer(time) {
              this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

              let counterId = setInterval(() => {
                      if (this.spent_time_counter > 0)
                          this.spent_time_counter--
                      else {
                          clearInterval(counterId)
                          this.is_requested = false
                          this.spent_time_counter = null
                      }
                      localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                  }, 1000
              )
          },*/
        submit() {
            let data = new FormData();
            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("updateScriptParams", {
                slugForm: data
            }).then((response) => {
                this.$notify({
                    title: "Информация о скрипте",
                    text: "Информация о скрипте успешно обновлена!",
                    type: "success"
                })
                this.$emit("callback", response.data)

                window.location.reload()
            }).catch(err => {
                this.$notify({
                    title: "Информация о скрипте",
                    text: "Ошибка обновления информации о скрипте",
                    type: "error"
                })
            })
        }
    }
}
</script>

<script setup>
import ShopWheelForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopWheelForm.vue";
import ShopForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopForm.vue";
import CompanyForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Company/CompanyForm.vue";
import CertificateForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/CertificateForm.vue";
import TablePlanner from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/Tables/TablePlanner.vue";
import CoffeeConfigForm from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/CoffeeConfigForm.vue";
</script>
<template>

    <div class="row" style="position: sticky; top: 0px;z-index: 1000;">
        <div class="col-12">

            <div class="btn-group w-100 px-3 py-2" style="overflow-x:auto;">
                <button
                    type="button"
                    class="btn-info   btn p-3"
                    @click="tab=0"
                    style="line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===0}"
                    aria-current="page"><i class="fa-solid fa-scroll mr-2"></i> Магазин
                </button>

                <button
                    type="button"
                    class="btn-info  btn p-3"
                    @click="tab=4"
                    style=";line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===4}"
                    aria-current="page"><i class="fa-solid fa-table mr-2"></i> Столики и бронь
                </button>

                <button
                    type="button"
                    class="btn-info  btn p-3"
                    @click="tab=5"
                    style=";line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===5}"
                    aria-current="page"><i class="fa fa-coffee mr-2"></i> Кофе в подарок
                </button>

                <button
                    type="button"
                    class="btn-info  btn p-3"
                    @click="tab=3"
                    style=";line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===3}"
                    aria-current="page"><i class="fa-solid fa-certificate mr-2"></i> Реферальный сертификат
                </button>

                <button
                    type="button"
                    class="btn-info  btn p-3"
                    @click="tab=2"
                    style=";line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===2}"
                    aria-current="page"><i class="fa-solid fa-coins mr-2"></i> Юридические данные
                </button>

                <button
                    type="button"
                    class="btn-info d-block btn p-3"
                    @click="tab=1"
                    style="line-height:100%;white-space: nowrap;"
                    v-bind:class="{'active':tab===1}"
                    aria-current="page"><i class="fa-solid fa-users mr-2"></i> Интерактив в магазине
                </button>


            </div>
        </div>
    </div>



    <form v-on:submit.prevent="submit">
        <div v-if="tab===0" class="py-3">
            <ShopForm
                v-if="form"
                v-model="form"></ShopForm>

            <button
                style="z-index: 100;"
                type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
            </button>
        </div>

        <div v-if="tab===4" class="py-3">

            <TablePlanner
                v-if="form"
                v-model="form"></TablePlanner>

            <button
                style="z-index: 100;"
                type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
            </button>
        </div>

        <div v-if="tab===5" class="py-3">

            <CoffeeConfigForm
                v-if="form"
                v-model="form"></CoffeeConfigForm>

            <button
                style="z-index: 100;"
                type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
            </button>
        </div>

        <div v-if="tab===3" class="py-3">
            <CertificateForm
                v-if="form"
                v-model="form"></CertificateForm>

            <button
                style="z-index: 100;"
                type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
            </button>
        </div>

        <div v-if="tab===1" class="py-3">
            <ShopWheelForm
                v-if="form"
                v-model="form"></ShopWheelForm>

            <button
                style="z-index: 100;"
                type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
            </button>
        </div>

    </form>

    <template v-if="tab===2">
        <CompanyForm class="py-3"></CompanyForm>
    </template>


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

              //  window.location.reload()
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

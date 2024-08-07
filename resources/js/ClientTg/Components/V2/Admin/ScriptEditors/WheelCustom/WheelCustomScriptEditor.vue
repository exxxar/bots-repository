<script setup>
import WheelOfFortuneShopVariant from "@/ClientTg/Components/V2/Games/WheelOfFortuneShopVariant.vue";
import ParametrizedTextArea from "@/ClientTg/Components/V2/Admin/Other/ParametrizedTextArea.vue";
</script>
<template>
    <form v-on:submit.prevent="submit" v-if="loaded_params">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.can_play"
                   role="switch" id="script-settings-is_disabled">
            <label class="form-check-label" for="script-settings-wheel-of-fortune-can_play">Состояние колеса
                фортуны: <span v-bind:class="{'text-primary fw-bold':form.can_play}">вкл</span> \
                <span v-bind:class="{'text-primary fw-bold':!form.can_play}">выкл</span></label>
        </div>
        <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.rules_text"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play"></textarea>
            <label for="script-settings-disabled_text">Правила колеса фортуны
                <span
                    v-if="(form.rules_text||'').length>0">{{ (form.rules_text || '').length }}/4000</span>
            </label>
        </div>

        <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.main_text"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play" required></textarea>
            <label for="script-settings-disabled_text">Текст в боте
                <span
                    v-if="(form.main_text||'').length>0">{{ (form.main_text || '').length }}/4000</span>
            </label>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   maxlength="100"
                   v-model="form.btn_text"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Текст кнопки
                <span
                    v-if="(form.btn_text||'').length>0">{{ (form.btn_text || '').length }}/100</span>
            </label>
        </div>

        <ParametrizedTextArea
            v-model="form.win_message"
            :maxlength="4000"
            :required="true"
            class="mb-2">
            <template #title>
                Текст пользователю при выигрыше
            </template>
        </ParametrizedTextArea>

        <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.callback_message"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play" required></textarea>
            <label for="script-settings-disabled_text"> Детали получения приза
                <span
                    v-if="(form.callback_message||'').length>0">{{ (form.callback_message || '').length }}/4000</span>
            </label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   min="0"
                   v-model="form.max_attempts"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Максимальное число попыток</label>
        </div>


        <div class="alert alert-light mb-2">
            <p class="mb-0">
                <i class="fa-solid fa-trophy text-primary"></i>
                Призы, которые может выиграть пользователь, максимум <strong class="fw-bold text-primary">10</strong>
            </p>
            <p class="mb-0"
               v-if="form.wheels.length===0"><strong class="fw-bold text-primary">Внимание!</strong> Вы еще не добавили ни одного приза в колесо!</p>
        </div>
        <div class="input-group mb-2 align-items-start"
             v-if="form.wheels.length>0"
             v-for="(item, index) in form.wheels">
            <div class="form-floating">

                <textarea class="form-control"
                          v-model="form.wheels[index].value"
                          maxlength="4000"
                          style="min-height:100px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play" required></textarea>
                <label for="script-settings-disabled_text">#{{ index + 1 }} - описание приза в колесе
                    <span
                        v-if="(form.wheels[index].value||'').length>0">{{
                            (form.wheels[index].value || '').length
                        }}/4000</span>
                </label>
            </div>
            <button type="button"
                    @click="removeWheel(index)"
                    class="btn btn-outline-light"><i class="fa-solid fa-trash text-danger"></i></button>
        </div>


        <button
            type="button"
            :disabled="form.wheels.length>=10"
            @click="addWheel"
            class="btn btn-outline-primary p-3 w-100 mb-5">Добавить приз
        </button>

        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>
</template>
<script>
export default {
    props:["modelValue"],
    data() {
        return {
            loaded_params:false,
            form: {
                can_play: true,
                rules_text: null,
                max_attempts: 1,
                main_text: null,
                callback_message: null,
                win_message: null,
                btn_text: null,
                wheels: []
            }
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
        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            this.loaded_params = true
        })

    },
    methods: {
        removeWheel(index) {
            this.form.wheels.splice(index, 1)

            this.$notify({
                title: 'Колесо фортуны',
                text: 'Приз успешно удален',
                type: 'success'
            })
        },
        addWheel() {
            if (!this.form.wheels)
                this.form.wheels = []

            if (this.form.wheels.length < 10) {

                this.form.wheels.push({
                    key: "wheel_text",
                    type: "text",
                    value: null,
                })

                this.$notify({
                    title: 'Колесо фортуны',
                    text: 'Новый приз успешно добавлен',
                    type: 'success'
                })
            }

            else{
                this.$notify({
                    title: 'Колесо фортуны',
                    text: 'Вы уже добавили максимальное число призов!',
                    type: 'error'
                })
            }
        },
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


            this.$store.dispatch("updateWheelCustomScriptParams", {
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
        },
        loadScriptParams() {

        }
    }
}
</script>

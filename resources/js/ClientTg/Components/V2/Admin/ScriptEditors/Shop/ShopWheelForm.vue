<script setup>
import WheelOfFortuneShopVariant from "@/ClientTg/Components/V2/Games/WheelOfFortuneShopVariant.vue";
import ParametrizedTextArea from "@/ClientTg/Components/V2/Admin/Other/ParametrizedTextArea.vue";
</script>
<template>

    <div v-if="loaded_params">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.wheel_of_fortune.can_play"
                   role="switch" id="script-settings-is_disabled">
            <label class="form-check-label" for="script-settings-wheel-of-fortune-can_play">Состояние колеса
                фортуны: <span v-bind:class="{'text-primary fw-bold':form.wheel_of_fortune.can_play}">вкл</span> \
                <span v-bind:class="{'text-primary fw-bold':!form.wheel_of_fortune.can_play}">выкл</span></label>
        </div>
        <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.wheel_of_fortune.rules"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play"></textarea>
            <label for="script-settings-disabled_text">Правила колеса фортуны
                <span
                    v-if="(form.wheel_of_fortune.rules||'').length>0">{{ (form.wheel_of_fortune.rules || '').length }}/4000</span>
            </label>
        </div>

        <ParametrizedTextArea
            v-model="form.win_message"
            :maxlength="4000"
            class="mb-2">
            <template #title>
                Текст пользователю при выигрыше
            </template>
        </ParametrizedTextArea>


        <p class="alert alert-light mb-2">Редактирование призов, описания, отметки места получения приза, цвет
            сектора и цвет текста. Максимальное число секторов <span class="fw-bold text-primary">10</span>, сейчас
            создано <span class="fw-bold text-primary">{{ (form.wheel_of_fortune.items || []).length }}</span>
            секторов. <strong class="fw-bold text-primary">Внимание!</strong> При удалении сектора идет пересчет его
            номера!</p>


        <div class="accordion accordion-flush" :id="'wheel_of_fortune'">
            <div class="accordion-item" v-for="(item, index) in form.wheel_of_fortune.items">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            :data-bs-target="'#wheel-sector-'+index" aria-expanded="false"
                            :aria-controls="'wheel-sector-'+index">
                        Сектор {{ item.value }} #{{ item.id }}
                    </button>
                </h2>
                <div :id="'wheel-sector-'+index" class="accordion-collapse collapse"
                     :data-bs-parent="'#wheel_of_fortune'">
                    <div class="input-group  my-2">
                        <div class="form-floating">
                            <input type="text"
                                   v-model="form.wheel_of_fortune.items[index].value"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Телеграм-эмодзи</label>
                        </div>
                        <div class="dropdown">
                            <button

                                class="btn btn-outline-light text-primary w-100 h-100 rounded-0"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-face-smile-beam"></i>
                            </button>
                            <div class="dropdown-menu p-0"
                                 style="width:300px;">
                                <div class="row row-cols-5 w-100 p-2">
                                    <div
                                        class="col mb-2" v-for="smile in smiles">
                                        <a
                                            @click="addSmile(index, smile)"
                                            href="javascript:void(0)"
                                            class="btn btn-outline-light"
                                        >{{ smile }}</a>
                                    </div
                                    >
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                             <textarea class="form-control"
                                       v-model="form.wheel_of_fortune.items[index].description"
                                       maxlength="4000"
                                       style="min-height:100px;"
                                       placeholder="Leave a comment here"
                                       :id="'script-settings-description-'+index" required>
                             </textarea>
                        <label :for="'script-settings-description-'+index">Описание приза</label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="color"
                               v-model="form.wheel_of_fortune.items[index].bgColor"
                               class="form-control" :id="'script-settings-bgColor-'+index"
                               placeholder="name@example.com" required>
                        <label :for="'script-settings-bgColor-'+index">Цвет фона сектора</label>
                    </div>

                    <!--
                                            <div class="form-floating mb-2">
                                                <input type="color"
                                                       v-model="form.wheel_of_fortune.items[index].color"
                                                       class="form-control" :id="'script-settings-color-'+index"
                                                       placeholder="name@example.com" required>
                                                <label :for="'script-settings-color-'+index">Цвет шрифта сектора</label>
                                            </div>
                    -->

                    <p class="alert-light alert mb-2">Впишите или выберите где можно получить приз: <span
                        @click="attachMarkText(index, 'во время доставки')"
                        class="fw-bold text-primary cursor-pointer text-decoration-underline">во время доставки</span>
                        или <span
                            @click="attachMarkText(index, 'в заведении')"
                            class="fw-bold text-primary cursor-pointer text-decoration-underline">в заведении</span>.
                        Вы можете вписать сразу несколько
                        вариантов.</p>
                    <div class="form-floating my-2">
                        <input type="search"
                               v-model="form.wheel_of_fortune.items[index].mark"
                               class="form-control"
                               :name="'wheel_of_fortune-mark-'+index"
                               :id="'wheel_of_fortune-mark-'+index" placeholder="name@example.com" required>
                        <label :for="'wheel_of_fortune-mark-'+index">Где выдается приз</label>
                    </div>

                    <a href="javascript:void(0)"
                       @click="removeSector(index)"
                       class="btn btn-link w-100 text-center my-3"><i class="fa-regular fa-trash-can"></i> Удалить
                        сектор #{{ item.id }} ({{ item.value || '-' }})</a>
                </div>
            </div>

        </div>

        <div class="form-check form-switch my-2">
            <input class="form-check-input"
                   v-model="need_auto_random_smiles"
                   type="checkbox" role="switch" id="need_auto_random_smiles">
            <label class="form-check-label" for="need_auto_random_smiles">Добавлять смайл случайным образом</label>
        </div>

        <button
            type="button"
            :disabled="(form.wheel_of_fortune.items||[]).length===10"
            @click="addSector"
            class="btn btn-outline-primary w-100 p-3 mb-2">Добавить еще сектор
        </button>

        <div class="alert alert-light mb-2">
            <p>Демонстрация заполнения</p>
            <WheelOfFortuneShopVariant
                :is-admin="true"
                v-if="loaded&&(form.wheel_of_fortune.items||[]).length>=3"
                v-model="form.wheel_of_fortune.items"></WheelOfFortuneShopVariant>
        </div>
    </div>

</template>
<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            loaded: true,
            loaded_params:false,
            need_auto_random_smiles: true,
            smiles: ["🥤", "🥗", "🍔", "🍗", "🍟", "🥓", "🌯", "🍱", "🍜", "🍲", "🍧", "🍨", "🧁", "🥞",
                "💎", "🤖", "🎲", "🎯", "🏆", "😊", "😎", "🌻", "👽", "💌", "📚", "🐶", "👻", "🏀", "👓", "🎓"],
            form: {
                win_message: null,
                wheel_of_fortune: {
                    can_play: true,
                    rules: 'Колесо фортуны доступно 1 раз в сутки. В качестве приза вы можете выиграть 1 из предложенных призов и воспользоваться ими в заведении или на доставке:) Приятного отдыха!',
                    // short_description:'В данный момент розыгрыш недоступен',
                    items: [
                        {
                            id: 1,
                            value: "🍅",
                            bgColor: "#fac600",
                            color: "#ffffff",
                            mark: 'в заведении',
                            description: null,
                        },
                        {
                            id: 2,
                            value: "🍲",
                            bgColor: "#ffffff",
                            color: "#000000",
                            mark: 'в заведении & на доставке',
                            description: null,
                        },
                        {
                            id: 3,
                            value: "🍦",
                            bgColor: "#ff2e55",
                            color: "#ffffff",
                            description: null,
                            mark: 'на доставке',
                        },
                        {
                            id: 4,
                            value: "🍓",
                            bgColor: "#a1043a",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении',
                        },
                        {
                            id: 5,
                            value: "☕",
                            bgColor: "#ffffff",
                            color: "#000000",
                            description: null,
                            mark: 'в заведении',
                        },
                        {
                            id: 6,
                            value: "🍕",
                            bgColor: "#c92729",
                            color: "#ffffff",
                            description: null,
                            mark: 'на доставке',
                        },
                        {
                            id: 7,
                            value: "⭐",
                            bgColor: "#ffffff",
                            color: "#000000",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                        {
                            id: 8,
                            value: "🎁",
                            bgColor: "#c92729",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                        {
                            id: 9,
                            value: "🚀",
                            bgColor: "#ffffff",
                            color: "#ffffff",
                            description: null,
                            mark: 'в заведении & на доставке',
                        },
                    ]
                }
            },
        }
    },
    watch: {
        'form.wheel_of_fortune.items': {
            handler: function (newValue) {
                this.loaded = false
                this.$nextTick(() => {
                    this.loaded = true
                })
            },
            deep: true
        },
        'form': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.form)
            },
            deep: true
        },
    },
    mounted() {
        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            this.loaded_params = true
            this.loaded = true
        })
    },
    methods: {
        removeSector(index) {
            this.form.wheel_of_fortune.items.splice(index, 1)

            let i = 1
            this.form.wheel_of_fortune.items.forEach(item => {
                item.id = i
                i++
            })
            this.$notify({
                title: "Редактор",
                text: "Сектор успешно удален! Идентификаторы секторов пересчитаны",
                type: "success"
            })
        },
        attachMarkText(index, text) {
            this.form.wheel_of_fortune.items[index].mark +=
                (this.form.wheel_of_fortune.items[index].mark || '').length === 0 ?
                    text : " & " + text
        },
        addSector() {
            if ((this.form.wheel_of_fortune.items || []).length === 0)
                this.form.wheel_of_fortune.items = []

            let getRandomInt = (min, max) => {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            let value = this.need_auto_random_smiles ?
                this.smiles[getRandomInt(0, this.smiles.length - 1)] :
                this.form.wheel_of_fortune.items.length + 1

            if (this.form.wheel_of_fortune.items.length < 10) {
                this.form.wheel_of_fortune.items.push({
                    id: this.form.wheel_of_fortune.items.length + 1,
                    value: value,
                    bgColor: ((this.form.wheel_of_fortune.items || []).length + 1) % 2 === 0 ? "#c92729" : "#ffffff",
                    color: "#ffffff",
                    description: 'Описание приза №' + (this.form.wheel_of_fortune.items.length + 1),
                    mark: 'в заведении & на доставке',
                })

                this.$notify({
                    title: "Редактор",
                    text: "Сектор успешно добавлен!",
                    type: "success"
                })
            } else {
                this.$notify({
                    title: "Редактор",
                    text: "Достигнут лимит секторов!",
                    type: "error"
                })
            }

        },
        addSmile(index, smile) {
            this.form.wheel_of_fortune.items[index].value = smile
        },
    }
}
</script>

<script setup>
import SlugForm from "@/ClientTg/Components/V2/Admin/Slugs/SlugForm.vue";
</script>
<template>
    <ul class="nav nav-tabs mb-2">
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=0"
               v-bind:class="{'active fw-bold':tab===0}"
               aria-current="page"
               href="javascript:void(0)">Основное</a>
        </li>
        <li class="nav-item">
            <a class="nav-link "
               v-bind:class="{'active fw-bold':tab===1}"
               @click="tab=1"
               href="javascript:void(0)">CashBack</a>
        </li>
        <li class="nav-item">
            <a class="nav-link "
               v-bind:class="{'active fw-bold':tab===2}"
               @click="tab=2"
               href="javascript:void(0)">Мои друзья</a>
        </li>
    </ul>
    <form
        v-if="tab===0"
        v-on:submit.prevent="submitCompanyForm">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="companyForm.title"
                   maxlength="255"
                   class="form-control"
                   id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">Название
                <span
                    class="ml-1"
                    style="font-size:10px;"
                    v-if="(companyForm.title||'').length>0">({{ companyForm.title.length }}/255)</span>
            </label>
        </div>

        <div class="form-floating">
            <textarea
                v-model="companyForm.description"
                maxlength="512"
                required
                class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                style="height: 200px"></textarea>
            <label for="floatingTextarea2">Описание
                <span
                    class="ml-1"
                    style="font-size:10px;"
                    v-if="(companyForm.description||'').length>0">({{ companyForm.description.length }}/512)</span>
            </label>
        </div>

        <h6 class="opacity-75 my-2">Контактная информация</h6>

        <div class="form-floating mb-2">
            <input type="text"
                   v-mask="['+7(###)###-##-##']"
                   v-model="companyForm.phones[0]"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Телефон</label>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="companyForm.links.inst"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Инста</label>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="companyForm.links.vk"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Вконтакте</label>
        </div>

        <div class="form-floating mb-2">
            <input type="email"
                   v-model="companyForm.email"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Почта</label>
        </div>
        <div class="form-floating mb-2">
            <input type="url"
                   v-model="companyForm.links.site"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Сайт</label>
        </div>
        <h6 class="opacity-75 my-2">Расположение заведения</h6>
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="companyForm.address"
                   class="form-control"
                   id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Адрес расположения
                <span
                    class="ml-1"
                    style="font-size:10px;"
                    v-if="(companyForm.address||'').length>0">({{ companyForm.address.length }}/255)</span>
            </label>
        </div>
        <div class="form-floating mb-2">
               <textarea
                   v-model="companyForm.links.map_link"
                   class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                   style="height: 200px"></textarea>

            <label for="floatingInput">Виджет с Яндекс.Картой</label>
        </div>
        <h6 class="opacity-75 my-3">График работы</h6>
        <div class="alert alert-light p-2"
             style="font-size:12px;"
             role="alert">
            Укажите график работы вашего заведения. Если в какой-то день ваше заведение <span
            class="fw-bold">закрыто</span> - поставьте галочку сбоку от времени и укажите причину.
        </div>
        <template v-for="(item, index) in companyForm.schedule">
            <p class="mb-0" style="font-size:12px;">{{ companyForm.schedule[index].day }}</p>

            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input class="form-check-input mt-0"
                           v-model="companyForm.schedule[index].closed"
                           type="checkbox" value="" aria-label="Checkbox for following text input">
                </div>

                <div class="form-floating" v-if="!companyForm.schedule[index].closed">
                    <input type="time"
                           v-model="companyForm.schedule[index].start_at"
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Начало</label>
                </div>
                <div class="form-floating" v-if="!companyForm.schedule[index].closed">
                    <input type="time"
                           v-model="companyForm.schedule[index].end_at"
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Окончание</label>
                </div>

                <div class="form-floating" v-if="companyForm.schedule[index].closed">
                    <input type="text"
                           v-model="companyForm.schedule[index].closed_comment"
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Причина закрытия</label>
                </div>
            </div>
        </template>


        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>

    <form
        v-if="tab===1"
        v-on:submit.prevent="submitBotForm">
        <div class="alert alert-light mb-2">
            Максимальное значение CashBack, которое пользователь может списать во время покупки в магазине, в % от цены
            заказа.
        </div>
        <div class="form-floating mb-2">
            <input type="number"
                   v-model="botForm.max_cashback_use_percent"
                   min="0"
                   max="100"
                   class="form-control"
                   id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">CashBack</label>
        </div>

        <div class="alert alert-light mb-2">
            Уровни автоматического начисления CashBack по реферальной программе, в %.
        </div>
        <div class="form-floating mb-2">
            <input type="number"
                   v-model="botForm.level_1"
                   min="0"
                   max="100"
                   class="form-control"
                   id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">Уровень 1, %</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   v-model="botForm.level_2"
                   min="0"
                   max="100"
                   class="form-control"
                   id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">Уровень 2, %</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   v-model="botForm.level_3"
                   min="0"
                   max="100"
                   class="form-control"
                   id="floatingInput"
                   placeholder="name@example.com" required>
            <label for="floatingInput">Уровень 3, %</label>
        </div>

        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_cashback_config"
                       type="checkbox"
                       id="need-cashback-config">
                <label class="form-check-label" for="need-cashback-config">
                    Необходимо настроить CashBack по категориям
                </label>
            </div>

        </div>
        <div class="mb-2" v-if="need_cashback_config">
            <h6 class="opacity-75 my-2">Настройка категорий CashBack-а</h6>

            <div class="alert alert-light" role="alert">
                Категории CashBack - это возможность разделить накопления и траты CashBack пользователями бота на
                указанные цели, например, кофейня может создать категории: на кофе, на десерты - и начислять баллы
                за купленный кофе отдельно от баллов за купленный десерт
            </div>

            <div class="d-flex justify-content-between flex-wrap"
                 :key="'social-link'+index"
                 v-for="(item, index) in botForm.cashback_config">


                <div class="input-group mb-2">
                    <div class="form-floating">
                        <input type="text"
                               placeholder="Название категории"
                               aria-label="Название категории"
                               maxlength="255"
                               v-model="botForm.cashback_config[index].title"
                               class="form-control" id="floatingInput">
                        <label for="floatingInput">Название категории</label>
                    </div>
                    <button
                        type="button"
                        @click="removeCashBackConfig(index)"
                        class="btn btn-outline-light text-danger"><i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>


            </div>

            <button
                type="button"
                @click="addCashBackConfig()"
                class="btn mb-2 rounded-sm btn-outline-primary p-3 w-100">
                Добавить категорию
            </button>
        </div>
        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_cashback_rules"
                       type="checkbox"
                       id="need-cashback-rules">
                <label class="form-check-label" for="need-cashback-rules">
                    Необходимо настроить оповещения под CashBack
                </label>
            </div>

        </div>
        <div class="col-md-12 col-12 mb-2" v-if="need_cashback_rules">

            <div class="form-floating mb-2">
                <select class="form-select"
                        v-model="selected_warning"
                        @change="addWarning"
                        id="floatingSelect" aria-label="Floating label select example">
                    <option :value="null">Не выбрано</option>
                    <option :value="item" v-for="item in filteredWarnings">
                        {{ item.title }}
                    </option>
                </select>
                <label for="floatingSelect">
                    <i class="fa-solid fa-triangle-exclamation text-danger"></i> Правила критических
                    оповещений
                </label>
            </div>


            <template v-for="(warn, index) in botForm.warnings">

                <p class="m-0 fst-italic">{{ getWarning(warn.rule_key).title || 'Не найдено' }}</p>
                <div class="input-group mb-2">

                    <div class="input-group-text">
                        <div class="form-check d-flex justify-content-center align-items-center">
                            <input class="form-check-input"
                                   v-model="botForm.warnings[index].is_active"
                                   type="checkbox"
                                   :id="'warning-is-active-'+index">
                        </div>
                    </div>


                    <div class="form-floating">
                        <input type="number"
                               placeholder="Значение"
                               aria-label="Значение"
                               v-model="botForm.warnings[index].rule_value"
                               min="0"
                               class="form-control" id="floatingInput">
                        <label for="floatingInput">Значение</label>
                    </div>
                    <button
                        type="button"
                        @click="removeWarning(index)"
                        class="btn btn-outline-light text-danger"><i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>


            </template>
        </div>

        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>

    <template v-if="tab===2">
        <h6 class="fw-bold">Варианты скрипта</h6>
        <ul class="list-group mb-2" v-if="(scripts||[]).length>0">
            <li
                @click="selectScript(item)"
                v-for="item in scripts"
                v-bind:class="{'bg-primary text-white':(selected_script||{id:null}).id===item.id}"
                class="list-group-item d-flex justify-content-between">{{ item.command || '-' }} <small class="fw-bold">#{{
                    item.id
                }}</small>
            </li>
        </ul>
        <h6 class="fw-bold">Редактор</h6>
        <SlugForm :item="selected_script"
                  v-on:callback="callbackScriptChange"
                  v-if="load_script"/>
    </template>


</template>
<script>
export default {
    data() {
        return {
            scripts: [],
            selected_script: null,
            load_script: true,
            tab: 0,
            need_cashback_config: false,
            need_cashback_rules: false,
            need_cashback_fired: false,
            warnings: [
                {
                    title: "Сумма чека больше чем",
                    key: "bill_sum_more_then"
                },
                {
                    title: "Сумма начисления кэшбэка больше чем",
                    key: "cashback_up_sum_more_then"
                },
                {
                    title: "Сумма списания кэшбэка больше чем",
                    key: "cashback_down_sum_more_then"
                }
            ],
            cashback_fire_periods: [
                {
                    title: 'Не сгорает',
                    value: 0,
                },
                {
                    title: '7 дней',
                    value: 7,
                },
                {
                    title: '15 дней',
                    value: 15,
                },
                {
                    title: '30 дней',
                    value: 30,
                },
                {
                    title: '60 дней',
                    value: 60,
                },
                {
                    title: '60 дней',
                    value: 90,
                },
                {
                    title: '120 дней',
                    value: 120,
                },
                {
                    title: '180 дней',
                    value: 180,
                },
                {
                    title: '360 дней',
                    value: 360,
                }
            ],
            selected_warning: null,
            botForm: {
                warnings: [],
                payment_provider_token: null,
                auto_cashback_on_payments: false,
                level_1: 0,
                level_2: 0,
                level_3: 0,
                max_cashback_use_percent: 0,
                cashback_config: []
            },
            companyForm: {
                id: null,
                title: null,
                description: null,
                address: null,
                phones: [],
                links: {
                    vk: null,
                    inst: null,
                    map_link: null,
                    site: null,
                },
                email: null,

                schedule: [

                    {
                        day: 'Понедельник',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Вторник',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Среда',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Четверг',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Пятница',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Суббота',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },
                    {
                        day: 'Воскресенье',
                        start_at: '08:00',
                        end_at: '20:00',
                        closed: false,
                        closed_comment: 'Выходной',
                    },

                ],
            }

        }
    },
    computed: {
        currentBot() {
            return window.currentBot
        },
        filteredWarnings() {
            if (this.botForm.warnings.length === 0)
                return this.warnings;

            return this.warnings.filter(item => {
                return !(this.botForm.warnings.findIndex(sub => sub.rule_key === item.key) >= 0)
            })
        }
    },
    watch: {
        tab(oldV, newV) {
            if (this.tab === 2)
                this.loadFriendsScriptVariants()
        }
    },
    mounted() {
        const company = this.currentBot.company

        this.companyForm.id = company.id || null
        this.companyForm.title = company.title || null
        this.companyForm.description = company.description || null
        this.companyForm.email = company.email || null
        this.companyForm.address = company.address || null

        this.companyForm.phones = company.phones || ['+7']

        this.botForm.payment_provider_token = this.currentBot.payment_provider_token || null
        this.botForm.auto_cashback_on_payments = this.currentBot.auto_cashback_on_payments || false
        this.botForm.max_cashback_use_percent = this.currentBot.max_cashback_use_percent || 0
        this.botForm.level_1 = this.currentBot.level_1 || 0
        this.botForm.level_2 = this.currentBot.level_2 || 0
        this.botForm.level_3 = this.currentBot.level_3 || 0
        this.botForm.cashback_config = this.currentBot.cashback_config || []
        this.botForm.warnings = this.currentBot.warnings || []


        let isCorrectLinks = (links) => {
            if (!links)
                return false

            let params = ['inst', 'vk', 'map_link', 'site']
            let isCorrect = false
            let correctCount = 0;
            Object.keys(links).forEach(key => {
                if (params.indexOf(key) !== -1) {
                    isCorrect = true;
                    correctCount++;
                }

            })

            return isCorrect && correctCount === params.length;
        }

        this.companyForm.links = isCorrectLinks(company.links) ? company.links : {
            vk: null,
            inst: null,
            map_link: null,
            site: null,
        }

        let isCorrectSchedule = (schedule) => {
            if ((schedule || []).length < 7)
                return false

            let isCorrect = true
            schedule.forEach(day => {
                isCorrect = isCorrect && typeof day == 'object'
            })

            return isCorrect
        }

        this.companyForm.schedule = isCorrectSchedule(company.schedule) ? company.schedule : this.companyForm.schedule
    },
    methods: {
        submitBotForm() {
            let data = new FormData();
            Object.keys(this.botForm)
                .forEach(key => {
                    const item = this.botForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("updateBotParams", {
                botForm: data
            }).then((response) => {
                this.$notify({
                    title: "Информация о боте",
                    text: "Информация о боте успешно обновлена!",
                    type: "success"
                })
                this.$emit("callback", response.data)

                window.location.reload()
            }).catch(err => {
                this.$notify({
                    title: "Информация о боте",
                    text: "Ошибка обновления информации о боте",
                    type: "error"
                })
            })

        },
        callbackScriptChange() {
            this.loadFriendsScriptVariants()
        },
        loadFriendsScriptVariants() {
            this.selected_script = null
            this.load_script = false
            this.$store.dispatch("friendsLoadScriptVariants")
                .then((response) => {
                    this.load_script = true
                    this.scripts = response || []
                    if (this.scripts.length > 0)
                        this.selectScript(this.scripts[0])

                })
                .catch(err => {
                    this.load_script = true
                })
        },
        selectScript(item) {
            this.selected_script = null
            this.load_script = false
            this.$nextTick(() => {
                this.selected_script = item
                this.load_script = true
            })
        },

        submitCompanyForm() {
            let data = new FormData();
            Object.keys(this.companyForm)
                .forEach(key => {
                    const item = this.companyForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("updateCompany",
                {
                    companyForm: data
                }).then((response) => {
                this.$notify({
                    title: "Информация о компании",
                    text: "Информация о компании успешно обновлена!",
                    type: "success"
                })
                this.$emit("callback", response.data)

                window.location.reload()
            }).catch(err => {
                this.$notify({
                    title: "Информация о компании",
                    text: "Ошибка обновления информации о компании",
                    type: "error"
                })
            })

        },
        addWarning() {

            const item = this.selected_warning

            this.botForm.warnings.push({
                rule_key: item.key,
                rule_value: 0,
                is_active: true,
            })

            this.selected_warning = null

        },
        addCashBackConfig() {

            this.botForm.cashback_config = this.botForm.cashback_config == null ? [] : this.botForm.cashback_config;

            this.botForm.cashback_config.push({
                title: null,
            })

        },
        getWarning(key) {
            let item = this.warnings.find(item => item.key === key)


            return (!item) ? {
                title: 'Не найдено'
            } : item;

        },
        removeWarning(index) {
            this.botForm.warnings.splice(index, 1)
        },
        removeCashBackConfig(index) {
            this.botForm.cashback_config.splice(index, 1)
        },
    }
}
</script>

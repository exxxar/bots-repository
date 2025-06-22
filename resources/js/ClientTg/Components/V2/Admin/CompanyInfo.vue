<script setup>
import SlugForm from "@/ClientTg/Components/V2/Admin/Slugs/SlugForm.vue";
</script>
<template>


    <div class="row" style="position: sticky; top: 0px;z-index: 1000;">
        <div class="col-12">

            <div class="btn-group w-100 px-3 py-2" style="overflow-x:auto;">
            <button
                type="button"
                class="btn-info   btn p-3"
                @click="tab=0"
                style="white-space: nowrap;line-height:100%;"
                v-bind:class="{'active':tab===0}"
                aria-current="page"><i class="fa-solid fa-scroll mr-2"></i>Основное
            </button>

            <button
                type="button"
                class="btn-info   btn p-3"
                @click="tab=1"
                style="white-space: nowrap;line-height:100%;"
                v-bind:class="{'active':tab===1}"
                aria-current="page"><i class="fa-solid fa-coins mr-2"></i>Баллы
            </button>

            <button
                type="button"
                class="btn-info   btn p-3"
                @click="tab=2"
                style="white-space: nowrap;line-height:100%;"
                v-bind:class="{'active':tab===2}"
                aria-current="page"><i class="fa-solid fa-users mr-2"></i>Друзья
            </button>

                <button
                    type="button"
                    class="btn-info   btn p-3"
                    @click="tab=3"
                    style="white-space: nowrap;line-height:100%;"
                    v-bind:class="{'active':tab===3}"
                    aria-current="page"><i class="fa-solid fa-icons mr-2"></i>Иконки
                </button>

                <button type="button"
                        @click="goToEditor"
                       style="white-space: nowrap;line-height:100%;"
                        class="btn-primary btn p-3 "><i class="fa-solid fa-arrow-up-right-from-square mr-2"></i> Настройки магазина</button>

                <button type="button"
                        data-bs-dismiss="modal"
                        style="white-space: nowrap;line-height:100%;"
                        @click="goToIntegrations"
                        class="btn-primary  btn p-3 "><i class="fa-solid fa-arrow-up-right-from-square mr-2"></i> Интеграции</button>
            </div>
        </div>
    </div>

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
            Максимальное значение баллов, которое пользователь может списать во время покупки в магазине, в % от цены
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
            Уровни автоматического начисления баллов по реферальной программе, в %.
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
                    Необходимо настроить баллы по категориям
                </label>
            </div>

        </div>
        <div class="mb-2" v-if="need_cashback_config">
            <h6 class="opacity-75 my-2">Настройка категорий баллов</h6>

            <div class="alert alert-light" role="alert">
                Категории баллов - это возможность разделить накопления и траты баллов пользователями бота на
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

    <template v-if="tab===3">
        <div class="divider w-100 my-3">Пункты меню</div>
        <div class="row row-cols-1">
            <div class="col mb-2" v-for="(item, index) in  iconForm.items.length">
                <button
                    type="button"
                    v-bind:class="{
                        'btn-primary text-white':index===selected_menu_item_index,
                        'btn-outline-primary': index!==selected_menu_item_index
                     }"
                    @click="selectMenuItem(index)"
                    class="mb-0 btn w-100">{{ iconForm.items[index].title }}
                </button>
            </div>
        </div>
        <div class="divider w-100 my-3">Редактор</div>

        <div class="form-check form-switch">
            <input class="form-check-input"
                   v-model="iconForm.items[selected_menu_item_index].is_visible"
                   type="checkbox" role="switch" id="iconForm-is_visible">
            <label class="form-check-label" for="iconForm-is_visible">
                Виден пользователям:
                <span
                    v-bind:class="{'fw-bold text-primary':iconForm.items[selected_menu_item_index].is_visible}">виден</span>
                /
                <span v-bind:class="{'fw-bold text-primary':!iconForm.items[selected_menu_item_index].is_visible}">не виден</span>
            </label>
        </div>
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="iconForm.items[selected_menu_item_index].title"
                   required
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Название меню</label>
        </div>

        <template v-if="iconForm.items[selected_menu_item_index].has_icon">
            <div v-if="iconForm.items[selected_menu_item_index].image_url &&
        !photos_for_upload[iconForm.items[selected_menu_item_index].slug] ">
                <!--            <a
                                class="w-100 text-center mb-2"
                                @click="delete photos_for_upload[iconForm.items[selected_menu_item_index].slug]"
                                href="javascript:void(0)">Вернуть стандартное фото</a>-->
                <img
                    v-lazy="'/images/shop-v2-2/'+iconForm.items[selected_menu_item_index].image_url"
                    class="img-thumbnail w-100" alt="...">
            </div>
            <div v-else>
                <img
                    class="img-thumbnail w-100"
                    v-lazy="getPhoto(photos_for_upload[iconForm.items[selected_menu_item_index].slug]).imageUrl">
            </div>

            <p
                class="mb-0 w-100 text-center bg-success text-white rounded-2 my-2 p-3"
                v-if="photos_for_upload[iconForm.items[selected_menu_item_index].slug]">Фото добавлено в очередь,
                <a
                    class="text-white"
                    @click="delete photos_for_upload[iconForm.items[selected_menu_item_index].slug]"
                    href="javascript:void(0)">отменить?</a>
            </p>
            <div v-if="iconConfig.errorMessage" class="alert alert-danger my-2" v-html="iconConfig.errorMessage"></div>
            <div class="form-floating my-2">
                <input type="file" :id="'menu-photos-'+selected_menu_item_index" accept="image/*"
                       required
                       class="form-control"
                       @change="onChangePhotos($event, selected_menu_item_index)"
                />
                <label for="floatingInput">Изображение</label>
            </div>

        </template>


        <button
            @click="storeMenu"
            style="z-index: 100;"
            type="button" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>

    </template>

</template>
<script>
export default {
    data() {
        return {
            selected_menu_item_index: 0,
            scripts: [],
            photos_for_upload: [],
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
            iconConfig:{
                maxWidth: 500, // Максимальная ширина (px)
                maxHeight: 500, // Максимальная высота (px)
                maxSizeMB: 2, // Максимальный размер (MB)
                errorMessage: "",
            },
            iconForm: {
                items: []
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
        },
        'selected_menu_item_index': {
            handler: function (newValue) {
                this.iconConfig.errorMessage = ""
            },
            deep: true
        },
        'iconForm': {
            handler: function (newValue) {
               this.iconConfig.errorMessage = ""
            },
            deep: true
        },

    },
    mounted() {

        if (this.currentBot.settings)
            if ((this.currentBot.settings["icons"] || null) != null)
                this.iconForm.items = this.currentBot.settings["icons"]

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
        goToEditor(){
            this.$router.push({ name: 'MenuV2' })

            setTimeout(()=>{
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    const modalInstance = bootstrap.Modal.getInstance(openModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

                const modal = new bootstrap.Modal('#script-setting-editor', {})
                modal.show();

            }, 300)

        },
        goToIntegrations(){

            this.$preloader.show();
            this.$router.push({ name: 'IntegrationsV2' })


        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        selectMenuItem(index) {
           /* if (this.iconForm.items[index].has_icon) {
                let img = document.querySelector("#menu-photos-" + this.selected_menu_item_index)
                img.value = null
            }*/

            this.selected_menu_item_index = index
        },
        onChangePhotos(e, index) {
            const file = e.target.files[0]

            if (!file) return;

            const fileSizeMB = file.size / (1024 * 1024);
            if (fileSizeMB > this.iconConfig.maxSizeMB) {
                this.iconConfig.errorMessage = `Файл слишком большой. Максимальный размер: ${this.iconConfig.maxSizeMB}MB`;
                return;
            }

            const img = new Image();
            img.src = URL.createObjectURL(file);
            img.onload = () => {
                if (img.width > this.iconConfig.maxWidth || img.height > this.iconConfig.maxHeight) {
                    this.iconConfig.errorMessage = `Изображение слишком большое. Макс: ${this.iconConfig.maxWidth}x${this.iconConfig.maxHeight}px`;
                } else {
                    this.iconConfig.errorMessage = "";
                    const slug = this.iconForm.items[index].slug
                    this.photos_for_upload[slug] = file
                }
            };


        },
        storeMenu() {
            let data = new FormData();
            Object.keys(this.iconForm)
                .forEach(key => {
                    const item = this.iconForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            Object.keys(this.photos_for_upload).forEach(item => {
                data.append(item + '[]', this.photos_for_upload[item])
            })

            this.$store.dispatch("updateBotMenuItems", {
                iconForm: data
            }).then((response) => {
                this.$notify({
                    title: "Иконки",
                    text: "Иконки меню успешно обновлены!",
                    type: "success"
                })
                this.$emit("callback", response.data)

                //window.location.reload()
            }).catch(err => {
                this.$notify({
                    title: "Иконки",
                    text: "Ошибка обновления информации",
                    type: "error"
                })
            })
        },
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

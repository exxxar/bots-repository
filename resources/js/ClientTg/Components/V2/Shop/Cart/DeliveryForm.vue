<template>
    <template v-if="mode===1&&deliveryForm">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="deliveryForm.name"
                   class="form-control" id="deliveryForm-name"
                   placeholder="Иванов Иван Иванович" required>
            <label for="deliveryForm-name">Ф.И.О. <span class="fw-bold text-danger">*</span></label>
        </div>
        <div class="form-floating mb-2">
            <input type="text"
                   v-mask="'+7(###)###-##-##'"
                   v-model="deliveryForm.phone"
                   class="form-control" id="deliveryForm-phone"
                   placeholder="+7(000)000-00-00" required>
            <label for="deliveryForm-phone">Номер телефона <span class="fw-bold text-danger">*</span></label>
        </div>
    </template>


    <template v-if="mode===0&&deliveryForm">

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="deliveryForm.name"
                   class="form-control" id="deliveryForm-name"
                   placeholder="Иванов Иван Иванович" required>
            <label for="deliveryForm-name">Ф.И.О. <span class="fw-bold text-danger">*</span></label>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   v-mask="'+7(###)###-##-##'"
                   v-model="deliveryForm.phone"
                   class="form-control" id="deliveryForm-phone"
                   placeholder="+7(000)000-00-00" required>
            <label for="deliveryForm-phone">Номер телефона <span class="fw-bold text-danger">*</span></label>
        </div>

        <template v-if="!deliveryForm.need_pickup">
            <div

                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.city"
                       class="form-control" id="deliveryForm-city"
                       placeholder="Ваш город" required>
                <label for="deliveryForm-city">Ваш город <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.street"
                       class="form-control" id="deliveryForm-street"
                       placeholder="Улица" required>
                <label for="deliveryForm-street">Улица <span class="fw-bold text-danger">*</span></label>
            </div>


            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.building"
                       class="form-control" id="deliveryForm-building"
                       placeholder="Номер дома" required>
                <label for="deliveryForm-building">Номер дома <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.flat_number"
                       class="form-control" id="deliveryForm-flat-number"
                       placeholder="Номер квартиры">
                <label for="deliveryForm-flat-number">Номер квартиры </label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.entrance_number"
                       class="form-control" id="deliveryForm-entrance-number"
                       placeholder="Номер подъезда">
                <label for="deliveryForm-entrance-number">Номер подъезда</label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="deliveryForm.floor_number"
                       class="form-control" id="deliveryForm-floor-number"
                       placeholder="Номер этажа">
                <label for="deliveryForm-floor-number">Номер этажа</label>
            </div>

        </template>
        <template v-else>
            <template v-if="settings.need_table_list">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="need_select_table_by_number"
                           role="switch"
                           id="select-table-number-from-list">
                    <label class="form-check-label" for="select-table-number-from-list">Выбрать номер столика из
                        списка</label>
                </div>
                <div class="row row-cols-5" v-if="need_select_table_by_number">
                    <div class="col" v-for="num in parseInt(settings.max_tables)">
                        <a href="javascript:void(0)"
                           @click="deliveryForm.table_number=num"
                           v-bind:class="{'btn-primary':deliveryForm.table_number==num,'btn-outline-primary':deliveryForm.table_number!=num}"
                           class="btn w-100 mb-2">
                            {{ num }}
                        </a>
                    </div>
                </div>
            </template>
            <div
                class="form-floating mb-2">
                <input type="number"
                       min="1"
                       max="200"
                       v-model="deliveryForm.table_number"
                       class="form-control" id="deliveryForm-table-number"
                       placeholder="Номер столика">
                <label for="deliveryForm-table-number">Номер столика</label>
            </div>
        </template>

        <div class="form-floating">
            <textarea class="form-control"
                      v-model="deliveryForm.info"
                      style="height:200px;line-height:150%;"
                      placeholder="Информация" id="deliveryForm-info"></textarea>
            <label v-if="!deliveryForm.need_pickup" for="deliveryForm-info">Информация для доставщика</label>
            <label v-else for="deliveryForm-info">Информация для сотрудника</label>
        </div>

        <template v-if="!deliveryForm.need_pickup">
            <h6 class="opacity-75 mt-3">К какому времени приготовить?</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.when_ready}"
                   @click="deliveryForm.when_ready = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-stopwatch-20 mr-2"></i>
                    <span class="px-2">По готовности</span>
                </a>
                <a href="javascript:void(0)"
                   @click="deliveryForm.when_ready = false"
                   v-bind:class="{'active':!deliveryForm.when_ready}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-clock mr-2"></i>
                    <span class="px-2">К указанному времени</span>
                </a>

            </div>

            <div
                v-if="!deliveryForm.when_ready"
                class="form-floating">
                <input type="datetime-local"
                       v-model="deliveryForm.time"
                       class="form-control" id="deliveryForm-time" placeholder="Время доставки" required>
                <label for="deliveryForm-time">Время доставки</label>
            </div>
        </template>

        <template v-if="settings.need_health_restrictions||false">
            <h6 class="opacity-75 mt-3" v-if="!deliveryForm.need_pickup">Ограничения по здоровью</h6>

            <div class="list-group my-3" v-if="!deliveryForm.need_pickup">

                <a href="javascript:void(0)"
                   @click="deliveryForm.has_disability = false"
                   v-bind:class="{'active':!deliveryForm.has_disability}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-heart mr-2"></i>
                    <span class="px-2">Нет ограничений по здоровью</span>
                </a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':deliveryForm.has_disability}"
                   @click="deliveryForm.has_disability = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-house-medical-flag mr-2"></i>

                    <span class="px-2">Есть ограничения по здоровью</span>
                </a>
            </div>

            <div class="list-group my-3" v-if="deliveryForm.has_disability&&!deliveryForm.need_pickup">
                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-1"> <i class="fa-solid fa-head-side-mask mr-2"></i> Болею</label>
                    <input type="checkbox"
                           value="болею"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-1">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-ear-deaf mr-2"></i> Плохо слышит \ говорит</label>
                    <input type="checkbox"
                           value="плохо слышит или говорит"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-5">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-3"> <i class="fa-solid fa-glasses mr-2"></i> Слабовидящий</label>
                    <input type="checkbox"
                           value="слабовидящий"
                           class="form-check-input"
                           v-model="deliveryForm.disabilities" id="switch-3">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-4"> <i class="fa-solid fa-wheelchair mr-2"></i> Ограничения мобильности</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="ограничения мобильности"
                           v-model="deliveryForm.disabilities" id="switch-4">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-person-dots-from-line mr-2"></i> Пищевая
                        аллергия</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="пищевая аллергия"
                           v-model="deliveryForm.disabilities" id="switch-5">
                </a>

            </div>

            <div class="form-floating mb-2" v-if="deliveryForm.disabilities.indexOf('пищевая аллергия')!==-1">
                <input type="text" class="form-control"
                       v-model="deliveryForm.allergy"
                       id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Укажите на что аллергия
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>
        </template>

    </template>

</template>
<script>
export default {
    props: ["modelValue", "mode"],
    data() {
        return {
            deliveryForm: null,
            need_select_table_by_number: false,
        }
    },
    watch: {
        'deliveryForm': {
            handler: function (newValue) {

                localStorage.setItem("cashman_self_product_delivery_form_address", this.deliveryForm.address || '')
                localStorage.setItem("cashman_self_product_delivery_form_city", this.deliveryForm.city || '')
                localStorage.setItem("cashman_self_product_delivery_form_street", this.deliveryForm.street || '')
                localStorage.setItem("cashman_self_product_delivery_form_building", this.deliveryForm.building || '')
                localStorage.setItem("cashman_self_product_delivery_form_flat_number", this.deliveryForm.flat_number || '')
                localStorage.setItem("cashman_self_product_delivery_form_entrance_number", this.deliveryForm.entrance_number || '')


                if ((this.deliveryForm.disabilities || []).length > 0)
                    localStorage.setItem("cashman_self_product_delivery_form_entrance_disabilities", JSON.stringify(this.deliveryForm.disabilities || []))
                else
                    localStorage.removeItem("cashman_self_product_delivery_form_entrance_disabilities");


                this.$emit("update:modelValue", this.deliveryForm)
            },
            deep: true
        },
        'modelValue': {
            handler: function (newValue) {
                this.deliveryForm = newValue
            },
            deep: true
        },
    },
    mounted() {
        this.deliveryForm = this.modelValue

        this.deliveryForm.name = localStorage.getItem("cashman_self_product_delivery_form_name") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_name") : null

        this.deliveryForm.phone = localStorage.getItem("cashman_self_product_delivery_form_phone") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_phone") : null

        this.deliveryForm.address = localStorage.getItem("cashman_self_product_delivery_form_address") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_address") : null

        this.deliveryForm.city = localStorage.getItem("cashman_self_product_delivery_form_city") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_city") : null

        this.deliveryForm.street = localStorage.getItem("cashman_self_product_delivery_form_street") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_street") : null

        this.deliveryForm.building = localStorage.getItem("cashman_self_product_delivery_form_building") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_building") : null

        this.deliveryForm.flat_number = localStorage.getItem("cashman_self_product_delivery_form_flat_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_flat_number") : null

        this.deliveryForm.entrance_number = localStorage.getItem("cashman_self_product_delivery_form_entrance_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_entrance_number") : null

        this.deliveryForm.disabilities = localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities") != null ?
            JSON.parse(localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities")) : []

        if (this.deliveryForm.disabilities.length > 0)
            this.deliveryForm.has_disability = true
    },
    computed: {

        bot() {
            return window.currentBot
        },
        settings() {
            return this.bot.settings
        },


    },
}
</script>

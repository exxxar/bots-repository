<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Название оказываемой услуги">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название услуги

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="inlineQueryForm.title"
                       maxlength="255"
                       aria-describedby="service-title" required>
            </div>

            <div class="col-12 mb-3">

                <p v-if="categories">
                    Ранее созданные категории:
                    <span
                        @click="inlineQueryForm.category = category"
                        class="badge bg-primary mr-1 cursor-pointer" v-for="category in categories">{{category}}</span>
                </p>
                <label class="form-label"
                       id="service-category">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Категория услуги (используется для группировки)
                            </div>
                        </template>
                    </Popper>
                    Категория услуги
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="text" class="form-control"
                       placeholder="Категория услуги"
                       aria-label="Категория услуги"
                       v-model="inlineQueryForm.category"
                       maxlength="255"
                       aria-describedby="service-category" required>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="service-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация об оказываемой услуге
                            </div>
                        </template>
                    </Popper>
                    Описание услуги
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="inlineQueryForm.description">
                        Длина текста {{ inlineQueryForm.description.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание услуги"
                          aria-label="Описание услуги"
                          maxlength="255"
                          v-model="inlineQueryForm.description"
                          aria-describedby="service-description" required>
                    </textarea>

            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Цена оказываемой услуги">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Цена услуги, руб

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="number" class="form-control"
                       placeholder="Цена"
                       aria-label="Цена"
                       min="0"
                       v-model="inlineQueryForm.price"
                       aria-describedby="price" required>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Цена скидки на оказываемую услугу">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Цена скидки на услугу, руб

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="number" class="form-control"
                       placeholder="Цена"
                       aria-label="Цена"
                       min="0"
                       v-model="inlineQueryForm.discount_price"
                       aria-describedby="price" required>
            </div>


            <div class="col-12 mb-3">
                <label class="form-label" id="service-images">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Изображение, которое видит пользователь при выборе услуги
                            </div>
                        </template>
                    </Popper>
                    Изображение к услуге
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <BotMediaList
                    :need-photo="true"
                    :selected="inlineQueryForm.images"
                    v-on:select="selectPhotos"></BotMediaList>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="inlineQueryForm.need_prepayment"
                           type="checkbox" id="need_prepayment">
                    <label class="form-check-label" for="need_prepayment">
                        Необходима предоплата
                    </label>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="inlineQueryForm.id===null">Создать услугу</span>
                    <span v-else>Обновить услугу</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: [ "bot","inlineQuery"],
    data() {
        return {
            load: false,
            need_reset: false,
            categories: [],
            inlineQueryForm: {
                id:null,
                command:null,
                description:null,
                items:[]
            }
        }
    },

    watch: {
        inlineQueryForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {


        if (this.inlineQuery)
            this.$nextTick(() => {
                this.inlineQueryForm = {
                    id: this.inlineQuery.id || null,
                    command: this.inlineQuery.command || null,
                    description: this.inlineQuery.description || null,
                    items: this.inlineQuery.items || [],
                }
            })

    },
    methods: {

        selectPhotos(item) {
            if (!this.inlineQueryForm.images)
                this.inlineQueryForm.images = []

            let index = this.inlineQueryForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.inlineQueryForm.images.splice(index, 1)
            else
                this.inlineQueryForm.images.push(item.file_id)
        },
        resetForm() {

        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.inlineQueryForm)
                .forEach(key => {
                    const item = this.inlineQueryForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);
            data.append('appointment_event_id', this.eventId);

            this.$store.dispatch(this.inlineQueryForm.id === null ?
                    "addAppointmentService" :
                    "updateAppointmentService",
                {
                    appointmentServiceForm: data
                }).then((response) => {
                this.$emit("callback", response.data)

                this.$notify("Услуги успешно создано");
            }).catch(err => {
                this.$notify("Услуги создания события");
            })

        },

    }
}
</script>



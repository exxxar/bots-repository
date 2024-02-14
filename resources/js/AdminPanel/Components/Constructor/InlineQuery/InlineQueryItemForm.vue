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
                       v-model="serviceForm.title"
                       maxlength="255"
                       aria-describedby="service-title" required>
            </div>

            <div class="col-12 mb-3">

                <p v-if="categories">
                    Ранее созданные категории:
                    <span
                        @click="serviceForm.category = category"
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
                       v-model="serviceForm.category"
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
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="serviceForm.description">
                        Длина текста {{ serviceForm.description.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание услуги"
                          aria-label="Описание услуги"
                          maxlength="255"
                          v-model="serviceForm.description"
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
                       v-model="serviceForm.price"
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
                       v-model="serviceForm.discount_price"
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
                    :selected="serviceForm.images"
                    v-on:select="selectPhotos"></BotMediaList>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="serviceForm.need_prepayment"
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
                    <span v-if="serviceForm.id===null">Создать услугу</span>
                    <span v-else>Обновить услугу</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: ["eventId", "bot","service"],
    data() {
        return {
            load: false,
            need_reset: false,
            categories: [],
            serviceForm: {
                id:null,
                title: null,
                appointment_event_id: null,
                description: null,
                category: null,
                images: [],
                need_prepayment: false,
                price: 0,
                discount_price: 0,
            }
        }
    },

    watch: {
        serviceForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {
        this.loadAppointmentServiceCategories()

        console.log("Service form", this.service)
        if (this.service)
            this.$nextTick(() => {
                this.serviceForm = {
                    id: this.service.id || null,
                    appointment_event_id: this.service.appointment_event_id || null,
                    title: this.service.title || null,
                    category: this.service.category || null,
                    description: this.service.description || null,
                    images: this.service.images || [],
                    need_prepayment: this.service.need_prepayment || false,
                    price: this.service.price || 0,
                    discount_price: this.service.discount_price || 0,
                }

            })

    },
    methods: {
        loadAppointmentServiceCategories(){
          this.$store.dispatch("loadAppointmentServiceCategories",{
              dataObject: {
                  bot_id: this.bot.id || null,
                  event_id: this.eventId || null,
              }
          }).then(resp=>{
              this.categories = resp
          })
        },
        selectPhotos(item) {
            if (!this.serviceForm.images)
                this.serviceForm.images = []

            let index = this.serviceForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.serviceForm.images.splice(index, 1)
            else
                this.serviceForm.images.push(item.file_id)
        },
        resetForm() {

        },

        submitForm() {
            let data = new FormData();
            Object.keys(this.serviceForm)
                .forEach(key => {
                    const item = this.serviceForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);
            data.append('appointment_event_id', this.eventId);

            this.$store.dispatch(this.serviceForm.id === null ?
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



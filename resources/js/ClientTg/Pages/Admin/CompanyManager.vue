<script setup>
import Location from "@/ClientTg/Components/Admin/Location.vue";
</script>

<template>
    <div class="card card-style bg-1"

         style="height: 350px;">
        <div class="card-center"    v-if="company">
            <div class="w-100 d-flex justify-content-center p-3">
                <img
                    class="object-cover" style="width:100px; border-radius:50%;"
                    v-lazy="'/images-by-company-id/'+company.id+'/'+company.image" alt="">
            </div>

            <h2 class="color-white font-700 text-center mb-0">
                {{ company.title || 'Нет названия' }}
            </h2>
            <p class="color-white text-center opacity-60 mt-n1 mb-3">
                {{ company.address || 'Нет адреса' }}
            </p>
            <a href="javascript:void(0)"
               @click="step=0"
               v-bind:class="{'bg-highlight':step===0,'bg-gray2-light': step!==0}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                Информация о компании
            </a>
            <a href="javascript:void(0)"
               @click="step=1"
               v-bind:class="{'bg-highlight':step===1,'bg-gray2-light': step!==1}"
               class="btn btn-m font-900 text-uppercase btn-center-xl">
                Информация о расположении
            </a>
        </div>

        <div class="card-center" v-else>
            <p>Загружаем данные компании</p>
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border color-orange-dark" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>

    <div class="card card-style" v-if="step===0">
        <div class="content" v-if="company">
            <form v-on:submit.prevent="submitForm" >

                <label class="form-label d-flex justify-content-between" id="company-title">
                       <span>
                             <Popper content="Название вашей компании">
                            <i class="fa-regular fa-circle-question mr-1"></i>
                        </Popper>
                        Название компании
                       </span>

                    <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                </label>
                <input type="text" class="form-control "
                       placeholder="Название"
                       aria-label="Название"
                       v-model="companyForm.title"
                       maxlength="255"
                       aria-describedby="company-title" required>


                <label class="form-label d-flex justify-content-between mt-2" id="company-title">
                    <span>
                          <Popper content="Тип налогооблажения вашей компании">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Тип налогооблажения
                    </span>


                    <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                </label>
                <select type="text" class="form-control"
                        aria-label="Выберите налогооблажение"
                        v-model="companyForm.vat_code"
                        aria-describedby="company-vat-code" required>
                    <option :value="code.id" v-for="(code, index) in vat_codes">
                        {{ code.title || 'Не указано' }}
                    </option>
                </select>


                <label class="form-label d-flex justify-content-between mt-2"
                       id="company-slug">
                    <span>
                          <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Название компании на АНГЛИЙСКОМ<br>
                                без пробелов! можно использовать _<br>
                                Должно быть уникальным! Не отображается пользователю.
                            </div>
                        </template>
                    </Popper>
                    Домен компании
                    </span>

                    <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                </label>
                <input type="text" class="form-control"
                       placeholder="Мнемоническое имя"
                       aria-label="Мнемоническое имя"
                       v-model="companyForm.slug"
                       maxlength="255"
                       aria-describedby="company-slug" required>


                <label class="form-label d-flex justify-content-between flex-wrap mt-2" id="company-description">
                  <span>
                         <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Добавится в раздел "О Нас"</div>
                        </template>
                    </Popper>
                    Описание компании

                  </span>

                    <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

                    <small class="text-gray-400 w-100" style="font-size:10px;"
                           v-if="companyForm.description">
                        Длина текста {{ companyForm.description.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание компании"
                          aria-label="Описание компании"
                          v-model="companyForm.description"
                          aria-describedby="company-description" required></textarea>

                <!--- НОвый блок -->
                <label class="form-label"
                       id="company-address">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Где находится главное заведение компании!<br>Можно не указывать, т.к. есть
                                еще
                                "Локации"
                            </div>
                        </template>
                    </Popper>
                    Основной адрес компании</label>
                <input type="text" class="form-control"
                       placeholder="Адрес"
                       aria-label="Адрес"
                       maxlength="255"
                       v-model="companyForm.address"
                       aria-describedby="company-address">


                <label class="form-label"
                       id="company-email">Основная почта компании</label>
                <input type="email" class="form-control"
                       placeholder="Почтовый адрес"
                       aria-label="Почтовый адрес"
                       maxlength="255"
                       v-model="companyForm.email"
                       aria-describedby="company-email">


                <label class="form-label"
                       id="company-manager">Менеджер компании</label>
                <input type="text" class="form-control"
                       placeholder="Имя менеджера"
                       aria-label="Имя менеджера"
                       v-model="companyForm.manager"
                       maxlength="255"
                       aria-describedby="company-manager">

                <div class="divider divider-small my-3 bg-highlight "></div>
                <h6 class="d-flex justify-content-between">Логотип компании  <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span></h6>

                <div class="d-flex justify-content-center">
                    <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                        +
                        <input type="file" id="photos"
                               accept="image/*" @change="onChangePhotos"
                               style="display:none;"/>

                    </label>
                    <div class="mb-2  img-preview"
                         style="margin-right: 10px;"
                         v-if="photo">
                        <img v-lazy="getPhoto().imageUrl">
                        <div class="remove">
                            <a @click="photo=null"><i class="fa-regular fa-trash-can"></i></a>
                        </div>
                    </div>
                    <div class="mb-2 img-preview"
                         style="margin-right: 10px;"
                         v-if="companyForm.image">
                        <img v-lazy="'/images/'+companyForm.slug+'/'+companyForm.image">
                        <div class="remove">
                            <a @click="removeCompanyImage"><i class="fa-regular fa-trash-can"></i></a>
                        </div>
                    </div>
                </div>



                <div class="divider divider-small my-3  bg-highlight"></div>
                <h6>Телефонные номера</h6>
                <div class="d-flex justify-content-between">
                    <small>Номер</small>
                </div>

                <div class="d-flex justify-content-between mb-2"
                     :key="'phone'+index"
                     v-for="(item, index) in companyForm.phones">

                            <input type="text" class="form-control w-100 mr-2"
                                   v-mask="'+7(###)###-##-##'"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="Номер телефона"
                                   maxlength="255"
                                   v-model="companyForm.phones[index]"
                                   :aria-describedby="'company-phone-'+index">

                        <button
                            type="button"
                            @click="removeItem('phones', index)"
                            class="btn btn-outline-danger py-1 px-3 "><i class="fa-regular fa-trash-can"></i>
                        </button>

                </div>


                <button
                    type="button"
                    @click="addItem('phones')"
                    class="btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">Добавить еще номер
                </button>

                <div class="divider divider-small my-3  bg-highlight"></div>
                <h6>Ссылки на соц. сети</h6>


                <div class="d-flex justify-content-between">
                    <small>Ссылка</small>
                </div>


                <div class="d-flex justify-content-between mb-2"
                     :key="'link'+index"
                     v-for="(item, index) in companyForm.links">

                    <input type="text" class="form-control mr-2 w-100 "
                           placeholder="Ссылка на соц.сеть"
                           aria-label="Ссылка на соц.сеть"
                           maxlength="255"
                           v-model="companyForm.links[index]"
                           :aria-describedby="'company-link-'+index">

                    <button
                        type="button"
                        @click="removeItem('links', index)"
                        class="btn btn-outline-danger py-1 px-3 "><i class="fa-regular fa-trash-can"></i>
                    </button>

                </div>

                <button
                    type="button"
                    @click="addItem('links')"
                    class="btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                    Добавить еще ссылку
                </button>


                <div class="divider divider-small my-3  bg-highlight"></div>
                <h6>График работы</h6>


                <div class="d-flex justify-content-between align-items-center">
                    <small>День недели </small>
                    <a href="javascript:void(0)" @click="schedulePlaceholder"><small>Заполнить\очистить</small></a>
                </div>


                <div
                    :key="'link'+index"
                    class="d-flex justify-content-between mb-2"
                    v-for="(item, index) in companyForm.schedule">
                    <input type="text" class="form-control mr-2 w-100 "
                           placeholder="День недели и время работы"
                           aria-label="День недели и время работы"
                           maxlength="255"
                           v-model="companyForm.schedule[index]"
                           :aria-describedby="'company-schedule-'+index">

                    <button
                        type="button"
                        @click="removeItem('schedule', index)"
                        class="btn btn-outline-danger py-1 px-3"><i class="fa-regular fa-trash-can"></i>
                    </button>

                </div>
                <button
                    type="button"
                    :disabled="companyForm.schedule.length===7"
                    @click="addItem('schedule')"
                    class="btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                    Добавить еще время работы
                </button>

                <div class="divider divider-small my-3  bg-highlight"></div>

                <button
                    type="submit"
                    class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                    Сохранить данные компании
                </button>

            </form>
        </div>
        <div class="content" v-else>
           <p>Загружаем данные компании</p>
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border color-orange-dark" role="status">
                    <span class="sr-only">Загрузка...</span>
                </div>
            </div>
        </div>
    </div>


    <div  v-if="step===1">

            <Location v-if="company&&!load"
                      :company="company"
            />

    </div>



</template>

<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            step: 0,
            load: false,
            photo: null,
            removedImage: null,
            need_reset: false,
            vat_codes: [
                {
                    id: 1,
                    title: 'Общая система налогообложения'
                },
                {
                    id: 2,
                    title: 'Упрощенная (УСН, доходы)'
                },
                {
                    id: 3,
                    title: 'Упрощенная (УСН, доходы минус расходы)'
                },
                {
                    id: 4,
                    title: 'Единый налог на вмененный доход (ЕНВД)'
                },
                {
                    id: 5,
                    title: 'Единый сельскохозяйственный налог (ЕСН)'
                },
                {
                    id: 6,
                    title: 'Патентная система налогообложения'
                }
            ],
            company: null,
            companyForm: {
                id: null,
                title: null,
                slug: null,
                description: null,
                address: null,
                phones: [""],
                links: [""],
                email: null,
                vat_code: 1,
                schedule: [],
                manager: null,
            }
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'getCompany']),
    },
    watch: {
        companyForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {
        this.loadCompany()
    },
    methods: {
        resetForm() {
            this.photo = null
            this.removedImage = null
            this.companyForm = {
                id: null,
                title: null,
                slug: null,
                description: null,
                address: null,
                phones: [""],
                links: [""],
                email: null,
                schedule: [],
                manager: null,
                vat_code: 1,
            }
            this.$nextTick(() => {
                this.need_reset = false
            })

        },
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
            this.companyForm.image = null
        },

        schedulePlaceholder() {
            if (this.companyForm.schedule.length > 0) {
                this.companyForm.schedule = []
            } else {
                this.companyForm.schedule = [
                    "Понедельник - с 8:00 до 20:00",
                    "Вторник - с 8:00 до 20:00",
                    "Среда - с 8:00 до 20:00",
                    "Четверг - с 8:00 до 20:00",
                    "Пятница - с 8:00 до 20:00",
                    "Суббота - с 8:00 до 20:00",
                    "Воскресенье - выходной",
                ]
            }
        },
        removeCompanyImage() {
            this.removedImage = this.companyForm.image
            this.companyForm.image = null
        },
        addItem(name) {
            this.companyForm[name].push("")
        },
        removeItem(name, index) {
            this.companyForm[name].splice(index, 1)
        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.companyForm)
                .forEach(key => {
                    const item = this.companyForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('company_logo', this.photo);

            if (this.removedImage != null)
                data.append('removed_image', this.removedImage);

            this.$store.dispatch(this.companyForm.id === null ?
                    "createCompany" :
                    "updateCompany",
                {
                    companyForm: data
                }).then((response) => {
                this.$emit("callback", response.data)

                this.$notify("Компания успешно создана");
            }).catch(err => {
            })

        },
        loadCompany() {
            this.$store.dispatch("loadCompany").then(() => {
                this.$nextTick(() => {
                    const company = this.getCompany
                    this.company = company
                    this.companyForm = {
                        id: company.id || null,
                        title: company.title || null,
                        slug: company.slug || null,
                        image: company.image || null,
                        description: company.description || null,
                        address: company.address || null,
                        phones: company.phones || [""],
                        links: company.links || [""],
                        email: company.email || null,
                        vat_code: company.vat_code || 1,
                        schedule: company.schedule || [],
                        manager: company.manager || null,
                    }

                })
            })
        }

    }
}
</script>

<style lang="scss">

.popper {
    width: 100% !important;
    background: #323133 !important;
    padding: 10px !important;
    border-radius: 10px !important;
    color: white !important;
}


.img-preview,
.photo-loader {
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 42px;
    background: white;
    border-radius: 10px;
    border: 1px lightgray solid;

    position: relative;

}

.img-preview img,
.photo-loader img {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lime {
    color: lime;
}

.img-preview .remove {
    display: none;
}

.img-preview:hover .remove {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 10px;
    cursor: pointer;
}

.img-preview:hover .remove a {
    font-size: 12px;
    color: white;
}
</style>

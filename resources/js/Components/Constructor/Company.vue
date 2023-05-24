<template>
    <hr>
    <div class="row mb-2">
        <div class="col-12">
            <button
                @click="resetForm"
                class="btn btn-outline-success">Новая компания \ очистка формы</button>
        </div>
    </div>
    <form v-on:submit.prevent="submitForm">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label" id="company-title">
                        <Popper content="Название вашей компании">
                            <i class="fa-regular fa-circle-question mr-1"></i>
                        </Popper>
                        Название компании

                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                    </label>
                    <input type="text" class="form-control"
                           placeholder="Название"
                           aria-label="Название"
                           v-model="companyForm.title"
                           maxlength="255"
                           aria-describedby="company-title" required>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-slug">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Название компании на АНГЛИЙСКОМ<br>
                                    без пробелов! можно использовать _<br>
                                    Должно быть уникальным! Не отображается пользователю.
                                </div>
                            </template>
                        </Popper>
                        Название компании латиницей (домен компании)
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="Мнемоническое имя"
                           aria-label="Мнемоническое имя"
                           v-model="companyForm.slug"
                           maxlength="255"
                           aria-describedby="company-slug" required>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label " id="company-description">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Добавится в раздел "О Нас"</div>
                            </template>
                        </Popper>
                        Описание компании
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="companyForm.description">
                            Длина текста {{ companyForm.description.length }}</small>
                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание компании"
                              aria-label="Описание компании"
                              v-model="companyForm.description"
                              aria-describedby="company-description" required>
                    </textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-address">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Где находится главное заведение компании!<br>Можно не указывать, т.к. есть еще
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
                </div>
            </div>


            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-email">Основная почта компании</label>
                    <input type="email" class="form-control"
                           placeholder="Почтовый адрес"
                           aria-label="Почтовый адрес"
                           maxlength="255"
                           v-model="companyForm.email"
                           aria-describedby="company-email">
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"
                           id="company-manager">Менеджер компании</label>
                    <input type="text" class="form-control"
                           placeholder="Имя менеджера"
                           aria-label="Имя менеджера"
                           v-model="companyForm.manager"
                           maxlength="255"
                           aria-describedby="company-manager">
                </div>
            </div>

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Телефонные номера</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h6>Телефонный номер</h6>
                            </div>

                        </div>
                        <div class="row"
                             :key="'phone'+index"
                             v-for="(item, index) in companyForm.phones">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           v-mask="'+7(###)###-##-##'"
                                           placeholder="+7(000)000-00-00"
                                           aria-label="Номер телефона"
                                           maxlength="255"
                                           v-model="companyForm.phones[index]"
                                           :aria-describedby="'company-phone-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('phones', index)"
                                    class="btn btn-outline-danger w-100">Удалить
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    @click="addItem('phones')"
                                    class="btn btn-outline-success w-100">Добавить еще номер
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 ">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Ссылки на соц. сети</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h6>Ссылка</h6>
                            </div>

                        </div>
                        <div class="row"
                             :key="'link'+index"
                             v-for="(item, index) in companyForm.links">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="Ссылка на соц.сеть"
                                           aria-label="Ссылка на соц.сеть"
                                           maxlength="255"
                                           v-model="companyForm.links[index]"
                                           :aria-describedby="'company-link-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('links', index)"
                                    class="btn btn-outline-danger w-100">Удалить
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    @click="addItem('links')"
                                    class="btn btn-outline-success w-100">Добавить еще ссылку
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 ">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>График работы</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h6>День недели </h6>
                                <a class="btn btn-link" @click="schedulePlaceholder">Заполнить\очистить</a>
                            </div>

                        </div>
                        <div class="row"
                             :key="'link'+index"
                             v-for="(item, index) in companyForm.schedule">
                            <div class="col-10">
                                <div class="mb-3">
                                    <input type="text" class="form-control"
                                           placeholder="День недели и время работы"
                                           aria-label="День недели и время работы"
                                           maxlength="255"
                                           v-model="companyForm.schedule[index]"
                                           :aria-describedby="'company-schedule-'+index">
                                </div>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    @click="removeItem('schedule', index)"
                                    class="btn btn-outline-danger w-100">Удалить
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    :disabled="companyForm.schedule.length===7"
                                    @click="addItem('schedule')"
                                    class="btn btn-outline-success w-100">Добавить еще время работы
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 ">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Логотип компании <span class="badge rounded-pill text-bg-danger m-0">Нужно</span></h6>
                    </div>
                    <div class="card-body d-flex justify-content-start">

                        <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            +
                            <input type="file" id="photos"
                                   accept="image/*" @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>
                        <div class="mb-2 img-preview"
                             style="margin-right: 10px;"
                             v-if="photo">
                            <img v-lazy="getPhoto().imageUrl">
                            <div class="remove">
                                <a @click="photo=null">Удалить</a>
                            </div>
                        </div>


                        <div class="mb-2 img-preview"
                             style="margin-right: 10px;"
                             v-if="companyForm.image">
                            <img v-lazy="'/images/'+companyForm.slug+'/'+companyForm.image">
                            <div class="remove">
                                <a @click="removeCompanyImage">Удалить</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="companyForm.id===null">Создать компанию</span>
                    <span v-else>Обновить компанию</span>
                </button>
            </div>
        </div>
    </form>


</template>

<script>
export default {
    props: ["company"],
    data() {
        return {
            load: false,
            photo: null,
            removedImage: null,
            companyForm: {
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
            }
        }
    },

    mounted() {

        if (this.company)
            this.$nextTick(() => {
                this.companyForm = {
                    id: this.company.id || null,
                    title: this.company.title || null,
                    slug: this.company.slug || null,
                    image: this.company.image || null,
                    description: this.company.description || null,
                    address: this.company.address || null,
                    phones: this.company.phones || [""],
                    links: this.company.links || [""],
                    email: this.company.email || null,
                    schedule: this.company.schedule || [],
                    manager: this.company.manager || null,
                }

            })

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
            }

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

    }
}
</script>

<style>

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

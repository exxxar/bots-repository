<template>

    <form @submit.prevent="handleSubmit">

        <div class="form-check form-switch mb-2">
            <input
                class="form-check-input"
                type="checkbox"
                id="flexSwitchCheck"
                v-model="form.is_active"
            >
            <label class="form-check-label" for="flexSwitchCheck">
                Активен
            </label>
        </div>

        <!-- Заголовок -->
        <div class="mb-2 form-floating">
            <input
                type="text"
                class="form-control"
                id="title"
                v-model="form.title"
                placeholder="Введите заголовок"
            />
            <label for="title">Заголовок</label>
        </div>

        <!-- Описание -->
        <div class="mb-2 form-floating">
        <textarea
            class="form-control"
            id="description"
            style="min-height:200px;"
            v-model="form.description"
            placeholder="Введите описание"
        ></textarea>
            <label for="description">Описание</label>
        </div>

        <div class="form-floating mb-2 file-floating">
            <input
                type="file"
                class="form-control"
                id="imageInput"
                accept="image/*"
                @change="onFileChange"
            >
            <label for="imageInput">Загрузить изображение</label>
        </div>

        <div v-if="preview" class="mb-2">
            <div class="card">
                <div class="card-body">
                    <img :src="preview" class="img-fluid rounded border img-preview">
                </div>
            </div>

        </div>

        <div v-if="form.image" class="mb-2">
            <div class="card">
                <div class="card-body">
                    <img
                        v-lazy="'/images-by-bot-id/'+bot.id+'/'+form.image"
                        class="img-fluid rounded border img-preview">
                </div>
            </div>

        </div>


        <!-- Дополнительная плата -->
        <div class="mb-2 form-floating">
            <input
                type="number"
                class="form-control"
                id="extra_charge"
                v-model="form.extra_charge"
                placeholder="Введите дополнительную плату"
            />
            <label for="extra_charge">Дополнительная плата</label>
        </div>

        <div class="form-check form-switch mb-2">
            <input
                class="form-check-input"
                type="checkbox"
                id="flexSwitchCheck"
                v-model="form.demo_mode"
            >
            <label class="form-check-label" for="flexSwitchCheck">
                Режим тестирования
            </label>
        </div>

        <template v-if="!form.demo_mode">
            <!-- Навигация по табам -->
            <ul class="nav nav-tabs mb-2">
                <li class="nav-item" v-for="tab in tabs" :key="tab.key">
                    <button
                        type="button"
                        class="nav-link"
                        :class="{ active: activeTab === tab.key }"
                        @click="activeTab = tab.key"
                    >
                        {{ tab.label }}
                    </button>
                </li>
            </ul>

            <!-- Секция Партнёр -->
            <template v-show="activeTab === 'partner'">
                <h4>Данные партнёра</h4>
                <div class="row row-cols-1">
                    <div class="col mb-2 " v-for="(value, key) in form.legal_info.partner" :key="key">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control"
                                v-model="partner[key]"
                                :id="'partner_' + key"
                                placeholder=" "
                            />
                            <label :for="'partner_' + key">{{ labels.partner[key] || key }}</label>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Секция Контракты -->
            <template v-show="activeTab === 'contract'">
                <h4>Контракт</h4>
                <div class="row row-cols-1">
                    <div class="col mb-2" v-for="(value, key) in form.legal_info.contract" :key="key">

                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control"
                                v-model="form.legal_info.contract[key]"
                                :id="'contract_' + key "
                                placeholder=" "
                            />
                            <label :for="'contract_' + key ">{{ labels.contract[key] || key }}</label>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Секция Контакты -->
            <template v-show="activeTab === 'contacts'">
                <h4>Контактные лица</h4>
                <div
                    v-for="(contact, i) in form.legal_info.contacts"
                    :key="'contact-'+i"
                    class="rounded mb-2 position-relative"
                >
                    <div class="divider my-3">Контакт</div>
                    <div class="row row-cols-1">
                        <div class="col mb-2" v-for="(value, key) in contact" :key="key">

                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact[key]"
                                    :id="'contact_' + key + i"
                                    placeholder=" "
                                />
                                <label :for="'contact_' + key + i">{{ labels.contact[key] || key }}</label>
                            </div>
                        </div>
                    </div>
                    <button
                        class="btn btn-sm btn-danger w-100"
                        @click="form.legal_info.contacts.splice(i, 1)"
                    >
                        Удалить контакт
                    </button>


                </div>
                <button class="btn btn-outline-primary" @click="addContact">+ Добавить контакт</button>
            </template>

            <!-- Секция Документы -->
            <template v-show="activeTab === 'documents'">
                <h4>Документы</h4>
                <div
                    v-for="(doc, i) in form.legal_info.documents"
                    :key="'doc'+i"
                    class="border rounded p-3 mb-2 position-relative"
                >
                    <button
                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                        @click="form.legal_info.documents.splice(i, 1)"
                    >
                        Удалить документ
                    </button>

                    <div class="row row-cols-1">
                        <div class="col mb-2" v-for="(value, key) in doc" :key="key">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="doc[key]"
                                    :id="'doc_' + key + i"
                                    placeholder=" "
                                />
                                <label :for="'doc_' + key + i">{{ labels.document[key] || key }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary" @click="addDocument">+ Добавить документ</button>
            </template>


        </template>


        <nav

            class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
            style="border-radius:10px 10px 0px 0px;">
            <button type="submit" class="btn btn-primary w-100 p-3">Отправить</button>
        </nav>
        <!-- Кнопка отправки -->

    </form>

</template>

<script>
export default {
    props: ["initialData"],
    data() {
        return {
            file: null,
            preview: null,
            activeTab: 'partner',
            labels: {
                partner: {
                    organization_name: 'Название организации',
                    inn: 'ИНН',
                    ogrn: 'ОГРН',
                    kpp: 'КПП',
                    legal_address: 'Юридический адрес',
                    actual_address: 'Фактический адрес',
                    email: 'E-mail',
                    phone: 'Телефон',
                    status: 'Статус'
                },
                contract: {
                    contract_number: 'Номер договора',
                    contract_name: 'Название',
                    start_date: 'Дата начала',
                    end_date: 'Дата окончания',
                    contract_status: 'Статус',
                    total_amount: 'Сумма'
                },
                contact: {full_name: 'ФИО', position: 'Должность', email: 'E-mail', phone: 'Телефон'},
                document: {file_name: 'Файл', document_type: 'Тип документа'},
            },
            tabs: [
                {key: 'partner', label: 'Партнёр'},
                {key: 'contract', label: 'Договор'},
                {key: 'contacts', label: 'Контакты'},
                {key: 'documents', label: 'Документы'},
            ],
            form: {
                id:null,
                title: "",
                description: "",
                image: "",
                is_active: true,
                extra_charge: 0,
                demo_mode: true,
                config: {
                    excludes: [],
                    bg_color: 'transparent',
                },
                legal_info: {
                    partner: {
                        organization_name: "ООО «Альфа-Партнер»",
                        legal_form: "ООО",
                        inn: "7701234567",
                        ogrn: "1027700132195",
                        kpp: "770101001",
                        registration_date: "2015-04-10",
                        legal_address: "г. Москва, ул. Центральная, д. 12",
                        actual_address: "г. Москва, ул. Центральная, д. 14",
                        bank_name: "ПАО Сбербанк",
                        bik: "044525225",
                        checking_account: "40702810900000012345",
                        correspondent_account: "30101810400000000225",
                        email: "info@alfapartner.ru",
                        phone: "+7 (495) 123-45-67",
                        status: "Активен",
                        notes: "Надёжный партнёр, сотрудничество с 2018 года"
                    },
                    contacts: [
                        {
                            full_name: "Иванов Сергей Петрович",
                            position: "Генеральный директор",
                            email: "ivanov@alfapartner.ru",
                            phone: "+7 (495) 555-12-34",

                        },
                        {
                            full_name: "Кузнецова Анна Дмитриевна",
                            position: "Юрист",
                            email: "kuznetsova@alfapartner.ru",
                            phone: "+7 (495) 555-12-35",
                        }
                    ],
                    contracts: {
                        contract_number: "ДГ-2025/14",
                        contract_name: "Договор на оказание консультационных услуг",
                        contract_type: "Услуги",
                        start_date: "2025-02-01",
                        end_date: "2026-02-01",
                        contract_status: "Подписан",
                        total_amount: 1200000,
                        currency: "RUB",
                        payment_terms: "Оплата в течение 10 рабочих дней после выставления счёта",
                        responsible_person: "Петров И.А.",
                        signed_by: "Иванов С.П.",
                        signature_date: "2025-01-28",
                        auto_renewal: false,
                        termination_reason: null
                    },

                    documents: [
                        {
                            file_name: "Договор_ДГ-2025-14.pdf",
                            file_path: "/docs/contracts/ДГ-2025-14.pdf",
                            document_type: "Договор",
                            uploaded_at: "2025-01-28T14:12:00",
                            verified: true,
                            comment: "Подписан обеими сторонами"
                        }
                    ],


                }
            }
        };
    },
    computed: {
        bot() {
            return window.currentBot || null
        }
    },
    mounted() {
        if (this.initialData) {
            this.form = {...this.initialData}
        }
    },
    methods: {
        addContract() {
            this.form.legal_info.contracts.value.push({id: Date.now(), partner_id: 1})
        },
        addContact() {
            this.form.legal_info.contacts.value.push({id: Date.now(), partner_id: 1})
        }
        ,
        addDocument() {
            this.form.legal_info.documents.value.push({id: Date.now(), contract_id: 10})
        },
        onFileChange(e) {
            const file = e.target.files[0]
            this.file = file

            if (file) {
                this.preview = URL.createObjectURL(file)
            }

            this.$emit("select", file)
        },
        handleSubmit() {
            let data = new FormData();
            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.file) {
                data.append('file', this.file);
            }

            this.$store.dispatch("updatePartner", {
                form: data
            }).then((response) => {
                this.$notify({
                    title: "Отлично!",
                    text: "Параметры успешно сохранены",
                    type: "success"
                });
            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка сохранения параметров",
                    type: "error"
                });
            })
        }
    }
};
</script>

<style scoped>
.img-preview {
    height: 200px;
    width: 100%;
    object-fit: cover;
}
</style>

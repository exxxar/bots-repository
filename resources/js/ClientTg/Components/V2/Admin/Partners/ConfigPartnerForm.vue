<template>

    <form @submit.prevent="handleSubmit">
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
            rows="3"
            v-model="form.description"
            placeholder="Введите описание"
        ></textarea>
            <label for="description">Описание</label>
        </div>

        <!-- Изображение -->
        <div class="mb-2 form-floating">
            <input
                type="text"
                class="form-control"
                id="image"
                v-model="form.image"
                placeholder="Введите URL изображения"
            />
            <label for="image">Ссылка на изображение</label>
        </div>

        <!-- Активность -->
        <div class="mb-2 form-check">
            <input
                type="checkbox"
                class="form-check-input"
                id="is_active"
                v-model="form.is_active"
            />
            <label class="form-check-label" for="is_active">Активен</label>
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
        <div v-show="activeTab === 'partner'">
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
        </div>

        <!-- Секция Контракты -->
        <div v-show="activeTab === 'contract'">
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
        </div>

        <!-- Секция Контакты -->
        <div v-show="activeTab === 'contacts'">
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
        </div>

        <!-- Секция Документы -->
        <div v-show="activeTab === 'documents'">
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
        </div>





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
    props: ["partner"],
    data() {
        return {
            activeTab:'partner',
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
                title: "",
                description: "",
                image: "",
                is_active: true,
                extra_charge: 0,
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

        handleSubmit() {
            const formData = {...this.form};
            // Преобразуем JSON-строки в объекты, если они не пустые
            if (formData.config) {
                formData.config = JSON.parse(formData.config);
            }
            if (formData.legal_info) {
                formData.legal_info = JSON.parse(formData.legal_info);
            }
            console.log("Отправленные данные:", formData);
            // Здесь можно добавить логику отправки данных на сервер
        }
    }
};
</script>

<style scoped>
/* Добавьте стили, если необходимо */
</style>

<template>
    <form v-on:submit.prevent="submitForm">
        <div class="form-floating mb-3">
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
                maxlength="1000"
                required
                class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                style="height: 200px"></textarea>
            <label for="floatingTextarea2">Описание
                <span
                    class="ml-1"
                    style="font-size:10px;"
                    v-if="(companyForm.description||'').length>0">({{ companyForm.description.length }}/1000)</span>
            </label>
        </div>

        <h6 class="opacity-75 my-3">Контактная информация</h6>

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
        <h6 class="opacity-75 my-3">Расположение заведения</h6>
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
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения</button>
    </form>
</template>
<script>
export default {
    data() {
        return {
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
                    }
                ],
            }

        }
    },
    computed: {
        currentBot() {
            return window.currentBot
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

        let isCorrectLinks = (links) => {
            let params = ['inst', 'vk', 'map_link']
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

        this.companyForm.links = isCorrectLinks(company.links) ? company.links : this.companyForm.links

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
    }
}
</script>

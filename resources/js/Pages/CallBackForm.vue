<script setup>
defineProps({
    bot: {
        type: Object,
    },

});
</script>
<template>
    <div class="container pb-3 pt-3">

        <div class="row">
            <div class="col-12">
                <h4 class="mb-3 mt-3">Часто задаваемые вопросы</h4>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Что такое система CashBack?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show"
                             aria-labelledby="headingOne" data-bs-parent="#faq">
                            <div class="accordion-body">
                                <p>CashBack - это система, которая позволяет пользователям зарабатывать бонусы при
                                    оплате покупок в различных заведениях. В данном случае, CashBack начисляет бонусы
                                    после каждой покупки еды и напитков в заведении.</p>

                                <p>Для участия в программе нужно иметь профиль в системе, где вы сможете отслеживать
                                    количество накопленных бонусов и получать информацию о специальных предложениях и
                                    акциях.</p>

                                <p>Чтобы начислить бонусы, необходимо отсканировать свой QR-код из своего профиля после
                                    каждой покупки в заведении. Количество начисляемых бонусов зависит от условий
                                    программы и может изменяться.</p>

                                <p>CashBack позволяет пользователям получать дополнительные выгоды и бонусы при
                                    стандартных покупках в различных заведениях. Кроме того, это помогает заведениям
                                    удерживать постоянных клиентов и мотивировать их к повторным визитам.</p>

                                <p> Общие рекомендации использования системы CashBack:</p>
                                <ul>
                                    <li>Создайте профиль в системе и связанный с ним QR-код заранее.</li>
                                    <li>Проверяйте условия программы, чтобы понимать, какие покупки позволят вам
                                        заработать больше бонусов.
                                    </li>
                                    <li>Следите за акциями и специальными предложениями, чтобы получить дополнительные
                                        бонусы.
                                    </li>
                                    <li>Не забывайте отсканировать свой QR-код после каждой покупки, чтобы начислить
                                        бонусы.
                                    </li>
                                    <li>Используйте накопленные бонусы для оплаты следующих покупок или обменяйте их на
                                        подарки и скидки.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour"
                                    aria-expanded="false" aria-controls="collapseFour">
                                О нашем продукте
                            </button>
                        </h2>
                        <div id="collapseFour"
                             class="accordion-collapse collapse"
                             aria-labelledby="headingFour"
                             data-bs-parent="#faq">
                            <div class="accordion-body">
                                <p>Для начала немного о нашем продукте: Кэш Бэк Бот - это телеграм бот с несколькими
                                    инновационными и очень важными функциями для любого бизнеса:</p>
                                <ol>
                                    <li>
                                        Это 2х или 3х уровневая система лояльности на основе Кеш Бэка, которая
                                        встраивается в телеграмм предприятия, она дает и списывает Кеш бек с помощью
                                        нескольких простых действий. Так же она привлекает клиентов, возвращает их снова
                                        и снова, а еще она мотивирует их рекомендовать Ваш бизнес своим друзьям и
                                        знакомым с помощью множества удобных функционалов.
                                    </li>
                                    <li>
                                        Это Ваша новая социальная сеть, где вы можете вести блог о вашем бизнесе, а так
                                        же делать рекламу и управлять клиентской базой. Очень важно, что на телеграмм не
                                        распространились санкции, поэтому привлекать новых клиентов легче, чем например
                                        через инстаграм.
                                    </li>
                                    <li>
                                        Клиентская база, которая формируется автоматически по вашему запросу (пол,
                                        возраст, район проживания клиента и т.д), с номерами клиентов и гостей вашего
                                        бизнеса, которым можно делать рассылку с помощью бота (совершенно бесплатно)
                                    </li>
                                    <li>
                                        Удобное меню и логистика по боту, с помощью кнопок в меню: прайс, фото,
                                        контактные данные, меню, акции, услуги и так далее, все что может помочь клиенту
                                        узнать о вас все.
                                    </li>
                                </ol>
                                <p>Вообщем Кеш Бек Бот это супер технологичный, современный и удобный сервис, который
                                    включает самые лучшие рекламные и маркетинговые ресурсы для Вашего бизнеса. На
                                    данный момент сложно себе представить, ресурс который лучше справиться с функцией
                                    увеличения продаж и прибыли в Вашем бизнесе.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                Как это работает?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse"
                             aria-labelledby="headingTwo" data-bs-parent="#faq">
                            <div class="accordion-body">
                                <iframe style="width: 100%; min-height: 400px;"
                                        src="https://www.youtube.com/embed/pxuuL7I4OBQ"
                                        title="Система Лояльности с помощью Телеграмм Бота" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Примеры работы бота
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse"
                             aria-labelledby="headingThree" data-bs-parent="#faq">
                            <div class="accordion-body">


                                <div class="list-group">
                                    <a :href="item.url"
                                       target="_blank"
                                       v-for="(item, index) in examples"
                                       class="list-group-item list-group-item-action"
                                       aria-current="true">
                                        {{ item.title || 'Не указан заголовок' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h4 class="mb-3 mt-3">Задать свой вопрос</h4>

                <form v-on:submit.prevent="submit">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="callbackForm.responseType"
                               type="checkbox"
                               role="switch"
                               id="callbackForm-responseType-1">
                        <label class="form-check-label" for="callbackForm-responseType-1">
                            <span v-if="callbackForm.responseType">Ответить на почту</span>
                            <span v-else>Ответить по телефону</span>
                        </label>
                    </div>

                    <div class="mb-3" v-if="!callbackForm.responseType">
                        <label for="callbackForm-phone-1"
                               class="form-label">Телефон для ответа</label>
                        <input type="text"
                               class="form-control" id="callbackForm-phone-1"
                               v-mask="'+7(###)###-##-##'"
                               v-model="callbackForm.phone"
                               placeholder="+7(000)000-00-00" required>
                    </div>
                    <div class="mb-3" v-if="callbackForm.responseType">
                        <label for="callbackForm-email-1"
                               class="form-label">Почта для ответа</label>
                        <input type="email" class="form-control"
                               v-model="callbackForm.email"
                               id="callbackForm-email-1"
                               placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="callbackForm-name-1"
                               class="form-label">Как к вам обращаться?</label>
                        <input type="text" class="form-control"
                               id="callbackForm-name-1"
                               v-model="callbackForm.name"
                               placeholder="Иванов Иван Иванович" required>
                    </div>
                    <div class="mb-3">
                        <label for="callbackForm-message-1" class="form-label">Ваш вопрос</label>
                        <textarea class="form-control"
                                  v-model="callbackForm.message"
                                  placeholder="Текст вашего вопроса"
                                  id="callbackForm-message-1" rows="3">
                    </textarea>
                    </div>

                    <div class="card w-100 mb-3 mt-3 border-success">
                        <div class="card-body">
                            <p>Перед отправкой данных ознакомьтесь с <a href="#">правилами данного сервиса</a> и с <a
                                href="#">политикой конфиденциальности</a>.</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       v-model="confirm"
                                       type="checkbox" role="switch" id="confirm">
                                <label class="form-check-label" for="confirm">С правилами ознакомлен</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                            :disabled="!confirm"
                            class="btn btn-outline-success w-100">Отправить вопрос
                    </button>
                </form>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-12">
                <h4>Контактная информация</h4>
                <p>Телефон: <strong><a href="tel:+79490000000">+7(949)000-00-00</a></strong></p>
                <p>Почта: <strong><a href="mailto:inbox@your-cashman.com">inbox@your-cashman.com</a></strong></p>
                <p>Мы в телеграмм: <strong><a href="https://t.me/your-cashman">https://t.me/your-cashman</a></strong>
                </p>
                <p>Мы в инсте: <strong><a href="">@your.cashman</a></strong></p>
                <p>Мы в вк: <strong><a href="">your.cashman</a></strong></p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            examples: [
                {
                    title: "ARCoffee - бот для кофейни",
                    url: "https://t.me/arcoffee_dn_bot"
                },
                {
                    title: "Д•Р•У•З•Ь•Я - бот для ресторана",
                    url: "https://t.me/friends_coffee_bot"
                },
                {
                    title: "Амати бот - Клуб красоты Амати",
                    url: "https://t.me/amati_beauty_bot"
                }
            ],
            confirm: false,
            callbackForm: {
                email: null,
                phone: null,
                message: null,
                name: null,
                responseType: 0,
            }
        }
    },
    methods: {
        submit() {

        }
    }
}
</script>

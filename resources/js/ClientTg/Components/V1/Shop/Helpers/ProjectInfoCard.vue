<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style">
        <div class="content mb-2">
            <h3>Часто задаваемые вопросы</h3>
            <p class="color-highlight font-12 mt-n2 mb-2">Действительно важные вопросы</p>
            <p>
                Нам часто задают эти вопросы, поэтому мы создали этот небольшой раздел, чтобы помочь вам понять как работает наша система.
            </p>

            <div class="divider mt-2 mb-2"></div>

            <div class="d-flex">
                <div class="pt-1">
                    <h5 data-activate="question-1" class="font-600">Что такое система CashBack?</h5>
                </div>
                <div class="ml-auto mr-1 pr-2">
                    <div class="custom-control classic-switch">
                        <input type="checkbox" class="classic-input" id="question-1">
                        <label class="custom-control-label" for="question-1"></label>
                        <i class="fa fa-angle-down font-11 color-green1-dark"></i>
                    </div>
                </div>
            </div>
            <div data-switch="question-1" class="switch-is-unchecked">
                <p class="mb-1">CashBack - это система, которая позволяет пользователям зарабатывать бонусы при
                    оплате покупок в различных заведениях. В данном случае, CashBack начисляет бонусы
                    после каждой покупки еды и напитков в заведении.</p>

                <p class="mb-1">Для участия в программе нужно иметь профиль в системе, где вы сможете отслеживать
                    количество накопленных бонусов и получать информацию о специальных предложениях и
                    акциях.</p>

                <p class="mb-1">Чтобы начислить бонусы, необходимо отсканировать свой QR-код из своего профиля после
                    каждой покупки в заведении. Количество начисляемых бонусов зависит от условий
                    программы и может изменяться.</p>

                <p class="mb-1">CashBack позволяет пользователям получать дополнительные выгоды и бонусы при
                    стандартных покупках в различных заведениях. Кроме того, это помогает заведениям
                    удерживать постоянных клиентов и мотивировать их к повторным визитам.</p>

                <p class="mb-1"> Общие рекомендации использования системы CashBack:</p>
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

            <div class="divider mt-2 mb-2"></div>

            <div class="d-flex">
                <div class="pt-1">
                    <h5 data-activate="question-2" class="font-600"> О нашем продукте</h5>
                </div>
                <div class="ml-auto mr-1 pr-2">
                    <div class="custom-control classic-switch">
                        <input type="checkbox" class="classic-input" id="question-2">
                        <label class="custom-control-label" for="question-2"></label>
                        <i class="fa fa-angle-down font-11 color-green1-dark"></i>
                    </div>
                </div>
            </div>
            <div data-switch="question-2" class="switch-is-unchecked">
                <p class="mb-2">Для начала немного о нашем продукте: Кэш Бэк Бот - это телеграм бот с несколькими
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
                        узнать о Вас все.
                    </li>
                </ol>
                <p class="mb-2">Вообщем Кеш Бек Бот это супер технологичный, современный и удобный сервис, который
                    включает самые лучшие рекламные и маркетинговые ресурсы для Вашего бизнеса. На
                    данный момент сложно себе представить, ресурс который лучше справиться с функцией
                    увеличения продаж и прибыли в Вашем бизнесе.</p>
            </div>

            <div class="divider mt-2 mb-2"></div>

            <div class="d-flex">
                <div class="pt-1">
                    <h5 data-activate="question-3" class="font-600"> Как это работает?</h5>
                </div>
                <div class="ml-auto mr-1 pr-2">
                    <div class="custom-control classic-switch">
                        <input type="checkbox" class="classic-input" id="question-3">
                        <label class="custom-control-label" for="question-3"></label>
                        <i class="fa fa-angle-down font-11 color-green1-dark"></i>
                    </div>
                </div>
            </div>
            <div data-switch="question-3" class="switch-is-unchecked">
                <iframe style="width: 100%;"
                        src="https://www.youtube.com/embed/pxuuL7I4OBQ"
                        title="Система Лояльности с помощью Телеграмм Бота" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
            </div>

            <div class="divider mt-2 mb-2"></div>

            <div class="d-flex">
                <div class="pt-1">
                    <h5 data-activate="question-4" class="font-600"> Примеры работы бота</h5>
                </div>
                <div class="ml-auto mr-1 pr-2">
                    <div class="custom-control classic-switch">
                        <input type="checkbox" class="classic-input" id="question-4">
                        <label class="custom-control-label" for="question-4"></label>
                        <i class="fa fa-angle-down font-11 color-green1-dark"></i>
                    </div>
                </div>
            </div>
            <div data-switch="question-4" class="switch-is-unchecked">

                <div class="list-group list-custom-small">
                    <a href="#"
                       @click="open(item.url)"
                       v-for="(item, index) in examples">
                        <i class="fa-solid fa-robot font-14 color-brown1-dark"></i>
                        <span>  {{ item.title || 'Не указан заголовок' }}</span>
                        <i class="fa fa-angle-right"></i>
                    </a>

                </div>


            </div>

            <ReturnToBot class="my-2"/>
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

        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        // baseJS.handler()
    },
    methods:{
        open(url) {
            this.tg.openLink(url)
        },
    }
}
</script>

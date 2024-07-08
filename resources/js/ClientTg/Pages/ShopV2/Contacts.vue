<template>

    <div class="container py-3">
        <div
            v-html="map"
            class="d-flex" style="min-height:300px;">
        </div>
        <h6 class="my-3">г. Донецку, ул. Артема, 165</h6>
        <h6 class="opacity-75 mb-3">Контактная информация</h6>

        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span class="text-primary fw-bold">7(949)000-00-00</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Инста</span>
                <span class="text-primary fw-bold">@test_insta_profile</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Вконтакте</span>
                <span class="text-primary fw-bold">https://vk.com/test_vk_public</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Почта</span>
                <span class="text-primary fw-bold">test@gmail.com</span>
            </li>

        </ul>

        <h6 class="opacity-75 my-3">Прием заказов осуществляется</h6>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Понедельник</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Вторник</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Среда</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Четверг</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Пятница</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Суббота</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Воскресенье</span>
                <span class="text-primary fw-bold">10:00 - 21:59</span>
            </li>
        </ul>
    </div>
</template>
<script>

export default {
    data() {
        return {
            map: '<div style="position:relative;overflow:hidden;"><a href="https://yandex.ru/maps/24880/khartsyzk/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Харцызск</a><a href="https://yandex.ru/maps/24880/khartsyzk/?ll=38.145854%2C48.040252&mode=whatshere&utm_medium=mapframe&utm_source=maps&whatshere%5Bpoint%5D=38.144137%2C48.042410&whatshere%5Bzoom%5D=14&z=14" style="color:#eee;font-size:12px;position:absolute;top:14px;">Харцызск — Яндекс Карты</a><iframe src="https://yandex.ru/map-widget/v1/?ll=38.145854%2C48.040252&mode=whatshere&whatshere%5Bpoint%5D=38.144137%2C48.042410&whatshere%5Bzoom%5D=14&z=14" width="560" height="400" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe></div>\n'
        }
    },
    mounted() {
        this.tg.BackButton.hide()
    },
}
</script>

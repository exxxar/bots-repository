
<template>


    <table class="table table-borderless  rounded-sm shadow-l" style="overflow: hidden;">
        <thead>
        <tr class="bg-gray1-dark">
            <th scope="col" class="color-theme">Ключ</th>
            <th scope="col" class="color-theme">Значение</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">Имя из телеграмма</th>
            <td class="font-weight-bold">{{botUser.fio_from_telegram||'Не указано'}}</td>

        </tr>

        <tr v-bind:class="{'bg-red1-light':botUser.phone==null}">
            <th scope="row">Номер телефона</th>
            <td class="font-weight-bold">{{botUser.phone||'Не указано'}}</td>

        </tr>
        <tr v-bind:class="{'bg-red1-light':botUser.age==null}">
            <th scope="row">Возраст</th>
            <td class="font-weight-bold">{{botUser.age||'Не указано'}}</td>

        </tr>
        <tr>
            <th scope="row">Пол</th>
            <td class="font-weight-bold">{{botUser.sex?'Мужчина':'Женщина'}}</td>

        </tr>

        <tr v-bind:class="{'bg-red1-light':botUser.country==null}">
            <th scope="row">Страна</th>
            <td class="font-weight-bold">{{botUser.country||'Не указано'}}</td>

        </tr>

        <tr v-bind:class="{'bg-red1-light':botUser.city==null}">
            <th scope="row">Город</th>
            <td class="font-weight-bold">{{botUser.city||'Не указано'}}</td>

        </tr>

        <tr v-bind:class="{'bg-red1-light':botUser.address==null}">
            <th scope="row">Адрес</th>
            <td class="font-weight-bold">{{botUser.address||'Не указано'}}</td>

        </tr>

        <tr >
            <th scope="row">VIP</th>
            <td class="font-weight-bold">{{botUser.is_vip ? 'Да':'Нет'}}</td>

        </tr>

        <tr>
            <th scope="row">Администратор</th>
            <td class="font-weight-bold">{{botUser.is_admin ? 'Да':'Нет'}}</td>

        </tr>
        <tr>
            <th scope="row">На работе</th>
            <td class="font-weight-bold">{{botUser.is_work ? 'Да':'Нет'}}</td>

        </tr>

        <tr>
            <th scope="row">В заведении</th>
            <td class="font-weight-bold">{{botUser.user_in_location ? 'Да':'Нет'}}</td>

        </tr>

        </tbody>
    </table>
    <a href="#" class="btn btn-m btn-full mb-1 rounded-s text-uppercase font-900 shadow-s bg-brown2-dark">
        Редактировать пользователя
    </a>
</template>
<script>
export default {
    props: ["botUser"]
}
</script>

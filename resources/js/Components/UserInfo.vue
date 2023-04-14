
<template>
    <div class="card">
        <div class="card-header">
            <h4>Информация о пользователе</h4>
        </div>
        <div class="card-body">
            <p>Имя из телеграмма <strong>{{botUser.fio_from_telegram||'Не указано'}}</strong></p>
            <p>Возраст <strong>{{botUser.age||'Не указано'}}</strong></p>
            <p>День рождения <strong>{{botUser.birthday||'Не указано'}}</strong></p>
            <p>Пол <strong>{{botUser.sex?'Мужчина':'Женщина'}}</strong></p>
            <p>Страна <strong>{{botUser.country||'Не указано'}}</strong></p>
            <p>Город <strong>{{botUser.city||'Не указано'}}</strong></p>
            <p>Адрес <strong>{{botUser.address||'Не указано'}}</strong></p>
            <p>Явяется VIP <strong>{{botUser.is_vip ? 'Да':'Нет'}}</strong></p>
            <p>Явяется администратором данного бота <strong>{{botUser.is_admin ? 'Да':'Нет'}}</strong></p>
            <p v-if="botUser.is_admin">Работает в данный момент <strong>{{botUser.is_work ? 'Да':'Нет'}}</strong></p>
            <p>Пользователь в заведении <strong>{{botUser.user_in_location ? 'Да':'Нет'}}</strong></p>
        </div>
    </div>
</template>
<script>
export default {
    props: ["botUser"]
}
</script>

<template>
    <div id="cashback-item-info" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height:220px; display:block;"
         data-menu-effect="menu-over">

        <div class="w-100" v-if="item">
            <table class="table table-borderless  rounded-sm shadow-l m-0" style="overflow: hidden;">
                <thead>
                <tr class="bg-gray1-dark">
                    <th scope="col" class="color-theme">Параметр</th>
                    <th scope="col" class="color-theme">Значение</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Тип операции</th>
                    <td class="font-weight-bold">{{item.operation_type ? 'Начисление':'Списание'}}</td>

                </tr>
                <tr>
                    <th scope="row">Сумма CashBack, руб</th>
                    <td class="font-weight-bold">{{item.amount || 0}}</td>

                </tr>

                <tr v-if="item.operation_type">
                    <th scope="row">Уровень начисления</th>
                    <td class="font-weight-bold">{{item.level || 0}}</td>

                </tr>
                <tr v-if="item.operation_type">
                    <th scope="row">Сумма в чеке, руб</th>
                    <td class="font-weight-bold">{{item.money_in_check || 0}}</td>

                </tr>
                <tr>
                    <th scope="row">Дата операции</th>
                    <td class="font-weight-bold">{{$filters.current(item.created_at)}}</td>

                </tr>
                <tr>
                    <th scope="row">Описание операции</th>
                    <td class="font-weight-bold">{{item.description || 'Нет описания'}}</td>

                </tr>

                <tr>
                    <th scope="row">TG id сотрудника</th>
                    <td class="font-weight-bold">{{item.employee.telegram_chat_id || 'Не указано'}}</td>

                </tr>

                <tr>
                    <th scope="row">Имя сотрудника</th>
                    <td class="font-weight-bold">{{item.employee.fio_from_telegram || 'Не указано'}}</td>

                </tr>

                <tr>
                    <th scope="row">Телефон сотрудника</th>
                    <td class="font-weight-bold">{{item.employee.phone || 'Не указано'}}</td>

                </tr>

                </tbody>
            </table>
        </div>



    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            item: null
        }
    },
    mounted() {
        window.addEventListener("show-chashback-info", (e) => {
            this.item = e.detail.item || null

            console.log("tesst")

            this.$nextTick(() => {

                $('#cashback-item-info').showMenu();
            })
        });


    },
    methods: {}
}
</script>
<style>

</style>

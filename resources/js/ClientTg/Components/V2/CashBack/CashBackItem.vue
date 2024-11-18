<template>
    <div
        @click="open(item)"
        class="list-group-item d-flex flex-column">

        <div class="d-flex justify-content-between w-100">
            <div>
                <i class="fa fa-circle-up text-success mr-2" style="font-size:24px;" v-if="item.operation_type"></i>
                <i class="fa-regular fa-circle-down text-danger mr-2" style="font-size:24px;"  v-else></i>

                <span class="fw-bold">{{ item.amount || 0 }} руб. </span>
            </div>


            <span class="text-primary fw-bold" v-if="item.operation_type">чек {{ item.money_in_check || 0 }} руб.</span>
            <span class="text-primary fw-bold" v-else>списание</span>
        </div>

        <div class="w-100 mt-2" v-if="item.is_open">
            <table class="table table-borderless  rounded-sm shadow-l m-0  p-0"  style="overflow: hidden;">
                <thead>
                <tr class="bg-gray1-dark">
                    <th scope="col" class="color-theme">Параметр</th>
                    <th scope="col" class="color-theme">Значение</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Тип операции</th>
                    <td class="fw-bold text-primary">{{item.operation_type ? 'Начисление':'Списание'}}</td>

                </tr>
                <tr>
                    <th scope="row">Сумма баллов, руб</th>
                    <td class="fw-bold text-primary">{{item.amount || 0}}</td>

                </tr>

                <tr v-if="item.operation_type">
                    <th scope="row">Уровень начисления</th>
                    <td class="fw-bold text-primary">{{item.level || 0}}</td>

                </tr>
                <tr v-if="item.operation_type">
                    <th scope="row">Сумма в чеке, руб</th>
                    <td class="fw-bold text-primary">{{item.money_in_check || 0}}</td>

                </tr>
                <tr>
                    <th scope="row">Дата операции</th>
                    <td class="fw-bold text-primary">{{$filters.current(item.created_at)}}</td>

                </tr>
                <tr>
                    <th scope="row">Описание операции</th>
                    <td class="fw-bold text-primary">{{item.description || 'Нет описания'}}</td>

                </tr>

                <tr>
                    <th scope="row">TG id сотрудника</th>
                    <td class="fw-bold text-primary">{{item.employee.telegram_chat_id || 'Не указано'}}</td>

                </tr>

                <tr>
                    <th scope="row">Имя сотрудника</th>
                    <td class="fw-bold text-primary">{{item.employee.fio_from_telegram || 'Не указано'}}</td>

                </tr>

                <tr>
                    <th scope="row">Телефон сотрудника</th>
                    <td class="fw-bold text-primary">{{item.employee.phone || 'Не указано'}}</td>

                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    props:["item"],
    methods:{
        open(item){
            item.is_open = !(item.is_open || false)

        },
    }
}
</script>

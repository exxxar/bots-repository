<template>
    <div class="container py-3">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <div class="form-floating">
                <input type="number"
                       min="1"
                       v-model="table_number"
                       class="form-control" id="floatingInputGroup1" placeholder="Номер столика">
                <label for="floatingInputGroup1">Номер столика</label>
            </div>
        </div>
        <h6 class="divider fw-bold">или</h6>
        <div class="row row-cols-4 ">
            <div class="col mb-2" v-for="table in 30">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center mb-0"> {{table}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            table_number:null,
            selected_table:null,
            tables:[
                {
                    id:1,
                    reserved_time:null,//время резервирования
                    title:"Стол №1",
                    description:"Большой стол для мероприятий",
                    size:10, //лимит посетителей

                    waiter: null, //официант
                    //данные поля можно сделать в отдельной таблице многие ко многим
                    //или завести таблицу истории
                    persons:[], // персоны за столом
                    order:[],
                    start_at: null, //начал использоваться
                    closed_at: null, //закончил использоваться
                }
            ]
        }
    },
    computed: {
        bot() {
            return window.currentBot
        },
    },
    methods: {

    }
}
</script>

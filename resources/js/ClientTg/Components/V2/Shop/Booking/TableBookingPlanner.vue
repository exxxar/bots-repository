<script setup>
import BookingForm from "@/ClientTg/Components/V2/Shop/Booking/BookingForm.vue";
import AdminReservations from "@/ClientTg/Components/V2/Admin/Shop/Tables/AdminReservations.vue";
</script>

<template>
    <div class="p-2" v-if="bot">

        <!-- Список выбранных столиков -->
        <div class="p-2" v-if="sortedSelectedTables.length>0">

            <template v-if="self.is_admin">

                <AdminReservations></AdminReservations>
            </template>

            <h6 class="fw-bold">Столики на выбор</h6>
            <div

                class="row row-cols-2 "
            >
                <div class="col mb-2"
                     @click="openModal(table)"
                     v-for="(table, index) in sortedSelectedTables"
                     :key="index">

                    <div class="border d-flex flex-column px-3">
                        <img v-lazy="'/images/tables/'+table.image" alt="table" class="table-img-small"/>
                        <p class="fw-bold mb-2">Столик №{{ table.number }}</p>
                        <p>{{ table.description }}</p>
                    </div>

                </div>


            </div>
        </div>
        <p class="alert alert-light" v-else>
            Нет столиков для бронирования
        </p>
    </div>

    <!-- Определение Модального Окна -->
    <div class="modal fade" id="booking-modal" tabindex="-1" aria-labelledby="mySimpleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySimpleModalLabel">Бронирование столика</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <template v-if="selectedTable">
                        <BookingForm :table-info="selectedTable"></BookingForm>
                    </template>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Booking",
    data() {
        return {
            selectedTable: null
        };
    },
    computed: {
        self() {
            return window.self || null
        },
        bot() {
            return window.currentBot
        },
        sortedSelectedTables() {
            let settings = this.bot.settings

            if (!settings.tables_variants)
                return []
            return settings.tables_variants
                .sort((a, b) => a.seats - b.seats);
        }
    },
    mounted() {

    },

    methods: {
        openModal(table) {
            this.selectedTable = null
            this.$nextTick(() => {
                this.selectedTable = table
                const myModalElement = document.getElementById('booking-modal');
                const myModal = new bootstrap.Modal(myModalElement);
                myModal.show();
            })

        }
    },
};
</script>

<style scoped>
.hall-constructor {

}

.table-img-small {
    width: 100%;
    object-fit: cover;
}

.table-img {
    width: 100%;
    object-fit: cover;
    height: 150px;
}

</style>

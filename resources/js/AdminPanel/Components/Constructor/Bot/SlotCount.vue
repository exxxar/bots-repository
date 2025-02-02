<template>
    <h6>Ваши доступные слоты
        <p>Число слотов <strong>{{ botsCount+maxSlotsCount }}</strong>.
            Отображается ботов <strong>{{ botsCount }}</strong> шт. под вашим управлением</p>
    </h6>

    <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25"
         aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar progress-bar-striped bg-success"
             v-bind:style="{'width': slotsPercent+'%'}"></div>
    </div>

    <a href="#" >Пополнить слоты</a>
</template>
<script>
export default {
    props:["botsCount"],
    computed:{
        profile() {
            return window.profile || null
        },
        maxSlotsCount(){
            return this.profile?.manager?.max_bot_slot_count || 0
        },
        slotsPercent() {
            return (this.botsCount / this.maxSlotsCount) * 100
        },

    }
}
</script>

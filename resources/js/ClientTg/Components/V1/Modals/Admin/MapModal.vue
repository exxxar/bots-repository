<template>

    <div :id="'map-selector'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="500" data-menu-effect="menu-over"
         style="height: 500px;display: block;">
        <h1 class="text-center mt-3 text-uppercase font-700">Выбор координаты</h1>

        <YandexMap
            :coordinates="[coords.lat,coords.lon]"

            @click="onClick"

        >
            <YandexMarker
                v-if="load"
                :coordinates="[coords.lat,coords.lon]" :marker-id="123">
            </YandexMarker>
        </YandexMap>





    </div>



</template>
<script>
import {YandexMap, YandexMarker} from 'vue-yandex-maps'

export default {
    components: {YandexMap, YandexMarker},
    setup() {
        return {
            center: [45.035470, 38.975313],
        }
    },

    data(){
      return {
          load:true,
          coords:{
              lat:45.035470,
              lon:38.975313,
          }
      }
    },
    mounted() {

        window.addEventListener("open-map-modal", (e) => {
            $('#map-selector').showMenu();
        } );
    },
    methods: {

        onClick(e) {
            let tmp =  e.get('coords');
            this.load = false
            this.coords.lat = tmp[0]
            this.coords.lon = tmp[1]

            this.$nextTick(()=>{

                this.load = true
            })

            this.$botNotification.notification("Координаты", "Вы выбрали координаты")
         this.$botPages.mapCallback(this.coords)
        }
    },
   // props: ["coords", "multiply"]
}

</script>
<style>

.map {
    width:100%;
    height:100%;

}

.yandex-container,
.ymap-container {
    height: 100%;

}
</style>

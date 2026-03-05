<template>
    <div class="map-picker">

        <div class="btn-group mb-2">

            <button
                class="btn btn-outline-secondary"
                @click="changeLanguage('local')">
                LOCAL
            </button>

            <button
                class="btn btn-outline-secondary"
                @click="changeLanguage('ru')">
                RU
            </button>

            <button
                class="btn btn-outline-secondary"
                @click="changeLanguage('en')">
                EN
            </button>

        </div>

        <div class="row mb-2">

            <div class="col">
                <input
                    v-model="search"
                    @keyup.enter="searchAddress"
                    class="form-control"
                    placeholder="Поиск адреса"
                >
            </div>

            <div class="col-auto">
                <button class="btn btn-primary" @click="searchAddress">
                    Найти
                </button>
            </div>

            <div class="col-auto">
                <button class="btn btn-outline-secondary" @click="locateUser">
                    📡
                </button>
            </div>

        </div>

        <div ref="map" class="map"></div>

        <div class="mt-2 small text-muted">
            {{ address }} ({{lat}}, {{lng}})
        </div>

    </div>
</template>

<script>
import L from "leaflet"

export default {

    name: "MapPicker",

    props: {
        lat: Number,
        lng: Number,
        address: String
    },

    emits: ["update:lat","update:lng","update:address"],

    data(){
        return{
            map:null,
            marker:null,
            search:"",

            tileLayer: null,

            layers: {
                local: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                ru: "https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png",
                en: "https://{s}.tile.openstreetmap.de/{z}/{x}/{y}.png"
            },

            currentLang: "local"
        }
    },

    mounted(){
        this.initMap()
    },

    methods:{

        initMap(){

            let lat = this.lat || 47.4979
            let lng = this.lng || 19.0402

            this.map = L.map(this.$refs.map).setView([lat, lng], 15)

            this.tileLayer = L.tileLayer(this.layers.local, {
                attribution: "© OpenStreetMap"
            }).addTo(this.map)


            this.marker = L.marker([lat,lng]).addTo(this.map)

            this.map.on("click", this.onMapClick)
        },

        onMapClick(e){

            let {lat,lng} = e.latlng

            this.setMarker(lat,lng)

            this.reverseGeocode(lat,lng)
        },
        changeLanguage(lang) {

            this.currentLang = lang

            if (this.tileLayer)
                this.map.removeLayer(this.tileLayer)

            this.tileLayer = L.tileLayer(this.layers[lang], {
                attribution: "© OpenStreetMap"
            }).addTo(this.map)

        },
        setMarker(lat,lng){

            if(this.marker)
                this.map.removeLayer(this.marker)

            this.marker = L.marker([lat,lng]).addTo(this.map)

            this.map.setView([lat,lng],15)

            this.$emit("update:lat",lat)
            this.$emit("update:lng",lng)
        },

        async searchAddress(){

            if(!this.search) return

            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.search)}&accept-language=ru`

            let res = await fetch(url)
            let data = await res.json()

            if(!data.length) return

            let place = data[0]

            let lat = parseFloat(place.lat)
            let lng = parseFloat(place.lon)

            this.setMarker(lat,lng)

            this.$emit("update:address",place.display_name)
        },

        async reverseGeocode(lat,lng){

            let url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ru`

            let res = await fetch(url)
            let data = await res.json()

            if(data.display_name)
                this.$emit("update:address",data.display_name)
        },

        locateUser(){

            navigator.geolocation.getCurrentPosition(pos=>{

                let lat = pos.coords.latitude
                let lng = pos.coords.longitude

                this.setMarker(lat,lng)

                this.reverseGeocode(lat,lng)

            })
        }

    }

}
</script>

<style scoped>

.map{
    height:450px;
    width:100%;
    border-radius:10px;
}

</style>

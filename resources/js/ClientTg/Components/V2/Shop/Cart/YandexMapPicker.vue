<template>
    <div class="map-container">


        <div class="input-group mb-2">

            <div
                class="form-floating">
                <input type="text"
                       v-model="searchQuery"
                       @keyup.enter="searchAddress"
                       class="form-control" id="deliveryForm-city"
                       placeholder="Ваш город">
                <label for="deliveryForm-city">Адрес</label>
            </div>

            <button
                type="button"
                class="btn btn-primary" @click="searchAddress">Найти
            </button>
        </div>

        <div class="my-2 small" v-if="findAddress">
            <strong>Адрес:</strong> {{ findAddress }} ({{ coords.lat }}, {{ coords.lng }})
        </div>

        <div ref="map" class="map"></div>


    </div>
</template>

<script>
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

export default {
    name: "MapPickerVector",

    props: {
        ruNames: {
            type: Array,
            default: () => []
        },

        address: {
            type: String,
            default: ""
        },
        // ключ MapTiler или OpenMapTiles
        mapKey: {
            type: String,
            required: true
        }
    },
    emits: ["update:address"],
    watch: {
        'searchQuery': {
            handler: function (newValue) {
                localStorage.setItem("cashman_self_map_tile_search_query", this.searchQuery)
            },
            deep: true
        },
        'findAddress': {
            handler: function (newValue) {
                this.$emit("update:address", this.findAddress || this.searchQuery);
                this.$emit("update:lng", this.coords.lng);
                this.$emit("update:lat", this.coords.lat);


                localStorage.setItem("cashman_self_map_tile_search_query",  this.findAddress)

                window.dispatchEvent(new CustomEvent('change-delivery-address', {
                    detail: {
                        address: this.findAddress || this.searchQuery,
                        lng: this.coords.lng,
                        lat: this.coords.lat,
                    }
                }));
            },
            deep: true
        },
    },
    computed: {
        bot() {
            return window.currentBot
        },
        shopCoordsParsed() {
            const shopCoords = this.bot?.settings?.shop_coords ?? null

            if (!shopCoords) {
                return {
                    lat: 0,
                    lng: 0
                }
            }

            const coords = shopCoords.split(',')

            const lng= parseFloat(coords[0] ?? 0)
            const lat = parseFloat(coords[1] ?? 0)

            return {
                lat,
                lng
            }
        }
    },
    data() {
        return {
            map: null,
            marker: null,
            searchQuery: "",
            coords: {lat: null, lng: null},
            findAddress: ""
        };
    },

    mounted() {
        this.initMap();

        this.searchQuery = localStorage.getItem("cashman_self_map_tile_search_query") != null ?
            localStorage.getItem("cashman_self_map_tile_search_query") : null

    },

    methods: {
        initMap() {
            this.map = new maplibregl.Map({
                container: this.$refs.map,
                style: `https://api.maptiler.com/maps/streets/style.json?key=${this.mapKey}`,
                center: [this.shopCoordsParsed.lat || 0, this.shopCoordsParsed.lng || 0],
                zoom: 13
            });

            this.map.addControl(new maplibregl.NavigationControl());

            this.map.on("load", () => {
                this.applyRussianLabels();
            });

            this.map.on("click", (e) => {
                const {lng, lat} = e.lngLat;
                this.coords = {lat, lng};
                this.placeMarker(lng, lat);
                this.reverseGeocode(lat, lng);
            });
        },

        applyRussianLabels() {
            const layers = this.map.getStyle().layers;

            layers.forEach(layer => {
                if (layer.type === "symbol" && layer.layout && layer.layout["text-field"]) {
                    this.map.setLayoutProperty(layer.id, "text-field", [
                        "coalesce",
                        ["get", "name:ru"], // русский
                        ["get", "name"],    // fallback
                        ["get", "name:uk"],
                        ["get", "name:en"]
                    ]);
                }
            });
        },

        placeMarker(lng, lat) {
            if (this.marker) this.marker.remove();

            this.marker = new maplibregl.Marker({color: "red"})
                .setLngLat([lng, lat])
                .addTo(this.map);


        },

        async searchAddress() {
            if (!this.searchQuery.trim()) return;

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
                this.searchQuery
            )}&addressdetails=1&limit=1`;

            const res = await fetch(url);
            const data = await res.json();

            if (!data.length) return;

            const {lat, lon} = data[0];

            this.map.flyTo({center: [lon, lat], zoom: 16});
            this.placeMarker(lon, lat);

            this.coords = {lat, lng: lon};
            this.findAddress = this.formatAddress(data[0].address);


        },

        async reverseGeocode(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;

            const res = await fetch(url);
            const data = await res.json();

            this.findAddress = this.formatAddress(data.address);
        },

        formatAddress(addr) {
            if (!addr) return "";

            const street = [addr.road, addr.house_number].filter(Boolean).join(", ");
            const city = addr.city || addr.town || addr.village || "";

            let full = [street, city].filter(Boolean).join(", ");

            // Подмена на русский через словарь
            this.ruNames.forEach(item => {
                full = full.replace(item.original, item.ru);
            });

            this.$notify({
                title:'Адрес',
                text:'Адрес доставки:'+this.findAddress ,
            })
            return full;
        },


    }
};
</script>

<style scoped>
.map {
    width: 100%;
    height: 450px;
    border-radius: 6px;
    overflow: hidden;
}
</style>

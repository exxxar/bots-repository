<template>

    <div class="card card-style" >
        <div class="content">
    <form v-on:submit.prevent="submitLocation">


        <div class="fac fac-checkbox fac-red"><span></span>
            <input id="box1-fac-checkbox"
                   v-model="locationForm.can_booking"
                   type="checkbox" value="0" >
            <label for="box1-fac-checkbox"> Можно бронировать столик</label>
        </div>


        <label class="form-label  d-flex justify-content-between mt-2" id="location-address">
            Адрес заведения
            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

        </label>
        <input type="text" class="form-control"
               placeholder="Адрес"
               aria-label="Адрес"
               maxlength="255"
               v-model="locationForm.address"
               aria-describedby="location-address" required>

        <div class="divider divider-small my-3  bg-highlight"></div>
        <h6>Геопозиция</h6>
        <label class="form-label  d-flex justify-content-between mt-2" id="location-lat">
            Широта
            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

        </label>
        <input type="text" class="form-control"
               v-mask="'##.######'"
               placeholder="##.######"
               aria-label="Широта"
               maxlength="255"
               v-model="locationForm.lat"
               aria-describedby="location-lat" required>


        <label class="form-label  d-flex justify-content-between mt-2" id="location-lon">
            Долгота
            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

        </label>
        <input type="text" class="form-control"
               v-mask="'##.######'"
               placeholder="##.######"
               aria-label="Долгота"
               maxlength="255"
               v-model="locationForm.lon"
               aria-describedby="location-lon" required>

        <label class="form-label  d-flex justify-content-between mt-2" id="location-description">
            Описание локации
            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

            <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="locationForm.description">
                Длина текста {{ locationForm.description.length }}</small>
        </label>
        <textarea type="text" class="form-control"
                  placeholder="Описание локации"
                  aria-label="Описание локации"
                  maxlength="255"
                  v-model="locationForm.description"
                  aria-describedby="location-description" required>
                    </textarea>

        <div class="divider divider-small my-3  bg-highlight"></div>

        <h6>Фотографии локаций</h6>


        <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
            <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                <span>+</span>
                <input type="file" id="location-photos" multiple accept="image/*"
                       @change="onChangePhotos"
                       style="display:none;"/>

            </label>
            <div class="mb-2 img-preview" style="margin-right: 10px;"
                 v-for="(img, index) in locationForm.photos"
                 v-if="locationForm.photos">
                <img v-lazy="getPhoto(img).imageUrl">
                <div class="remove">
                    <a @click="removePhoto(index)">Удалить</a>
                </div>
            </div>

        </div>


        <button class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100"
                type="submit">
            <span v-if="locationForm.id">Обновить расположение</span>
            <span v-else>Добавить расположение</span>
        </button>


    </form>

        </div>
    </div>
    <div class="mt-3"
         v-if="locations"
         >

        <div class="card card-style bg-25" data-card-height="200" style="height: 250px;">
            <div class="card-center">
                <p class="text-center mb-2">
                    <i class="fa-solid fa-location-dot color-red2-dark fa-4x"></i>
                </p>
                <h2 class="color-white font-700 text-center mb-0">{{ company.title || 'Не установлен' }}</h2>
                <p class="color-white text-center opacity-60 mt-n1 mb-3">Локации компании </p>

                <div class="p-3">
                    <button
                        @click="submitLocations"
                        :disabled="locations.length===0&&deletedLocations.length===0"
                        class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                        Сохранить локации заведений
                    </button>
                </div>

            </div>
            <div class="card-overlay bg-black opacity-80"></div>
        </div>

        <div class="card card-style" @click="selectLocation(location)" v-for="(location, index) in locations">
            <div class="content">
                <h4>{{ location.address || 'Не указано' }}</h4>

                <a
                    v-if="location.can_booking"
                    href="javascript:void(0)" class="chip chip-small bg-dark2-dark w-100 my-3">
                    <i class="fa fa-check bg-green1-dark"></i>
                    <span class="color-white">Можно забронировать столик</span>
                </a>

                <ul>
                    <li v-if="location.location_channel">Канал заведения <strong>{{ location.location_channel }} </strong></li>
                    <li>{{ location.description || 'Не указано' }}</li>
                    <li>Широта: {{ location.lat }}</li>
                    <li>Долгота: {{ location.lon }}</li>
                </ul>

                <h6 v-if="location.photos">Фотографии локаций</h6>
                <div class="w-100 d-flex" v-if="location.photos">
                    <div class="mb-2 img-preview"
                         style="margin-right: 10px;"
                         v-for="(img, index) in location.photos"
                         v-if="location.photos.length>0">
                        <img v-lazy="getPhoto(img).imageUrl">
                    </div>
                </div>

                <div class="w-100 d-flex" v-if="location.images">
                    <div class="mb-2 img-preview"
                         style="margin-right: 10px;"
                         v-for="(img, index) in location.images"
                         v-if="location.images.length>0">
                        <img v-lazy="'/images-by-company-id/'+company.id+'/'+img">
                    </div>
                </div>

                <button
                    type="button"
                    @click="removeItem( index)"
                    class="btn btn-outline-danger py-1 px-3 text-center w-100">
                    <i class="fa-regular fa-trash-can"></i>
                    Удалить
                </button>

            </div>
        </div>


    </div>





</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["company"],
    data() {
        return {
            locations: [],
            deletedLocations: [],
            locationForm: {
                id: null,
                lat: null,
                lon: null,
                address: null,
                description: null,
                location_channel: null,
                can_booking: false,
                photos: [],
                company_id: null,
            }
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'getCompany', 'getLocations']),
    },
    mounted() {
        this.loadLocationsByCompany();
    },
    methods: {
        loadLocationsByCompany() {
            this.$store.dispatch("loadLocationsByCompany").then((resp) => {
                this.locations = this.getLocations
            }).catch(() => {

            })
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.locationForm.photos.splice(index, 1)
        },
        removeItem(index) {
            if (this.locations[index].id != null)
                this.deletedLocations.push(this.locations[index].id)
            this.locations.splice(index, 1)
        },
        submitLocations() {
            this.locations.forEach(location => {
                let data = new FormData();
                Object.keys(location)
                    .forEach(key => {
                        const item = location[key] || ''
                        if (typeof item === 'object')
                            data.append(key, JSON.stringify(item))
                        else
                            data.append(key, item)
                    });

                if (location.photos) {
                    for (let i = 0; i < location.photos.length; i++)
                        data.append('files[]', location.photos[i]);

                    data.delete("photos")
                }


                if (this.deletedLocations.length > 0)
                    data.append("deleted_locations", JSON.stringify(this.deletedLocations))

                this.$store.dispatch("createLocation", {
                    locationForm: data
                }).then((response) => {
                    this.$emit("callback")
                    this.$botNotification.success("Локация", "Локация успешно созадана и сохранена");
                }).catch(err => {

                })
            })
        },
        selectLocation(item) {
            this.locationForm = item
        },
        submitLocation() {
            this.locationForm.company_id = this.company.id
            this.locations.push(this.locationForm);
            this.$botNotification.success("Локация", "окация успешно добавлена в список. Не забудьте сохранить");
            this.locationForm = {
                id: null,
                lat: null,
                lon: null,
                address: null,
                description: null,
                location_channel: null,
                can_booking: false,
                photos: [],
            }
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.locationForm.photos.push(files[i])
        },
    }
}
</script>


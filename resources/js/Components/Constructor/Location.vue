<template>
    <div class="card">
        <div class="card-body">

            <form v-on:submit.prevent="addLocation">
                <h6>Локации к компании #{{companyId||'Не установлен'}}</h6>
                <div class="row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="locationForm.can_booking"
                                   type="checkbox" value="false" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Можно бронировать столик
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" id="location-address">Адрес заведения</label>
                            <input type="text" class="form-control"
                                   placeholder="Адрес"
                                   aria-label="Адрес"
                                   maxlength="255"
                                   v-model="locationForm.address"
                                   aria-describedby="location-address" required>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label class="form-label" id="location-lat">Широта</label>
                            <input type="text" class="form-control"
                                   v-mask="'##.######'"
                                   placeholder="##.######"
                                   aria-label="Широта"
                                   maxlength="255"
                                   v-model="locationForm.lat"
                                   aria-describedby="location-lat" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label class="form-label" id="location-lon">Долгота</label>
                            <input type="text" class="form-control"
                                   v-mask="'##.######'"
                                   placeholder="##.######"
                                   aria-label="Долгота"
                                   maxlength="255"
                                   v-model="locationForm.lon"
                                   aria-describedby="location-lon" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" id="location-description">Описание компании</label>
                            <textarea type="text" class="form-control"
                                      placeholder="Описание локации"
                                      aria-label="Описание локации"
                                      maxlength="255"
                                      v-model="locationForm.description"
                                      aria-describedby="location-description" required>
                    </textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label" id="location-channel">Телеграм канал заведения</label>
                            <input type="text" class="form-control"
                                   placeholder="Номер телеграм канала"
                                   aria-label="Номер телеграм канала"
                                   maxlength="255"
                                   v-model="locationForm.location_channel"
                                   aria-describedby="location-channel" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                            <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                <span>+</span>
                                <input type="file" id="location-photos" multiple accept="image/*"
                                       @change="onChangePhotos"
                                       style="display:none;"/>

                            </label>
                            <div class="mb-2 img-preview" style="margin-right: 10px;"
                                 v-for="(img, index) in locationForm.photos"
                                 v-if="locationForm.photos.length>0">
                                <img v-lazy="getPhoto(img).imageUrl">
                                <div class="remove">
                                    <a @click="removePhoto(index)">Удалить</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-success w-100"
                                type="submit">Добавить локацию
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-12 mt-3"
                     v-if="locations.length>0"
                     v-for="(location, index) in locations">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h6>Адрес локации <strong>{{ location.address || 'Не указано' }}</strong>
                                (ш:{{ location.lat }},д:{{ location.lon }})
                                <span class="badge bg-success" v-if="location.can_booking">Можно забронировать столик</span>
                            </h6>
                            <a class="cursor-pointer"
                               @click="removeItem( index)">Удалить</a>
                        </div>
                        <div class="card-body">
                            <p>Канал заведения <strong>{{ location.location_channel }}</strong></p>
                            <p>{{ location.description }}</p>
                            <div class="w-100 d-flex">
                                <div class="mb-2 img-preview" style="margin-right: 10px;"
                                     v-for="(img, index) in location.photos"
                                     v-if="location.photos.length>0">
                                    <img v-lazy="getPhoto(img).imageUrl">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button
                        @click="submitLocations"
                        :disabled="locations.length===0"
                        class="btn btn-outline-primary p-3 w-100">Сохранить локации для заведения</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:["companyId"],
    data() {
        return {
            locations:[],
            locationForm: {
                lat: null,
                lon: null,
                address: null,
                description: null,
                location_channel: null,
                can_booking: false,
                photos: [],
                company_id:null,
            }
        }
    },
    methods: {
        getPhoto(imgObject){
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.locationForm.photos.splice(index, 1)
        },
        removeItem(index) {
            this.locations.splice(index, 1)
        },
        submitLocations(){
            this.locations.forEach(location=>{
                let data = new FormData();
                Object.keys(location)
                    .forEach(key => {
                        const item = location[key] || ''
                        if (typeof item === 'object')
                            data.append(key, JSON.stringify(item))
                        else
                            data.append(key, item)
                    });

                for (let i = 0; i < location.photos.length; i++)
                    data.append('images[]', location.photos[i]);

                data.delete("photos")

                this.$store.dispatch("createLocation", {
                    locationForm: data
                }).then((response) => {
                    this.$emit("callback", response.data)
                }).catch(err => {

                })
            })
        },
        addLocation() {
            this.locationForm.company_id = this.companyId
            this.locations.push(this.locationForm);
            this.locationForm = {
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


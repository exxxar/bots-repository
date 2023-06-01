<template>
    <div class="row">
        <div class="col-12">
            <h6>Операции с кнопками</h6>
        </div>
        <div class="col-12">

            <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                <button type="button"
                        @click="part=0"
                        class="btn btn-success"><i class="fa-solid fa-scroll mr-1"></i> Привязать скрипт</button>
                <button type="button"
                        @click="part=1"
                        class="btn btn-success"><i class="fa-solid fa-file-lines mr-1"></i>Привязать страницу</button>
                <button type="button"
                        @click="part=2"
                        class="btn btn-success"><i class="fa-solid fa-file-audio mr-1"></i>Привязать диалог</button>
            </div>

        </div>

        <div class="col-12 mt-2" v-if="part===0">
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                  Раздел "Привязать скрипт" находится в разработке
                </div>
            </div>
        </div>

        <div class="col-12 mt-2" v-if="part===1">
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                    Раздел "Привязать страницу" находится в разработке
                </div>
            </div>
        </div>

        <div class="col-12 mt-2" v-if="part===2">
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                    Раздел "Привязать диалог" находится в разработке
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:["data"],
    data(){
      return {
          part:0
      }
    },
    mounted() {
        console.log(this.data)
    }
}
</script>

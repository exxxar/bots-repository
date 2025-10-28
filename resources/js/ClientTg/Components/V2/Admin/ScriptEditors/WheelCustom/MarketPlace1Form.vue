<template>
  <div class="card">
      <div class="card-body">
          <h3 class="fw-bold text-primary text-center my-3">
              Три простых шага и вы в игре
          </h3>

          <form v-on:submit.prevent="submit">

              <h6><span class="fw-bold text-primary">Шаг 1.</span> На каком маркетплейсе вы совершили покупку?</h6>
              <div class="d-flex justify-content-around my-2">
                  <div class="form-check m-0 p-0">
                      <input class="form-check-input hide-input"
                             value="yandex"
                             v-model="form.marketplace_id"
                             type="radio" name="radioDefault" id="radioDefault1">
                      <label class="form-check-label img-option" for="radioDefault1">
                          <img src="/images/marketplaces/yandex-market-sign-logo.png" alt="Yandex">
                      </label>
                  </div>

                  <div class="form-check m-0 p-0">
                      <input class="form-check-input hide-input"
                             value="wb"
                             v-model="form.type"
                             type="radio" name="radioDefault" id="radioDefault2">
                      <label class="form-check-label img-option" for="radioDefault2">
                          <img src="/images/marketplaces/wildberries-sign-logo.svg" alt="Wildberries">
                      </label>
                  </div>

                  <div class="form-check m-0 p-0">
                      <input class="form-check-input hide-input"
                             v-model="form.type"
                             value="ozon"
                             type="radio" name="radioDefault" id="radioDefault3">
                      <label class="form-check-label img-option" for="radioDefault3">
                          <img src="/images/marketplaces/ozon-icon-logo.svg" alt="Ozon">
                      </label>
                  </div>
              </div>
              <h6><span class="fw-bold text-primary">Шаг 2.</span> Отправьте скриншот отзыва</h6>
              <div class="alert alert-warning">
                  Внимание! Отзыв должен быть обязательно с ТЕКСТОМ!
              </div>
              <div class="mb-3">
                  <label for="fileInput-1" class="upload-area">
                      <div class="text-muted" v-if="form.file_1==null">
                          📁 Перетащите изображение сюда или нажмите, чтобы выбрать
                      </div>
                      <img v-else v-lazy="getPhoto(form.file_1).imageUrl">
                  </label>

                  <input type="file"
                         @change="onChangePhotos1"
                         required id="fileInput-1" class="hide-input" accept="image/*">
              </div>
              <h6><span class="fw-bold text-primary">Шаг 3.</span> Отправьте скриншот покупки</h6>
              <div class="mb-3">
                  <label for="fileInput2" class="upload-area">
                      <div class="text-muted" v-if="form.file_2==null">
                          📁 Перетащите изображение сюда или нажмите, чтобы выбрать
                      </div>
                      <img v-else v-lazy="getPhoto(form.file_2).imageUrl">
                  </label>

                  <input type="file"
                         @change="onChangePhotos2"
                         required id="fileInput2" class="hide-input" accept="image/*">
              </div>

              <button type="submit" class="btn btn-primary w-100 p-3">Отправить</button>
          </form>
      </div>
  </div>
</template>
<script>
export default {
    data(){
      return {
          form:{
              marketplace_id:null,
              file_1:null,
              file_2:null,
          }
      }
    },
    methods: {
        submit(){
            this.$preloader.show()
            this.$emit("callback", this.form)
        },
        onChangePhotos1(e) {
            const file = e.target.files[0]
            this.form.file_1 = file
        },
        onChangePhotos2(e) {
            const file = e.target.files[0]
            this.form.file_2 = file
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
    }
}
</script>
<style>

.section-divider {
    height: 0;
    border-top: 1px solid #DDD;
    text-align: center;
    margin-top: 40px;
    margin-bottom: 40px;
}

.section-divider > span {
    color: #3498db;
    display: inline-block;
    position: relative;
    top: -11px;
    font-size: 15px;
    width: 20px;
    margin-left:5px;
    height: 20px;
    background: #ff9941;
    border-radius: 50%;
}

.section-divider > span:nth-of-type(1),
.section-divider > span:nth-of-type(3) {
    width: 15px;
    height: 15px;
    top: -13px;
}

.upload-area {
    display: flex;
    position: relative;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border: 2px dashed #6c757d;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.upload-area img {
    width:100%;
    object-fit:cover;
}

.hide-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.img-option {
    display: inline-block;
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    width:90px;
    height:90px;
}

.img-option img {
    width: 100%;
    object-fit:cover;
    height: auto;
    display: block;
}

.img-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

/* Активное состояние (выбранный радио-батон) */
.hide-input:checked + .img-option {
    border-color: #0d6efd;
    box-shadow: 0 0 0 4px rgba(13,110,253,0.25);
    background-color: #e9f3ff;
}
</style>

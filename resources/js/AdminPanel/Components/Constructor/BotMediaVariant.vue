<template>
    <div class="d-flex mb-2">
                    <span class="badge bg-secondary cursor-pointer mr-1"
                          v-bind:class="{'bg-success':type=='video'}"
                          @click="$emit('update:type', 'video')">Видео</span>
        <span class="badge bg-secondary cursor-pointer mr-1"
              v-bind:class="{'bg-success':type=='video_note'}"
              @click="$emit('update:type', 'video_note')">Кружок</span>
        <span class="badge bg-secondary cursor-pointer mr-1"
              v-bind:class="{'bg-success':type=='photo'}"
              @click="$emit('update:type', 'photo')">Изображение</span>
        <span class="badge bg-secondary cursor-pointer mr-1"
              v-bind:class="{'bg-success':type=='audio'}"
              @click="$emit('update:type', 'audio')">Звук</span>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control"
               :value="value"
               @input="$emit('update:value', $event.target.value)"
               id="custom-url-link-for-success" placeholder="https://....">
        <label for="custom-url-link-for-success">Ссылка на файл</label>
    </div>

</template>
<script>
export default {
    props: {
        type: String,
        value: String,
    },


}
</script>

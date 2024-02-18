<template>
    <div class="d-flex justify-content-center">
        <video
            v-if="(type=='video'||type=='video_note')&&content.indexOf('http')==-1"
            class="w-100"
            v-bind:class="{'video-circle':type=='video_note'}"
            controls
            poster="/images/load.gif">
            <source

                :src="'/file-by-file-id/'+content"
                type="video/mp4"/>

        </video>

        <iframe class="w-100"
                v-if="content.indexOf('http')!=-1"
                style="min-height:300px;" :src="content"
                frameborder="0"
                allowfullscreen>

        </iframe>

        <img v-if="type=='photo'&&content.indexOf('http')==-1"
             class="w-100"
             v-lazy="'/file-by-file-id/'+content"
             alt="">
        <img v-if="type=='photo'&&content.indexOf('http')!=-1"
             class="w-100"
             v-lazy="content"
             alt="">
        <audio v-if="type=='audio'&&content.indexOf('http')!=-1" controls autoplay
               :src="content"></audio>
        <audio v-if="type=='audio'&&content.indexOf('http')==-1" controls autoplay
               :src="'/file-by-file-id/'+content"></audio>
    </div>
</template>
<script>

export default {
    props: ["type", "content"],
}
</script>
<style lang="scss">
.video-circle {
    border-radius: 50%;
    overflow: hidden;
    padding: 0;
    margin: 0;
    height: 300px;
    width: 300px;

    box-sizing: border-box;
    /* border: 2px #3F51B5 solid; */
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 0px 4px 1px #d9d9d9;

}
</style>

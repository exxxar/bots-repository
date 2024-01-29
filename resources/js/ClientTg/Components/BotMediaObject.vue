<template>
    <div class="d-flex justify-content-center">

        <vue-core-video-player
            v-if="type=='video'||type=='video_note'"
            :src="'/file-by-file-id/'+content"></vue-core-video-player>
<!--        <video
            v-if="type=='video'||type=='video_note'"
            class="w-100"
            v-bind:class="{'video-circle':type=='video_note'}"
            autoplay
            poster="/images/load.gif">
            <source
                :src="'/file-by-file-id/'+content"
                type="video/mp4"/>
        </video>-->

        <img v-if="type=='photo'"
             class="w-100"
             v-lazy="'/file-by-file-id/'+content"
             alt="">
        <audio v-if="type=='audio'" controls autoplay
               :src="'/file-by-file-id/'+content"></audio>
    </div>
</template>
<script>
import VueCoreVideoPlayer from 'vue-core-video-player';
export default {
    props: ["type", "content"],
    components: {
        VueCoreVideoPlayer
    }
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

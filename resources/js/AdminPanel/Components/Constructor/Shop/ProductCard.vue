<template>
    <div class="card">
        <div class="card-header">
            <p>#{{item.id}}</p>
        </div>
        <div class="card-body">
            <p>{{item.title}}</p>

            <div :id="'image-slider-'+item.id" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item "
                         style="height: 300px;"
                         v-bind:class="{'active': index === 1}"
                         v-for="(img, index) in item.images">
                        <img :src="img" class="d-block w-100 object-fit-cover" alt="...">
                    </div>

                </div>

                <button class="carousel-control-prev" type="button" :data-bs-target="'#image-slider-'+item.id" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" :data-bs-target="'#image-slider-'+item.id" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:["item"]
}
</script>

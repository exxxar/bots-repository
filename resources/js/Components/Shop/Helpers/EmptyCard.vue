<template>
    <div class="card bg-20 mt-4 content rounded-m shadowl">
        <div class="card-body">
            <h4 class="color-white"><slot name="title"></slot></h4>
            <p class="color-white">
                <slot name="head"></slot>
            </p>
            <div class="card card-style ml-0 mr-0 mb-3 bg-white">
                <div class="content">
                    <slot name="body"></slot>
                </div>
            </div>
        </div>
        <div class="card-overlay bg-highlight opacity-85 rounded-m shadow-l"></div>
        <div class="card-overlay dark-mode-tint rounded-m shadow-l"></div>
    </div>
</template>
<script>
export default {

}
</script>

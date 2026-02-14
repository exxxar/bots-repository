<template>
    <div class="border-end h-100 p-2">
        <h6 class="mb-3">Компоненты</h6>
        <div class="row row-cols-2 g-1">
            <div
                v-for="item in library" class="col m-0">
                <button

                    :key="item.type"
                    style="word-break:break-all; font-size:12px; min-height:50px;"
                    class="btn btn-outline-secondary w-100 mb-2 text-start text-center"
                    @click="$emit('add', item.type)"
                >
                    {{ item.name }}
                </button>
            </div>
        </div>

    </div>
</template>

<script>
import { COMPONENT_LIBRARY } from '@/AdminPanel/Components/HTMLConstructor/modules/componentLibrary'

export default {
    name: 'Palette',
    data() {
        return {
            library: COMPONENT_LIBRARY
        }
    }
}
</script>

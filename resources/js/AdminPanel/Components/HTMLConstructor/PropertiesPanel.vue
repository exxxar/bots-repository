<script setup>
import MarginControls from "@/AdminPanel/Components/HTMLConstructor/MarginControls.vue";
import PaddingControls from "@/AdminPanel/Components/HTMLConstructor/PaddingControls.vue";

</script>
<template>
    <div  style="height:100vh;overflow-y:scroll;">

        <div v-if="!component" class="text-muted">
            Выберите компонент на холсте
        </div>


        <div v-else>

            <p class="small text-muted mb-2">#: {{ component.id }}</p>
            <p class="small text-muted mb-2">Тип: {{ component.type }}</p>

            <template v-if="component.type === 'button'">
                <div class="mb-2">
                    <label class="form-label">Текст</label>
                    <input v-model="component.props.text" class="form-control"/>
                </div>

                <div class="mb-2">
                    <label class="form-label">Вариант</label>
                    <select v-model="component.props.variant" class="form-select">
                        <option>primary</option>
                        <option>secondary</option>
                        <option>success</option>
                        <option>danger</option>
                        <option>warning</option>
                        <option>info</option>
                        <option>light</option>
                        <option>dark</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Размер</label>
                    <select v-model="component.props.size" class="form-select">
                        <option>md</option>
                        <option>sm</option>
                        <option>lg</option>
                    </select>
                </div>

                <div class="form-check mb-2">
                    <input
                        id="btn-block"
                        type="checkbox"
                        v-model="component.props.block"
                        class="form-check-input"
                    />
                    <label class="form-check-label" for="btn-block">На всю ширину</label>
                </div>
            </template>

            <template v-else-if="component.type === 'badge'">

                <label class="form-label">Текст</label>
                <input class="form-control" v-model="component.props.text">

                <label class="form-label mt-2">Цвет</label>
                <select class="form-select" v-model="component.props.variant">
                    <option value="primary">primary</option>
                    <option value="secondary">secondary</option>
                    <option value="success">success</option>
                    <option value="danger">danger</option>
                    <option value="warning">warning</option>
                    <option value="info">info</option>
                    <option value="light">light</option>
                    <option value="dark">dark</option>
                </select>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="component.props.pill" id="pill">
                    <label class="form-check-label" for="pill">Скруглённый (pill)</label>
                </div>

            </template>

            <template v-else-if="component.type === 'alert'" >

                <label class="form-label">Текст</label>
                <textarea class="form-control" v-model="component.props.text"></textarea>

                <label class="form-label mt-2">Тип</label>
                <select class="form-select" v-model="component.props.variant">
                    <option value="primary">primary</option>
                    <option value="secondary">secondary</option>
                    <option value="success">success</option>
                    <option value="danger">danger</option>
                    <option value="warning">warning</option>
                    <option value="info">info</option>
                    <option value="light">light</option>
                    <option value="dark">dark</option>
                </select>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="component.props.dismissible" id="dismissible">
                    <label class="form-check-label" for="dismissible">Закрываемый</label>
                </div>

            </template>


            <template v-else-if="component.type === 'col'">

                <div class="mb-2">
                    <label class="form-label">Размер колонки (1–12)</label>
                    <input
                        type="number"
                        min="1"
                        max="12"
                        v-model.number="props.size"
                        class="form-control"
                    />
                </div>

                <div class="form-check mb-2">
                    <input
                        type="checkbox"
                        v-model="props.showBorder"
                        class="form-check-input"
                        id="col-border"
                    />
                    <label class="form-check-label" for="col-border">
                        Отображать границы
                    </label>
                </div>

                <div class="mb-2">
                    <label class="form-label">Добавить элемент</label>
                    <select
                        class="form-select"
                        @change="$emit('add-element-to-col', { colId: component.id, type: $event.target.value })"
                    >
                        <option value="">Выберите компонент</option>
                        <option value="button">Кнопка</option>
                        <option value="image">Картинка</option>
                        <option value="card">Карточка</option>
                        <option value="navbar">Navbar</option>
                    </select>
                </div>

                <!-- FLEX SETTINGS -->
                <div class="mt-3 p-2 border rounded bg-light" v-if="component.type === 'col'">
                    <label class="form-label fw-bold">Flex настройки</label>

                    <!-- Включить flex -->
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="flex-enabled"
                               v-model="component.props.flexEnabled">
                        <label class="form-check-label" for="flex-enabled">Включить Flex</label>
                    </div>

                    <template v-if="component.props.flexEnabled">

                        <div class="mb-2">
                            <label class="form-label">Перенос строк (flex-wrap)</label>
                            <select class="form-select" v-model="component.props.flexWrap">
                                <option value="nowrap">Без переноса (nowrap)</option>
                                <option value="wrap">Переносить (wrap)</option>
                                <option value="wrap-reverse">Переносить в обратном порядке (wrap-reverse)</option>
                            </select>
                        </div>

                        <!-- Направление -->
                        <div class="mb-2">
                            <label class="form-label">Направление</label>
                            <select class="form-select" v-model="component.props.flexDirection">
                                <option value="row">Горизонтально (row)</option>
                                <option value="column">Вертикально (column)</option>
                            </select>
                        </div>

                        <!-- justify-content -->
                        <div class="mb-2">
                            <label class="form-label">Выравнивание по основной оси</label>
                            <select class="form-select" v-model="component.props.justifyContent">
                                <option value="start">Слева / Сверху</option>
                                <option value="center">По центру</option>
                                <option value="end">Справа / Снизу</option>
                                <option value="between">Между</option>
                                <option value="around">Вокруг</option>
                                <option value="evenly">Равномерно</option>
                            </select>
                        </div>

                        <!-- align-items -->
                        <div class="mb-2">
                            <label class="form-label">Выравнивание по поперечной оси</label>
                            <select class="form-select" v-model="component.props.alignItems">
                                <option value="start">Начало</option>
                                <option value="center">Центр</option>
                                <option value="end">Конец</option>
                                <option value="stretch">Растянуть</option>
                            </select>
                        </div>

                    </template>
                </div>

            </template>

            <template v-else-if="component.type === 'card'">
                <div class="mb-2">
                    <label class="form-label">Заголовок</label>
                    <input v-model="component.props.title" class="form-control"/>
                </div>
                <div class="mb-2">
                    <label class="form-label">Текст</label>
                    <textarea v-model="component.props.text" class="form-control" rows="3"/>
                </div>
            </template>

            <!-- IMAGE -->
            <template v-else-if="component.type === 'image'">
                <div class="mb-2">
                    <label class="form-label">URL</label>
                    <input v-model="component.props.src" class="form-control"/>
                </div>

                <div class="mb-2">
                    <label class="form-label">Alt</label>
                    <input v-model="component.props.alt" class="form-control"/>
                </div>

                <div class="mb-2">
                    <label class="form-label">Высота</label>
                    <input v-model="component.props.height" class="form-control"/>
                </div>

                <div class="form-check mb-2">
                    <input type="checkbox" v-model="component.props.fluid" class="form-check-input"/>
                    <label class="form-check-label">Адаптивная ширина</label>
                </div>

                <div class="form-check mb-2">
                    <input type="checkbox" v-model="component.props.rounded" class="form-check-input"/>
                    <label class="form-check-label">Скругление</label>
                </div>
            </template>

            <!-- NAVBAR -->
            <template v-else-if="component.type === 'navbar'">
                <div class="mb-2">
                    <label class="form-label">Brand</label>
                    <input v-model="component.props.brand" class="form-control"/>
                </div>

                <div class="mb-2">
                    <label class="form-label">Фон</label>
                    <select v-model="component.props.bg" class="form-select">
                        <option>light</option>
                        <option>dark</option>
                        <option>primary</option>
                        <option>secondary</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Вариант</label>
                    <select v-model="component.props.variant" class="form-select">
                        <option>light</option>
                        <option>dark</option>
                    </select>
                </div>
            </template>

            <!-- ROW -->
            <template v-else-if="component.type === 'row'">
                <button
                    class="btn btn-sm btn-outline-primary mb-2"
                    @click="$emit('add-col', component.id)"
                >
                    Добавить колонку
                </button>
            </template>

            <template v-else-if="component.type === 'text'">

                <!-- TEXT -->
                <label class="form-label">Текст</label>
                <textarea class="form-control" v-model="component.props.text"></textarea>

                <!-- FONT SIZE -->
                <label class="form-label mt-3">Размер шрифта</label>
                <select class="form-select" v-model="component.props.fontSize">
                    <option value="">Не выбран</option>
                    <option value="fs-1">fs-1 (крупный)</option>
                    <option value="fs-2">fs-2</option>
                    <option value="fs-3">fs-3</option>
                    <option value="fs-4">fs-4</option>
                    <option value="fs-5">fs-5</option>
                    <option value="fs-6">fs-6 (мелкий)</option>
                </select>

                <!-- FONT WEIGHT -->
                <label class="form-label mt-3">Жирность</label>
                <select class="form-select" v-model="component.props.fontWeight">
                    <option value="">Не выбран</option>
                    <option value="fw-light">Light</option>
                    <option value="fw-normal">Normal</option>
                    <option value="fw-semibold">Semibold</option>
                    <option value="fw-bold">Bold</option>
                </select>

                <!-- FONT STYLE -->
                <label class="form-label mt-3">Стиль</label>
                <select class="form-select" v-model="component.props.fontStyle">
                    <option value="">Не выбран</option>
                    <option value="fst-italic">Italic</option>
                </select>

                <!-- TEXT COLOR -->
                <label class="form-label mt-3">Цвет текста</label>
                <input class="form-control" type="text" v-model="component.props.textColor"
                       placeholder="text-primary или #ff0000">

                <!-- ALIGN -->
                <label class="form-label mt-3">Выравнивание</label>
                <select class="form-select" v-model="component.props.textAlign">
                    <option value="">Не выбран</option>
                    <option value="text-start">Слева</option>
                    <option value="text-center">По центру</option>
                    <option value="text-end">Справа</option>
                </select>

                <!-- LINE HEIGHT -->
                <label class="form-label mt-3">Межстрочный интервал</label>
                <select class="form-select" v-model="component.props.lineHeight">
                    <option value="">Не выбран</option>
                    <option value="lh-1">lh-1</option>
                    <option value="lh-sm">lh-sm</option>
                    <option value="lh-base">lh-base</option>
                    <option value="lh-lg">lh-lg</option>
                </select>

                <!-- LETTER SPACING -->
                <label class="form-label mt-3">Межбуквенный интервал (px)</label>
                <input type="number" class="form-control" v-model.number="component.props.letterSpacing">

                <!-- TEXT TRANSFORM -->
                <label class="form-label mt-3">Регистр</label>
                <select class="form-select" v-model="component.props.textTransform">
                    <option value="">Не выбран</option>
                    <option value="text-uppercase">UPPERCASE</option>
                    <option value="text-lowercase">lowercase</option>
                    <option value="text-capitalize">Capitalize</option>
                </select>

            </template>


            <template v-else-if="component.type === 'input'">
                <label class="form-label">Placeholder</label>
                <input class="form-control" v-model="component.props.placeholder"/>

                <label class="form-label mt-2">Тип</label>
                <select class="form-select" v-model="component.props.type">
                    <option value="text">text</option>
                    <option value="email">email</option>
                    <option value="number">number</option>
                    <option value="password">password</option>
                </select>
            </template>

            <template v-else-if="component.type === 'textarea'">
                <label class="form-label">Placeholder</label>
                <input class="form-control" v-model="component.props.placeholder"/>

                <label class="form-label mt-2">Строки</label>
                <input type="number" min="1" class="form-control" v-model.number="component.props.rows"/>
            </template>

            <template v-else-if="component.type === 'select'">
                <label class="form-label">Опции</label>
                <textarea class="form-control"
                          v-model="optionsString"
                          @input="updateOptions"></textarea>
            </template>

            <template v-else-if="component.type === 'carousel'">

                <label class="form-label">Интервал (мс)</label>
                <input type="number" class="form-control" v-model.number="component.props.interval">

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="component.props.showIndicators" id="indicators">
                    <label class="form-check-label" for="indicators">Показывать индикаторы</label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="component.props.showControls" id="controls">
                    <label class="form-check-label" for="controls">Показывать стрелки</label>
                </div>

                <hr class="my-3">

                <label class="form-label">Слайды</label>

                <div v-for="img in component.props.images" :key="img.id" class="border p-2 mb-2 rounded">
                    <label class="form-label">URL картинки</label>
                    <input class="form-control" v-model="img.src">

                    <label class="form-label mt-2">Alt</label>
                    <input class="form-control" v-model="img.alt">

                    <button class="btn btn-sm btn-danger mt-2"
                            @click="removeImage(img.id)">
                        Удалить
                    </button>
                </div>

                <button class="btn btn-sm btn-primary mt-2" @click="addImage">
                    Добавить картинку
                </button>

            </template>

            <template v-else-if="component.type === 'collapse'">

                <label class="form-label">Заголовок</label>
                <input class="form-control" v-model="component.props.title">

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" v-model="component.props.show" id="collapse-show">
                    <label class="form-check-label" for="collapse-show">Открыт по умолчанию</label>
                </div>

            </template>





            <div v-else class="text-muted small">
                Для этого типа компонента редактор ещё не настроен.
            </div>



            <div class="form-check mb-2">
                <input
                    id="btn-margins"
                    type="checkbox"
                    v-model="need_margins"
                    class="form-check-input"
                />
                <label class="form-check-label" for="btn-margins">Нужны внешние отступы</label>
            </div>

            <template v-if="need_margins">
                <MarginControls v-model="component.props"></MarginControls>
            </template>

            <div class="form-check mb-2">
                <input
                    id="btn-paddings"
                    type="checkbox"
                    v-model="need_paddings"
                    class="form-check-input"
                />
                <label class="form-check-label" for="btn-paddings">Нужны внутренние отступы</label>
            </div>

            <template v-if="need_paddings">
                <PaddingControls v-model="component.props"></PaddingControls>
            </template>


            <div style="position:sticky; bottom:0px; z-index:100;" class="bg-white">
                <label class="form-label fw-bold">Операции с элементом</label>
                <div class="btn-group w-100 mb-2" role="group">

                    <button type="button"
                            class="btn btn-outline-primary"
                            @click.stop="$emit('duplicate-component', component.id)">
                        <i class="fa-solid fa-copy"></i>
                    </button>

                    <button type="button"
                            class="btn btn-outline-primary"
                            @click.stop="$emit('move-up-component', component.id)">
                        <i class="fa-solid fa-arrow-up"></i>
                    </button>

                    <button type="button"
                            class="btn btn-outline-primary"
                            @click.stop="$emit('move-down-component', component.id)">
                        <i class="fa-solid fa-arrow-down"></i>
                    </button>

                    <button type="button"
                            class="btn btn-outline-danger"
                            @click.stop="$emit('delete-component', component.id)">
                        <i class="fa-solid fa-trash"></i>
                    </button>

                </div>
            </div>

        </div>


    </div>
</template>

<script>
import {
    findComponentById,
    insertNextTo,
    moveComponent
} from "@/AdminPanel/Components/HTMLConstructor/modules/pageStore";

export default {
    name: 'PropertiesPanel',
    props: {
        component: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            need_margins: false,
            need_paddings: false,
        }
    },
    computed: {
        optionsString: {
            get() {
                return this.component.props.options.join('\n')
            },
            set(v) {
                this.component.props.options = v.split('\n')
            }
        },
        props() {
            if (!this.component)
                return null
            return this.component.props || this.component.defaultProps
        }
    },
    methods: {
        addImage() {
            this.component.props.images.push({
                id: 'img_' + Math.random().toString(36).substr(2, 9),
                src: 'https://via.placeholder.com/800x300',
                alt: 'Новый слайд'
            })
        },
        removeImage(id) {
            this.component.props.images =
                this.component.props.images.filter(i => i.id !== id)
        }
    }

}
</script>

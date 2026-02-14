export const COMPONENT_LIBRARY = [
    {
        type: 'button',
        name: 'Кнопка',
        defaultProps: {
            text: 'Кнопка',
            variant: 'primary',
            size: 'md',
            block: false,
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,
            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null

        }
    },

    {
        type: 'image',
        name: 'Картинка',
        defaultProps: {
            src: 'https://via.placeholder.com/300x150',
            alt: 'Изображение',
            rounded: false,
            fluid: true,
            height:'100',
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,
            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null

        }
    },

    {
        type: 'navbar',
        name: 'Навбар',
        defaultProps: {
            brand: 'My App',
            bg: 'light',
            variant: 'light',
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,
            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null

        },
        children: []
    },
    {
        type: 'col',
        name: 'Колонка',
        defaultProps: {
            size: 6,
            showBorder: false,

            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,

            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null,

            // FLEX
            flexEnabled: false,
            flexDirection: 'row',          // row | column
            justifyContent: 'start',       // start | center | end | between | around | evenly
            alignItems: 'start'   ,         // start | center | end | stretch
            flexWrap: 'nowrap',   // nowrap | wrap | wrap-reverse
            // start | center | end | stretch
        },
        children: []
    },

    {
        type: 'row',
        name: 'Row',
        defaultProps: {
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,
            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null


        },
        children: [

        ]
    },

    {
        type: 'card',
        name: 'Card',
        defaultProps: {
            title: 'Заголовок карточки',
            text: 'Текст карточки',
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null,
            paddingTop: null,
            paddingRight: null,
            paddingBottom: null,
            paddingLeft: null

        },
        children: [ ]
    },
    {
        type: 'text',
        name: 'Текст',
        defaultProps: {
            text: 'Текстовый блок',

            // TEXT STYLES
            fontSize: null,        // fs-1 ... fs-6 или кастом
            fontWeight: null,      // fw-bold, fw-semibold, fw-light
            fontStyle: null,       // fst-italic
            textColor: null,       // text-primary, text-danger, #hex
            textAlign: null,       // text-start, text-center, text-end
            lineHeight: null,      // lh-1, lh-sm, lh-base, lh-lg
            letterSpacing: null,   // custom style
            textTransform: null,   // text-uppercase, text-lowercase, text-capitalize

            // MARGINS
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    },
    {
        type: 'input',
        name: 'Поле ввода',
        defaultProps: {
            placeholder: 'Введите текст',
            type: 'text', // text, email, number, password
            value: '',
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    },
    {
        type: 'textarea',
        name: 'Многострочное поле',
        defaultProps: {
            placeholder: 'Введите текст',
            rows: 3,
            value: '',
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    },
    {
        type: 'select',
        name: 'Выпадающий список',
        defaultProps: {
            options: ['Option 1', 'Option 2', 'Option 3'],
            selected: null,
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    },
    {
        type: 'carousel',
        name: 'Карусель',
        defaultProps: {
            interval: 3000,
            showIndicators: true,
            showControls: true,
            images: [
                { id: 'img1', src: 'https://via.placeholder.com/800x300', alt: 'Slide 1' },
                { id: 'img2', src: 'https://via.placeholder.com/800x300', alt: 'Slide 2' }
            ]
        },
        children: [] // НЕ нужно, карусель управляется через props
    },

    {
        type: 'collapse',
        name: 'Collapse (спойлер)',
        defaultProps: {
            title: 'Заголовок',
            show: false, // открыто/закрыто по умолчанию
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: [

        ] // ВАЖНО: сюда будут попадать вложенные компоненты
    },
    {
        type: 'badge',
        name: 'Бейдж',
        defaultProps: {
            text: 'Badge',
            variant: 'primary', // primary, secondary, success, danger, warning, info, light, dark
            pill: false,
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    },
    {
        type: 'alert',
        name: 'Alert',
        defaultProps: {
            text: 'Это уведомление',
            variant: 'primary', // primary, secondary, success, danger, warning, info, light, dark
            dismissible: false,
            marginTop: null,
            marginRight: null,
            marginBottom: null,
            marginLeft: null
        },
        children: []
    }






]

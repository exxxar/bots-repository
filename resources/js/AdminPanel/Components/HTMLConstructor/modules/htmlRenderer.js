// Генерация HTML-кода из структуры страницы

export function renderBlockHTML(block) {
    switch (block.type) {
        case 'button':
            return `<button class="btn btn-${block.props.variant}${
                block.props.size !== 'md' ? ' btn-' + block.props.size : ''
            }${block.props.block ? ' w-100' : ''}">${block.props.text}</button>`

        case 'row':
            return `<div class="row">
${(block.children || []).map(renderBlockHTML).join('\n')}
</div>`

        case 'col':
            return `<div class="col-${block.props?.size || 12}">
${(block.children || []).map(renderBlockHTML).join('\n')}
</div>`

        case 'card':
            return `<div class="card">
  <div class="card-body">
    <h5 class="card-title">${block.props.title}</h5>
    <p class="card-text">${block.props.text}</p>
  </div>
</div>`

        case 'text':
            return `<p class="${textClasses(block)}">${escape(block.props.text)}</p>`
        case 'input':
            return `<input type="${block.props.type}" placeholder="${escape(block.props.placeholder)}" class="form-control" />`
        case 'textarea':
            return `<textarea rows="${block.props.rows}" class="form-control">${escape(block.props.value)}</textarea>`
        case 'select':
            return `<select class="form-select"> ${block.props.options.map(o => ` <option>${escape(o)}</option>`).join('\n')} </select>`
        case 'badge':
            return `<span class="badge bg-${block.props.variant}">${escape(block.props.text)}</span>`
        case 'alert':
            return `<p class="alert alert-${block.props.variant}">${escape(block.props.text)}</p>`
        case 'carousel':
            return renderCarousel(block)
        case 'collapse':
            return renderCollapse(block)
        case 'my-widget':
            return renderVueComponent(block)
        default:
            return `<!-- Unknown component: ${block.type} -->`
    }
}

export function textClasses(block) {
    const p = block.props

    return [
        p.fontSize,          // fs-1 ... fs-6
        p.fontWeight,        // fw-bold, fw-light
        p.fontStyle,         // fst-italic
        p.textAlign,         // text-start, text-center, text-end
        p.textTransform,     // text-uppercase, text-lowercase
        p.lineHeight,        // lh-1, lh-sm, lh-base, lh-lg

        // margins
        p.marginTop,
        p.marginRight,
        p.marginBottom,
        p.marginLeft
    ]
        .filter(Boolean)
        .join(' ')
}

function colClasses(block) {
    const p = block.props
    return [
        `col-${p.size}`,
        p.marginTop,
        p.marginRight,
        p.marginBottom,
        p.marginLeft,
        p.paddingTop,
        p.paddingRight,
        p.paddingBottom,
        p.paddingLeft,
        p.flexEnabled ? 'd-flex' : '',
        p.flexEnabled ? 'flex-' + p.flexDirection : '',
        p.flexEnabled ? 'justify-content-' + p.justifyContent : '',
        p.flexEnabled ? 'align-items-' + p.alignItems : '',
        p.flexEnabled ? 'flex-' + p.flexWrap : ''
    ].filter(Boolean).join(' ')
}

function renderCollapse(block) {
    const id = 'collapse-' + block.id

    return `
<button class="btn btn-outline-primary w-100 text-start" data-bs-toggle="collapse" data-bs-target="#${id}">
  ${escape(block.props.title)}
</button>

<div id="${id}" class="collapse ${block.props.show ? 'show' : ''}">
  <div class="card card-body mt-2">
${indent(renderChildren(block.children))}
  </div>
</div>`
}

function renderCarousel(block) {
    const id = 'carousel-' + block.id
    const p = block.props

    return `
<div id="${id}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="${p.interval}">
  <div class="carousel-inner">
${p.images.map((img, i) => `
    <div class="carousel-item ${i === 0 ? 'active' : ''}">
      <img src="${img.src}" class="d-block w-100" alt="${escape(img.alt)}">
    </div>`).join('\n')}
  </div>
</div>`
}

function renderVueComponent(block) {
    const tag = block.type
    const props = Object.entries(block.props)
        .map(([k, v]) => `:${k}="${JSON.stringify(v)}"`)
        .join(' ')

    return `<${tag} ${props}></${tag}>`
}



function renderChildren(children) {
    return children.map(c => renderBlockHTML(c)).join('\n')
}

function indent(str) {
    return str.split('\n').map(line => ' ' + line).join('\n')
}

export function renderPageHTML(page) {
    return page.map(renderBlockHTML).join('\n')
}

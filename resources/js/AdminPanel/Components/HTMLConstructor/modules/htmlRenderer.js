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

        default:
            return ''
    }
}

export function renderPageHTML(page) {
    return page.map(renderBlockHTML).join('\n')
}

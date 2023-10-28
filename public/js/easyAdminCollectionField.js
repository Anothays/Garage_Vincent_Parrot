document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.field-image input[type="file"]')
    inputs.forEach((input) => {
        initInput(input)
    })
})

document.addEventListener('ea.collection.item-added', function(e) {
    const input = document.querySelector('.field-collection-item-last .field-image input[type="file"]')
    initInput(input)
})

function initInput(input) {
    const accordionBody = input.closest('.accordion-body')
    const inputParent = input.closest('.field-collection-item ')
    const img = document.createElement('img')
    if (!inputParent.className.includes('field-collection-item-last')) {
        img.src = `/media/uploads/${input.placeholder}`
        img.alt = input.placeholder
        img.className = "w-100"
        // img.style.maxWidth = '400px'
        accordionBody.prepend(img)
    }
    input.addEventListener('change', e => {
        input.nextElementSibling.innerText = e.target.value.replace("C:\\fakepath\\", '')
        img.remove()
    })
}
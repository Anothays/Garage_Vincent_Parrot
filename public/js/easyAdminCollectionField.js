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
        if (e.target.files[0]?.size > 2000000) { // 8388608
            alert('Attention ! Le fichier ne peut pas excéder 2 Mb')
            e.target.value = null
            return
        }

        const inputs = document.querySelectorAll('.field-image input[type="file"]')
        let totalbytes = 0
        inputs.forEach(input => {
            if (input.files) {
                totalbytes += +input.files[0]?.size
            }
        })
        if (totalbytes > 8388608) {
            alert('Attention ! Vous ne pouvez pas télécharger plus de 8 Mb en une seule fois')
            e.target.value = null
            return
        }
        console.log(totalbytes)
        input.nextElementSibling.innerText = e.target.value.replace("C:\\fakepath\\", '')
        img.remove()
    })
}
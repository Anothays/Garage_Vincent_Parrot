class Carousel {
    constructor(htmlElement, options) {
        this.options = options
        this.container = htmlElement
        this.customCarousel = htmlElement.querySelector('#customCarousel')
        this.maxSlidesVisible = this.options.maxSlidesVisible
        this.prev = htmlElement.querySelector('#customCarousel-prev ')
        this.next = htmlElement.querySelector('#customCarousel-next ')
        this.items = [...htmlElement.querySelectorAll('#customCarousel .customCarousel-item')]



        /** Valeur d'une translation égale à la largeur d'une card testimonial */
        this.itemWidth =
              Number(getComputedStyle(this.items[0].children[0]).maxWidth.replace('px', ''))
            + Number(getComputedStyle(this.items[0].children[0]).marginLeft.replace('px', ''))
            + Number(getComputedStyle(this.items[0].children[0]).marginRight.replace('px', ''))


        this.items.forEach((item, key) => {
            if (key === 0) {
                item.classList.add('customActive')
            }
            if (key !== 0) {
                this.items[0].appendChild(item.children[0])
            }
            item.style.transition = '0.3s'
        })

        /** Hide next and prev button if not enough cards  */
        if (this.items.length < this.maxSlidesVisible) {
            this.prev.style.display = this.next.style.display = 'none'
            this.items[0].style.marginInline = 'auto'
            return
        }

        this.prev.addEventListener('click', e => { this.goTo('prev') })
        this.next.addEventListener('click', e => { this.goTo('next') })

        window.addEventListener('resize', e => {
            this.#setSlidesVisible(e)
        })
        this.container.style.maxWidth =  `${this.maxSlidesVisible * this.itemWidth}px`

        this.#setSlidesVisible()

    } // end constructor


    goTo(position) {
        const currentActiveItem = this.items.find(item => item.className.includes('customActive'))
        const nextPosition = this.items.indexOf(currentActiveItem) + (position === 'next' ? 1 : -1)
        const newActiveItemIndex = (nextPosition < 0 ? this.items.length - 1 : nextPosition) % this.items.length
        const newActiveItem = this.items[newActiveItemIndex]
        currentActiveItem.classList.remove('customActive')
        newActiveItem.classList.add('customActive')

        /** transfert children to next item */
        while (currentActiveItem.firstChild) {
            const child = currentActiveItem.firstChild;
            currentActiveItem.removeChild(child);
            newActiveItem.appendChild(child);
        }
        if (position === 'next') {
            if (currentActiveItem.style.translate === `-${this.itemWidth}px`) {
                /** First child go to last position */
                const firstChild = newActiveItem.firstElementChild
                newActiveItem.appendChild(firstChild)
            }
        } else if (position === 'prev') {
            if (currentActiveItem.style.translate === `0px` || currentActiveItem.style.translate === '') {
                const lastChild = newActiveItem.lastElementChild
                newActiveItem.insertBefore(lastChild, newActiveItem.firstElementChild)
            }
            newActiveItem.style.translate = `-${this.itemWidth}px`

        }

        /** Make transition */
        setTimeout(() => {
            if (position === 'next') {
                currentActiveItem.style.translate = '0px'
                newActiveItem.style.translate = `-${this.itemWidth}px`
            } else if (position === 'prev') {
                currentActiveItem.style.translate = '0px'
                newActiveItem.style.translate = `0px`
            }
        }, 10)
    }

    #setSlidesVisible(event) {

        const containerComputedStyle = getComputedStyle(this.container)
        const containerWidth = Number(containerComputedStyle.width.replace('px', ''))

        this.slidesVisible = Math.floor(containerWidth / this.itemWidth)
        this.slidesVisible = this.slidesVisible > this.maxSlidesVisible ? this.maxSlidesVisible : this.slidesVisible
        this.customCarousel.style.width =  `${this.slidesVisible * this.itemWidth}px`

    }
}


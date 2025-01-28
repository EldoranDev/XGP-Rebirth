import { Controller } from '@hotwired/stimulus';

export default class TabsController extends Controller {
    static targets = ['link'];
    static activeClass = 'current';

    initialize() {
        this.handleTurboClick = this.handleTurboClick.bind(this);
    }

    connect() {
        this.element.addEventListener('turbo:click', this.handleTurboClick);
    }

    disconnect() {
        this.element.removeEventListener('turbo:click', this.handleTurboClick);
    }

    handleTurboClick(event) {
        for (let i = 0; i < this.linkTargets.length; i++) {
            const target = this.linkTargets[i];

            if (target === event.target) {
                this.element.style = `background-position: 15px -${33 * (i+1)}px;`
                target.classList.add(TabsController.activeClass);
            } else {
                target.classList.remove(TabsController.activeClass);
            }
        }
    }

}
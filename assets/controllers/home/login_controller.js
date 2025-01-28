import { Controller } from '@hotwired/stimulus';

export default class LoginController extends Controller {
    static targets = ['form', 'button'];
    static openClass = 'show';

    initialize() {
        this.handleClick = this.handleClick.bind(this);
    }

    connect() {
        this.buttonTarget.addEventListener('click', this.handleClick);
    }

    disconnect() {
        this.buttonTarget.removeEventListener('click', this.handleClick);
    }

    handleClick(event) {
        this.formTarget.classList.toggle(LoginController.openClass);
        this.formTarget.style = `display: ${this.formTarget.classList.contains(LoginController.openClass) ? 'block' : 'none'};`
    }

}
import { Controller } from '@hotwired/stimulus';

export default class LoginController extends Controller {

    initialize() {
        this.handleResponse = this.handleResponse.bind(this);
    }

    connect() {
        this.element.addEventListener('turbo:before-fetch-response', this.handleResponse);
    }

    disconnect() {
        this.element.removeEventListener('turbo:before-fetch-response', this.handleResponse);
    }

    handleResponse(event) {
        if (event.detail.fetchResponse.redirected) {
            Turbo.visit(event.detail.fetchResponse.location, {
                'frame': '_top',
            });
        }

        event.preventDefault();
    }

}
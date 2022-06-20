import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        message: '',
    };

    connect() {

    }

    confirm(e) {
        if (!confirm(this.messageValue)) {
            e.preventDefault();
        }
    }
}
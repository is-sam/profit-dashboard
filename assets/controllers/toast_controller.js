import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "element" ]

    close(event) {
        let toast = document.querySelector(this.element.dataset.dismissTarget);
        toast.classList.add('transform', 'opacity-0', 'transition', 'duration-1000');
        setTimeout(() => toast.remove(), 1000);
    }
}

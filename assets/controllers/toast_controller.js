import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    close() {
        let toast = this.element;
        toast.classList.add('transform', 'opacity-0', 'transition', 'duration-500');
        setTimeout(() => toast.remove(), 500);
    }
}

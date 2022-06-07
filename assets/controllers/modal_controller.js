import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    open({params: {name}}) {
        let modal = document.getElementById(`${name}_modal`);
        modal.classList.remove('animate-fade-out');
        modal.classList.add('animate-fade-in');
        modal.classList.remove('hidden');
    }

    close() {
        let modal = this.element;
        modal.classList.remove('animate-fade-in');
        modal.classList.add('animate-fade-out');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }
}

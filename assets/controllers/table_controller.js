import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['subrow', 'iconOpend', 'iconClosed'];

    toggle() {
        this.iconOpendTarget.hidden = !this.iconOpendTarget.hidden;
        this.iconClosedTarget.hidden = !this.iconClosedTarget.hidden;
        this.subrowTargets.forEach(subrow => {
            subrow.hidden = !subrow.hidden;
        });
    }
}
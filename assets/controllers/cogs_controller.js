import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['cost', 'edit', 'save'];

    edit() {
        this.editMode(true);
    }

    save({params: {url}}) {
        fetch(url, {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({cost: this.costTarget.value})
        }).then(res => {
            if (res.status !== 200) {
                console.log('Error while saving cost');
                return;
            }
            this.editMode(false);
        });
    }

    editMode(mode) {
        this.editTarget.hidden = mode;
        this.saveTarget.hidden = !mode;
        this.costTarget.disabled = !mode;
    }

}
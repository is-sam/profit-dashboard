import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['cost', 'costInput', 'edit', 'save', 'average'];

    edit() {
        this.editMode(true);
    }

    save({params: {url}}) {
        fetch(url, {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({cost: this.costInputTarget.value})
        }).then(res => {
            if (res.status !== 200) {
                console.log('Error while saving cost');
                return;
            }
            this.costTarget.innerHTML = `${this.costInputTarget.value}€`;
            this.editMode(false);
        });
    }

    editMode(edit) {
        this.editTarget.hidden = edit;
        this.saveTarget.hidden = !edit;
        this.costTarget.hidden = edit;
        this.costInputTarget.hidden = !edit;
    }

}
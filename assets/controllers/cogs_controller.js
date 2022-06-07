import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['cost', 'edit', 'save'];

    edit() {
        this.editTarget.hidden = true;
        this.saveTarget.hidden = false;
        this.costTarget.disabled = false;
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
            this.costTarget.disabled = true;
            this.saveTarget.hidden = true;
            this.editTarget.hidden = false;
        });
    }

}
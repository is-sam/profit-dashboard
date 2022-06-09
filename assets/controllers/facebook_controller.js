import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ 'adAccount' ];

    login() {
        FB.login(function(response){
            if (response.status === 'connected') {
                const data = {userId: response.authResponse.userID};

                fetch(`/marketing/facebook-ads/set-data`, {
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                }).then(res => {
                    console.log("Request complete! response:", res);
                    window.location.reload();
                });
            }
        });
    }

    save() {
        const adAccount = this.adAccountTargets.find(target => target.checked);

        if (!adAccount) {
            alert('Please select an ad account');
            return;
        }

        const data = {accountId: adAccount.value};

        fetch(`/marketing/facebook-ads/set-data`, {
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        }).then(res => {
            console.log("Request complete! response:", res);
            window.location.reload();
        });
    }
}
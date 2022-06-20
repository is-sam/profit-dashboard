import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { AmpPlugin } from '@easepick/amp-plugin';

export default class extends Controller {
    connect() {
        const picker = new easepick.create({
            element: this.element,
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css',
                '/build/easepick.css',
            ],
            grid: 1,
            calendars: 1,
            // format: 'DD/MM/YYYY',
            // autoApply: false,
            readonly: false,
            firstDay: 1,
            zIndex: 10,
            plugins: [
                AmpPlugin
            ],
            AmpPlugin: {
                darkMode: true,
            },
        });
    }
}
import { Controller } from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { RangePlugin } from '@easepick/range-plugin';
import { PresetPlugin } from '@easepick/preset-plugin';
import { AmpPlugin } from '@easepick/amp-plugin';
import moment from 'moment';

export default class extends Controller {
    static targets = ['startDate', 'endDate'];

    connect() {
        const picker = new easepick.create({
            element: this.startDateTarget,
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.0/dist/index.css',
                '/build/easepick.css',
            ],
            grid: 2,
            calendars: 2,
            // format: 'DD/MM/YYYY',
            // autoApply: false,
            firstDay: 1,
            zIndex: 10,
            plugins: [
                RangePlugin,
                PresetPlugin,
                AmpPlugin,
            ],
            RangePlugin: {
                elementEnd: this.endDateTarget
            },
            AmpPlugin: {
                darkMode: true,
            },
            PresetPlugin: {
                customPreset: {
                    'Today': [
                        moment().toDate(),
                        moment().toDate()
                    ],
                    'Yesterday': [
                        moment().subtract(1, 'day').toDate(),
                        moment().subtract(1, 'day').toDate()
                    ],
                    'Last 7 days': [
                        moment().subtract(7, 'days').toDate(),
                        moment().toDate()
                    ],
                    'Last 30 days': [
                        moment().subtract(30, 'days').toDate(),
                        moment().toDate()
                    ],
                    'Last 90 days': [
                        moment().subtract(90, 'days').toDate(),
                        moment().toDate()
                    ],
                    'This week': [
                        moment().startOf('isoWeek').toDate(),
                        moment().toDate()
                    ],
                    'This month': [
                        moment().startOf('month').toDate(),
                        moment().toDate()
                    ],
                    'Last month': [
                        moment().subtract(1, 'month').startOf('month').toDate(),
                        moment().subtract(1, 'month').endOf('month').toDate()
                    ],
                    'This year': [
                        moment().startOf('year').toDate(),
                        moment().toDate()
                    ],
                    'Last year': [
                        moment().subtract(1, 'year').startOf('year').toDate(),
                        moment().subtract(1, 'year').endOf('year').toDate()
                    ],
                },
            },
            // setup(picker) {
            //     picker.ui.container.dataset.theme = 'dark';
            // }
        });
    }
}
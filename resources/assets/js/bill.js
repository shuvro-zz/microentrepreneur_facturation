const preloader = require('./mixins/preloader.js')
const navigation = require('./mixins/navigation.js')
const _ = require('lodash')

const app = new Vue({
    el: '#bill',
    mixins: [preloader, navigation],
    data () {
        return {
            data: window.data,
            bill: window.bill || {
                benefits: [{
                    'value': '',
                    'quantity': '',
                    'unit_price': '',
                    'currency': '',
                    'designation': ''
                }]
            },
            clients: window.clients || [],
            benefits: window.benefits || [],
            errors: window.errors || {},
            client_id: window.bill ? window.bill.client_id : '',
            client: window.bill && window.bill.client ? window.bill.client.name : '',

        }
    },
    watch: {
        'bill.client': function () {
            this.client_id = this.bill.client
        }
    },

    mounted () {
      this.benefits = this.benefits
    },

    methods: {
        querySearch(queryString, cb) {
            const benefits = this.benefits
            var results = queryString ? benefits.filter(this.createFilter(queryString)) : benefits;
            // call callback function to return suggestions
            cb(results);
        },
        createFilter(queryString) {
            return (benefit) => {
                return (benefit.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
            };
        },
        addBenfit: function(idx) {
            if (idx == this.bill.benefits.length- 1) {
                this.bill.benefits.push({
                    'value': '',
                    'quantity': '',
                    'unit_price': '',
                    'currency': ''
                })
            }
        },
        removeBenefit: function(idx) {
            this.bill.benefits.splice(idx, 1)
        },
        error: function (idx, field) {
            const key = 'benefit_' + idx + '_' + field
            return errors[key];
        }
    }
})
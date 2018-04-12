const preloader = require('./mixins/preloader.js')
const navigation = require('./mixins/navigation.js')

const app = new Vue({
    el: '#default-app',
    mixins: [preloader, navigation],
    data () {
        return {
            data: window.data,
            client: window.client || {},
            errors: window.errors || {}
        }
    },
    methods: {
        onSubmit: function () {
            this.$el.querySelector('form').submit()
        }
    }
})
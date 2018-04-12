module.exports = {
    mounted () {
        this.$el.classList.remove('d-none')
        document.getElementById('main-loader').remove()
    }
}
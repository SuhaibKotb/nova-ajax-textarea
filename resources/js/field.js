Nova.booting((Vue, router, store) => {
    Vue.component('index-ajax-textarea', require('./components/IndexField'))
    Vue.component('detail-ajax-textarea', require('./components/DetailField'))
    Vue.component('form-ajax-textarea', require('./components/FormField'))
})

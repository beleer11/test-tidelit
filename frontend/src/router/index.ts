import { createRouter, createWebHistory } from 'vue-router'
import BookList from '../components/BookList.vue'
import BookDetails from '../components/BookDetails.vue'

const routes = [
    { path: '/', component: BookList },
    { path: '/books/:id', component: BookDetails }
]

export default createRouter({
    history: createWebHistory(),
    routes
})

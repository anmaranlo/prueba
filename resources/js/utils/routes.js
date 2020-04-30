import VueRouter from 'vue-router';

import Home from '../components/Home/Home';

const routes = [
    { path: '/', name: 'home', component: Home }
];

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});

export default router;
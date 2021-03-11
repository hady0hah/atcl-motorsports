import Router from 'vue-router'
const router = new Router({
    mode: 'history',
    routes: [
        // {
        //     name: 'buildcv',
        //     path: '/buildcv',
        //     component: () => import(/* webpackChunkName: "CvBuilder" */ "../components/CvBuilder.vue"),
        //     beforeEnter: validateAuthentication
        // },
        {
            path: '*',
            component: () => import(/* webpackChunkName: "Homepage" */ "../components/Home.vue")
        },
        {
            path: '/',
            name: 'home',
            component: () => import(/* webpackChunkName: "Homepage" */ "../components/Home.vue"),
        },
        {
            path: '/regulations',
            name: 'regulations',
            component: () => import(/* webpackChunkName: "Regulations" */ "../components/Regulations.vue"),
        },
        {
            name: 'event',
            path: '/event',
            props: true,
            component: () => import(/* webpackChunkName: "Event" */ "../components/EventDetail.vue"),
        },
        {
            name: 'driver',
            path: '/driver/:id',
            component: () => import(/* webpackChunkName: "Driver" */ "../components/DriverDetail.vue"),
        },
    ]
});
export default router;

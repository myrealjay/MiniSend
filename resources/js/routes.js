import Landing from './components/Landing.vue';
import Main from './Main.vue';
import DashBoard from './components/DashBoard.vue';
import ViewEmail from './components/ViewEmail.vue';
import SendEmail from './components/SendEmail.vue';

export const routes = [
    {
        path: '/',
        component: Main,
        children: [
            {
                path: '',
                name: 'landing',
                component: Landing
            },
            {
                path: '/dashboard',
                name: 'dashboard',
                meta: {
                    requiresAuth: true
                },
                component: DashBoard
            },
            {
                path: '/view-email/:id',
                name: 'view-email',
                meta: {
                    requiresAuth: true
                },
                component: ViewEmail
            },
            {
                path: '/send-email',
                name: 'send-email',
                meta: {
                    requiresAuth: true
                },
                component: SendEmail
            },
        ]
    }
];
import axios from 'axios';
export function initialize(router) {

    let user = localStorage.getItem('user');
    let token = null;
    let apikey = '';

    if (user) {
        user = JSON.parse(user);
        token = user.access_token;
        apikey = user.company.api_key;
    }

    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        if (requiresAuth && !token) {
            next('/');
        }
        else {
            next();
        }
    });


    axios.interceptors.response.use(null, (error) => {
        if (error.response) {
            if (error.response.status == 401) {
                router.push('/');
            }
        }

        return Promise.reject(error);
    });

    if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
        axios.defaults.headers.common["minisend-key"] = `${apikey}`;
    }
}

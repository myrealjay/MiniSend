import Dashboard from '../components/Dashboard.vue';
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import axios from 'axios';
import flushPromises from 'flush-promises'
import { routes } from '../routes';
import VueRouter from 'vue-router'
const router = new VueRouter({
    routes,
    mode: 'history'
});
const localVue = createLocalVue()
localVue.use(VueRouter);

describe('Dashboard.vue', () => {

    it('It retrive user and company as computed properties when loaded', async () => {

        const wrapper = mount(Dashboard, {
            localVue,
            computed: {
                user() {
                    return {
                        name: 'James Bond'
                    }
                },
                company() {
                    return {
                        api_key: 'abc123'
                    }
                }
            }
        });

        expect(wrapper.text()).toContain('Welcome James Bond');

    });

    it('It displays api key when button is clicked', async () => {
        const wrapper = mount(Dashboard, {
            computed: {
                user() {
                    return {
                        name: 'James Bond'
                    }
                },
                company() {
                    return {
                        api_key: 'abc123'
                    }
                }
            }
        });

        expect(wrapper.text()).not.toContain('abc123');

        let button = wrapper.find('#api');
        await button.trigger('click');
        expect(wrapper.text()).toContain('abc123');
    })

    it('It navigates to send mail page when button iis clicked', async () => {
        const wrapper = mount(Dashboard, {
            localVue,
            router,
            computed: {
                user() {
                    return {
                        name: 'James Bond'
                    }
                },
                company() {
                    return {
                        api_key: 'abc123'
                    }
                }
            }
        });
        expect(wrapper.vm.$route.name).toBe('landing');
        let button = wrapper.find('#sendmail');
        await button.trigger('click');
        expect(wrapper.vm.$route.name).toBe('send-email');

    })

});
import AllEmails from '../components/datatables/AllEmails';
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import axios from 'axios';
import { routes } from '../routes';
import { initialize } from '../helpers/intercept.js';
import datatable from '../components//datatables/datatable.vue';
import pagination from '../components/datatables/Pagination.vue';
import vSelect from 'vue-select'
import VueRouter from 'vue-router';
import flushPromises from 'flush-promises'

const router = new VueRouter({
    routes,
    mode: 'history'
});

global.localStorage.setItem('user', JSON.stringify({ access_token: 'abc123', company: { api_key: 'abc123' } }));
const localVue = createLocalVue()
localVue.use(VueRouter);
initialize(router);
localVue.component("datatable", datatable);
localVue.component("pagination", pagination);
localVue.component('v-select', vSelect)

afterEach(() => {
    jest.clearAllMocks();
});

jest.mock('axios');

const data = {
    data: {
        data: {
            data: [
                {
                    id: 1,
                    sender: {
                        name: 'John', email: 'john@gmail.com'
                    },
                    receiver: {
                        name: 'mark', email: 'mark@gmail.com'
                    },
                    subject: 'sample email',
                    text_content: 'my text content',
                    html_content: 'my html content',
                    attachments: "[]",
                    status: 'sent'
                },
                {
                    id: 2,
                    sender: {
                        name: 'Ben', email: 'ben@gmail.com'
                    },
                    receiver: {
                        name: 'Jude', email: 'jude@gmail.com'
                    },
                    subject: 'another email',
                    text_content: 'my text content',
                    html_content: 'my html content',
                    attachments: "[]",
                    status: 'posted'
                },
            ],
        }
    },
};

const disinctData = {
    data: {
        data: {
            senders: [
                { from: { name: 'ben', email: 'ben@gmail.com' } }
            ],
            receivers: [
                { to: { name: 'ben', email: 'ben@gmail.com' } }
            ],
            subjects: [
                "subject1", 'subject2'
            ]
        }
    }
};


describe('AllEmails.vue', () => {

    it('it fetches data correctly from backend when mounted', async () => {
        axios.post.mockImplementationOnce(() => Promise.resolve(data))
        axios.get.mockImplementationOnce(() => Promise.resolve(disinctData))

        const wrapper = mount(AllEmails, { localVue, router });
        expect(wrapper.text()).toContain('No data available');

        await flushPromises()

        expect(wrapper.text()).not.toContain('No data available');

        expect(wrapper.vm.emails.length).toBe(2);

        expect(wrapper.vm.senders.length).toBe(1);

        expect(wrapper.vm.senders[0].name).toBe('ben');

    });

    it("It vavigates top view page when an item iis viewe", async () => {
        axios.post.mockImplementationOnce(() => Promise.resolve(data))
        axios.get.mockImplementationOnce(() => Promise.resolve(disinctData))

        const wrapper2 = mount(AllEmails, { localVue, router });
        await flushPromises()
        const ActionButton = wrapper2.find('#email1');
        await ActionButton.trigger('click');
        await flushPromises()
        const Link = wrapper2.find('#email1link');
        await Link.trigger('click');
        expect(wrapper2.vm.$route.name).toBe('view-email');

        global.localStorage.removeItem('user');

    })

});
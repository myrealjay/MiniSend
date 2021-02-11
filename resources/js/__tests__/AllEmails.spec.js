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
    axios.post.mockImplementationOnce(() => Promise.resolve(data))
    axios.get.mockImplementationOnce(() => Promise.resolve(disinctData))
    it('it fetches data correctly from backend when mounted', async () => {

        const wrapper = mount(AllEmails, { localVue });

        expect(wrapper.text()).toContain('No data available');

        await flushPromises()

        expect(wrapper.text()).not.toContain('No data available');

        expect(wrapper.vm.emails.length).toBe(2);

        expect(wrapper.vm.senders.length).toBe(1);

        expect(wrapper.vm.senders[0].name).toBe('ben');

    });

});
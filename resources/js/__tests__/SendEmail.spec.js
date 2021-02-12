import SendEmail from '../components/SendEmail.vue';
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import axios from 'axios';
import flushPromises from 'flush-promises'
import { routes } from '../routes';
import VueRouter from 'vue-router'
import tinymce from "../components/tinymice";
import vSelect from 'vue-select'
import { initialize } from '../helpers/intercept.js';
import datatable from '../components//datatables/datatable.vue';
import pagination from '../components/datatables/Pagination.vue';

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
        message: 'Email sent successully'
    },
};
describe('SendEmail.vue', () => {
    axios.post.mockImplementationOnce(() => Promise.resolve(data))
    it('It sends email when send button is clicked', async () => {

        const wrapper = shallowMount(SendEmail, {
            localVue,
            components: {
                tinymce
            }
        });
        wrapper.find('#sendername').setValue('Mark');
        wrapper.find('#senderemail').setValue('mark@gmail.com');
        wrapper.find('#receivername').setValue('John');
        wrapper.find('#receiveremail').setValue('john@gmail.com');
        wrapper.find('#subject').setValue('Testing Mail');
        wrapper.find('#text_content').setValue('my text content');
        wrapper.vm.html_content = '<h1>I love this</h1>';
        let button = wrapper.find('#send');

        expect(wrapper.vm.success).toBe('')

        await button.trigger('click');

        expect(wrapper.vm.success).toBe('Email sent successully')

        await flushPromises()
        expect(wrapper.text()).toContain('Email sent successully');

    });

});
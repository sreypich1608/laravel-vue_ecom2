<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">Customer Mangement</p>

                    <div class="_overflow _table_div">
                        <table
                            class="_table"
                            style="table-layout: fixed; width: 100%"
                        >
                            <!-- TABLE TITLE -->
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th style="width: 100px">First Name</th>
                                <th style="width: 100px">Last Name</th>
                                <th style="width: 100px">Email</th>
                                <th style="width: 100px">Phone</th>
                                <th style="width: 100px">Address</th>
                                <th style="width: 100px">Created at</th>
                                <th style="width: 150px">Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(customer, i) in customerList" :key="i">
                                <td>{{ customer.id }}</td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.firstname }}
                                </td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.lastname }}
                                </td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.email }}
                                </td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.phone }}
                                </td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.address }}
                                </td>
                                <td class="overflow-hidden-cell">
                                    {{ customer.created_at }}
                                </td>
                                <td>
                                    <Button
                                        @click="showDetail(customer, i)"
                                        type="info"
                                        size="small"
                                        class="mr-2"
                                        >Detail</Button
                                    >
                                    <Button
                                        @click="showDelete(customer, i)"
                                        type="error"
                                        size="small"
                                        :loading="isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <tr v-if="!customerList.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
                <Page :total="100" />

                <!--edit category Modal start-->
                <Modal
                    width="870px"
                    v-model="detailmodal"
                    title="Customer Detail"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="p-3">
                        <Space direction="vertical" size="large">
                        <Space>
                            <p style="width: 80px;font-size: 15px;">First Name:</p>
                            <Input
                                v-model="data.firstname"
                                style="width: 300px"
                            />
                            <p style="width: 80px;font-size: 15px;">Last Name:</p>
                            <Input
                                v-model="data.lastname"
                                style="width: 320px"
                            />
                        </Space>
                        <Space>
                            <p style="width: 80px;font-size: 15px;">Email:</p>
                            <Input
                                v-model="data.email"
                                style="width: 300px"
                            />
                            <p style="width: 80px;font-size: 15px;">Phone:</p>
                            <Input
                                v-model="data.phone"
                                style="width: 320px"
                            />
                        </Space>
                    </Space>
                    <Space style="width: 800px;margin-top: 20px;" direction="vertical" size="large">
                            <Input v-model="data.address" type="textarea" :rows="4" />    
                        </Space>
                    </div>
                    <template #footer>
                        <Button type="defualt" @click="detailmodal = false"
                            >Close</Button
                        >
                    </template>
                </Modal>
                <deleteModal></deleteModal>
                <!-- category Modal close-->
            </div>
        </div>
    </div>
</template>
<script>
import { Space } from "view-ui-plus";
import deleteModal from "../component/deleteModal.vue";
import { mapGetters } from "vuex/dist/vuex.cjs.js";

export default {
    data() {
        return {
            data: {
                firstname: "",
                lastname: "",
                email: "",
                phone: "",
                address: "",
            },
            detailmodal: false,
            isDeleting: false,
            customerList: [],
            index: -1,
        };
    },
    methods: {
        showDetail(customer, i) {
            this.data = customer;
            this.detailmodal = true;
        },
        showDelete(customer, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_customer",
                data: customer,
                deleteIndex: i,
                isDeleted: false,
            };
            this.$store.commit("setDeleteModalObj", deleteModalObj);
        },
        async fetchAdminCustomer() {
            const res = await this.callApi("get", "app/get_customer");
            if (res.status == 200) {
                this.customerList = res.data;
            } else {
                this.swr();
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchAdminCustomer();
    },
    components: {
        deleteModal,
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                console.log("Delete modal object:", obj.isDeleted);
                this.customerList.splice(obj.deleteIndex, 1);
                this.fetchAdminCustomer();
            }
        },
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"]),
    },
};
</script>
<style>
.overflow-hidden-cell {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

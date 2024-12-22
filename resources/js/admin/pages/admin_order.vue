<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Orders Mangement
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table" style="table-layout: fixed; width: 100%;">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 100px;">First Name</th>
                                <th style="width: 100px;">Last Name</th>
                                <th style="width: 100px;">Email</th>
                                <th style="width: 100px;">Total</th>
                                <th style="width: 120px;">Payment Status</th>
                                <th style="width: 130px;">Status</th>
                                <th style="width: 100px;">Created at</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(order, i) in orderList" :key="i">
                                <td>{{ order.order_id }}</td> 
                                <td class="overflow-hidden-cell">{{ order.firstname }}</td>
                                <td class="overflow-hidden-cell">{{ order.lastname }}</td>
                                <td class="overflow-hidden-cell">{{ order.email }}</td>
                                <td class="overflow-hidden-cell">{{ order.total_price }}</td>
                                <td class="overflow-hidden-cell">
                                    <span v-if="order.payment_status == 'pending'" class="badge badge-danger">{{ order.payment_status }}</span>
                                    <span v-if="order.payment_status == 'paid'" class="badge badge-success">{{ order.payment_status }}</span>
                                </td>
                                <td class="overflow-hidden-cell">
                                    <span v-if="order.order_status == 'pending'" class="badge badge-danger">{{ order.order_status }}</span>
                                    <span v-if="order.order_status == 'processing'" class="badge badge-primary">{{ order.order_status }}</span>
                                    <span v-if="order.order_status == 'completed'" class="badge badge-success">{{ order.order_status }}</span>
                                    <span v-if="order.order_status == 'cancelled'" class="badge badge-secondary">{{ order.order_status }}</span>
                                </td>
                                <td class="overflow-hidden-cell">{{ order.created_at }}</td>
                                <td class="overflow-hidden-cell">
                                    <Button
                                        @click="showEdit(order, i)"
                                        type="info"
                                        size="small"
                                        class="mr-2"
                                        >Edit</Button
                                    >
                                    <Button
                                        @click="showDelete(order, i)"
                                        type="error"
                                        size="small"
                                        :loading="isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <tr v-if="!orderList.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
                <Page :total="100" />

                <!--edit category Modal start-->
                <Modal width="900px"
                    v-model="editmodal"
                    title="Edit Order"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="px-4 py-2">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-size: 15px;">First Name: <span>{{ data.firstname }}</span></p>
                                <p style="font-size: 15px;">Last Name: <span>{{ data.lastname }}</span></p>
                                <p style="font-size: 15px;">Email: <span>{{ data.email }}</span></p>
                                <p style="font-size: 15px;">Phone: <span>{{ data.phone }}</span></p>
                                <p style="font-size: 15px;">Payment Method: <span style="text-transform: capitalize;color: blue;">{{ data.payment_method }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p style="font-size: 15px;color: blue;">Total: <span>${{ data.total_price }}</span></p>
                                <p style="font-size: 15px;">Date&Time: <span>{{ data.created_at }}</span></p>
                                <p style="font-size: 15px;">Address: <span>{{ data.address }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="display: flex;flex-direction: row;">
                                <p style="font-size: 15px;margin-right: 10px;color: red;">Payment Status: </p>
                                <Select v-model="data.payment_status"
                                    style="width: 150px"
                                    placeholder="Selet Payment Status"
                                >
                                    <Option value="pending" style="width: 150px">pending</Option>
                                    <Option value="paid" style="width: 150px">paid</Option>
                                </Select>
                            </div>
                            <div class="col-md-6" style="display: flex;flex-direction: row;">
                                <p style="font-size: 15px;margin-right: 10px;color: red;">Order Status: </p>
                                <Select v-model="data.order_status"
                                    style="width: 150px"
                                    placeholder="Selet Order Status"
                                >
                                    <Option value="pending" style="width: 150px">pending</Option>
                                    <Option value="processing" style="width: 150px">processing</Option>
                                    <Option value="completed" style="width: 150px">completed</Option>
                                    <Option value="cancelled" style="width: 150px">cancelled</Option>
                                </Select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="_table" style="table-layout: fixed; width: 100%;">
                            <!-- TABLE TITLE -->
                            <tr style="text-align: center;height: 40px;">
                                <th style="width: 10px;">No.</th>
                                <th style="width: 50px;">Image</th>
                                <th style="width: 200px;">ProductName</th>
                                <th style="width: 100px;">Quantity</th>
                                <th style="width: 100px;">Price</th>
                                <th style="width: 100px;">SubTotal</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(detail, i) in orderDetails" :key="i">
                                <td>{{ i+1 }}</td> 
                                <td class="overflow-hidden-cell">
                                    <img :src="`/uploads/${ detail.image_url }`" alt="" style="object-fit: contain;widows: 30px;height: 30px;">
                                </td>
                                <td class="overflow-hidden-cell">{{ detail.product_name }}</td>
                                <td class="overflow-hidden-cell">{{ detail.quantity }}</td>
                                <td class="overflow-hidden-cell">{{ detail.price }}</td>
                                <td class="overflow-hidden-cell">{{ detail.subtotal }}</td>
                            </tr>
                            <tr v-if="!orderDetails.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                    <template #footer>
                        <Button type="defualt" @click="editmodal = false"
                            >Close</Button
                        >
                        <Button
                            @click="edit"
                            :disabled="isAdding"
                            :loading="isAdding"
                            type="primary"
                            >{{
                                isAdding ? "Editing.." : "Edit Order"
                            }}</Button
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
                total_price: "",
                payment_status: "",
                order_status: "",
                phone: "",
                address: "",
                created_at: "",
            },
            editmodal: false,
            isAdding: false,
            isDeleting: false,
            orderList: [],
            token: "",
            orderDetails: [],
        };
    },
    methods: {
        async showEdit(order, i) {
            this.editmodal = true;
            this.data = order;
            const res = await this.callApi(
                'post','app/get_order_details',{order_id:order.order_id}
            )
            if(res.status == 200){
                this.orderDetails = res.data.products
                this.data.phone = res.data.shipping.phone
                this.data.phone = res.data.shipping.address
            }else{
                this.swr()
            }
        },
        async edit() {
            this.isAdding = true;
            const editdata = {
                order_id: this.data.order_id,
                payment_status: this.data.payment_status,
                order_status: this.data.order_status
            }
            const res = await this.callApi(
                "post",
                "app/edit_order",
                editdata
            );
            if (res.status == 200) {
                this.success('Order updated successfully!');
                this.fetchOrders();
                this.editmodal = false;
                this.isAdding = false;
            } else {
                this.swr();
                this.editmodal = false;
                this.isAdding = false;
            }
        },
        async fetchOrders(){
            const res = await this.callApi("get", "app/get_order");
            if (res.status == 200) {
                this.orderList = res.data;
            } else {
                this.swr();
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchOrders()
    },
    components: {
        deleteModal,
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                console.log("Delete modal object:", obj.isDeleted);
                this.orderList.splice(obj.deleteIndex, 1)
                this.fetchOrders()
            }
        },
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
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
<template>
    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">My Order</h3>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /BREADCRUMB -->
    <div class="container">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order, i) in order_list" :key="i">
                    <td>{{ i+1 }}</td>
                    <td>
                        {{ formatCreateAt(order.created_at) }}
                    </td>
                    <td>${{ order.total_price }}</td>
                    <td>
                        <span style="font-size: 14px;" v-if="order.status == 'pending'" class="badge badge-danger">{{ order.status }}</span>
                        <span style="font-size: 14px;" v-if="order.status == 'completed'" class="badge badge-success">{{ order.status }}</span>
                        <span style="font-size: 14px;" v-if="order.status == 'processing'" class="badge badge-primary">{{ order.status }}</span>
                        <span style="font-size: 14px;" v-if="order.status == 'cancelled'" class="badge badge-secondary">{{ order.status }}</span>
                    </td>
                    <td>
                        <Button @click="openMoreModal(order.id)" type="info">More</Button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- more detail of each order start -->
        <Modal width="900px"
                    v-model="moremodal"
                    title="Order Detail"
                    :mask-closable="false"
                    :closable="false"
                >
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
                            <tr v-for="(detail, i) in order_details" :key="i">
                                <td>{{ i+1 }}</td> 
                                <td class="overflow-hidden-cell">
                                    <img :src="`/uploads/${ detail.image_url }`" alt="" style="object-fit: contain;widows: 30px;height: 30px;">
                                </td>
                                <td class="overflow-hidden-cell">{{ detail.product_name }}</td>
                                <td class="overflow-hidden-cell">{{ detail.quantity }}</td>
                                <td class="overflow-hidden-cell">{{ detail.price }}</td>
                                <td class="overflow-hidden-cell">{{ detail.subtotal }}</td>
                            </tr>
                            <tr v-if="!order_details.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                    <template #footer>
                        <Button type="defualt" @click="moremodal = false"
                            >Close</Button
                        >
                    </template>
                </Modal>
        <!-- more detail of each order end -->
        
    </div>
</template>
<script>
export default {
    data() {
        return {
            order_list: [],
            moremodal:false,
            order_details:[],
        };
    },
    methods: {
        async openMoreModal(order_id) {
            this.moremodal = true
            const res = await this.callApi("post", "user/get_order_details", {order_id:order_id});
            if (res.status == 200) {
                this.order_details = res.data.order_details;
            }
            console.log(this.order_details);
        },
        formatCreateAt(date) {
            const dateObject = new Date(date);
            return dateObject.toLocaleString('en-Us', { day: 'numeric', month: 'numeric', year: 'numeric', hour:'numeric', minute:'numeric', second:'numeric' });
        },
        async fetchOrder() {
            const res = await this.callApi("get", "user/get_order");
            if (res.status == 200) {
                this.order_list = res.data.order;
            } else {
                console.log(res);
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchOrder();
    },
};
</script>

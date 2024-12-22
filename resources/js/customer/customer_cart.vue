<template>
    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">Cart</h3>
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
                    <th>Image</th>
                    <th>Nmae</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cart, i) in cart_item_list" :key="i">
                    <td>
                        <img
                            :src="`/uploads/${cart.image_url}`"
                            style="
                                width: 30px;
                                height: 30px;
                                object-fit: contain;
                            "
                            alt=""
                        />
                    </td>
                    <td>{{ cart.name }}</td>
                    <td>
                        <button
                            @click="cart_descrease(cart)"
                            class="btn btn-primary p-3 mr-4"
                        >
                            -
                        </button>
                        {{ cart.quantity }}
                        <button
                            @click="cart_inscrease(cart)"
                            class="btn btn-primary p-3 ml-4"
                        >
                            +
                        </button>
                    </td>
                    <td>{{ cart.price }}</td>
                    <td>{{ cart.subtotal }}</td>
                    <td>
                        <button
                            @click="removeCartItem(cart)"
                            class="btn btn-danger"
                        >
                            Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row mt-5">
            <div class="col-md-10">
                <h3>Total:{{ this.total }}</h3>
            </div>
            <div class="col-md-2">
                <router-link to="/customer_checkout" class="btn btn-primary">Checkout</router-link>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            cart_item_list: [],
            total: 0,
        };
    },
    methods: {
        async cart_inscrease(cart) {
            const res = await this.callApi("post", "user/update_cart_item", {
                id: cart.id,
                action: "increase",
            });
            if (res.status === 200) {
                cart.quantity += 1;
                cart.subtotal = cart.quantity * cart.price;
                this.calculateTotal();
            } else {
                this.swr(); // Handle error
            }
        },
        async cart_descrease(cart) {
            if (cart.quantity > 1) {
                const res = await this.callApi(
                    "post",
                    "user/update_cart_item",
                    {
                        id: cart.id,
                        action: "decrease",
                    }
                );
                if (res.status === 200) {
                    cart.quantity -= 1;
                    cart.subtotal = cart.quantity * cart.price;
                    this.calculateTotal();
                } else {
                    this.swr(); // Handle error
                }
            }
            console.log(this.cart_item_list);
        },
        async removeCartItem(cart) {
            const res = await this.callApi(
                "post",
                `user/remove_cart_item`,
                { id: cart.id }
            );
            if (res.status === 200) {
                this.cart_item_list = this.cart_item_list.filter(
                    (item) => item.id !== cart.id
                );
                this.calculateTotal();
                this.success("Item removed successfully");
            } else {
                this.swr(); // Handle error
            }
        },
        async fetchCartItem() {
            const res = await this.callApi("get", "user/get_cart_item");
            if (res.status == 200) {
                this.cart_item_list = res.data.cart_item;
                this.calculateTotal();
            } else {
                this.swr();
            }
        },
        calculateTotal() {
            this.total = 0;
            for (const i of this.cart_item_list) {
                this.total += parseFloat(i.subtotal);
            }
            this.total = parseFloat(this.total).toFixed(2);
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchCartItem();
    },
};
</script>

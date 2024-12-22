<template>
    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">Checkout</h3>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-7">
                    <!-- Billing Details -->
                    <div class="billing-details">
                        <div class="section-title">
                            <h3 class="title">Billing address</h3>
                        </div>
                        <div class="form-group">
                            <input
                                v-model="customer_detail.firstname"
                                class="input"
                                type="text"
                                name="first-name"
                                placeholder="First Name"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-model="customer_detail.lastname"
                                class="input"
                                type="text"
                                name="last-name"
                                placeholder="Last Name"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-model="customer_detail.email"
                                class="input"
                                type="email"
                                name="email"
                                placeholder="Email"
                            />
                        </div>
                        <div class="form-group">
                            <input
                                v-model="customer_detail.phone"
                                class="input"
                                type="tel"
                                name="tel"
                                placeholder="Telephone"
                            />
                        </div>
                        <div class="order-notes">
                            <textarea
                                v-model="customer_detail.address"
                                class="input"
                                placeholder="Address"
                            ></textarea>
                        </div>
                    </div>
                    <!-- /Billing Details -->
                </div>

                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <div class="section-title text-center">
                        <h3 class="title">Your Order</h3>
                    </div>
                    <div class="order-summary">
                        <div class="order-col">
                            <div><strong>PRODUCT</strong></div>
                            <div><strong>TOTAL</strong></div>
                        </div>
                        <div class="order-products">
                            <div
                                v-for="(cart, i) in cart_item_list"
                                :key="i"
                                class="order-col"
                            >
                                <div>{{ cart.quantity }}x {{ cart.name }}</div>
                                <div>${{ cart.subtotal }}</div>
                            </div>
                        </div>
                        <div class="order-col">
                            <div>Shiping</div>
                            <div><strong>FREE</strong></div>
                        </div>
                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <div>
                                <strong class="order-total"
                                    >${{ this.total }}</strong
                                >
                            </div>
                        </div>
                    </div>
                    <div class="payment-method">
                        <div class="input-radio">
                            <input
                                value="cash"
                                v-model="selectedPaymentMethod"
                                type="radio"
                                name="payment"
                                id="payment-1"
                            />
                            <label for="payment-1">
                                <span></span>
                                Cash on Delivery
                            </label>
                            <div class="caption">
                                <p>
                                    You will pay when our delivery man arrive.
                                </p>
                            </div>
                        </div>
                        <div hidden class="input-radio">
                            <input
                                value="card"
                                v-model="selectedPaymentMethod"
                                type="radio"
                                name="payment"
                                id="payment-2"
                            />
                            <label for="payment-2">
                                <span></span>
                                Card
                            </label>
                            <div class="caption">
                                <p>You will pay right now. By bank card.</p>
                            </div>
                        </div>
                    </div>
                    <a
                        href="#"
                        @click="placeOrder"
                        class="primary-btn order-submit"
                        >Place order</a
                    >
                </div>
                <!-- /Order Details -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->
</template>
<script>
export default {
    data() {
        return {
            customer_detail: [],
            cart_item_list: [],
            total: 0,
            selectedPaymentMethod: "cash",
        };
    },
    methods: {
        async placeOrder() {
            try {
                const payload = {
                    payment_method: this.selectedPaymentMethod, // 'cash' or 'card'
                    address: this.customer_detail.address,
                    phone: this.customer_detail.phone,
                    email: this.customer_detail.email,
                };

                const response = await this.callApi(
                    "post",
                    "user/place_order",
                    payload
                );

                if (response.status === 200) {
                    this.success("Order placed successfully!");
                    // this.$router.push({
                    //     name: "order_confirmation",
                    //     params: { orderId: response.data.order_id },
                    // });

                    // If card payment, redirect to Stripe payment
                    if (payload.payment_method === "card") {
                        window.location.href = `/stripe/checkout/${response.data.order_id}`;
                    }
					this.total =0
					this.fetchCheckout();
                } else {
                    this.e(response.data.message);
                }
            } catch (error) {
                console.error(error);
                alert("Failed to place order. Please try again.");
            }
        },
        async fetchCheckout() {
            const [resCus, resCart] = await Promise.all([
                this.callApi("get", "user/customer_account"),
                this.callApi("get", "user/get_cart_item"),
            ]);
            if (resCus.status == 200) {
                this.customer_detail = resCus.data.customer_acc;
            }
            if (resCart.status == 200) {
                this.cart_item_list = resCart.data.cart_item;
            }
            for (let i in this.cart_item_list) {
                this.total = 0;
                this.total += parseFloat(this.cart_item_list[i].subtotal);
            }
            this.total = parseFloat(this.total).toFixed(2);
        },
    },
    created() {
        this.fetchCheckout();
    },
};
</script>

<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">{{ products }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Product
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="ios-paper" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">{{ customer }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Customer
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="ios-paper-outline" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">{{ adminuser }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Admin Staff
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="md-copy" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">{{ orders }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Order
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="md-list-box" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">${{ income }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Income
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="ios-paper" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div
                            class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two"
                        >
                            <div class="_1adminOverveiw_card_left">
                                <p class="_1adminOverveiw_card_left_num">${{ expense }}</p>

                                <p class="_1adminOverveiw_card_left_title">
                                    Total Expense
                                </p>
                            </div>
                            <div class="_1adminOverveiw_card_right">
                                <Icon type="ios-paper-outline" />
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            products: 0,
            customer:0,
            orders:0,
            adminuser:0,
            income:0,
            expense:0,
        }
    },
    async created() {
        const res = await this.callApi(
            'get','app/dashboard'
        )
        if (res.status == 200) {
            this.products = res.data.products
            this.customer = res.data.customer
            this.orders = res.data.orders
            this.adminuser = res.data.adminuser
            this.income = res.data.income
            this.expense = res.data.expense
        }else{
            this.swr()
        }
    },
}
</script>
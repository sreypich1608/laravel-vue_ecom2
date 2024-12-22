<template>
    <!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						Product Details
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
					<!-- Product main img -->
					<div class="col-md-6">
						<img :src="`/uploads/${product.image_url}`" alt="">
					</div>
					<!-- /Product main img -->
					<!-- Product details -->
					<div class="col-md-6">
						<div class="product-details">
							<h2 class="product-name">{{ product.name  }}</h2>
							<div>
								<h3 class="product-price">${{ product.price }} </h3>
								<span class="product-available">In Stock</span>
							</div>
							<div class="qty-label mb-3">
								Available: {{ product.stock }}
							</div>
                            <ul class="product-links">
								<li>Category:</li>
								<li><a href="#">{{ product.category_name }}</a></li>
							</ul>
							<div class="add-to-cart mt-5">
								<button @click="add_to_cart(product.id)" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
							</div>
						</div>
					</div>
					<!-- /Product details -->

					<!-- Product tab -->
					<div class="col-md-12">
						<div id="product-tab">
							<!-- product tab nav -->
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab">Description</a></li>
							</ul>
							<!-- /product tab nav -->

							<!-- product tab content -->
							<div class="tab-content">
									<div class="row">
										<div class="col-md-12">
											<p style="font-size: 15px;">{{ product.description }}</p>
										</div>
									</div>
							</div>
							<!-- /product tab content  -->
						</div>
					</div>
					<!-- /product tab -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

</template>
<script>
export default {
    props: {
        product_id: {
            type: String,
            default: null,
            required: true,
        },
    },
    data() {
        return {
            product: null,
        };
    },
    methods: {
        async get_product() {
            const productId = this.product_id || this.$route.query.product_id; // Use product_id from query
            if (productId) {
                const res = await this.callApi('post',"quick_view", { product_id: productId });
                if (res.status === 200) {
                    this.product = res.data.product;
                }
            }
        },async add_to_cart(product) {
            console.log(product);
            const res = await this.callApi("post", "user/add_to_cart", {id:product});
            if (res.status === 200) {
                this.success(res.data.msg);
            } else if (res.status === 401) {
                this.info(res.data.msg);
                window.location.href = res.data.redirect; // Redirect to login page
            }
        },
    },
    created() {
        this.get_product();
    },
};
</script>
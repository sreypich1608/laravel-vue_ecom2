<template>
    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">New Products</h3>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab1" class="tab-pane active">
                                <div
                                    style="
                                        display: grid;
                                        grid-gap: 10px;
                                        grid-template-columns: auto auto auto auto;
                                    "
                                    class="products-slick"
                                    data-nav="#slick-nav-1"
                                >
                                    <!-- product -->
                                    <div style="width: 300px;"
                                        v-for="(pro, i) in new_product_list"
                                        :key="i"
                                        class="product"
                                    >
                                        <div class="product-img">
                                            <img
                                                style="
                                                    height: 200px;
                                                    width: 300px;
                                                    object-fit: contain;
                                                "
                                                :src="`/uploads/${pro.image_url}`"
                                                alt=""
                                            />
                                            <div class="product-label">
                                                <span class="sale">-30%</span>
                                                <span class="new">NEW</span>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">
                                                Category
                                            </p>
                                            <h3 class="product-name">
                                                <a href="#">{{ pro.name }}</a>
                                            </h3>
                                            <h4 class="product-price">
                                                ${{ pro.price }}
                                            </h4>
                                            <div class="product-rating">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="product-btns">
                                                <button
                                                    @click="quickView(pro)"
                                                    class="quick-view"
                                                >
                                                    <i class="fa fa-eye"></i
                                                    ><span class="tooltipp"
                                                        >quick view</span
                                                    >
                                                </button>
                                            </div>
                                        </div>
                                        <div class="add-to-cart">
                                            <button
                                                class="add-to-cart-btn"
                                                @click="add_to_cart(pro)"
                                            >
                                                <i
                                                    class="fa fa-shopping-cart"
                                                ></i>
                                                add to cart
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /product -->
                                </div>
                                <div
                                    id="slick-nav-1"
                                    class="products-slick-nav"
                                ></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->
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
            new_product_list: [],
            searchQuery: "", // for search query
            selectedCategory: null, // for selected category
        };
    },
    computed: {
        filteredProductList() {
            return this.new_product_list.filter((product) => {
                const matchesSearch = product.name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase());
                const matchesCategory = this.selectedCategory
                    ? product.category_id === this.selectedCategory
                    : true;
                return matchesSearch && matchesCategory;
            });
        },
    },
    methods: {
        searchProducts() {
            this.selectedCategory = null; // Reset category filter
            this.fetchProducts(); // Fetch again with search query
        },
        setCategoryFilter(categoryId) {
            this.selectedCategory = categoryId;
            this.fetchProducts(); // Fetch products for this category
        },
        async quickView(product) {
            this.$router.push({
                name: "product_detail", // The name of the route for your product detail page
                query: { product_id: product.id },
            });
        },
        async add_to_cart(product) {
            const res = await this.callApi("post", "user/add_to_cart", product);
            if (res.status === 200) {
                this.success(res.data.msg);
            } else if (res.status === 401) {
                this.info(res.data.msg);
                window.location.href = res.data.redirect; // Redirect to login page
            }
        },
        async fetchCustomer() {
            const { search, category_id } = this.$route.query;
            const res = await this.callApi("get", "customer_home");
            if (res.status == 200) {
                this.new_product_list = res.data.new_product;
                // Apply search filter
                if (search) {
                    this.searchQuery = search.toLowerCase();
                    this.new_product_list = this.new_product_list.filter(
                        (product) =>
                            product.name
                                .toLowerCase()
                                .includes(this.searchQuery)
                    );
                }

                // Apply category filter
                if (category_id) {
                    this.selectedCategory = parseInt(category_id, 10);
                    this.new_product_list = this.new_product_list.filter(
                        (product) =>
                            product.category_id === this.selectedCategory
                    );
                }
            } else {
                this.swr();
            }
        },
    },
    created() {
        const categoryId = this.$route.query.category_id;
        if (categoryId) {
            // this.selectedCategory = categoryId;
            this.selectedCategory = categoryId ? parseInt(categoryId, 10) : null;
        }
        this.fetchCustomer();
    },
    watch: {
        $route: "fetchCustomer", // Automatically fetch products when the route changes
    },
};
</script>

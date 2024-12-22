<template>
	<div v-if="$route.meta.showNavbar">
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li @click="logout"><a><i class="fa fa-dollar"></i> Log out</a></li>
						<li><a href="/customer_account"><i class="fa fa-user-o"></i> My Account</a></li>
						<li><a href="/customer_order"><i class="fa fa-user-o"></i> My Order</a></li>
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="{{ asset('customer/img/logo.png')}}" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<!-- <div class="col-md-6">
							<div class="header-search">
								<form>
									<input style="width: 400px;border-bottom-left-radius: 30px;border-top-left-radius: 30px;" class="input" placeholder="Search here">
									<button class="search-btn">Search</button>
								</form>
							</div>
						</div> -->
						<!-- /SEARCH BAR -->
						<!-- SEARCH BAR -->
                        <div class="col-md-6">
                            <div class="header-search">
                                <form @submit.prevent="searchProducts">
                                    <input v-model="searchQuery" style="width: 400px; border-bottom-left-radius: 30px;border-top-left-radius: 30px;" class="input" placeholder="Search here">
                                    <button class="search-btn">Search</button>
                                </form>
                            </div>
                        </div>
                        <!-- /SEARCH BAR --> 
						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Cart -->
								<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty">{{ cart_item_list.length ? cart_item_list.length : 0 }}</div>
									</a>
									<div class="cart-dropdown">
										<div class="cart-list">
											<div v-for="(item, i) in cart_item_list" :key="i" class="product-widget">
												<div class="product-img">
													<img :src="`/uploads/${item.image_url}`" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">{{ item.name }}</a></h3>
													<h4 class="product-price"><span class="qty">{{item.quantity}}x</span>${{ item.price }}</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>
										</div>
										<div class="cart-summary">
											<small>3 Item(s) selected</small>
											<h5>SUBTOTAL: ${{ this.total }}</h5>
										</div>
										<div class="cart-btns">
											<a href="/customer_cart">View Cart</a>
											<a href="/customer_checkout">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->

		<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav" style="flex-direction: row;">
						<li :class="{ active: !selectedCategory }">
							<a @click="setCategoryFilter(null)">Home</a>
						</li>
						<li :class="{ active: selectedCategory === category.id }" style="text-transform: capitalize;" v-for="(category, i) in categoryList" :key="i">
							<a @click="setCategoryFilter(category.id)">{{ category.name }}</a>
						</li>
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->
	</div>
        
		<router-view></router-view>
</template>

<script>
export default{
	props: {
        user: {
            type: [Object, Boolean],
            required: true,
        },
    },
	data(){
		return {
			categoryList:[],
			cart_item_list:[],
            total:0,
			searchQuery: '',
		}
	},
	methods:{
		setCategoryFilter(categoryId) {
			console.log('Navigating to category:', categoryId);  // Debugging line
			this.$router.push({
				name: '/',
				query: { category_id: categoryId },
			});
		},
        navigateHome() {
            this.$router.push({
                name: '/',
                query: { category_id: '' }, // Clear category filter
            });
        },
		async searchProducts() {
            this.$router.push({
                name: '/',
                query: { search: this.searchQuery },
            });
        },
		async fetchCategory(){
            const res = await this.callApi("get", "app/get_category");
            if (res.status == 200) {
                this.categoryList = res.data;
            } else {
                //this.swr();
            }
        },
		async logout(){
            const res = await this.callApi('post','user/customer_logout')
            if (res.status === 200) {
                localStorage.removeItem('customer_token'); // Clear login status
                this.$router.push('/customer_login'); // Redirect to login
                this.$emit('update:user', null); // Clear user data
                this.$store.commit('setUpdateUser', false);
                }
        },
		async fetchCartItem(){
            const res = await this.callApi('get','user/get_cart_item');
            if(res.status == 200){
                this.cart_item_list = res.data.cart_item
                for(const i of this.cart_item_list){
                    this.total += parseFloat(i.subtotal)
                }
                this.total = parseFloat(this.total).toFixed(2)
            }else{
                //this.swr();
            }
        }
	},
	created() {
        this.token = window.Laravel.csrfToken;
        this.fetchCategory()
		this.fetchCartItem()
    },
	watch: {
    '$route.query.category_id': {
        immediate: true, // Run this immediately when the component is created
        handler(newCategoryId) {
            this.selectedCategory = newCategoryId ? parseInt(newCategoryId, 10) : null;
        },
    },
},
}
</script>
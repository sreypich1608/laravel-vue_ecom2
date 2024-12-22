<template>
    <!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<h3 class="breadcrumb-header">Customer Account</h3>
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
				<div class="row order-details">
                    <div class="section-title">
						<h3 class="title">Account Detail</h3>
					</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.firstname" class="input" type="text" name="first-name" placeholder="First Name">
							</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.lastname" class="input" type="text" name="last-name" placeholder="Last Name">
							</div>
                        </div>
                    </div>
                    <div class="row text-black">
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.email" class="input" type="email" name="email" placeholder="Email">
							</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.phone" class="input" type="text" name="phone" placeholder="phone +885">
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.password" class="input" type="text" name="password" placeholder="password">
							</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
								<input v-model="data.repeatpassword" class="input" type="text" name="re-password" placeholder="Repeat password">
							</div>
                        </div>
                    </div>
                    <div class="row">
							<textarea v-model="data.address" class="input" placeholder="Customer Address"></textarea>
						</div>
					
					<!-- Order Details -->
					<div class="col-md-3">
						<button @click="updateCustomer" class="primary-btn order-submit">Update</button>
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
export default{
    data(){
        return {
            data:{
                id:'',
                firstname:'',
                lastname:'',
                email:'',
                phone:'',
                password:'',
                repeatpassword:'',
                address:''
            },
        }
    },
    methods:{
        async updateCustomer(){
            if (this.data.firstname== ""){
                return this.e("customer first name is require!!!");
            }
            if (this.data.lastname == ""){
                return this.e("customer first name is require!!!");
            }
            if (this.data.email == ""){
                return this.e("customer email is require!!!");
            }
            if (this.data.address == ""){
                return this.e("customer address is require!!!");
            }
            if (this.data.phone == ""){
                return this.e("customer address is require!!!");
            }
            const res = await this.callApi('post','user/customer_update_acc',this.data)
            if(res.status === 200){
                this.success("Account update successfully.");
            }else{
                if (res.status == 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async fetchCustomer(){
            const res = await this.callApi('get','user/customer_account');
            if(res.status == 200){
                const customer = res.data.customer_acc
                this.data={
                    id:customer.id,
                    firstname : customer.firstname,
                    lastname : customer.lastname,
                    email:customer.email,
                    phone:customer.phone,
                    passowrd:'',
                    repeatpassord:'',
                    address:customer.address
                }
            }else{
                this.swr();
            }
        }
    },
    created(){
        this.fetchCustomer()
    }
}
</script>
<template>
    <Card
        style="
            margin-left: auto;
            margin-right: auto;
            margin-top: 100px;
            max-width: 450px;
        "
    >
        <template #title>
            <h3>Customer Register</h3>
        </template>
        <div class="px-3 pb-3">
            <div class="space">
            <Label>Email :</Label>
            <Input type="email" v-model="data.email" placeholder="email" />
        </div>
        <div class="space">
            <Label>Password :</Label>
            <Input
                type="password"
                password
                v-model="data.password"
                placeholder="******"
            />
        </div>
        <div class="space">
            <Label>Repead Password :</Label>
            <Input
                type="password"
                password
                v-model="data.repead_password"
                placeholder="******"
            />
        </div>
        <div class="space">
            <a href="/customer_login"><p>go back to login. if you have account</p></a>
        </div>
        <Button
            style="width: 100%; margin-top: 20px"
            @click="register"
            :disabled="isLogging"
            :loading="isLogging"
            type="primary"
            >{{ isLogging ? 'Register..':'Register' }}</Button
        >
        </div>
    </Card>
</template>
<script>
export default {
    data() {
        return {
            data: {
                password: "",
                email: "",
                repead_password:"",
            },
            isLogging:false,
        };
    },
    methods: {
        async register(){
            if (this.data.passrepead_passwordword == "")
                return this.e("repead_password is required");
            if (this.data.password.trim() == "")
                return this.e("Password is required");
            if (this.data.email.trim() == "")
                return this.e("Email is required");
            if (this.data.password.length <6)
                return this.e("password is required 6 or more digit");
            if (this.data.password != this.data.repead_password)
                return this.e("password and repead passowrd is not the same. please check");
            this.isLogging = true
            const res = await this.callApi('post','customer_register',this.data)
            if(res.status === 200){
                this.success("You are register success. please login.")
                window.location = '/customer_login'
                // localStorage.setItem('admin_login', true); // Mark as logged in
                // this.$router.push(res.data.redirect); // Redirect to the dashboard
                //this.$store.commit('setUpdateUser', res.data.user);
            }else if(res.status === 422){
                for(let i in res.data.errors){
                    this.e(res.data.errors[i][0])
                }
            }
            else{
                if(res.status ==401){
                    this.info(res.data.msg)
                }else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    },
};
</script>


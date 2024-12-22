import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../admin/pages/dashboard.vue';
import Category from '../admin/pages/category.vue';
import Product from '../admin/pages/product.vue';
import AdminUser from '../admin/pages/createUser.vue';
import AdminLogin from '../admin/pages/admin_login.vue';
import AdminCustomer from '../admin/pages/admin_customer.vue';
import AdminOrder from '../admin/pages/admin_order.vue';

import CustomerHome from '../customer/welcome.vue';
import CustomerLogin from '../customer/customer_login.vue';
import CustomerRegister from '../customer/customer_register.vue';
import CustomerCheckout from '../customer/customer_checkout.vue';
import CustomerAccount from '../customer/customer_account.vue';
import CustomerCart from '../customer/customer_cart.vue';
import customer_product_detail from '../customer/customer_product_detail.vue';
import customer_See_Order from '../customer/customer_order.vue';

const routes = [
    // Admin routes
    { path: '/dashboard', component: Dashboard, name: '/dashboard', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/category', component: Category, name: '/category', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/product', component: Product, name: '/product', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/adminuser', component: AdminUser, name: '/adminuser', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/admincustomer', component: AdminCustomer, name: '/admincustomer', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/adminorder', component: AdminOrder, name: '/adminorder', meta: { requiresAuth: true, admin: true, showNavbar: false } },
    { path: '/admin_login', component: AdminLogin, name: 'admin_login', meta: { showNavbar: false, admin: true } },

    // Customer routes
    { path: '/', component: CustomerHome, name: '/', meta: { showNavbar: true, customer: true },props: route => ({ category_id: route.query.category_id }) },
    { path: '/product_detail', component: customer_product_detail, name: 'product_detail', meta: { showNavbar: true}, props: true,},
    { path: '/customer_cart', component: CustomerCart, name: 'customer_cart', meta: { showNavbar: true, customer: true } },
    { path: '/customer_order', component: customer_See_Order, name: 'customer_order', meta: { showNavbar: true, customer: true } },
    { path: '/customer_checkout', component: CustomerCheckout, name: 'customer_checkout', meta: { showNavbar: true, customer: true, requiresAuth: true } },
    { path: '/customer_account', component: CustomerAccount, name: '/customer_account', meta: { showNavbar: true, customer: true, requiresAuth: true } },
    { path: '/customer_login', component: CustomerLogin, name: 'customer_login', meta: { showNavbar: false } },
    { path: '/customer_register', component: CustomerRegister, name: 'customer_register', meta: { showNavbar: false } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guard
router.beforeEach((to, from, next) => {
    const adminAuthenticated = localStorage.getItem('admin_login'); // Check if admin is logged in
    const customerAuthenticated = localStorage.getItem('customer_token'); // Check if customer is logged in

    if (to.matched.some(record => record.meta.admin)) {
        // Admin route handling
        if (to.meta.requiresAuth && !adminAuthenticated) {
            next({ name: 'admin_login' }); // Redirect unauthenticated admin to login
        } else {
            next(); // Proceed if admin is authenticated
        }
    } else if (to.matched.some(record => record.meta.customer)) {
        // Customer route handling
        if (to.meta.requiresAuth && !customerAuthenticated) {
            next({ name: 'customer_login' }); // Redirect unauthenticated customer to login
        } else {
            next(); // Proceed if customer is authenticated
        }
    } else {
        // Public route
        next();
    }
});

export default router;

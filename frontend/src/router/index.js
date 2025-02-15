import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/auth/Login.vue";
import Register from "@/components/auth/Register.vue";
import AdminDashboard from "@/components/admin/AdminDashboard.vue";
import UserDashboard from "@/components/user/UserDashboard.vue";
import { useUserStore } from "@/stores/userStore";

const routes = [
  { path: "/login", name: "Login", component: Login },
  { path: "/register", name: "Register", component: Register },
  {
    path: "/admin",
    name: "AdminDashboard",
    component: AdminDashboard,
    meta: { requiresRole: "admin" },
  },
  {
    path: "/user",
    name: "UserDashboard",
    component: UserDashboard,
    meta: { requiresRole: "user" },
  },
  { path: "/:pathMatch(.*)*", redirect: "/login" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const userStore = useUserStore();

  if (!userStore.user) {
    if (to.path !== "/login") {
      return next("/login");
    } else {
      return next();
    }
  }

  const token = userStore.user.token;
  if (!token) {
    userStore.logout();
    return next("/login");
  }

  if (to.meta.requiresRole) {
    const userRole = userStore.user.role;

    if (to.meta.requiresRole !== userRole) {
      return next(userRole === "admin" ? "/admin" : "/user");
    }
  }

  next();
});

export default router;

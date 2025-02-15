<template>
  <div class="login-container">
    <h2>Login</h2>

    <form @submit.prevent="login">
      <div class="form-group">
        <label>Email:</label>
        <input type="email" v-model="email" required />
      </div>

      <div class="form-group">
        <label>Password:</label>
        <input type="password" v-model="password" required />
      </div>

      <button type="submit" class="btn primary">Login</button>
    </form>

    <p v-if="errorMsg" class="error-msg">{{ errorMsg }}</p>

    <p class="register-link">
      Don't have an account?
      <span @click="goToRegister" class="register-btn">Sign up</span>
    </p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/userStore";

const email = ref("");
const password = ref("");
const errorMsg = ref("");
const router = useRouter();
const userStore = useUserStore();

const login = async () => {
  try {
    const res = await axios.post("/login", {
      email: email.value,
      password: password.value,
    });

    userStore.setUser(res.data);

    router.push(res.data.user.role === "admin" ? "/admin" : "/user");
  } catch (err) {
    errorMsg.value = "Invalid credentials!";
  }
};

const goToRegister = () => {
  router.push("/register");
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
  text-align: center;
}

h2 {
  margin-bottom: 1rem;
  color: #333;
}

.form-group {
  text-align: left;
  margin-bottom: 10px;
}

label {
  font-weight: bold;
  color: #555;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
}

.btn {
  padding: 12px 16px;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  font-size: 14px;
  background: #4e54c8;
  color: white;
}

.btn:hover {
  background: #3c409a;
}

.error-msg {
  margin-top: 10px;
  color: red;
}

.register-link {
  margin-top: 15px;
  font-size: 14px;
  color: #555;
}

.register-btn {
  color: #4e54c8;
  font-weight: bold;
  cursor: pointer;
  text-decoration: underline;
}

.register-btn:hover {
  color: #3c409a;
}
</style>

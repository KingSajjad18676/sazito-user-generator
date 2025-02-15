<template>
  <div class="container">
    <div class="register-box">
      <h2>Register</h2>

      <form @submit.prevent="handleRegister">
        <div class="input-group">
          <label>Name</label>
          <input
            type="text"
            v-model="name"
            placeholder="Enter your name"
            required
          />
        </div>

        <div class="input-group">
          <label>Email</label>
          <input
            type="email"
            v-model="email"
            placeholder="Enter your email"
            required
          />
        </div>

        <div class="input-group">
          <label>Password</label>
          <input
            type="password"
            v-model="password"
            placeholder="Enter your password"
            required
          />
        </div>

        <div class="input-group">
          <label>Confirm Password</label>
          <input
            type="password"
            v-model="password_confirmation"
            placeholder="Confirm your password"
            required
          />
        </div>

        <button type="submit" class="btn">Register</button>

        <p v-if="error" class="error">{{ error }}</p>

        <p class="login-text">
          Already have an account?
          <button @click="goToLogin" class="login-btn">Login</button>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const error = ref("");
const router = useRouter();

const handleRegister = async () => {
  try {
    const response = await axios.post("/register", {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    alert("Registration successful! Please log in.");
    router.push("/");
  } catch (err) {
    error.value = err.response?.data?.message || "Registration failed.";
  }
};

const goToLogin = () => {
  router.push("/");
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.register-box {
  background: #fff;
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.15);
  text-align: center;
  width: 100%;
  max-width: 400px;
}

h2 {
  margin-bottom: 1.5rem;
  color: #333;
  font-size: 26px;
  font-weight: bold;
}

.input-group {
  text-align: left;
  margin-bottom: 1.2rem;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  color: #444;
}

input {
  width: 100%;
  padding: 12px;
  border: 2px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
  transition: all 0.3s ease;
}

input:focus {
  border-color: #4e54c8;
  outline: none;
  box-shadow: 0 0 8px rgba(78, 84, 200, 0.3);
}

.btn {
  width: 100%;
  background: #4e54c8;
  color: white;
  padding: 14px;
  font-size: 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease, transform 0.2s;
}

.btn:hover {
  background: #3c409a;
  transform: scale(1.02);
}

.error {
  color: red;
  margin-top: 1rem;
  font-size: 14px;
}

.login-text {
  margin-top: 1.5rem;
  font-size: 14px;
  color: #555;
}

.login-btn {
  background: none;
  border: none;
  color: #4e54c8;
  font-weight: bold;
  cursor: pointer;
  transition: color 0.3s ease;
}

.login-btn:hover {
  color: #3c409a;
}
</style>

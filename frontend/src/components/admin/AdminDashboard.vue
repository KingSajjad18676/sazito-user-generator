<template>
  <div class="admin-dashboard">
    <h2>Admin Dashboard</h2>

    <form @submit.prevent="createUsers" class="form-container">
      <div class="form-group">
        <label>Number of Users to Create:</label>
        <input
          type="number"
          v-model.number="count"
          min="1"
          max="100"
          required
        />
      </div>

      <button type="submit" class="btn primary">Create Users</button>
    </form>

    <div v-if="responseMsg" class="response-box">
      <h3>Response:</h3>
      <pre>{{ responseMsg }}</pre>
    </div>

    <button class="btn danger" @click="logout">Logout</button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/userStore";

const count = ref(1);
const message = ref("");
const responseMsg = ref("");
const router = useRouter();
const userStore = useUserStore();

const createUsers = async () => {
  try {
    const res = await axios.post(
      "/admin/create-users",
      {
        num_users: count.value,
        message: message.value,
      },
      {
        headers: {
          Authorization: `Bearer ${userStore.user.token}`,
        },
      }
    );
    responseMsg.value = res.data;
  } catch (err) {
    responseMsg.value = err.response?.data || "Error creating users";
  }
};

const logout = async () => {
  try {
    await axios.post(
      "/logout",
      {},
      {
        headers: {
          Authorization: `Bearer ${userStore.user.token}`,
        },
      }
    );
    userStore.logout();
    router.push("/login");
  } catch (err) {
    console.error(err);
  }
};
</script>

<style scoped>
.admin-dashboard {
  max-width: 500px;
  margin: 50px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  font-family: Arial, sans-serif;
}

h2 {
  margin-bottom: 20px;
  color: #333;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
  text-align: left;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
  color: #444;
}

input,
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  transition: border-color 0.3s ease-in-out;
}

input:focus,
textarea:focus {
  border-color: #007bff;
  outline: none;
}

textarea {
  min-height: 80px;
  resize: vertical;
}

.btn {
  padding: 10px;
  border: none;
  border-radius: 5px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease-in-out;
}

.primary {
  background: #007bff;
  color: white;
}

.primary:hover {
  background: #0056b3;
}

.danger {
  background: #dc3545;
  color: white;
  margin-top: 15px;
}

.danger:hover {
  background: #c82333;
}

.response-box {
  margin-top: 20px;
  padding: 15px;
  border-radius: 5px;
  background: #f8f9fa;
  text-align: left;
  font-size: 14px;
  color: #333;
}
</style>

<template>
  <div class="user-dashboard">
    <h2>User Dashboard</h2>

    <div class="online-users">
      <h3>Online Users</h3>
      <ul>
        <li v-for="user in onlineUsers" :key="user.id">
          {{ user.name }}
        </li>
      </ul>
    </div>

    <button class="logout-btn" @click="logout">Logout</button>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/userStore";
import { setupPing } from "@/services/pingService";

const router = useRouter();
const userStore = useUserStore();
const onlineUsers = ref([]);
const messages = ref([]);

const { startPinging, stopPinging } = setupPing();

const fetchOnlineUsers = async () => {
  try {
    const res = await axios.get("/online-users", {
      headers: { Authorization: `Bearer ${userStore.user.token}` },
    });
    onlineUsers.value = res.data;
  } catch (err) {
    console.error("Error fetching online users:", err);
  }
};

const fetchMessages = async () => {
  try {
    const res = await axios.get("/messages", {
      headers: { Authorization: `Bearer ${userStore.user.token}` },
    });
    messages.value = res.data;
  } catch (err) {
    console.error("Error fetching messages:", err);
  }
};

const logout = async () => {
  try {
    await axios.post(
      "/logout",
      {},
      { headers: { Authorization: `Bearer ${userStore.user.token}` } }
    );
    userStore.logout();
    router.push("/");
  } catch (err) {
    console.error("Logout failed:", err);
  }
};

let onlineUsersInterval;

onMounted(() => {
  startPinging();
  fetchOnlineUsers();
  onlineUsersInterval = setInterval(fetchOnlineUsers, 5000);
  fetchMessages();
});

onUnmounted(() => {
  stopPinging();
  clearInterval(onlineUsersInterval);
});
</script>

<style scoped>
.user-dashboard {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.15);
  text-align: center;
}

h2 {
  margin-bottom: 1rem;
  color: #333;
}

.online-users,
.messages {
  margin-bottom: 20px;
  padding: 15px;
  border-radius: 8px;
  background: #f9f9f9;
}

h3 {
  color: #4e54c8;
  margin-bottom: 10px;
}

ul {
  list-style: none;
  padding: 0;
}

li {
  padding: 5px 0;
  font-size: 14px;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  background: #4e54c8;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #3c409a;
}

.logout-btn {
  margin-top: 15px;
  background: #ff4b5c;
}

.logout-btn:hover {
  background: #d43f51;
}
</style>

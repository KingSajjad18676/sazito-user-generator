import axios from "axios";
import { useUserStore } from "@/stores/userStore";

export const setupPing = () => {
  let pingInterval = null;

  const startPinging = () => {
    pingInterval = setInterval(async () => {
      try {
        const userStore = useUserStore();
        if (!userStore.user.token) return;

        await axios.post(
          "/ping",
          {},
          {
            headers: {
              Authorization: `Bearer ${userStore.user.token}`,
            },
          }
        );
      } catch (error) {
        console.error("Ping failed:", error);
      }
    }, 3000);
  };

  const stopPinging = () => {
    if (pingInterval) {
      clearInterval(pingInterval);
      pingInterval = null;
    }
  };

  return { startPinging, stopPinging };
};

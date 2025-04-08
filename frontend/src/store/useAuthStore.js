import { create } from "zustand";
import { persist } from "zustand/middleware";
import axios from "axios";

axios.defaults.withCredentials = true;

const useAuthStore = create(
  persist(
    (set) => ({
      user: null,
      loading: null,
      error: null,

      login: async (credentials) => {
        set({ loading: true, error: null });
        try {
          const res = await axios.post("/api/login", credentials);
          const userRes = await axios.get("/api/profile");

          set({ user: userRes.data, loading: false });
        } catch (error) {
          set({
            error: error.response?.data?.message || "Login failed",
            loading: false,
          });
        }
      },

      fetchUser: async () => {
        set({ loading: true, error: null });
        try {
          const user = await axios.get("/api/profile");
          set({ user: user.data });
        } catch (error) {
          set({ user: null, loading: false });
        }
      },

      logout: async () => {
        await axios.post("/api/logout");
        set({ user: null });
      },
    }),
    {
      name: "auth-storage",
      partialize: (state) => ({ user: state.user }),
    }
  )
);

export default useAuthStore;

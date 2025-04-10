import { create } from "zustand";
import axios from "axios";
import { persist } from "zustand/middleware";

axios.defaults.withCredentials = true;

const useOrderStore = create(
  persist(
    (set) => ({
      loading: false,
      error: null,
      orders: null,

      makeOrder: async (orderData) => {
        set({ loading: true, error: null });
        try {
          const res = await axios.post("/api/orders", orderData);
          set({ loading: false, orders: res.data });
          return res.data;
        } catch (error) {
          const errorData = error.response?.data || {
            message: error.message || "Erreur lors de la commande",
          };
          set({ error: errorData, loading: false });
          throw errorData;
        }
      },

      clearError: () => set({ error: null }),
      clearOrders: () => set({ orders: null }),
    }),
    {
      name: "order-storage",
      getStorage: () => localStorage,
    }
  )
);

export default useOrderStore;

import { create } from 'zustand'
import { donationAPI } from '../services/api'

export const useDonationStore = create((set) => ({
  balance: 0,
  orders: [],
  transfers: [],
  conversions: [],
  loading: false,
  error: null,

  fetchBalance: async () => {
    try {
      set({ loading: true })
      const response = await donationAPI.getBalance()
      if (response.data.success) {
        set({ balance: response.data.data.balance, error: null })
      }
    } catch (error) {
      set({ error: error.message })
    } finally {
      set({ loading: false })
    }
  },

  fetchOrders: async (page = 1, limit = 10) => {
    try {
      set({ loading: true })
      const response = await donationAPI.getOrders(page, limit)
      if (response.data.success) {
        set({ orders: response.data.data.orders, error: null })
      }
    } catch (error) {
      set({ error: error.message })
    } finally {
      set({ loading: false })
    }
  },

  fetchHistory: async (type = 'all', limit = 50) => {
    try {
      set({ loading: true })
      const response = await donationAPI.getHistory(type, limit)
      if (response.data.success) {
        set({ conversions: response.data.data.history, error: null })
      }
    } catch (error) {
      set({ error: error.message })
    } finally {
      set({ loading: false })
    }
  },

  addDonation: async (data) => {
    try {
      set({ loading: true })
      const response = await donationAPI.addDonation(data)
      if (response.data.success) {
        set({ error: null })
        return response.data.data
      }
    } catch (error) {
      set({ error: error.message })
      throw error
    } finally {
      set({ loading: false })
    }
  },

  transferCoins: async (data) => {
    try {
      set({ loading: true })
      const response = await donationAPI.transferCoins(data)
      if (response.data.success) {
        set({ error: null })
        return response.data.data
      }
    } catch (error) {
      set({ error: error.message })
      throw error
    } finally {
      set({ loading: false })
    }
  },

  convertCoins: async (data) => {
    try {
      set({ loading: true })
      const response = await donationAPI.convertCoins(data)
      if (response.data.success) {
        set({ error: null })
        return response.data.data
      }
    } catch (error) {
      set({ error: error.message })
      throw error
    } finally {
      set({ loading: false })
    }
  }
}))

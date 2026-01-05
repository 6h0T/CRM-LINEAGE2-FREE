import { create } from 'zustand'
import { authAPI } from '../services/api'

export const useAuthStore = create((set) => ({
  isAuthenticated: false,
  user: null,
  loading: true,
  error: null,

  checkAuth: async () => {
    try {
      set({ loading: true })
      const response = await authAPI.checkAuth()
      if (response.data.success) {
        set({
          isAuthenticated: true,
          user: response.data.user,
          error: null
        })
      } else {
        set({
          isAuthenticated: false,
          user: null
        })
      }
    } catch (error) {
      set({
        isAuthenticated: false,
        user: null,
        error: error.message
      })
    } finally {
      set({ loading: false })
    }
  },

  logout: async () => {
    try {
      await authAPI.logout()
      set({
        isAuthenticated: false,
        user: null
      })
    } catch (error) {
      set({ error: error.message })
    }
  },

  setUser: (user) => set({ user, isAuthenticated: !!user })
}))

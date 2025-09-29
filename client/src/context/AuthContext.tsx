import React, { createContext, useContext, useState, useEffect } from 'react';
import { User } from '../types';
import { apiService } from '../services/api';

interface AuthContextType {
  user: User | null;
  isLoading: boolean;
  login: (username: string, password: string) => Promise<void>;
  logout: () => void;
  getUserRole: () => 'client' | 'teacher' | 'student' | null;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const initAuth = async () => {
      const token = localStorage.getItem('token');
      if (token) {
        try {
          const response = await apiService.me();
          setUser(response.data);
        } catch (error) {
          console.error('Failed to get user info:', error);
          localStorage.removeItem('token');
          localStorage.removeItem('userId');
        }
      }
      setIsLoading(false);
    };

    initAuth();
  }, []);

  const login = async (username: string, password: string) => {
    try {
      const response = await apiService.login(username, password);
      
      if (response.accessToken) {
        const userResponse = await apiService.me();
        setUser(userResponse.data);
      }
    } catch (error) {
      throw error;
    }
  };

  const logout = () => {
    apiService.logout();
    setUser(null);
  };

  const getUserRole = (): 'client' | 'teacher' | 'student' | null => {
    if (!user?.person) return null;
    
    if (user.person.client) return 'client';
    if (user.person.teacher) return 'teacher';
    if (user.person.student) return 'student';
    
    return null;
  };

  return (
    <AuthContext.Provider value={{ user, isLoading, login, logout, getUserRole }}>
      {children}
    </AuthContext.Provider>
  );
};
import React, {createContext, useContext, useState} from 'react';
import {verifyToken} from "../api/verifyToken.js";
import {useQuery} from "@tanstack/react-query";

const AuthContext = createContext(null);

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({children}) => {
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [user, setUser] = useState(null);
    useQuery({
        queryKey: ['auth', {token}],
        queryFn: verifyToken,
        enabled: !!token,
        onSuccess: (data) => setUser(data.user),
        onError: () => {
            setToken(null);
            localStorage.removeItem('token');
            setUser(null);
        },
    });
    return (
        <AuthContext.Provider value={{user, token, setUser, setToken}}>
            {children}
        </AuthContext.Provider>
    );
};

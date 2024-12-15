import { useState } from 'react';

export const useAuth = () => {
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [user, setUser] = useState(null);
    return {
        token,
        setToken,
        user,
        setUser
    };
};

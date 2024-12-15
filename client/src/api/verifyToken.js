import axiosClient from "./axiosClient.js";
export const verifyToken = async ({queryKey}) => {
    const [_,{token}] = queryKey;
    if (!token) return null;
    const response = await axiosClient.get('/user');
    return response.data;
};
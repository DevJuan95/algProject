import axiosClient from "./axiosClient.js";

export async function fetchOrders({queryKey}){
    const [_,{page}] = queryKey;
    const response = await axiosClient.get(`/kitchen/orders?page=${page}`);
    return response.data;
}
import axiosClient from "./axiosClient.js";

export async function fetchOrder({queryKey}){
    const [_,{page,orderId}] = queryKey;
    const response = await axiosClient.get(`/kitchen/order/${orderId}/recipes?page=${page}`);
    return response.data;
}
import axiosClient from "./axiosClient.js";

export async function fetchPurchases({queryKey}){
    const [_,{page}] = queryKey;
    const response = await axiosClient.get(`/warehouse/purchases?page=${page}&per_page=20`);
    return response.data;
}
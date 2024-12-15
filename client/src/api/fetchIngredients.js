import axiosClient from "./axiosClient.js";
export const fetchIngredients = async ({queryKey}) => {
    const [endpoint] = queryKey;
    const response = await axiosClient.get(endpoint);
    return response.data;
};
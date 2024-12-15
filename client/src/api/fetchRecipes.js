import axiosClient from "./axiosClient.js";
export const fetchRecipes = async () => {
    const response = await axiosClient.get('/kitchen/recipes');
    return response.data;
};
import GenericTable from "../components/Table/GenericTable.jsx";
import {useQuery} from "@tanstack/react-query";
import {fetchRecipes} from "../api/fetchRecipes.js";
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";

export function RecetasPage() {
    const {data, isLoading, isError} = useQuery({queryKey: ['recipes'], queryFn: fetchRecipes});
    if (isLoading) return <GenericLoader/>
    if (isError) return <p>Error fetching the recipes</p>
    const headers = ['ID', 'Name', 'Ingredients'];
    const rows = data.map((recipe) => {
        const ingredients = (
            <ol>
                {recipe.ingredients.map((ingredient, key) => {
                    return (
                        <li key={ingredient.id}>
                            {ingredient.name} - Quantity: {ingredient.quantity}
                        </li>
                    );
                })}
            </ol>
        );
        return [
            recipe.id,
            recipe.name,
            ingredients
        ];
    });
    return (
        <div>
            <h3>Recipes:</h3>
            <GenericTable headers={headers} rows={rows}/>
        </div>
    );
}
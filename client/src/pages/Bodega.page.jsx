import TableWithPagination from "../components/Table/GenericTable.jsx";
import {fetchIngredients} from "../api/fetchIngredients.js";
import {useQuery} from "@tanstack/react-query";
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";

export function BodegaPage() {
    const {data, isLoading, isError} = useQuery({queryKey: ['warehouse/ingredients?page=1'], queryFn: fetchIngredients});
    if (isLoading) return <GenericLoader/>
    if (isError) return <p>Error loading the inventory</p>
    const headers = ['ID', 'Ingredient', 'Available stock'];
    const rows = data.map((ingredient) => [ingredient.id, ingredient.name, ingredient.stock]);
    return (
        <div>
            <h3>Warehouse inventory</h3>
            <TableWithPagination headers={headers} rows={rows}/>
        </div>
    );
}
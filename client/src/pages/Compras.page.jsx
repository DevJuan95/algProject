import GenericTable from "../components/Table/GenericTable.jsx";
import {useQuery} from "@tanstack/react-query";
import {fetchPurchases} from "../api/fetchPurchases.js";
import {useState} from "react";
import GenericPaginator from "../components/GenericPaginator/GenericPaginator.jsx";
import {Center, Container} from "@mantine/core";
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";

export function ComprasPage() {
    const [page, setPage] = useState(1);
    const {data, isLoading, isError} = useQuery({queryKey: ['purchases', {page}], queryFn: fetchPurchases});
    if (isLoading) return <GenericLoader/>
    if (isError) return <p>Error loading the purchases</p>

    const headers = ['ID', 'Ingredients','Quantity Bought', 'Purchase Date','Order ID'];
    const rows = data.data.map((purchase) => [
        purchase.id,
        purchase.ingredient.name,
        purchase.quantity,
        purchase.created_at,
        purchase.order_id,
    ]);
    return (
        <div>
            <h3>Marketplace purchases</h3>
            <GenericTable
                headers={headers}
                rows={rows}
            />
            <Container fluid pt="1rem">
                <Center>
                    <GenericPaginator page={data.meta.current_page} total={data.meta.last_page} onPageChange={setPage}/>
                </Center>
            </Container>
        </div>
    );
}
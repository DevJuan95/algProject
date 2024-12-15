import GenericTable from "../components/Table/GenericTable.jsx";
import {useQuery} from "@tanstack/react-query";
import {useState} from "react";
import GenericPaginator from "../components/GenericPaginator/GenericPaginator.jsx";
import {Center, Container} from "@mantine/core";
import GenericButton from "../components/GenericButton/GenericButton.jsx";
import {Link} from "@tanstack/react-router";
import {getRouteApi} from '@tanstack/react-router';
import {fetchOrder} from "../api/fetchOrder.js";
import GenericLoader from "../components/GenericLoader/GenericLoader.jsx";

const route = getRouteApi('/orden/$orderId');

export function OrdenPage() {
    const {orderId} = route.useParams();
    const [page, setPage] = useState(1);
    const {data, isLoading, isError} = useQuery({queryKey: ['order', {page, orderId}], queryFn: fetchOrder});
    if (isLoading) return <GenericLoader/>
    if (isError) return <p>Error loading the order</p>

    const headers = ['Meal name', 'Created At'];
    const rows = data.data.map((order) => [
        order.name,
        order.created_at,
    ]);
    return (
        <div>
            <h3>Order: {orderId}</h3>
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
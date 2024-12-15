import {Loader} from "@mantine/core";

export default function GenericLoader() {
    return (
        <div style={{textAlign: 'center', margin: '20px 0'}}>
            <Loader size="xl" color="#30aba9"/>
            <p>Loading...</p>
        </div>
    );
}
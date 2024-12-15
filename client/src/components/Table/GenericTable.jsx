import {Table} from '@mantine/core';

const GenericTable = ({headers, rows}) => {

    const tableData = {
        head: headers,
        body: rows
    }
    return (
        <div>
            <Table
                striped
                highlightOnHover
                withColumnBorders
                data={tableData}
                styles={
                    {
                        table:{
                            borderRadius: '10px',
                            boxShadow: '0 7px 10px rgba(0, 0, 0, 0.1)',
                            overflow: 'hidden',
                        },
                        thead: {
                            backgroundColor: '#00b19d',
                            color: 'white',
                            borderRadius: '50%',
                            fontWeight: 'Bolder',
                            textTransform: 'uppercase',
                        }
                    }
                }
            />
        </div>
    );
};

export default GenericTable;

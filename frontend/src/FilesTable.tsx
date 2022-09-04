import { DataGrid, GridColDef, GridValueGetterParams } from '@mui/x-data-grid';
import axios from 'axios';
import { useEffect, useState } from 'react';


// 
// "id": 1,
// "fileName": "/tmp/test.zipbLFiIj",
// "userName": "upload.zip",
// "uploadUser": "aaa",
// "uploadIp": "172.19.0.1",
// "created_at": "2022-08-25T16:37:43.000000Z",
// "updated_at": "2022-08-25T16:37:43.000000Z",
// "fileID": "",
// "timesDownloaded": 0,
// "deleteAt": 0

const columns: GridColDef[]= [
    { field: 'id', headerName: 'ID', width: 70 },
    { field: 'userName', headerName: 'Group name', width: 70 },
    { field: 'uploadUser', headerName: 'Uploaded by', width: 70 },
    { field: 'created_at', headerName: 'Created at', width: 70 },

    { field: 'timesDownloaded', headerName: 'Times downloaded', width: 70 },

]


const FilesTable = ()=>{
    const [rows, setRows] = useState([]);
    useEffect(()=>{
        const fetchFiles=async ()=>{
             setRows(await axios.get('http://localhost/files'));
            console.log(rows);
        }
        fetchFiles();
    },[])
return(
<DataGrid
  rows={rows}
  columns={columns}
  pageSize={5}
  rowsPerPageOptions={[5]}
  checkboxSelection
/>
    )
}


export default FilesTable;
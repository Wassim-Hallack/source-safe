<!DOCTYPE html>
<html>
<head>
    <title>User Operations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>User Operations Report</h1>
<p>Group Name: {{$data['group']['name']}}
    <br> User Name: {{$data['user']['name']}}
    <br> Email: {{$data['user']['email']}}
</p>
<table>
    <thead>
    <tr>
        <th>Operation ID</th>
        <th>File Name</th>
        <th>Operation</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data['user_operations'] as $operation)
        <tr>
            <td>{{ $operation['id'] }}</td>
            <td>{{ $operation['file']['name'] }}</td>
            <td>{{ $operation['operation'] }}</td>
            <td>{{ $operation['created_at'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

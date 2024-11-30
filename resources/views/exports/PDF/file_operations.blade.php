<!DOCTYPE html>
<html>
<head>
    <title>File Operations Report</title>
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
<h1>File Operations Report</h1>
<p>Group Name: {{$data['group']['name']}} - <br> File Name: {{$data['file']['name']}}</p>
<table>
    <thead>
    <tr>
        <th>Operation ID</th>
        <th>User Name</th>
        <th>User Email</th>
        <th>Operation</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data['file_operations'] as $operation)
        <tr>
            <td>{{ $operation['id'] }}</td>
            <td>{{ $operation['user']['name'] }}</td>
            <td>{{ $operation['user']['email'] }}</td>
            <td>{{ $operation['operation'] }}</td>
            <td>{{ $operation['created_at'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #111;
            color: #fff;
        }
        .leaderboard-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #222;
            border-radius: 8px;
        }
        .table th, .table td {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container leaderboard-container">
        <div class="d-flex justify-content-between mb-3">
            <div class="input-group">
                <form method="GET" action="{{ route('leaderboard.index') }}">
                    <input type="text"  class="form-control" placeholder="User ID"  aria-label="User ID" name="search" placeholder="Search by User ID">
                    <button  class="btn btn-light">Search</button>
                </form>
            </div>
        </div>

        <div class="mb-3">
            <form method="GET" action="{{ route('leaderboard.index') }}">
                <select name="filter" onchange="this.form.submit()">
                    <option value="">All Time</option>
                    <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>Today</option>
                    <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </form>
        </div>

        <div>  <form method="POST" action="{{ route('leaderboard.recalculate') }}">
            @csrf
            <button type="submit">Recalculate</button>
        </form></div>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Points</th>
                    <th>Rank</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->activities_sum_points }}</td>
                    <td>{{ $user->rank }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

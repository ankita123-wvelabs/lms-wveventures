@php 
$data = \Session::get('report');
@endphp
@if(sizeof($users) > 0)
<div class="panel-body">
    <div class="adv-table">
    <table  class="display table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th width="20%">Name</th>
                <th width="40%">Leaves</th>
                <th width="20%">Count</th>
                <th width="20%">LWP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
            <tr>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['leave_dates'] }}</td>
                <td>{{ $user['leaves'] }}</td>
                <td>{{ $user['lwps'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
{{ $users->render() }}
</div>
@else
<div class="panel-body">
    <div class="adv-table">
    No data available
    </div>
</div>
@endif
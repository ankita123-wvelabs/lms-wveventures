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
                    <th width="15%">Tax. income</th>
                    <th width="15%">Tds</th>
                    <th width="15%">Monthly Tds</th>
                    <th width="15%">Pervoius Month Salary</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                <tr>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ number_format(round($user['tax_income']),2) }}</td>
                    <td>{{ number_format(round($user['tds']),2) }}</td>
                    <td>{{ number_format(round($user['monthly_tds']),2) }}</td>
                    <td>{{ number_format(round($user['net_salary']),2) }}</td>
                    <td style="color: {{ $user['status'] == 2 ? 'Red' : ($user['status'] == 0 ? 'Orange' : 'Green')}}">{{ $user['status'] == 2 ? 'To be Processing' : ($user['status'] == 0 ? 'Processing' : 'Processed') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="panel-body">
    <div class="adv-table">
    No data available
    </div>
</div>
@endif